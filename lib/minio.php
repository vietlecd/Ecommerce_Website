<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

if (!function_exists('env')) {
    function env(string $k, ?string $default = null): ?string
    {
        $v = getenv($k);
        return ($v === false || $v === null) ? $default : $v;
    }
}

function s3_bucket(): string
{
    return env('S3_BUCKET', 'my-bucket');
}

/** Prefix tạm và final trong cùng bucket */
function s3_tmp_prefix(): string
{
    $p = env('S3_TMP_PREFIX', 'media/tmp/');
    return rtrim($p, '/') . '/';
}
function s3_final_prefix(): string
{
    $p = env('S3_FINAL_PREFIX', 'media/');
    return rtrim($p, '/') . '/';
}

function s3_endpoint_internal(): string
{
    return rtrim(env('S3_ENDPOINT_INTERNAL', env('S3_ENDPOINT', 'http://minio:9000')), '/');
}

function s3_endpoint_public(): string
{
    return rtrim(env('S3_PUBLIC_BASE', env('S3_ENDPOINT', 'http://minio:9000')), '/');
}

function s3_client_internal(): S3Client
{
    static $cli;
    if ($cli) return $cli;
    $cli = new S3Client([
        'version'                 => 'latest',
        'region'                  => env('S3_REGION', 'us-east-1'),
        'endpoint'                => s3_endpoint_internal(),
        'use_path_style_endpoint' => true,
        'credentials'             => [
            'key'    => env('S3_KEY', 'minio'),
            'secret' => env('S3_SECRET', 'minio123'),
        ],
    ]);
    return $cli;
}
function s3_client_public(): S3Client
{
    static $cli;
    if ($cli) return $cli;
    $cli = new S3Client([
        'version'                 => 'latest',
        'region'                  => env('S3_REGION', 'us-east-1'),
        'endpoint'                => s3_endpoint_public(),
        'use_path_style_endpoint' => true,
        'credentials'             => [
            'key'    => env('S3_KEY', 'minio'),
            'secret' => env('S3_SECRET', 'minio123'),
        ],
    ]);
    return $cli;
}

/** Ensure bucket exists */
function s3_ensure_bucket(string $bucket = null): void
{
    $s3 = s3_client_internal();
    $bucket = $bucket ?: s3_bucket();
    try {
        $s3->headBucket(['Bucket' => $bucket]);
    } catch (AwsException $e) {
        $s3->createBucket(['Bucket' => $bucket]);
    }
}

/** Put/Delete/Copy */
function s3_put_object(string $localFile, string $mime, string $key, bool $publicRead = false): void
{
    $s3 = s3_client_internal();
    $args = [
        'Bucket'       => s3_bucket(),
        'Key'          => $key,
        'SourceFile'   => $localFile,
        'ContentType'  => $mime,
        'CacheControl' => 'public, max-age=31536000, immutable',
    ];
    if ($publicRead) $args['ACL'] = 'public-read';
    $s3->putObject($args);
}
function s3_delete_object(string $key): void
{
    $s3 = s3_client_internal();
    $s3->deleteObject(['Bucket' => s3_bucket(), 'Key' => $key]);
}
function s3_copy_object(string $srcKey, string $dstKey, bool $publicRead = false): void
{
    $s3 = s3_client_internal();
    $args = [
        'Bucket'     => s3_bucket(),
        'Key'        => $dstKey,
        'CopySource' => rawurlencode(s3_bucket()) . '/' . str_replace('%2F', '/', rawurlencode($srcKey)),
        'MetadataDirective' => 'COPY',
    ];
    if ($publicRead) $args['ACL'] = 'public-read';
    $s3->copyObject($args);
}

/** Presigned / Public URL */
function s3_presigned_get(string $key, $expires = '+60 minutes'): string
{
    $s3 = s3_client_public();
    $cmd = $s3->getCommand('GetObject', ['Bucket' => s3_bucket(), 'Key' => $key]);
    $req = $s3->createPresignedRequest($cmd, $expires);
    return (string)$req->getUri();
}
function s3_public_url(string $key): string
{
    return s3_endpoint_public() . '/' . rawurlencode(s3_bucket()) . '/' . str_replace('%2F', '/', rawurlencode($key));
}
