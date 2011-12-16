<?php

namespace Gregwar\ImageBundle\Services;

use Gregwar\ImageBundle\ImageHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Image manipulation service
 *
 * @author Gregwar <g.passault@gmail.com>
 */
class ImageHandling
{
    private $cache_dir;
    private $container;
    private $handler_class;

    public function __construct($cache_dir, $handler_class, ContainerInterface $container)
    {
        $this->cache_dir = $cache_dir;
        $this->handler_class = $handler_class;
        $this->container = $container;
    }

    /**
     * Get a manipulable image instance
     *
     * @param string $file the image path
     *
     * @return object a manipulable image instance
     */
    public function open($file)
    {
        return $this->createInstance($file);
    }

    /**
     * Get a new image
     *
     * @param $w the width
     * @param $h the height
     *
     * @return object a manipulable image instance
     */
    public function create($w, $h)
    {
        return $this->createInstance(null, $w, $h);
    }

    /**
     * Creates an instance defining the cache directory
     */
    private function createInstance($file, $w = null, $h = null)
    {
        $asset = $this->container->get('templating.helper.assets');

        $handler_class = $this->handler_class;
        $image = new $handler_class($file, $w, $h);

        $image->setCacheDir($this->cache_dir);

        $image->setFileCallback(function($file) use ($asset) {
            return $asset->getUrl($file);
        });

        return $image;
    }
}
