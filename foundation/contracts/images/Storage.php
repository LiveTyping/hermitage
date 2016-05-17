<?php

namespace livetyping\hermitage\foundation\contracts\images;

use livetyping\hermitage\foundation\entities\Image;

/**
 * Interface Storage
 *
 * @package livetyping\hermitage\foundation\contracts\images
 */
interface Storage
{
    /**
     * @param string $path
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    public function get(string $path): Image;

    /**
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     *
     * @return void
     */
    public function put(Image $image);

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     *
     * @return void
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    public function delete(Image $image);
}
