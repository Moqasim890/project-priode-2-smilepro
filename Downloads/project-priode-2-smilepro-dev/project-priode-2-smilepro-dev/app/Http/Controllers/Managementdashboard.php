<?php

namespace App\Http\Controllers;

use App\Models\Afspraken;
use Illuminate\Http\Request;

class Managementdashboard extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Haal statistieken op via Model method
        $stats = Afspraken::getStats();
        
        // Haal laatste 7 dagen data op via Model method
        $lastSevenDays = Afspraken::getLastSevenDays();
        
        return view('Managementdashboard.index', compact('stats', 'lastSevenDays'));
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
