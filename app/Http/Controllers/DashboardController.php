<?php

namespace App\Http\Controllers;

use App\Models\Lexicon;
use App\Models\Tweets;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tweets = Tweets::count();
        $lexicon = Lexicon::count();
        return view('dashboard.pages.index', compact('tweets', 'lexicon'));
    }
}
