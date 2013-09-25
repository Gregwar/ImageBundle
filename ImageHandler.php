<?php

namespace Gregwar\ImageBundle;

use Gregwar\ImageBundle\Image;

/**
 * Image manipulation class
 *
 * @author Gregwar <g.passault@gmail.com>
 */
class ImageHandler extends Image
{
    protected $fileCallback   = null;
    protected $throwException = null;

    /**
     * @param null $originalFile
     * @param null $width
     * @param null $height
     * @param bool $throwException
     */
    public function __construct($originalFile = null, $width = null, $height = null, $throwException = null)
    {
        $this->throwException = $throwException;
        parent::__construct($originalFile, $width, $height);
    }


    /**
     * Defines the callback to call to compute the new filename
     */
    public function setFileCallback($file)
    {
        $this->fileCallback = $file;
    }

    /**
     * When processing the filename, call the callback
     */
    protected function getFilename($filename)
    {
        $callback = $this->fileCallback;

        if (null === $callback || substr($filename, 0, 1) == '/')
            return $filename;

        return $callback($filename);
    }

    public function save($file, $type = 'guess', $quality = 80)
    {
        try{
            return parent::save($file, $type, $quality);
        } catch(\Exception $e) {
            if($this->throwException) {
                throw $e;
            }
            return false;
        }
    }

    public function __toString()
    {
        try{
            return parent::__toString();
        } catch(\Exception $e) {
            if($this->throwException) {
                return $e->getMessage();
            }
            return '';
        }
    }
}

