<?php

namespace AppBundle\Command;

use AppBundle\Entity\Manuscript;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ImportManuscriptsCommand command.
 */
class ImportManuscriptsCommand extends ContainerAwareCommand
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    const SPACE = '/^\p{Z}+|\p{Z}+$/';

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, ?string $name = null) {
        parent::__construct($name);
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('app:import:manuscripts')
            ->setDescription('Import title and call number from a CSV file.')
            ->addArgument('files', InputArgument::IS_ARRAY, 'List of CSV files to import')
            ->addOption('commit', null, InputOption::VALUE_NONE, 'Commit the import to the database')
            ->addOption('skip', null, InputOption::VALUE_REQUIRED, 'Number of header rows to skip', 1)
        ;
    }

    protected function import($file, $skip, $commit) {
        $handle = fopen($file, 'r');
        for($i = 1; $i <= $skip; $i++) {
            fgetcsv($handle);
        }
        $this->logger->warn("Starting import of {$file} with commit:{$commit}.");
        while($row = fgetcsv($handle)) {
            $call = preg_replace(self::SPACE, '', $row[0]);
            $title = preg_replace(self::SPACE, '', $row[1]);
            $untitled = ($title ? false : true);
            $manuscript = new Manuscript();
            $manuscript->setCallNumber($call);
            $manuscript->setTitle(($untitled ? 'Poetry miscellany' : $title));
            $manuscript->setUntitled($untitled);
            if($commit) {
                $this->em->persist($manuscript);
                $this->em->flush();
            }
        }
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     *   Command input, as defined in the configure() method.
     * @param OutputInterface $output
     *   Output destination.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $input->getArgument('files');
        $skip = $input->getOption('skip');
        $commit = $input->getOption('commit');
        foreach($files as $file) {
            $this->import($file, $skip, $commit);
        }
    }

}
