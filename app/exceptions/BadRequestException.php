<?php

namespace livetyping\hermitage\app\exceptions;

/**
 * Class BadRequestException
 *
 * @package livetyping\hermitage\app\exceptions
 */
class BadRequestException extends HttpException
{
    /**
     * HttpException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = null, int $code = 0, \Exception $previous = null)
    {
        parent::__construct(400, $message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Bad Request';
    }
}
