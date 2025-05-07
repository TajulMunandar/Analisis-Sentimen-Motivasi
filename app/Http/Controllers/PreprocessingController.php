<?php

namespace App\Http\Controllers;

use App\Models\Preprocessing;
use App\Models\Tweets;
use Illuminate\Http\Request;

class PreprocessingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $processes = Preprocessing::all();
        return view('dashboard.pages.preprocessing')->with(compact('processes'));
    }

    public function getData()
    {
        $tweets = Tweets::all();
        return response()->json($tweets);
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
        foreach ($request->preprocessed_tweets as $data) {
            Preprocessing::updateOrCreate(
                ['tweet_id' => $data['tweet_id']],
                [
                    'clean_text' => $data['clean_text'],
                    'tokenized' => json_encode($data['tokenized']),
                    'feature_vector' => $data['feature_vector']
                ]
            );
        }

        return response()->json(["message" => "Preprocessing saved successfully"]);
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
