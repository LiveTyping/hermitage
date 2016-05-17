<?php

namespace livetyping\hermitage\foundation\bus\commands;

/**
 * Class MakeImageVersionCommand
 *
 * @package livetyping\hermitage\foundation\bus\commands
 */
final class MakeImageVersionCommand
{
    /** @var string */
    protected $pathToOriginal;
    
    /** @var string */
    protected $version;

    /**
     * MakeImageVersionCommand constructor.
     *
     * @param string $pathToOriginal
     * @param string $version
     */
    public function __construct(string $pathToOriginal, string $version)
    {
        $this->pathToOriginal = $pathToOriginal;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getPathToOriginal(): string
    {
        return $this->pathToOriginal;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
