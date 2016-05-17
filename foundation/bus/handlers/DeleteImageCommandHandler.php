<?php

namespace livetyping\hermitage\foundation\bus\handlers;

use livetyping\hermitage\foundation\bus\commands\DeleteImageCommand;
use livetyping\hermitage\foundation\contracts\images\Storage;

/**
 * Class DeleteImageCommandHandler
 *
 * @package livetyping\hermitage\foundation\bus\handlers
 */
final class DeleteImageCommandHandler
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /**
     * DeleteImageCommandHandler constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param \livetyping\hermitage\foundation\bus\commands\DeleteImageCommand $command
     */
    public function handle(DeleteImageCommand $command)
    {
        $image = $this->storage->get($command->getPath());
        $this->storage->delete($image);
    }
}
