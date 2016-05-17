<?php

namespace livetyping\hermitage\foundation\images;

use livetyping\hermitage\foundation\contracts\images\Storage as StorageContract;
use livetyping\hermitage\foundation\entities\Image;
use livetyping\hermitage\foundation\exceptions\ImageNotFoundException;
use League\Flysystem\FilesystemInterface;

/**
 * Class Storage
 *
 * @package livetyping\hermitage\foundation\images
 */
final class Storage implements StorageContract
{
    /** @var \League\Flysystem\FilesystemInterface */
    protected $filesystem;

    /**
     * Storage constructor.
     *
     * @param \League\Flysystem\FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $path
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    public function get(string $path): Image
    {
        $this->assertPresent($path);

        $image = new Image(
            $this->filesystem->read($path),
            $this->filesystem->getMimetype($path),
            $path
        );

        return $image;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool
    {
        return $this->filesystem->has($path);
    }

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     */
    public function put(Image $image)
    {
        $this->filesystem->put(
            $image->getPath(),
            $image->getBinary(),
            ['mimetype' => $image->getMimeType()]
        );
    }

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     *
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    public function delete(Image $image)
    {
        $this->assertPresent($image->getPath());

        $this->filesystem->deleteDir($image->getDirname());
    }

    /**
     * @param string $path
     *
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    protected function assertPresent(string $path)
    {
        if (!$this->has($path)) {
            throw new ImageNotFoundException($path);
        }
    }
}
