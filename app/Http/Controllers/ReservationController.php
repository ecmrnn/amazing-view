<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationAmenity;
use App\Services\BillingService;
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

        return view('app.reservations.show', [
            'reservation' => $reservation,
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
