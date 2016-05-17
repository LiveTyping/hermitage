<?php

namespace livetyping\hermitage\foundation\images\processor\manipulators;

use Intervention\Image\Constraint;
use Intervention\Image\Image;

/**
 * Class Resize
 *
 * @package livetyping\hermitage\foundation\images\processor\manipulators
 */
final class Resize extends Manipulator
{
    /** @var int|null */
    protected $width;

    /** @var int|null */
    protected $height;

    /** @var bool */
    protected $aspectRatio;
    
    /** @var bool */
    protected $interlace;

    /**
     * @param \Intervention\Image\Image $image
     */
    public function run(Image $image)
    {
        $callback = function (Constraint $constraint) {
            if ($this->aspectRatio) {
                $constraint->aspectRatio();
            }
            $constraint->upsize();
        };

        $image->resize($this->width, $this->height, $callback)->interlace($this->interlace);
    }

    /**
     * @param array $config
     */
    protected function configure(array $config)
    {
        $this->width = $config['width'] ?? null;
        $this->height = $config['height'] ?? null;
        $this->aspectRatio = $config['aspectRatio'] ?? true;
        $this->interlace = $config['interlace'] ?? true;
    }
}
