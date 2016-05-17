<?php

namespace livetyping\hermitage\foundation\contracts\images;

use livetyping\hermitage\foundation\entities\Image;

/**
 * Interface Processor
 *
 * @package livetyping\hermitage\foundation\contracts\images
 */
interface Processor
{
    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     */
    public function optimize(Image $image): Image;

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     * @param string                     $version
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     * @throws \livetyping\hermitage\foundation\exceptions\UnknownVersionNameException
     */
    public function make(Image $image, string $version): Image;
}
