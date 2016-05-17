<?php

namespace livetyping\hermitage\foundation\exceptions;

use Exception;

/**
 * Class ImageNotFoundException
 *
 * @package livetyping\hermitage\foundation\exceptions
 */
class ImageNotFoundException extends Exception
{
    /** @var string */
    protected $path;

    /**
     * ImageNotFoundException constructor.
     *
     * @param string     $path
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($path, $code = 0, \Exception $previous = null)
    {
        $this->path = $path;
        
        parent::__construct('Image not found at path: ' . $this->getPath(), $code, $previous);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
