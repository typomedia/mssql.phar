<?php

namespace mssql\Command;

use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class ExecCommand extends Command
{
    private $pdo;
    private $output;

    protected function configure()
    {
        $this
            ->setName('exec')
            ->setDescription('Executes statements')
            ->setHelp('Executes one or more statements...')
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
                'SELECT name FROM master.dbo.sysdatabases'
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
        $this->output = $output;

        $output->writeln($this->getApplication()->getName() .  '@' . $this->getApplication()->getVersion() . ' by Typomedia Foundation, Philipp Speck');

        $this->pdo = new PDO('sqlsrv:Server=' . $options['host'] . ',' . $options['port'], $options['user'], $options['pass']);
        $this->pdo->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $options['file'] ? file_get_contents($options['file']) : $options['query'];
        $this->exec($query);

        $end  = microtime(true);
        $time = round(($end - $start));

        $output->writeln('<info>Execution time: ' . $time . ' s</info>');
    }

    /**
     * @param string $query
     */
    private function exec($query)
    {
        try {
            $this->pdo->exec($query);
        } catch (PDOException $e) {
            $this->output->writeln('<error>' . $e->getMessage() . '</error>');
            exit(1);
        }
    }
}
