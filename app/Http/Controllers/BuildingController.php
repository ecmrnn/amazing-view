<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.buildings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.buildings.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        return view('app.buildings.show', [
            'building' => $building,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        return view('app.buildings.edit', [
            'building' => $building,
        ]);
    }
}
