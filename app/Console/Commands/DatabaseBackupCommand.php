<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A Backup file of the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getFilePath($filename)
    {
        return storage_path().'//backup/'.$filename;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = $this->argument('fileName');

        $filePath = $this->getFilePath($filename);

        $command = ''.env('DUMP_PATH').' --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE').'  > '.$filePath;

        $returnVar = null;
        $output = null;

        exec($command, $output, $returnVar);

        $this->info($filePath);

        return 0;
    }
}
