<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $total = Sentiment::count();
        $positive = Sentiment::where('sentiment', 'positif')->count();
        $negative = Sentiment::where('sentiment', 'negatif')->count();
        $neutral  = Sentiment::where('sentiment', 'netral')->count();
        return view('landing', compact('total', 'positive', 'negative', 'neutral'));
    }
}
