<?php

class DraftMedia
{
    protected static function &store(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['draft_uploads']) || !is_array($_SESSION['draft_uploads'])) {
            $_SESSION['draft_uploads'] = [];
        }
        return $_SESSION['draft_uploads'];
    }

    public static function recordUpload(string $draft, string $bucket, string $key): void
    {
        $store = &self::store();
        if (!isset($store[$draft])) $store[$draft] = [];
        $store[$draft][] = ['bucket' => $bucket, 'key' => $key, 'ts' => time()];
    }

    /**
     * Xoá toàn bộ object của draft bằng callback ($bucket,$key) => void.
     * Trả về số lượng đã xoá.
     */
    public static function discardDraft(string $draft, callable $deleter): int
    {
        $store = &self::store();
        $list = $store[$draft] ?? [];
        $cnt = 0;
        foreach ($list as $it) {
            try {
                $deleter($it['bucket'], $it['key']);
                $cnt++;
            } catch (\Throwable $e) {
            }
        }
        unset($store[$draft]); // clear draft
        return $cnt;
    }

    /**
     * Adopt: duyệt danh sách ảnh tạm
     */
    public static function adoptDraft(string $draft, callable $adopter): array
    {
        $store = &self::store();
        $list = $store[$draft] ?? [];
        $out = [];
        foreach ($list as $it) {
            $res = $adopter($it['bucket'], $it['key']);
            if (is_array($res) && isset($res['dstKey'], $res['dstUrl'])) {
                $out[] = [
                    'srcBucket' => $it['bucket'],
                    'srcKey'    => $it['key'],
                    'dstKey'    => $res['dstKey'],
                    'dstUrl'    => $res['dstUrl'],
                ];
            }
        }
        unset($store[$draft]);
        return $out;
    }
}
