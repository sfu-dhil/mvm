<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Image.
 *
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"url" = "ImageUrl", "file" = "ImageFile"})
 */
abstract class Image extends AbstractEntity {
    const URL = 'url';

    const FILE = 'file';

    /**
     * @var Feature
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Feature", inversedBy="images")
     */
    private $feature;

    /**
     * @var Content
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Content", inversedBy="images")
     */
    private $content;

    public function __construct() {
        parent::__construct();
    }

    abstract public function getType();

    /**
     * Set feature.
     *
     * @param null|\AppBundle\Entity\Feature $feature
     *
     * @return Image
     */
    public function setFeature(Feature $feature = null) {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature.
     *
     * @return null|\AppBundle\Entity\Feature
     */
    public function getFeature() {
        return $this->feature;
    }

    /**
     * Set content.
     *
     * @param null|\AppBundle\Entity\Content $content
     *
     * @return Image
     */
    public function setContent(Content $content = null) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return null|\AppBundle\Entity\Content
     */
    public function getContent() {
        return $this->content;
    }
}
