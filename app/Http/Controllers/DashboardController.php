<?php

namespace App\Http\Controllers;

use App\Models\Lexicon;
use App\Models\Sentiment;
use App\Models\Tweets;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tweets = Tweets::count();
        $lexicon = Lexicon::count();
        $sentimen = Sentiment::count();
        $positive = Sentiment::where('sentiment', 'positif')->count();
        $neutral = Sentiment::where('sentiment', 'netral')->count();
        $negative = Sentiment::where('sentiment', 'negatif')->count();
        return view('dashboard.pages.index', compact('tweets', 'lexicon', 'positive', 'neutral', 'negative', 'sentimen'));
    }
}
