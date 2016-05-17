<?php

namespace livetyping\hermitage\foundation\bus\handlers;

use livetyping\hermitage\foundation\bus\commands\MakeImageVersionCommand;
use livetyping\hermitage\foundation\contracts\images\Processor;
use livetyping\hermitage\foundation\contracts\images\Storage;

/**
 * Class MakeImageVersionCommandHandler
 *
 * @package livetyping\hermitage\foundation\bus\handlers
 */
final class MakeImageVersionCommandHandler
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Processor */
    protected $processor;

    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /**
     * MakeImageVersionCommandHandler constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Processor $processor
     * @param \livetyping\hermitage\foundation\contracts\images\Storage   $storage
     */
    public function __construct(Processor $processor, Storage $storage)
    {
        $this->processor = $processor;
        $this->storage = $storage;
    }

    /**
     * @param \livetyping\hermitage\foundation\bus\commands\MakeImageVersionCommand $command
     *
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     * @throws \livetyping\hermitage\foundation\exceptions\UnknownVersionNameException
     */
    public function handle(MakeImageVersionCommand $command)
    {
        $image = $this->storage->get($command->getPathToOriginal());
        $image = $this->processor->make($image, $command->getVersion());

        $this->storage->put($image);
    }
}
