<?php

namespace Gregwar\ImageBundle\Services;

use Gregwar\ImageBundle\ImageHandler;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Image manipulation service.
 *
 * @author Gregwar <g.passault@gmail.com>
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class ImageHandling
{
    /**
     * @var string
     */
    private $cacheDirectory;

    /**
     * @var int
     */
    private $cacheDirMode;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $handlerClass;

    /**
     * @var FileLocatorInterface|KernelInterface
     */
    private $fileLocator;

    /**
     * @var bool
     */
    private $throwException;

    /**
     * @param string                               $cacheDirectory
     * @param int                                  $cacheDirMode
     * @param string                               $handlerClass
     * @param ContainerInterface                   $container
     * @param KernelInterface|FileLocatorInterface $fileLocator
     * @param bool                                 $throwException
     * @param string                               $fallbackImage
     */
    public function __construct($cacheDirectory, $cacheDirMode, $handlerClass, ContainerInterface $container, $fileLocator, $throwException, $fallbackImage)
    {
        if (!$fileLocator instanceof FileLocatorInterface && $fileLocator instanceof KernelInterface) {
            throw new \InvalidArgumentException(
                'Argument 5 passed to '.__METHOD__.' must be an instance of '.
                'Symfony\Component\Config\FileLocatorInterface or Symfony\Component\HttpKernel\KernelInterface.'
            );
        }

        if ($fileLocator instanceof KernelInterface) {
            @trigger_error(
                'Pass Symfony\Component\HttpKernel\KernelInterface to '.__CLASS__.
                ' is deprecated since version 2.1.0 and will be removed in 3.0.'.
                ' Use Symfony\Component\Config\FileLocatorInterface instead.',
                E_USER_DEPRECATED
            );
        }

        $this->cacheDirectory = $cacheDirectory;
        $this->cacheDirMode = intval($cacheDirMode);
        $this->handlerClass = $handlerClass;
        $this->container = $container;
        $this->fileLocator = $fileLocator;
        $this->throwException = $throwException;
        $this->fallbackImage = $fallbackImage;
    }

    /**
     * Get a manipulable image instance.
     *
     * @param string $file the image path
     *
     * @return ImageHandler a manipulable image instance
     */
    public function open($file)
    {
        if (strlen($file) >= 1 && $file[0] == '@') {
            try {
                if ($this->fileLocator instanceof FileLocatorInterface) {
                    $file = $this->fileLocator->locate($file);
                } else {
                    $this->fileLocator->locateResource($file);
                }
            } catch (\InvalidArgumentException $exception) {
                if ($this->throwException || false == $this->fallbackImage) {
                    throw $exception;
                }

                $file = $this->fallbackImage;
            }
        }

        return $this->createInstance($file);
    }

    /**
     * Get a new image.
     *
     * @param string $w the width
     * @param string $h the height
     *
     * @return ImageHandler a manipulable image instance
     */
    public function create($w, $h)
    {
        return $this->createInstance(null, $w, $h);
    }

    /**
     * Creates an instance defining the cache directory.
     *
     * @param string      $file
     * @param string|null $w
     * @param string|null $h
     *
     * @return ImageHandler
     */
    private function createInstance($file, $w = null, $h = null)
    {
        $container = $this->container;
        $webDir = $container->getParameter('gregwar_image.web_dir');

        $handlerClass = $this->handlerClass;
        /** @var ImageHandler $image */
        $image = new $handlerClass($file, $w, $h, $this->throwException, $this->fallbackImage);

        $image->setCacheDir($this->cacheDirectory);
        $image->setCacheDirMode($this->cacheDirMode);
        $image->setActualCacheDir($webDir.'/'.$this->cacheDirectory);

        if ($container->has('templating.helper.assets')) {
            $image->setFileCallback(function ($file) use ($container) {
                return $container->get('templating.helper.assets')->getUrl($file);
            });
        } else {
            $image->setFileCallback(function ($file) use ($container) {
                return $container->get('assets.packages')->getUrl($file);
            });
        }

        return $image;
    }
}
