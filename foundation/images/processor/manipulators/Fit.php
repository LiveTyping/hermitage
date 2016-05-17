<?php

namespace livetyping\hermitage\foundation\images\processor\manipulators;

use Intervention\Image\Constraint;
use Intervention\Image\Image;

/**
 * Class Fit
 *
 * @package livetyping\hermitage\foundation\images\processor\manipulators
 */
final class Fit extends Manipulator
{
    /** @var int|null */
    protected $width;
    
    /** @var int|null */
    protected $height;
    
    /** @var string */
    protected $position;
    
    /** @var bool */
    protected $interlace;

    /**
     * @param \Intervention\Image\Image $image
     *
     * @return void
     */
    public function run(Image $image)
    {
        $callback = function (Constraint $constraint) {
            $constraint->upsize();
        };
        
        $image->fit($this->width, $this->height, $callback, $this->position)->interlace($this->interlace);
    }

    /**
     * @param array $config
     *
     * @return void
     */
    protected function configure(array $config)
    {
        $this->width = $config['width'] ?? null;
        $this->height = $config['height'] ?? null;
        $this->position = $config['position'] ?? 'center';
        $this->interlace = $config['interlace'] ?? true;
    }
}
