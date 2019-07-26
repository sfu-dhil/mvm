<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageFile
 *
 * @ORM\Table(name="image_file")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageFileRepository")
 */
class ImageFile extends Image
{

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return get_class() . ' #' . $this->id;
    }

    public function getType() {
        return Image::FILE;
    }
}
