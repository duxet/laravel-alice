<?php namespace duxet\Alice\Console;

use Illuminate\Console\Command;

class SeedCommand extends Command
{
    protected $name = 'alice:seed';
    protected $description = 'Seed database using Alice fixtures';

    public function fire()
    {
        $path = base_path('database/seeds/*.yml');
        $files = $this->laravel['files']->glob($path);

        $objects = $this->laravel['alice.loader']->load($files);
        $this->laravel['alice.persister']->persist($objects);
    }
}
