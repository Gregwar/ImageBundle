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

    public function __construct($cache_dir, ContainerInterface $container)
    {
        $this->cache_dir = $cache_dir;
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
     * Creates an instance defining the cache directory
     */
    private function createInstance($file)
    {
        $asset = $this->container->get('templating.helper.assets');

        $image = new ImageHandler($file);

        $image->setCacheDir($this->cache_dir);

        $image->setFileCallback(function($file) use ($asset) {
            return $asset->getUrl($file);
        });

        return $image;
    }
}
