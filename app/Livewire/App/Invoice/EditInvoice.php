<?php

namespace App\Livewire\App\Invoice;

use App\Models\Amenity;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditInvoice extends Component
{
    use DispatchesToast;

    protected $listeners = ['payment-added' => '$refresh'];
    
    public $invoice;
    #[Validate] public $issue_date;
    #[Validate] public $due_date;
    #[Validate] public $email;
    public $items;

    public function rules() {
        return [
            'issue_date' => 'nullable|date',
            'due_date' => 'required|date',
            'email' => 'required|email',
        ];
    }

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->issue_date = $invoice->issue_date;
        $this->due_date = $invoice->due_date;
        $this->email = $invoice->reservation->email;
        $this->items = collect();
    }

    public function update() {
        $this->dispatch('fetch-items');

        $this->validate([
            'issue_date' => $this->rules()['issue_date'],
            'due_date' => $this->rules()['due_date'],
        ]);

        $this->invoice->update([
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
        ]);

        $this->toast('Success!', description: 'Invoice updated successfully');
    }

    #[On('items-fetched')]
    public function setItems($items) {
        $this->items = $items;
    }

    public function render()
    {
        return view('livewire.app.invoice.edit-invoice');
    }
}
