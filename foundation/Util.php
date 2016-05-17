<?php

namespace livetyping\hermitage\foundation;

/**
 * Class Util
 *
 * @package livetyping\hermitage\foundation
 */
final class Util
{
    const VERSION_SEPARATOR = ':';

    /** @var array */
    protected static $mimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];

    /**
     * @param string $path
     *
     * @return string
     */
    public static function name(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function version(string $path): string
    {
        return self::separateExtensionAndVersion($path)['ver'];
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function extension(string $path): string
    {
        return self::separateExtensionAndVersion($path)['ext'];
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function isOriginal(string $path): bool
    {
        return empty(self::version($path));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function original(string $path): string
    {
        $result = $path;
        $version = self::version($path);
        if ($version) {
            $version = self::VERSION_SEPARATOR . $version;
            $result = str_replace($version, '', $result);
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function dirname(string $path): string
    {
        $dirname = dirname($path);

        return self::normalizeDirname($dirname);
    }

    /**
     * @param string $dirname
     * @param string $filename
     * @param string $extension
     * @param string $version
     *
     * @return string
     */
    public static function path(string $dirname, string $filename, string $extension, string $version = ''): string
    {
        $path = self::normalizeDirname($dirname);
        $path .= '/' . $filename . '.' . $extension;
        $path .= !empty($version) ? self::VERSION_SEPARATOR . $version : '';

        return ltrim($path, '/');
    }

    /**
     * @param string $mime
     *
     * @return string
     */
    public static function determineExtensionByMimeType(string $mime): string
    {
        return self::$mimeTypes[$mime] ?? '';
    }

    /**
     * @param string $dirname
     *
     * @return string
     */
    public static function normalizeDirname(string $dirname): string
    {
        $result = trim($dirname, '/');

        return $result === '.' ? '' : $result;
    }

    /**
     * @return array
     */
    public static function supportedMimeTypes(): array
    {
        return array_keys(self::$mimeTypes);
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected static function separateExtensionAndVersion(string $path): array
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $parts = explode(self::VERSION_SEPARATOR, $extension, 2);

        return [
            'ext' => $parts[0] ?? '',
            'ver' => $parts[1] ?? '',
        ];
    }
}
