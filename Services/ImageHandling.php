<?php

namespace Gregwar\ImageBundle\Services;

use Gregwar\ImageBundle\ImageHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

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
    private $kernel;

    public function __construct($cache_dir, $handler_class, ContainerInterface $container, KernelInterface $kernel)
    {
        $this->cache_dir = $cache_dir;
        $this->handler_class = $handler_class;
        $this->container = $container;
        $this->kernel = $kernel;
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
        if (strlen($file)>=1 && $file[0] == '@') {
            $file = $this->kernel->locateResource($file);
        }

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
        $container = $this->container;

        $handler_class = $this->handler_class;
        $image = new $handler_class($file, $w, $h);

        $image->setCacheDir($this->cache_dir);

        $image->setFileCallback(function($file) use ($container) {
            return $container->get('templating.helper.assets')->getUrl($file);
        });

        return $image;
    }
}
