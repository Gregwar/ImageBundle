<?php

namespace Gregwar\ImageBundle;

use Gregwar\Image\Image;

/**
 * Image manipulation class
 *
 * @author Gregwar <g.passault@gmail.com>
 */
class ImageHandler extends Image
{
    protected $fileCallback = null;

    /**
     * @param null $originalFile
     * @param null $width
     * @param null $height
     * @param bool $throwException
     */
    public function __construct($originalFile = null, $width = null, $height = null, $throwException = null, $fallbackImage = null)
    {
        parent::__construct($originalFile, $width, $height);

        $this->useFallback(!$throwException);
        $this->setFallback($fallbackImage);
    }


    /**
     * Defines the callback to call to compute the new filename
     */
    public function setFileCallback($fileCallback)
    {
        $this->fileCallback = $fileCallback;
    }

    /**
     * When processing the filename, call the callback
     */
    protected function getFilename($filename)
    {
        $callback = $this->fileCallback;

        if (null === $callback || substr($filename, 0, 1) == '/') {
            return $filename;
        }

        return $callback($filename);
    }

    public function save($file, $type = 'guess', $quality = 80)
    {
        return parent::save($file, $type, $quality);
    }

    public function __toString()
    {
        return parent::__toString();
    }
}
