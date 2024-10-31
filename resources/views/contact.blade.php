<x-guest-layout>
    {{-- Landing Page --}}
    <div class="min-h-screen px-5 py-40">
        <div class="grid max-w-screen-xl gap-20 mx-auto md:grid-cols-2 xl:grid-cols-3">
            <div class="space-y-5 text-center xl:col-span-2 md:text-left">
                <x-h1>
                    {!! $heading !!}
                </x-h1>
            
                <p class="max-w-sm mx-auto md:mx-0">
                    {!! $subheading !!}
            
                    <ul class="mx-auto space-y-2 w-max md:mx-0">
                        @foreach ($contact_details as $contact)
                            <li class="flex items-center gap-5"><x-line class="bg-zinc-800" />{{ $contact }}</li>
                        @endforeach
                    </ul>
                </p>
            </div>
            
            <livewire:email-form />
        </div>
    </div>
</x-guest-layout>