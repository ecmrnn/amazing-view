<?php

namespace App\Livewire\App\Invoice;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowInvoices extends Component
{
    public $invoice_count;
    public $invoice_by_status = [
        'all' => 0,
        'partial' => 0,
        'paid' => 0,
        'pending' => 0,
        'due' => 0,
    ];
    #[Url] public $status;

    public function mount() {
        $statuses = [
            'partial' => InvoiceStatus::PARTIAL->value,
            'paid' => InvoiceStatus::PAID->value,
            'pending' => InvoiceStatus::PENDING->value,
            'due' => InvoiceStatus::DUE->value,
        ];

        $counts = Invoice::selectRaw('status, COUNT(*) as count')
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('count', 'status');

        foreach ($statuses as $key => $status) {
            $this->invoice_by_status[$key] = $counts->get($status, 0);
        }

        $this->invoice_by_status['all'] = $counts->sum();
        $this->invoice_count = $this->status == '' ? Invoice::count() : Invoice::whereStatus($this->status)->count();
    }
    
    public function render()
    {
        return view('livewire.app.invoice.show-invoices');
    }
}
