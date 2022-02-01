<?php

namespace App\Command;

use App\Entity\Manuscript;
use App\Entity\ManuscriptContribution;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
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
    private UrlGeneratorInterface $generator;

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
        foreach($this->repo->findAll() as $manuscript) {
            /** @var Manuscript $manuscript $contributions */
            $contributions = $manuscript->getManuscriptContributions()->filter(function(ManuscriptContribution $mc) {
                return $mc->getRole()->getName() === 'compiler';
            });
            $iterator = $contributions->getIterator();
            $iterator->uasort(function(ManuscriptContribution $a, ManuscriptContribution $b) {
                return strcmp($a->getPerson()->getSortableName(), $b->getPerson()->getSortableName());
            });

            $citation = '';
            foreach($iterator as $contribution) {
                if($citation) {
                    $citation .= ' and ';
                }
                $citation .= $contribution->getPerson()->getSortableName();
            }
            $citation .= '. ';
            if($manuscript->getUntitled()) {
                $citation .= '[Untitled].';
            } else {
                $citation .= '"' . $manuscript->getTitle();
                if (ctype_punct($manuscript->getCallNumber()[-1])) {
                    $citation .= '"';
                }
                else {
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
    public function setEntityManager(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setUrlGenerator(UrlGeneratorInterface $generator) {
        $this->generator = $generator;
    }

}
