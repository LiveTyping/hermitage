<?php

namespace livetyping\hermitage\foundation\images\processor\manipulators;

use Intervention\Image\Constraint;
use Intervention\Image\Image;

/**
 * Class Optimize
 *
 * @package livetyping\hermitage\foundation\images\processor\manipulators
 */
final class Optimize extends Manipulator
{
    /** @var int|null */
    protected $maxWidth;

    /** @var int|null */
    protected $maxHeight;
    
    /** @var bool */
    protected $interlace;

    /**
     * @param \Intervention\Image\Image $image
     */
    public function run(Image $image)
    {
        $callback = function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        };

        $image->resize($this->maxWidth, $this->maxHeight, $callback)->interlace($this->interlace);
    }

    /**
     * @param array $config
     */
    protected function configure(array $config)
    {
        $this->maxWidth = $config['maxWidth'] ?? null;
        $this->maxHeight = $config['maxHeight'] ?? null;
        $this->interlace = $config['interlace'] ?? true;
    }
}
