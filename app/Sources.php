<?php

namespace livetyping\hermitage\app;

/**
 * Class Sources
 *
 * @package livetyping\hermitage\app
 */
final class Sources
{
    /** @var string[] */
    protected $files = [];

    /**
     * Sources constructor.
     *
     * @param array $files
     */
    public function __construct(array $files = [])
    {
        $this->files = $this->core();
        foreach ($files as $file) {
            $this->add((string)$file);
        }
    }

    /**
     * Adds file containing definitions
     *
     * @param string $file the name of a file containing definitions
     *
     * @return $this
     */
    public function add(string $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Returns the list of files containing definitions
     *
     * @return array
     */
    public function all(): array
    {
        return $this->files;
    }

    /**
     * @return string[]
     */
    protected function core(): array
    {
        return [
            __DIR__ . '/config/settings.php',
            __DIR__ . '/config/definitions.php',
            __DIR__ . '/config/decorators.php'
        ];
    }
}
