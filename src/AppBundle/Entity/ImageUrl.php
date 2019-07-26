<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageUrl
 *
 * @ORM\Table(name="image_url")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageUrlRepository")
 */
class ImageUrl extends Image
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
