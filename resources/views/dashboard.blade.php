
@switch(Auth::user()->role)
    @case(0)
        @include('guest.dashboard')
        @break
    @case(1)
        @include('frontdesk.dashboard')
        @break
    @case(2)
        @include('admin.dashboard')
        @break
    @default
        @break
@endswitch
