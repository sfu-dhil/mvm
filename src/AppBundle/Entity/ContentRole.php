<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ContentRole
 *
 * @ORM\Table(name="content_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentRoleRepository")
 */
class ContentRole extends AbstractTerm
{

    /**
     * @var Collection|ContentContribution[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContentContribution", mappedBy="role")
     */
    private $contributions;

    /**
     * Add contribution.
     *
     * @param \AppBundle\Entity\ContentContribution $contribution
     *
     * @return ContentRole
     */
    public function addContribution(\AppBundle\Entity\ContentContribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     *
     * @param \AppBundle\Entity\ContentContribution $contribution
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContribution(\AppBundle\Entity\ContentContribution $contribution)
    {
        return $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }
}
