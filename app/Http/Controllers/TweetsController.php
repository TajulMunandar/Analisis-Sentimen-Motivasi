<?php

namespace App\Http\Controllers;

use App\Models\Tweets;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweets::all();
        return view('dashboard.pages.tweets')->with(compact('tweets'));
    }

    public function exportCsv()
    {
        $tweets = Tweets::all();

        $filename = 'tweets.csv';
        $handle = fopen($filename, 'w+');

        // Header CSV
        fputcsv($handle, ['ID', 'Tweet ID', 'User ID', 'Username', 'text', 'Created At']);

        foreach ($tweets as $tweet) {
            fputcsv($handle, [
                $tweet->id,
                $tweet->tweet_id,
                $tweet->user_id,
                $tweet->username,
                $tweet->text,
                $tweet->created_at
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function scrape(Request $request)
    {
        $data = $request->json()->all();  // Ambil data JSON dari Flask
        if (!$data || !isset($data['tweets'])) {
            return response()->json(['error' => 'Data tweets tidak ditemukan'], 400);
        }

        foreach ($data['tweets'] as $tweet) {
            \App\Models\Tweets::create($tweet);
        }

        return response()->json(['message' => 'Tweets saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
