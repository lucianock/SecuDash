<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use App\Models\LinkedinPost;

class LinkedinScraperController extends Controller
{
    public function index()
    {
        return view('linkedin.index');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $process = new Process([
            'node', base_path('scraping/scraper.js'), $keyword
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            return back()->withErrors('Scraping failed: ' . $process->getErrorOutput());
        }

        $results = json_decode($process->getOutput(), true);

        foreach ($results as $post) {
            LinkedinPost::create([
                'content' => $post['content'],
                'url' => $post['url'] ?? '',
            ]);
        }

        return view('linkedin.results', compact('results', 'keyword'));
    }
}
