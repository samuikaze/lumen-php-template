<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DevelopmentServer extends Command
{
    /**
     * Server port.
     *
     * @var int
     */
    const PORT = 8080;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cmd = 'php -S localhost:'.self::PORT.' -t ./public';
        $this->info('Your server will serve at localhost:'.self::PORT.'!');
        $this->info('press Ctrl + C to terminate the server');
        shell_exec($cmd);

        return 0;
    }
}
