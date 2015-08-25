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
    private $cacheDirectory;
    private $cacheDirMode;
    private $container;
    private $handlerClass;
    private $kernel;
    private $throwException;

    public function __construct($cacheDirectory, $cacheDirMode, $handlerClass, ContainerInterface $container, KernelInterface $kernel, $throwException, $fallbackImage)
    {
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheDirMode = intval($cacheDirMode);
        $this->handlerClass = $handlerClass;
        $this->container = $container;
        $this->kernel = $kernel;
        $this->throwException = $throwException;
        $this->fallbackImage = $fallbackImage;
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
        $webDir = $container->getParameter('gregwar_image.web_dir');

        $handlerClass = $this->handlerClass;
        $image = new $handlerClass($file, $w, $h, $this->throwException, $this->fallbackImage);

        $image->setCacheDir($this->cacheDirectory);
        $image->setCacheDirMode($this->cacheDirMode);
        $image->setActualCacheDir($webDir.'/'.$this->cacheDirectory);

        $image->setFileCallback(function($file) use ($container) {
            return $container->get('templating.helper.assets')->getUrl($file);
        });

        return $image;
    }
}
