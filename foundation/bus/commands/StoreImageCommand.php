<?php

namespace livetyping\hermitage\foundation\bus\commands;

/**
 * Class StoreImageCommand
 *
 * @package livetyping\hermitage\foundation\bus\commands
 */
final class StoreImageCommand
{
    /** @var string */
    protected $mimeType;
    
    /** @var string */
    protected $binary;
    
    /** @var string */
    protected $path;

    /**
     * StoreImageCommand constructor.
     *
     * @param string $mimeType
     * @param string $binary
     */
    public function __construct(string $mimeType, string $binary)
    {
        $this->mimeType = $mimeType;
        $this->binary = $binary;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getBinary(): string
    {
        return $this->binary;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return (string)$this->path;
    }
    
    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }
}
