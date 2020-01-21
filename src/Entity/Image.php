<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

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
    public const URL = 'url';

    public const FILE = 'file';

    /**
     * @var Feature
     * @ORM\ManyToOne(targetEntity="App\Entity\Feature", inversedBy="images")
     */
    private $feature;

    /**
     * @var Content
     * @ORM\ManyToOne(targetEntity="App\Entity\Content", inversedBy="images")
     */
    private $content;

    public function __construct() {
        parent::__construct();
    }

    abstract public function getType();

    /**
     * Set feature.
     *
     * @param null|\App\Entity\Feature $feature
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
     * @return null|\App\Entity\Feature
     */
    public function getFeature() {
        return $this->feature;
    }

    /**
     * Set content.
     *
     * @param null|\App\Entity\Content $content
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
     * @return null|\App\Entity\Content
     */
    public function getContent() {
        return $this->content;
    }
}
