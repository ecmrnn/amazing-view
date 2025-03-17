@props([
    'status' => null,
    'role' => 999,
])

<a 
    @php
        $active_status = isset($_GET['status']) ? $_GET['status'] : '';

        if (isset($_GET['role']) && empty($status)) {
            $active_status = isset($_GET['role']) ? $_GET['role'] : '';
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