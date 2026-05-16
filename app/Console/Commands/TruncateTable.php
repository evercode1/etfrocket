<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class TruncateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate-table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate a database table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $table = trim($this->argument('table'));

        try {

            if (! Schema::hasTable($table)) {
                throw new InvalidArgumentException(
                    "Table [{$table}] does not exist."
                );
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table($table)->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        } catch (\Exception $e) {

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Successfully truncated table [{$table}].");

        return self::SUCCESS;
    }
}