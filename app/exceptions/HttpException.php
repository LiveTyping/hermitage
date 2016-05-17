<?php

namespace livetyping\hermitage\app\exceptions;

use Exception;

/**
 * Class HttpException
 *
 * @package livetyping\hermitage\app\exceptions
 */
class HttpException extends \Exception
{
    /** @var int */
    protected $statusCode;

    /**
     * HttpException constructor.
     *
     * @param int        $status
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct(int $status, $message = null, int $code = 0, \Exception $previous = null)
    {
        $this->statusCode = $status;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Error';
    }
}
