<?php

namespace livetyping\hermitage\app\factories;

use Interop\Container\ContainerInterface;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;

/**
 * Class CommandBus
 *
 * @package livetyping\hermitage\app\factories
 */
class CommandBus
{
    /** @var \Interop\Container\ContainerInterface */
    protected $container;

    /**
     * CommandBus constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \SimpleBus\Message\Bus\MessageBus
     */
    public function create(): MessageBus
    {
        $bus = new MessageBusSupportingMiddleware($this->middleware());
        $bus->appendMiddleware(new DelegatesToMessageHandlerMiddleware($this->createHandlerResolver()));

        return $bus;
    }

    /**
     * @return \SimpleBus\Message\Handler\Resolver\MessageHandlerResolver
     */
    protected function createHandlerResolver()
    {
        $serviceLocator = function ($id) {
            return $this->container->get($id);
        };

        $callableMap = new CallableMap(
            $this->container->get('command-bus.command-handler-map'),
            new ServiceLocatorAwareCallableResolver($serviceLocator)
        );

        return new NameBasedMessageHandlerResolver(new ClassBasedNameResolver(), $callableMap);
    }

    /**
     * @return array
     */
    protected function middleware()
    {
        $middleware = [];
        if ($this->container->has('command-bus.middleware')) {
            $middleware = $this->container->get('command-bus.middleware');
        }

        return $middleware;
    }
}
