<?php

namespace livetyping\hermitage\app\exceptions;

/**
 * Class UnauthorizedException
 *
 * @package livetyping\hermitage\app\exceptions
 */
class UnauthorizedException extends HttpException
{
    /**
     * UnauthorizedException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = null, int $code = 0, \Exception $previous = null)
    {
        parent::__construct(401, $message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Unauthorized';
    }
}
