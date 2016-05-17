<?php

namespace livetyping\hermitage\foundation\bus\handlers;

use livetyping\hermitage\foundation\bus\commands\StoreImageCommand;
use livetyping\hermitage\foundation\contracts\images\Generator;
use livetyping\hermitage\foundation\contracts\images\Processor;
use livetyping\hermitage\foundation\contracts\images\Storage;
use livetyping\hermitage\foundation\entities\Image;

/**
 * Class StoreImageCommandHandler
 *
 * @package livetyping\hermitage\foundation\bus\handlers
 */
final class StoreImageCommandHandler
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /** @var \livetyping\hermitage\foundation\contracts\images\Processor */
    protected $processor;

    /** @var \livetyping\hermitage\foundation\contracts\images\Generator */
    protected $generator;

    /**
     * StoreImageCommandHandler constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Storage   $storage
     * @param \livetyping\hermitage\foundation\contracts\images\Processor $processor
     * @param \livetyping\hermitage\foundation\contracts\images\Generator $generator
     */
    public function __construct(Storage $storage, Processor $processor, Generator $generator)
    {
        $this->storage = $storage;
        $this->processor = $processor;
        $this->generator = $generator;
    }

    /**
     * @param \livetyping\hermitage\foundation\bus\commands\StoreImageCommand $command
     */
    public function handle(StoreImageCommand $command)
    {
        $image = new Image($command->getBinary(), $command->getMimeType(), $this->generator->path());
        $image = $this->processor->optimize($image);

        $this->storage->put($image);
        $command->setPath($image->getPath());
    }
}
