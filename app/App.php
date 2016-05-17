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
    /**
     * @inheritdoc
     */
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->setDefinitionCache(new ApcuCache());

        $builder->addDefinitions(__DIR__ . '/config/settings.php');
        $builder->addDefinitions(__DIR__ . '/config/definitions.php');
        $builder->addDefinitions(__DIR__ . '/config/decorators.php');
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
