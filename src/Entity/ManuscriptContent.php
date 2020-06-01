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
 * ManuscriptContent.
 *
 * @ORM\Table(name="manuscript_content")
 * @ORM\Entity(repositoryClass="App\Repository\ManuscriptContentRepository")
 */
class ManuscriptContent extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $context;

    /**
     * @var Content
     * @ORM\ManyToOne(targetEntity="App\Entity\Content", inversedBy="manuscriptContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    /**
     * @var Manuscript
     * @ORM\ManyToOne(targetEntity="App\Entity\Manuscript", inversedBy="manuscriptContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manuscript;

    /**
     * @var PrintSource
     * @ORM\ManyToOne(targetEntity="App\Entity\PrintSource", inversedBy="manuscriptContents")
     * @ORM\JoinColumn(nullable=true)
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
    public function __toString() : string {
        return implode(', ', [$this->content, $this->manuscript]);
    }

    /**
     * Set context.
     *
     * @param null|string $context
     *
     * @return ManuscriptContent
     */
    public function setContext($context = null) {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context.
     *
     * @return null|string
     */
    public function getContext() {
        return $this->context;
    }

    /**
     * Set content.
     *
     * @param \App\Entity\Content $content
     *
     * @return ManuscriptContent
     */
    public function setContent(Content $content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return \App\Entity\Content
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return ManuscriptContent
     */
    public function setManuscript(Manuscript $manuscript) {
        $this->manuscript = $manuscript;

        return $this;
    }

    /**
     * Get manuscript.
     *
     * @return \App\Entity\Manuscript
     */
    public function getManuscript() {
        return $this->manuscript;
    }

    /**
     * Set printSource.
     *
     * @param \App\Entity\PrintSource $printSource
     *
     * @return ManuscriptContent
     */
    public function setPrintSource(PrintSource $printSource) {
        $this->printSource = $printSource;

        return $this;
    }

    /**
     * Get printSource.
     *
     * @return \App\Entity\PrintSource
     */
    public function getPrintSource() {
        return $this->printSource;
    }
}
