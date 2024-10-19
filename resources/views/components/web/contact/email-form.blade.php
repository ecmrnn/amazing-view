<form x-data="{ count : 200, max : 200 }" class="mx-auto space-y-3 md:ml-auto md:mx-0" action="/email" method="post">
    @csrf

    <h2 class="text-lg font-semibold">Send us an email here!</h2>

    <x-form.input-text label="Email Address" class="w-full" type="email" id="email" name="email" />
    <x-form.input-error :messages="$errors->get('email')" class="mt-2" />

    <x-form.input-text label="Subject" class="w-full" type="text" id="subject" name="subject" />
    <x-form.input-error :messages="$errors->get('subject')" class="mt-2" />

    <x-form.input-label for="message">Message</x-form.input-label>
    <x-form.input-error :messages="$errors->get('message')" class="mt-2" />
    <x-form.textarea x-on:keyup="count = max - $el.value.length" x-bind:maxlength="max" name="message" id="message" class="max-h-[200px]"></x-form.textarea>

    <p class="text-xs text-right">Remaining Characters: <span x-text="count"></span> / 200</p>

    <div class="flex justify-end">
        <x-primary-button class="inline-block ml-auto">Send Email</x-primary-button>
    </div>
</form>