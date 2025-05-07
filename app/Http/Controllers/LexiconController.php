<?php

namespace App\Http\Controllers;

use App\Models\Lexicon;
use Illuminate\Http\Request;

class LexiconController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lexicons = Lexicon::all();
        return view('dashboard.pages.lexicon')->with(compact('lexicons'));
    }

    public function import(Request $request)
    {
        // Validasi file harus format TSV
        $request->validate([
            'tsv_file' => 'required|mimes:txt,tsv|max:2048', // Maks 2MB
        ]);

        // Baca file TSV
        $file = $request->file('tsv_file');
        $handle = fopen($file->getRealPath(), "r");

        if (!$handle) {
            return back()->with('failed', 'Gagal membuka file!');
        }

        // Lewati header jika ada
        fgetcsv($handle, 0, "\t");

        while (($data = fgetcsv($handle, 0, "\t")) !== false) {
            Lexicon::updateOrCreate(
                ['word' => $data[0]], // Kata unik
                ['polarity' => $data[1]] // Sentimen
            );
        }

        fclose($handle);
        return back()->with('success', 'Data lexicon berhasil diimport!');
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
