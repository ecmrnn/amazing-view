@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
    'view_link' => '',
])

<div class="flex justify-end gap-1">
    @can('update billing')
        @if (in_array($row->status, [
                App\Enums\InvoiceStatus::PARTIAL->value,
                App\Enums\InvoiceStatus::PENDING->value,
                App\Enums\InvoiceStatus::PAID->value,
            ]))
            <x-tooltip text="Edit" dir="top">
                <a x-ref="content" href="{{ route($edit_link, ['billing' => $row->iid]) }}" wire:navigate.hover>
                    <x-icon-button><svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /><path d="m15 5 4 4" /></svg></x-icon-button>
                </a>
            </x-tooltip>
        @endif
    @endcan

    <x-tooltip text="View" dir="top">
        <a x-ref="content" href="{{ route($view_link, ['billing' => $row->iid]) }}" wire:navigate.hover>
            <x-icon-button><svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" /></svg></x-icon-button>
        </a>
    </x-tooltip>
</div>
