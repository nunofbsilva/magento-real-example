<?php
/**
 * Test fixture: intentional violations for Norma Priority Advisor (commonly_neglected).
 * Rules: php-sec-production-information-disclosure-via-explicit-display-errors-activation,
 *        adobe-empty-catch-block
 */
declare(strict_types=1);

namespace NormaTest\PriorityAdvisor\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('normatest:debug')->setDescription('Norma test fixture command');
        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->enableErrorDisplay();
        $this->enableVerboseErrors();

        try {
            $output->writeln('Running debug command');
        } catch (\RuntimeException $e) {
        }

        return Command::SUCCESS;
    }

    /**
     * Turn on error display.
     *
     * @return void
     */
    private function enableErrorDisplay(): void
    {
        ini_set('display_errors', 1);
    }

    /**
     * Turn on error display using a string value.
     *
     * @return void
     */
    private function enableVerboseErrors(): void
    {
        ini_set('display_errors', 'On');
    }
}
