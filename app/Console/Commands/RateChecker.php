<?php

namespace App\Console\Commands;

use App\Rate;
use Illuminate\Console\Command;

class RateChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check_rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the rate and update database if changed';

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
     * @return mixed
     */
    public function handle()
    {
        $lastRecord = Rate::orderBy('created_at','desc')->first();
        $this->info('Getting latest rates');
        $newRates = Rate::getLatest();
        $this->info("Buying: " . $newRates['buying'] . ' , Selling: ' . $newRates['selling']);

        // If No records exist
        if (! $lastRecord) {
            $this->comment('Database still new. Saving new results');
            Rate::create($newRates);
            return;
        }

        // If Last record is the same as new records
        if ($lastRecord->buying == $newRates['buying'] && $lastRecord->selling == $newRates['selling'] ) {
            $this->comment('Stored Records are up to date. Nothing saved');
            return;
        }

        // If Last record is different than new records, save the new ones.
        $this->comment('new Rates. Updating database and notifying users');
        Rate::create($newRates);
        // To do: Notify email list;


    }
}
