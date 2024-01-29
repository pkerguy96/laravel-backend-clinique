<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOldZipFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-old-zip-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        try {

            $zipDirectory = storage_path("app/public");  // Adjust this path as needed


            // Get all zip files in the directory
            $zipFiles = glob($zipDirectory . '/download_*.zip');

            foreach ($zipFiles as $zipFile) {
                /*    // Check the file's last modification time
            $modificationTime = filemtime($zipFile);

            // Calculate the difference in seconds
            $differenceInSeconds = time() - $modificationTime; */

                // Delete zip files older than one hour (3600 seconds)
                /*   if ($differenceInSeconds > 3600) { */
                unlink($zipFile);
                $this->info("Deleted old zip file: $zipFile");
                /*    } */
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
