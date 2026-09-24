<?php

namespace IgorSmoleac\DesignLaravelKit\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'design-laravel-kit:install';

    protected $description = 'Install design-laravel-kit: publish config and assets';

    public function handle(): int
    {
        $this->call('vendor:publish', ['--tag' => 'design-laravel-kit-config']);
        $this->call('design-laravel-kit:publish-assets');

        $this->info('Next steps:');
        $this->line(' - Add @designLaravelKitStyles to <head>');
        $this->line(' - Add @designLaravelKitScripts before </body>');

        return Command::SUCCESS;
    }
}
