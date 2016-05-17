<?php

namespace livetyping\hermitage\foundation\images\processor\manipulators;

use Intervention\Image\Image;

/**
 * Class Manipulator
 *
 * @package livetyping\hermitage\foundation\images\processor\manipulators
 */
abstract class Manipulator
{
    /**
     * Manipulator constructor.
     *
     * @param array $config
     */
    final public function __construct(array $config)
    {
        $this->configure($config);
    }
    
    /**
     * @param \Intervention\Image\Image $image
     *
     * @return void
     */
    abstract public function run(Image $image);

    /**
     * @param array $config
     *
     * @return void
     */
    abstract protected function configure(array $config);
}
