<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Manuscript;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import:manuscripts')]
class ImportManuscriptsCommand extends Command {
    final public const SPACE = '/^\p{Z}+|\p{Z}+$/';

    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
    ) {
        parent::__construct(null);
    }

    protected function configure() : void {
        $this
            ->setDescription('Import title and call number from a CSV file.')
            ->addArgument('files', InputArgument::IS_ARRAY, 'List of CSV files to import')
            ->addOption('commit', null, InputOption::VALUE_NONE, 'Commit the import to the database')
            ->addOption('skip', null, InputOption::VALUE_REQUIRED, 'Number of header rows to skip', 1)
        ;
    }

    protected function import($file, $skip, $commit) : void {
        $handle = fopen($file, 'r');

        for ($i = 1; $i <= $skip; $i++) {
            fgetcsv($handle);
        }
        $this->logger->warn("Starting import of {$file} with commit:{$commit}.");
        while ($row = fgetcsv($handle)) {
            $call = preg_replace(self::SPACE, '', (string) $row[0]);
            $title = preg_replace(self::SPACE, '', (string) $row[1]);
            $untitled = ($title ? false : true);
            $manuscript = new Manuscript();
            $manuscript->setCallNumber($call);
            $manuscript->setTitle($untitled ? 'Poetry miscellany' : $title);
            $manuscript->setUntitled($untitled);
            if ($commit) {
                $this->em->persist($manuscript);
                $this->em->flush();
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void {
        $files = $input->getArgument('files');
        $skip = $input->getOption('skip');
        $commit = $input->getOption('commit');

        foreach ($files as $file) {
            $this->import($file, $skip, $commit);
        }
    }
}
