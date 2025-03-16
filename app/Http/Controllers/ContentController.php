<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Page::pluck('status');
        
        return view('app.content.index', [
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->view('error.404', status:404);
        }

        return view('app.content.edit', [
            'page' => $page,
        ]);
    }
}
