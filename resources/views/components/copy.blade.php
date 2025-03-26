@props(['text' => 'Hello world!'])

<div x-data="{ text: @js($text), copied: false, copy() {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(this.text)
                .then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); })
                .catch(() => this.fallbackCopy());
        } else {
            this.fallbackCopy();
        }
    }, fallbackCopy() {
        let tempInput = document.createElement('input');
        tempInput.value = this.text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }}"
    class="w-min">
    <input type="text" x-model="text" class="p-2 border sr-only" readonly>
    
    <div x-show="!copied">
        <x-tooltip  text="Copy" dir="top">
            <x-icon-button x-ref="content" x-on:click="copy()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
            </x-icon-button>
        </x-tooltip>
    </div>

    <div x-show="copied">
        <x-tooltip text="Copied!" dir="top">
            <x-icon-button x-ref="content" x-on:click="copy()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy-check"><path d="m12 15 2 2 4-4"/><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
            </x-icon-button>
        </x-tooltip>
    </div>


</div>
