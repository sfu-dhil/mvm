<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ArchiveSource
 *
 * @ORM\Table(name="archive_source")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArchiveSourceRepository")
 */
class ArchiveSource extends AbstractSource
{
    /**
     * @var Collection|Manuscript[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Manuscript", mappedBy="archiveSource")
     */
    private $manuscripts;

    /**
     * Add manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return ArchiveSource
     */
    public function addManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    /**
     * Remove manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        return $this->manuscripts->removeElement($manuscript);
    }

    /**
     * Get manuscripts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscripts()
    {
        return $this->manuscripts;
    }
}
