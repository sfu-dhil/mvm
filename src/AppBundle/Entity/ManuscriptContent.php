<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ManuscriptContent
 *
 * @ORM\Table(name="manuscript_content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ManuscriptContentRepository")
 */
class ManuscriptContent extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $context;

    /**
     * @var Content
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Content", inversedBy="manuscriptContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    /**
     * @var Manuscript
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Manuscript", inversedBy="manuscriptContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manuscript;

    /**
     * @var PrintSource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PrintSource", inversedBy="manuscriptContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $printSource;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return implode(', ', array($this->content, $this->manuscript));
    }

    /**
     * Set context.
     *
     * @param string|null $context
     *
     * @return ManuscriptContent
     */
    public function setContext($context = null)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context.
     *
     * @return string|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set content.
     *
     * @param \AppBundle\Entity\Content $content
     *
     * @return ManuscriptContent
     */
    public function setContent(\AppBundle\Entity\Content $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return \AppBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return ManuscriptContent
     */
    public function setManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        $this->manuscript = $manuscript;

        return $this;
    }

    /**
     * Get manuscript.
     *
     * @return \AppBundle\Entity\Manuscript
     */
    public function getManuscript()
    {
        return $this->manuscript;
    }

    /**
     * Set printSource.
     *
     * @param \AppBundle\Entity\PrintSource $printSource
     *
     * @return ManuscriptContent
     */
    public function setPrintSource(\AppBundle\Entity\PrintSource $printSource)
    {
        $this->printSource = $printSource;

        return $this;
    }

    /**
     * Get printSource.
     *
     * @return \AppBundle\Entity\PrintSource
     */
    public function getPrintSource()
    {
        return $this->printSource;
    }
}
