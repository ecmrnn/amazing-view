<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationAmenity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.reservations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.reservations.create');
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
        $downpayment = $reservation->invoice->payments->first()->proof_image_path;
        
        $night_count = Carbon::parse($reservation->date_in)->diffInDays($reservation->date_out);
        // If night count is 0, set night_coutn to 1
        $night_count != 0 ?: $night_count = 1;

        $created_at_time = Carbon::parse($reservation->created_at)->format('H:i');
        $created_at_time_formatted = Carbon::parse($reservation->created_at)->format('g:i A');

        return view('app.reservations.show', [
            'reservation' => $reservation,

            'vatable_sales' => $breakdown['vatable_sales'],
            'vat' => $breakdown['vat'],
            'net_total' => $breakdown['net_total'],
            'downpayment' => $downpayment,
            
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
        $reservation = Reservation::where('rid', $reservation)->first();

        return view('app.reservations.edit', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateNote(Request $request, Reservation $reservation) {
        $validated = $request->validate([
            'note' => Reservation::rules()['note'] 
        ]);

        $reservation->note = $validated['note'];
        $reservation->save();

        return redirect()->route('app.reservations.show', ['reservation' => $reservation->rid]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
