<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeLinkedinPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-linkedin-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // app/Console/Commands/ScrapeLinkedinPosts.php
    public function handle()
    {
        $keyword = 'hacking';
        $process = new \Symfony\Component\Process\Process([
            'node',
            base_path('scraping/scraper.js'),
            $keyword
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Error: ' . $process->getErrorOutput());
            return;
        }

        $results = json_decode($process->getOutput(), true);

        foreach ($results as $post) {
            \App\Models\LinkedinPost::create([
                'content' => $post['content'],
                'url' => '', // si lo podés scrapear
            ]);
        }

        $this->info("Scraping done: " . count($results) . " posts saved.");
    }

}
