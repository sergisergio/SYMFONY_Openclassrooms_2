<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 20/07/2019
 * Time: 22:49
 */

namespace App\Command;


use App\Service\PurgerAdvert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeEntitiesCommand extends Command
{
    /**
     * @var PurgerAdvert
     */
    private $purger;

    protected static $defaultName = 'app:purge-entities';

    public function __construct(PurgerAdvert $purger)
    {
        $this->purger = $purger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Purges entities.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command is a test to purge entities...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Purge Entities',
            '============',
            '',
        ]);

        $progressBar = new ProgressBar($output, 1);
        $progressBar->setFormat('debug');


        $this->purger->purge(15);

        $progressBar->advance();

        $progressBar->finish();

        $output->writeln('');
        // outputs a message without adding a "\n" at the end of the line
        $output->writeln('<info>Entities have been purged !</info>');
    }

}