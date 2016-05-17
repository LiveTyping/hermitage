<?php

namespace livetyping\hermitage\app\handlers;

use livetyping\hermitage\app\exceptions\HttpException;
use Throwable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * Class ErrorLoggingDecorator
 *
 * @package livetyping\hermitage\app\handlers
 */
final class ErrorLoggingDecorator
{
    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    /** @var \Closure */
    protected $handler;

    /** @var array */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * ErrorLoggingDecorator constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param callable                 $handler
     */
    public function __construct(LoggerInterface $logger, callable $handler)
    {
        $this->logger = $logger;
        $this->handler = $handler;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param \Throwable                               $error
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, Throwable $error): Response
    {
        if (!$this->shouldntReport($error)) {
            $this->logger->critical($error->getMessage());
        }

        return call_user_func($this->handler, $request, $response, $error);
    }

    /**
     * @param \Throwable $error
     *
     * @return bool
     */
    protected function shouldntReport(Throwable $error): bool
    {
        if (!($error instanceof \Exception)) {
            return false;
        }

        foreach ($this->dontReport as $type) {
            if ($error instanceof $type) {
                return true;
            }
        }

        return false;
    }
}
