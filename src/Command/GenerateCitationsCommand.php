<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\Manuscript;
use App\Entity\ManuscriptContribution;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateCitationsCommand extends Command {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ManuscriptRepository;
     */
    private $repo;

    private ?UrlGeneratorInterface $generator = null;

    /**
     * Configure the command.
     */
    protected function configure() : void {
        $this->setName('app:generate:citations');
        $this->setDescription('Generate citations for each manuscript');
    }

    /**
     * Execute the command.
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void {
        foreach ($this->repo->findAll() as $manuscript) {
            /** @var Manuscript $manuscript $contributions */
            $contributions = $manuscript->getManuscriptContributions()->filter(fn (ManuscriptContribution $mc) => 'compiler' === $mc->getRole()->getName());
            $iterator = $contributions->getIterator();
            $iterator->uasort(fn (ManuscriptContribution $a, ManuscriptContribution $b) => strcmp($a->getPerson()->getSortableName(), $b->getPerson()->getSortableName()));

            $citation = '';
            foreach ($iterator as $contribution) {
                if ($citation) {
                    $citation .= ' and ';
                }
                $citation .= $contribution->getPerson()->getSortableName();
            }
            $citation .= '. ';
            if ($manuscript->getUntitled()) {
                $citation .= '[Untitled].';
            } else {
                $citation .= '"' . $manuscript->getTitle();
                if (ctype_punct($manuscript->getCallNumber()[-1])) {
                    $citation .= '"';
                } else {
                    $citation .= '."';
                }
            }

            $citation .= ' ' . $manuscript->getCallNumber() . ', ';
            $citation .= '<i>Manuscript Verse Miscellanies, 1700-1820</i>, 2021, ';
            $citation .= 'manuscript ID ' . $manuscript->getId() . ', ';

            $url = $this->generator->generate('manuscript_show', ['id' => $manuscript->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $citation .= "<a href='{$url}'>{$url}</a>.";

            $manuscript->setCitation($citation);
            $this->em->flush();
        }
    }

    /**
     * @required
     */
    public function setManuscriptRepository(ManuscriptRepository $repo) : void {
        $this->repo = $repo;
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setUrlGenerator(UrlGeneratorInterface $generator) : void {
        $this->generator = $generator;
    }
}
