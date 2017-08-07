<?php

namespace Zendesk;

require 'DocMethodMatcher.php';

use Psy\Configuration;
use Psy\Shell;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zendesk\API\HttpClient;

class LibagerCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('libager')
            ->setDescription('Test out features of the php api client.')
            ->addArgument('subdomain', InputArgument::REQUIRED)
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('token', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Configuration;
        $config->addTabCompletionMatchers([new DocMethodMatcher()]);

        $shell = new Shell($config);

        $client = new HttpClient($input->getArgument('subdomain'));
        $client->setAuth('basic', [
            'username' => $input->getArgument('username'),
            'token' => $input->getArgument('token')
        ]);

        $shell->setScopeVariables(['ticket' => 'karl']);
        $shell->setBoundObject($client);

        $shell->run();
    }
}
