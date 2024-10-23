<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.guests.index');
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
    public function show(string $reservation)
    {
        $reservation = Reservation::where('rid', $reservation)->first();
        $reservation->date_in = Carbon::parse($reservation->date_in)->format('F j, Y');
        $reservation->date_out = Carbon::parse($reservation->date_out)->format('F j, Y');
        $breakdown = Reservation::computeBreakdown($reservation);

        $night_count = Carbon::parse($reservation->date_in)->diffInDays($reservation->date_out);
        // If night count is 0, set night_coutn to 1
        $night_count != 0 ?: $night_count = 1;

        $created_at_time = Carbon::parse($reservation->created_at)->format('H:i');
        $created_at_time_formatted = Carbon::parse($reservation->created_at)->format('g:i A');

        return view('app.guests.show', [
            'reservation' => $reservation,

            'vatable_sales' => $breakdown['vatable_sales'],
            'vat' => $breakdown['vat'],
            'net_total' => $breakdown['net_total'],
            
            'night_count' => $night_count,
            'created_at_time' => $created_at_time,
            'created_at_time_formatted' => $created_at_time_formatted,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $reservation)
    {
        $reservation = Reservation::whereRid($reservation)->first();

        return view('app.guests.edit', ['reservation' => $reservation]);
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
