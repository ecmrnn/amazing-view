@props([
    'status' => null,
    'role' => 999,
])

<a 
    @php
        logger(request()->query());
        $active_status = request('status') ? request('status') : '';

        if (request('role') && empty($status)) {
            $active_status = request('role') ? request('role') : '';
            $status = $role;
        }
    @endphp

    wire:navigate
    @class([
        'px-3 py-2 ml-2 block rounded-e-md border-l',
        'text-sm text-zinc-800/75 border-slate-200 border-dashed' => $active_status != $status,
        'text-sm text-blue-500 bg-blue-50 border-blue-500 border-solid' => $active_status == $status
    ])
    {{ $attributes->merge() }}
    >
    {{ $slot }}
</a>