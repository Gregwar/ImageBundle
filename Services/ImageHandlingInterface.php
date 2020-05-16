<?php


namespace Gregwar\ImageBundle\Services;


use Gregwar\ImageBundle\ImageHandler;

interface ImageHandlingInterface
{
    /**
     * Get a manipulable image instance.
     *
     * @param string $file the image path
     *
     * @return ImageHandler a manipulable image instance
     */
    public function open($file);

    /**
     * Get a new image.
     *
     * @param string $w the width
     * @param string $h the height
     *
     * @return ImageHandler a manipulable image instance
     */
    public function create($w, $h);
}