<?php
namespace Grav\Plugin\Console;

use Github\Api\Enterprise\License;
use Grav\Common\GPM\Licenses;
use Grav\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RemoveLicenseCommand
 *
 * @package Grav\Plugin\Console
 */
class RemoveLicenseCommand extends ConsoleCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName("remove")
            ->setDescription("Removes a license")
            ->addOption(
                'slug',
                's',
                InputOption::VALUE_REQUIRED,
                'The product slug (e.g. admin-pro)'
            )
            ->setHelp('The <info>remove command</info> deletes a license entry for a premium plugin/theme')
        ;
    }

    /**
     * @return int|null|void
     */
    protected function serve()
    {
        $this->output->writeln('');
        $this->output->writeln('<magenta>Removing License</magenta>');
        $this->output->writeln('');

        $slug = $this->input->getOption('slug', null);

        $result = Licenses::set($slug, false);

        if ($result) {
            $this->output->writeln('Removed license for: <cyan>' . $slug . '</cyan>');
        } else {
            $this->output->writeln('<red>ERROR: no license found for:</red> <cyan>' . $slug . '</cyan>');
        }


    }

}

