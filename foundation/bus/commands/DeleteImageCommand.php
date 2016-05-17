<?php

namespace livetyping\hermitage\foundation\bus\commands;

/**
 * Class DeleteImageCommand
 *
 * @package livetyping\hermitage\foundation\bus\commands
 */
final class DeleteImageCommand
{
    /** @var string */
    protected $path;

    /**
     * DeleteImageCommand constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
