<?php

namespace App\Console\Commands;

use App\NewsFeed\NewYorkTimesNewsApiFeeder;
use App\NewsFeed\NewsApiFeeder;
use App\NewsFeed\TheGuardianNewsApiFeeder;
use Illuminate\Console\Command;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news from given sources and store in local database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newsSources = [
            'news api'          => NewsApiFeeder::class,
            'the guardian'      => TheGuardianNewsApiFeeder::class,
            'new york times'    => NewYorkTimesNewsApiFeeder::class,
        ];
        foreach($newsSources as $source => $feederClass){
            $this->info("Fetching data from ${source} and saving to local database");
            $feederInstance = $feederClass::init();
            $fetchCommand = $feederInstance->fetch();
            $this->info($fetchCommand['msg']);
        }
    }
}
