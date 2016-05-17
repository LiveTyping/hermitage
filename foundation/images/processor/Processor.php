<?php

namespace livetyping\hermitage\foundation\images\processor;

use livetyping\hermitage\foundation\contracts\images\Processor as ProcessorContract;
use livetyping\hermitage\foundation\entities\Image;
use livetyping\hermitage\foundation\exceptions\Exception;
use livetyping\hermitage\foundation\exceptions\UnknownVersionNameException;
use livetyping\hermitage\foundation\images\processor\manipulators\Manipulator;
use livetyping\hermitage\foundation\images\processor\manipulators\Fit;
use livetyping\hermitage\foundation\images\processor\manipulators\Optimize;
use livetyping\hermitage\foundation\images\processor\manipulators\Resize;
use Intervention\Image\ImageManager;
use function Assert\that;

/**
 * Class Processor
 *
 * @package livetyping\hermitage\foundation\images\processor
 */
class Processor implements ProcessorContract
{
    /** @var ImageManager */
    protected $manager;

    /** @var array */
    protected $optimizationParams = ['maxHeight' => 800, 'maxWidth' => 800, 'interlace' => true];

    /** @var array */
    protected $versions = [
        'thumb' => [
            'type' => 'fit',
            'height' => 200,
            'width' => 200,
        ],
    ];

    /** @var array */
    protected $manipulatorMap = [
        'optimize' => Optimize::class,
        'resize' => Resize::class,
        'fit' => Fit::class,
    ];

    /**
     * Processor constructor.
     *
     * @param \Intervention\Image\ImageManager|null $manager
     */
    public function __construct(ImageManager $manager = null)
    {
        $this->manager = $manager !== null ? $manager : new ImageManager();
    }

    /**
     * @param array $map
     */
    public function addManipulatorMap(array $map)
    {
        $this->manipulatorMap = array_replace($this->manipulatorMap, $map);
    }

    /**
     * @param array $params
     */
    public function setOptimizationParams(array $params)
    {
        $this->optimizationParams = $params;
    }

    /**
     * @param array $versions
     */
    public function setVersions(array $versions)
    {
        that($versions)->all()->isArray()->notEmptyKey('type');
        $this->versions = $versions;
    }

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     */
    public function optimize(Image $image): Image
    {
        $binary = $this->manipulate($image->getBinary(), 'optimize', $this->optimizationParams);

        return $image->modify($binary);
    }

    /**
     * @param \livetyping\hermitage\foundation\entities\Image $image
     * @param string                     $version
     *
     * @return \livetyping\hermitage\foundation\entities\Image
     * @throws \livetyping\hermitage\foundation\exceptions\UnknownVersionNameException
     */
    public function make(Image $image, string $version): Image
    {
        $config = $this->getVersionConfig($version);
        $manipulator = $this->extractManipulatorNameFromConfig($config);

        $binary = $this->manipulate($image->getBinary(), $manipulator, $config);

        return $image->modify($binary, $version);
    }

    /**
     * @param string $version
     *
     * @return array
     * @throws \livetyping\hermitage\foundation\exceptions\UnknownVersionNameException
     */
    protected function getVersionConfig(string $version): array
    {
        if (!isset($this->versions[$version])) {
            throw new UnknownVersionNameException();
        }

        return $this->versions[$version];
    }

    /**
     * @param string $binary
     * @param string $name
     * @param array  $config
     *
     * @return string
     */
    protected function manipulate(string $binary, string $name, array $config): string
    {
        $image = $this->manager->make($binary);
        $this->createManipulator($name, $config)->run($image);
        $image->encode();

        return (string)$image;
    }

    /**
     * @param string $name
     * @param array  $config
     *
     * @return \livetyping\hermitage\foundation\images\processor\manipulators\Manipulator
     * @throws \livetyping\hermitage\foundation\exceptions\Exception
     */
    protected function createManipulator(string $name, array $config): Manipulator
    {
        $class = $this->getManipulatorClass($name);
        that($class)->notBlank()->subclassOf(Manipulator::class);

        return new $class($config);
    }

    /**
     * @param array $config
     *
     * @return string
     */
    protected function extractManipulatorNameFromConfig(array $config): string
    {
        that($config)->notEmptyKey('type');

        return (string)$config['type'];
    }

    /**
     * @param string $name
     *
     * @return string
     * @throws \livetyping\hermitage\foundation\exceptions\Exception
     */
    protected function getManipulatorClass(string $name): string
    {
        if (!isset($this->manipulatorMap[$name])) {
            throw new Exception("Unknown manipulator name: {$name}.");
        }

        return $this->manipulatorMap[$name];
    }
}
