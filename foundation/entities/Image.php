<?php

namespace livetyping\hermitage\foundation\entities;

use livetyping\hermitage\foundation\Util;

/**
 * Class Image
 *
 * @package livetyping\hermitage\foundation\entities
 */
final class Image
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $dirname;

    /** @var string */
    protected $mimeType;

    /** @var string */
    protected $extension;

    /** @var string */
    protected $version;

    /** @var string */
    protected $binary;

    /**
     * Image constructor.
     *
     * @param string $binary
     * @param string $mimeType
     * @param string $path
     */
    public function __construct(string $binary, string $mimeType, string $path)
    {
        $this->binary = $binary;
        $this->mimeType = $mimeType;
        $this->name = Util::name($path);
        $this->dirname = Util::dirname($path);
        $this->version = Util::version($path);
        $this->extension = Util::determineExtensionByMimeType($mimeType);
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return (string)$this->mimeType;
    }

    /**
     * @return string
     */
    public function getBinary(): string
    {
        return (string)$this->binary;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return (string)$this->version;
    }

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return (string)$this->dirname;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return Util::path(
            (string)$this->dirname,
            (string)$this->name,
            (string)$this->extension,
            (string)$this->version
        );
    }

    /**
     * @param string $binary
     * @param string $version
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     */
    public function modify(string $binary, string $version = null): Image
    {
        $clone = clone $this;
        $clone->binary = $binary;
        $clone->version = $version ?? $clone->version;

        return $clone;
    }
}
