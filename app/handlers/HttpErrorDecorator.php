<?php

namespace livetyping\hermitage\app\handlers;

use livetyping\hermitage\app\exceptions\HttpException;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Body;

/**
 * Class HttpErrorDecorator
 *
 * @package livetyping\hermitage\app\handlers
 */
class HttpErrorDecorator
{
    /** @var callable */
    protected $handler;

    /** @var string */
    protected $defaultContentType = 'application/json';

    /** @var array */
    protected $knownContentTypes = [
        'application/json',
        'application/xml',
        'text/xml',
        'text/html',
    ];

    /**
     * HttpErrorDecorator constructor.
     *
     * @param callable $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param \Exception                               $exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, Exception $exception): Response
    {
        if (!($exception instanceof HttpException)) {
            return call_user_func($this->handler, $request, $response, $exception);
        }

        $contentType = $this->determineContentType($request);
        switch ($contentType) {
            case 'application/json':
                $output = $this->renderJsonErrorMessage($exception);
                break;

            case 'text/xml':
            case 'application/xml':
                $output = $this->renderXmlErrorMessage($exception);
                break;

            case 'text/html':
                $output = $this->renderHtmlErrorMessage($exception);
                break;
            default:
                $output = '';
        }

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $response->withStatus($exception->getStatusCode())
                        ->withHeader('Content-Type', $contentType)
                        ->withBody($body);
    }

    /**
     * @param \livetyping\hermitage\app\exceptions\HttpException $exception
     *
     * @return string
     */
    protected function renderTextException(HttpException $exception)
    {
        $text = sprintf('Type: %s' . PHP_EOL, $exception->getMessage());

        return $text;
    }

    /**
     * @param \livetyping\hermitage\app\exceptions\HttpException $exception
     *
     * @return string
     */
    protected function renderHtmlErrorMessage(HttpException $exception)
    {
        $title = $exception->getName();
        $html = '<p>' . $exception->getMessage() . '</p>';

        $output = sprintf(
            "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
            "<title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana," .
            "sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{" .
            "display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>",
            $title,
            $title,
            $html
        );

        return $output;
    }

    /**
     * @param \livetyping\hermitage\app\exceptions\HttpException $exception
     *
     * @return string
     */
    protected function renderJsonErrorMessage(HttpException $exception)
    {
        $error = [
            'message' => $exception->getMessage(),
            'status' => $exception->getStatusCode(),
        ];

        return json_encode($error, JSON_PRETTY_PRINT);
    }

    /**
     * @param \livetyping\hermitage\app\exceptions\HttpException $exception
     *
     * @return string
     */
    protected function renderXmlErrorMessage(HttpException $exception)
    {
        $xml = "<error>\n  <message>{$exception->getMessage()}</message>\n</error>";

        return $xml;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return string
     */
    private function determineContentType(Request $request)
    {
        $acceptHeader = $request->getHeaderLine('Accept');
        $selectedContentTypes = array_intersect(explode(',', $acceptHeader), $this->knownContentTypes);

        if (count($selectedContentTypes)) {
            return $selectedContentTypes[0];
        }

        return $this->defaultContentType;
    }
}
