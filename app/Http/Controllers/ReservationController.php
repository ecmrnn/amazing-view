<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pending_reservations = Reservation::where('status', Reservation::STATUS_PENDING)->count();
        $confirmed_reservations = Reservation::where('status', Reservation::STATUS_CONFIRMED)->count();
        $completed_reservations = Reservation::where('status', Reservation::STATUS_COMPLETED)->count();
        $expired_reservations = Reservation::where('status', Reservation::STATUS_EXPIRED)->count();

        return view('app.reservations.index', [
            'pending_reservations' => $pending_reservations,
            'confirmed_reservations' => $confirmed_reservations,
            'completed_reservations' => $completed_reservations,
            'expired_reservations' => $expired_reservations,
        ]);
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
            'reservation' => $reservation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $reservation)
    {
        $reservation_data = Reservation::where('rid', $reservation)->first();

        // dd($reservation_data);
        
        return view('app.reservations.edit', [
            'reservation' => $reservation_data
        ]);
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
