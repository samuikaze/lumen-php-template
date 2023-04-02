<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ApplicationKeyGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application key';

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
        $key = 'APP_KEY=base64:'.base64_encode(Str::random(32));

        $filename = base_path('.env');

        $contents = file_get_contents($filename);
        $contents = str_replace("\r\n", "\n", $contents);
        $contents = explode("\n", $contents);

        $contents = array_map(function ($content) use ($key) {
            if (stripos($content, 'APP_KEY') === 0) {
                return $key;
            }

            return $content;
        }, $contents);

        $contents = implode("\n", $contents);

        file_put_contents($filename, $contents);

        return 0;
    }
}
