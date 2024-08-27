<form x-data="{ count : 200, max : 200 }" class="mx-auto md:ml-auto md:mx-0 space-y-3" action="/email" method="post">
    @csrf

    <x-input-label for="email">Email Address</x-input-label>
    <x-text-input class="w-full" type="email" id="email" name="email" placeholder="juan.delacruz@mail.com" />

    <x-input-label for="subject">Subject</x-input-label>
    <x-text-input class="w-full" type="text" id="subject" name="subject" placeholder="What do you want to talk about?" />

    <x-input-label for="message">Message</x-input-label>
    <x-textarea x-on:keyup="count = max - $el.value.length" x-bind:maxlength="max" name="message" id="message"></x-textarea>

    <p class="text-xs">Remaining Characters: <span x-html="count"></span> / 200</p>

    <x-primary-button class="inline-block ml-auto">Send Email</x-primary-button>
</form>