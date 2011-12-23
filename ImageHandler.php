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
    protected $fileCallback = null;

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

        if (null === $callback)
            return $filename;

        return $callback($filename);
    }
}

