<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.billings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.billings.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::whereIid($id)->first();
        
        return view('app.billings.show', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $billing)
    {
        $invoice = Invoice::whereIid($billing)->first();
        
        if ($invoice) {
            return view('app.billings.edit', [
                'invoice' => $invoice
            ]);
        }

        return response()->view('error.404', status: 404);
    }

    public function guestBillings(User $user) {
        if ($user->id && Auth::user()->id) {
            return view('app.billings.guest.index', [
                'user' => $user,
            ]);
        }

        return response()->view('error.403', status: 403);
    }

    public function showGuestBillings(string $billing) {
        $invoice = Invoice::whereIid($billing)->first();

        if ($invoice && $invoice->reservation->user->id == Auth::user()->id) {
            return view('app.billings.show', [
                'invoice' => $invoice
            ]);
        }

        return response()->view('error.404', status: 404);
    }
}
