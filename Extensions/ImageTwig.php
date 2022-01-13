<?php

namespace Gregwar\ImageBundle\Extensions;

use Gregwar\ImageBundle\Services\ImageHandling;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * ImageTwig extension.
 *
 * @author Gregwar <g.passault@gmail.com>
 * @author bzikarsky <benjamin.zikarsky@perbility.de>
 */
class ImageTwig extends AbstractExtension
{
    /**
     * @var ImageHandling
     */
    private $imageHandling;

    /**
     * @var string
     */
    private $webDir;

    /**
     * @param ImageHandling $imageHandling
     * @param string        $webDir
     */
    public function __construct(ImageHandling $imageHandling, $webDir)
    {
        $this->imageHandling = $imageHandling;
        $this->webDir = $webDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('image', array($this, 'image'), array('is_safe' => array('html'))),
            new TwigFunction('new_image', array($this, 'newImage'), array('is_safe' => array('html'))),
            new TwigFunction('web_image', array($this, 'webImage'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param string $path
     *
     * @return object
     */
    public function webImage($path)
    {
        $directory = $this->webDir.'/';

        return $this->imageHandling->open($directory.$path);
    }

    /**
     * @param string $path
     *
     * @return object
     */
    public function image($path)
    {
        return $this->imageHandling->open($path);
    }

    /**
     * @param string $width
     * @param string $height
     *
     * @return object
     */
    public function newImage($width, $height)
    {
        return $this->imageHandling->create($width, $height);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'image';
    }
}
