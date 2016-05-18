<?php

namespace livetyping\hermitage\app;

use DI\ContainerBuilder;
use Doctrine\Common\Cache\ApcuCache;
use Exception;
use livetyping\hermitage\foundation\exceptions\ImageNotFoundException;
use livetyping\hermitage\foundation\exceptions\UnknownVersionNameException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;

/**
 * Class App
 *
 * @package livetyping\hermitage\app
 */
class App extends \DI\Bridge\Slim\App
{
    /** @var \livetyping\hermitage\app\Sources */
    private $sources;

    /**
     * App constructor.
     *
     * @param \livetyping\hermitage\app\Sources $sources
     */
    public function __construct(Sources $sources)
    {
        $this->sources = $sources;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->setDefinitionCache(new ApcuCache());

        foreach ($this->sources->all() as $source) {
            $builder->addDefinitions($source);
        }
    }

    /**
     * @inheritdoc
     */
    protected function handleException(Exception $e, Request $request, Response $response)
    {
        if (($e instanceof ImageNotFoundException) || ($e instanceof UnknownVersionNameException)) {
            $e = new NotFoundException($request, $response);
        }

        return parent::handleException($e, $request, $response);
    }
}
