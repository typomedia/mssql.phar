<?php

namespace mssql\Command;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class QueryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('query')
            ->setDescription('Fetches data from database')
            ->setHelp('Executes a statement and returning the results...')
            ->addOption(
                'host',
                'S',
                InputOption::VALUE_OPTIONAL,
                'Server host',
                'localhost'
            )
            ->addOption(
                'user',
                'U',
                InputOption::VALUE_REQUIRED,
                'Username'
            )
            ->addOption(
                'pass',
                'P',
                InputOption::VALUE_REQUIRED,
                'Password'
            )
            ->addOption(
                'port',
                'T',
                InputOption::VALUE_OPTIONAL,
                'Port',
                1433
            )
            ->addOption(
                'query',
                'Q',
                InputOption::VALUE_OPTIONAL,
                'Query',
                "SELECT name, filename FROM master.dbo.sysdatabases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')"
            )
            ->addOption(
                'file',
                'F',
                InputOption::VALUE_OPTIONAL,
                'Input file'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $options = $input->getOptions();

        $output->writeln($this->getApplication()->getName() .  '@' . $this->getApplication()->getVersion() . ' by Typomedia Foundation, Philipp Speck');

        $pdo = new PDO('sqlsrv:Server=' . $options['host'] . ',' . $options['port'], $options['user'], $options['pass']);
        $pdo->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $options['file'] ? file_get_contents($options['file']) : $options['query'];

        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();

        $table = new Table($output);
        $table->setRows($results);
        $table->render();

        $end  = microtime(true);
        $time = round(($end - $start));

        $output->writeln("<info>($count rows affected in $time s)</info>");
    }
}
