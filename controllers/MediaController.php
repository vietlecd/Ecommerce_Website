<?php
require_once __DIR__ . '/../lib/minio.php';
require_once __DIR__ . '/../lib/draft-media.php';

class MediaController
{
    /**
     * POST /index.php?controller=media&action=uploadImage
     * form-data: image (file), optional: draft (string)
     * return: { ok:true, key, url, width, height, alt }
     */
    public function uploadImage(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
                throw new Exception('Method not allowed', 405);
            }
            if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded');
            }

            s3_ensure_bucket();

            $draft = (string)($_POST['draft'] ?? $_GET['draft'] ?? '');

            $tmp  = $_FILES['image']['tmp_name'];
            $size = (int)($_FILES['image']['size'] ?? 0);

            // MIME guard
            $fi = new finfo(FILEINFO_MIME_TYPE);
            $mime = $fi->file($tmp);

            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (!in_array($mime, $allowed, true)) throw new Exception('Unsupported image type');
            if ($size > 10 * 1024 * 1024)         throw new Exception('File too large (>10MB)');

            $dim = @getimagesize($tmp);
            $w = $dim ? (int)$dim[0] : null;
            $h = $dim ? (int)$dim[1] : null;

            // Extension
            $ext = match ($mime) {
                'image/jpeg' => '.jpg',
                'image/png'  => '.png',
                'image/webp' => '.webp',
                'image/gif'  => '.gif',
                default      => '.bin',
            };

            // Key: TMP_PREFIX/draft/{token}/YYYY/MM/random.ext
            $ns  = s3_tmp_prefix() . 'draft/' . ($draft ? preg_replace('/[^a-zA-Z0-9_-]+/', '', $draft) : 'no-draft') . '/';
            $key = $ns . date('Y/m/') . bin2hex(random_bytes(16)) . $ext;

            s3_put_object($tmp, $mime, $key, false);

            if ($draft) DraftMedia::recordUpload($draft, s3_bucket(), $key);

            $url = s3_presigned_get($key, '+60 minutes');
            $alt = pathinfo($_FILES['image']['name'] ?? '', PATHINFO_FILENAME);

            echo json_encode([
                'ok'     => true,
                'key'    => $key,
                'url'    => $url,
                'width'  => $w,
                'height' => $h,
                'alt'    => $alt,
            ]);
        } catch (\Throwable $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 400;
            http_response_code($code);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    /** POST /index.php?controller=media&action=discardDraft  (sendBeacon khi rời trang) */
    public function discardDraft(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
                throw new Exception('Method not allowed', 405);
            }
            $draft = (string)($_POST['draft'] ?? '');
            if (!$draft) throw new Exception('Missing draft token');

            $deleted = DraftMedia::discardDraft($draft, function (string $bucket, string $key) {
                s3_delete_object($key);
            });

            echo json_encode(['ok' => true, 'deleted' => $deleted]);
        } catch (\Throwable $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 400;
            http_response_code($code);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}
