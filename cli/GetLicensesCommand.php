<?php
namespace Grav\Plugin\Console;

use Github\Api\Enterprise\License;
use Grav\Common\GPM\Licenses;
use Grav\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class GetLicensesCommand
 *
 * @package Grav\Plugin\Console
 */
class GetLicensesCommand extends ConsoleCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName("get")
            ->setDescription("Adds a license")
            ->addOption(
                'slug',
                's',
                InputOption::VALUE_OPTIONAL,
                'The product slug (e.g. admin-pro)'
            )
            ->setHelp('The <info>add command</info> adds a license entry for a premium plugin/theme')
        ;
    }

    /**
     * @return int|null|void
     */
    protected function serve()
    {
        xdebug_break();
        $this->output->writeln('');
        $this->output->writeln('<magenta>Displaying License</magenta>');
        $this->output->writeln('');

        $slug = $this->input->getOption('slug', null);

        $licenses = Licenses::get($slug);

        if (is_array($licenses)) {
            foreach ($licenses as $slug => $license) {
                $this->output->writeln('Found license for: <cyan>' . $slug . '</cyan> = <yellow>'. $license . '</yellow>');
            }
        } else {
            $this->output->writeln('Found license for: <cyan>' . $slug . '</cyan> = <yellow>'. $licenses . '</yellow>');
        }

    }

}

