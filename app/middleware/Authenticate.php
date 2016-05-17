<?php

namespace livetyping\hermitage\app\middleware;

use Carbon\Carbon;
use livetyping\hermitage\app\exceptions\UnauthorizedException;
use livetyping\hermitage\app\signer\Signer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Authenticate
 *
 * @package livetyping\hermitage\app\middleware
 */
class Authenticate
{
    const HEADER_AUTHENTICATE_TIMESTAMP = 'X-Authenticate-Timestamp';
    const HEADER_AUTHENTICATE_SIGNATURE = 'X-Authenticate-Signature';

    /** @var \livetyping\hermitage\app\signer\Signer */
    protected $signer;

    /** @var string */
    protected $secret;

    /** @var int */
    protected $timestampExpires;

    /**
     * Authenticate constructor.
     *
     * @param \livetyping\hermitage\app\signer\Signer $signer
     * @param string                                  $secret
     * @param int                                     $timestampExpires
     */
    public function __construct(Signer $signer, string $secret, int $timestampExpires = 120)
    {
        $this->signer = $signer;
        $this->secret = $secret;
        $this->timestampExpires = $timestampExpires;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable                                 $next
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \livetyping\hermitage\app\exceptions\UnauthorizedException
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $timestamp = $this->getTimestampFromRequest($request);
        if ($this->timestampHasExpired($timestamp)) {
            throw new UnauthorizedException('Timestamp has expired.');
        }

        $signature = $this->getSignatureFromRequest($request);
        $data = implode('|', [$request->getMethod(), rtrim($request->getUri(), '/'), $timestamp]);

        if (!$this->signer->verify($signature, $data, $this->secret)) {
            throw new UnauthorizedException('Signature is invalid.');
        }

        return $next($request, $response);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return int
     * @throws \livetyping\hermitage\app\exceptions\UnauthorizedException
     */
    protected function getTimestampFromRequest(Request $request): int
    {
        if (!$request->hasHeader(self::HEADER_AUTHENTICATE_TIMESTAMP)) {
            throw new UnauthorizedException('Timestamp is required.');
        }

        $timestamp = current($request->getHeader(self::HEADER_AUTHENTICATE_TIMESTAMP));

        if (!is_numeric($timestamp)) {
            throw new UnauthorizedException('Timestamp must be an integer.');
        }

        return (int)$timestamp;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return string
     * @throws \livetyping\hermitage\app\exceptions\UnauthorizedException
     */
    protected function getSignatureFromRequest(Request $request): string
    {
        if (!$request->hasHeader(self::HEADER_AUTHENTICATE_SIGNATURE)) {
            throw new UnauthorizedException('Signature is required.');
        }

        $signature = current($request->getHeader(self::HEADER_AUTHENTICATE_SIGNATURE));

        return $signature;
    }

    /**
     * @param int $timestamp
     *
     * @return bool
     */
    protected function timestampHasExpired(int $timestamp): bool
    {
        $date = Carbon::createFromTimestamp($timestamp, 'UTC');
        $start = Carbon::now('UTC')->subSeconds($this->timestampExpires);
        $end = Carbon::now('UTC');

        return !$date->between($start, $end);
    }
}
