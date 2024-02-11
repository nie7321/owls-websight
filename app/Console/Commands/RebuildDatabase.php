<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class RebuildDatabase extends Command
{
    use ConfirmableTrait;

    protected $signature = 'db:rebuild';

    protected $description = 'Rebuild DB & regenerate IDE helper files';

    public function handle()
    {
        $this->call('migrate:fresh', ['--seed' => true]);
        $this->call('cache:clear');
        $this->call('ide-helper:models', ['-N' => true]);
        $this->call('db:seed', ['--class' => 'DemoSeeder']);

        return Command::SUCCESS;
    }
}
