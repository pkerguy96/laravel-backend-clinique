<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Quote;

class FetchDailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-daily-quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the daily quote from the API';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiUrl = 'https://api.api-ninjas.com/v1/quotes?category=inspirational';
        $headers = [
            'X-Api-Key' => env('API_QUOTE')
        ];

        $response = Http::withHeaders($headers)->withoutVerifying()->get($apiUrl);
        if ($response->successful()) {
            Quote::truncate();
            // Parse the JSON response
            $quoteData = $response->json();
            $quote = $quoteData[0]['quote'];
            $quoteAuthor = $quoteData[0]['author'];
            Quote::create([
                'quote' => $quote,
                'author' => $quoteAuthor
            ]);
            $this->info("Fetched quote: $quote - $quoteAuthor");
        } else {
            // Handle the case when the API request was not successful
            $this->error('Failed to fetch the daily quote. API request unsuccessful.');
        }
    }
}
