<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Image
 *
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"url" = "ImageUrl", "file" = "ImageFile"})
 */
abstract class Image extends AbstractEntity
{

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

    abstract public function getType();

    public function __construct() {
        parent::__construct();
    }

    /**
     * Set feature.
     *
     * @param \AppBundle\Entity\Feature|null $feature
     *
     * @return Image
     */
    public function setFeature(\AppBundle\Entity\Feature $feature = null)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature.
     *
     * @return \AppBundle\Entity\Feature|null
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set content.
     *
     * @param \AppBundle\Entity\Content|null $content
     *
     * @return Image
     */
    public function setContent(\AppBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return \AppBundle\Entity\Content|null
     */
    public function getContent()
    {
        return $this->content;
    }
}
