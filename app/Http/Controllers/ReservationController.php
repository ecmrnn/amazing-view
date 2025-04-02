<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationAmenity;
use App\Models\User;
use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

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

    public function updateNote(Request $request, Reservation $reservation) {
        $validated = $request->validate([
            'note' => Reservation::rules()['note'] 
        ]);

        $reservation->note = $validated['note'];
        $reservation->save();

        return redirect()->route('app.reservations.show', ['reservation' => $reservation->rid]);
    }

    public function checkOut(Request $request, $reservation) {
        $reservation = Reservation::where('rid', $reservation)->first();
        
        return view('app.reservations.check-out', [
            'reservation' => $reservation,
        ]);
    }

    public function guestReservations(User $user) {
        if ($user->id == Auth::user()->id) {
            return view('app.reservations.guest.index', [
                'user' => $user,
            ]);
        } else {
            // return response()->view('error.404', status: 404);
        }
    }

    public function showGuestReservations(string $reservation) {
        $reservation = Reservation::where('rid', $reservation)->first();

        return view('app.reservations.show', [
            'reservation' => $reservation,
        ]);
    }
}
