<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\Manuscript;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ImportManuscriptsCommand command.
 */
class ImportManuscriptsCommand extends Command {
    public const SPACE = '/^\p{Z}+|\p{Z}+$/';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, ?string $name = null) {
        parent::__construct($name);
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Configure the command.
     */
    protected function configure() : void {
        $this
            ->setName('app:import:manuscripts')
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
            $call = preg_replace(self::SPACE, '', $row[0]);
            $title = preg_replace(self::SPACE, '', $row[1]);
            $untitled = ($title ? false : true);
            $manuscript = new Manuscript();
            $manuscript->setCallNumber($call);
            $manuscript->setTitle(($untitled ? 'Poetry miscellany' : $title));
            $manuscript->setUntitled($untitled);
            if ($commit) {
                $this->em->persist($manuscript);
                $this->em->flush();
            }
        }
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     *                              Command input, as defined in the configure() method.
     * @param OutputInterface $output
     *                                Output destination.
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void {
        $files = $input->getArgument('files');
        $skip = $input->getOption('skip');
        $commit = $input->getOption('commit');
        foreach ($files as $file) {
            $this->import($file, $skip, $commit);
        }
    }
}
