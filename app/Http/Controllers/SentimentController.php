<?php

namespace App\Http\Controllers;

use App\Models\Lexicon;
use App\Models\Preprocessing;
use App\Models\Sentiment;
use App\Models\Tweets;
use Illuminate\Http\Request;

class SentimentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sentiments = Sentiment::all();
        return view('dashboard.pages.sentiment')->with(compact('sentiments'));
    }

    public function getPreprocessedTweets()
    {
        $tweets = Preprocessing::all();
        return response()->json($tweets);
    }

    public function getLexicons()
    {
        $lexicons = Lexicon::all();
        return response()->json($lexicons);
    }

    public function saveSentiments(Request $request)
    {
        $data = $request->input('sentiments');

        if (!$data) {
            return response()->json(['error' => 'Data sentimen tidak ditemukan'], 400);
        }

        foreach ($data as $sentiment) {
            Sentiment::updateOrCreate(
                ['tweet_id' => $sentiment['tweet_id']],  // Pastikan tidak duplikat
                [
                    'tweet_id' => $sentiment['tweet_id'],
                    'sentiment' => $sentiment['predicted_sentiment'],
                    'confidence' => $sentiment['confidence'] ?? 0.0,
                ]
            );
        }

        return response()->json(['message' => 'Sentimen berhasil disimpan']);
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
