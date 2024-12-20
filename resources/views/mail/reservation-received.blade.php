<x-mail-layout>
    <div class="max-w-lg p-5 mx-auto bg-white rounded-lg shadow-lg">
        <header class="flex items-center gap-5">
            <div class="w-full max-w-24 aspect-square">
                <img src="{{ $message->embed(asset('storage/global/application-logo.png')) }}">
            </div>
        
            <hgroup>
                <p class="text-lg font-bold">Amazing View Mountain Resort</p>
                <address>Little Baguio, Paagahan Mabitac, Laguna, Philippines</address>
            </hgroup>
        </header>
    
        <main class="py-20">
            <p>Test Email</p>
        </main>
    
        <footer>
            <p>Thank you for choosing Amazing View Mountain Resort</p>
        </footer>
    </div>
</x-mail-layout>