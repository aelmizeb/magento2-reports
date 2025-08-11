<?php
namespace Originalapp\Reports\Console\Command;

use Originalapp\Reports\Cron\Stats;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatCommand extends Command
{
    /**
     * @var Stats
     */
    private $stats;

    /**
     * StatCommand constructor.
     */
    public function __construct(
        Stats $stats
    ) {
        $this->stats = $stats;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('originalapp-reports:stats')
            ->setDescription('Originalapp Reports Stats')
        ;
        parent::configure();
    }

    /**
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->stats->execute()) {
            return Cli::RETURN_SUCCESS;
        }

        return Cli::RETURN_FAILURE;
    }
}
