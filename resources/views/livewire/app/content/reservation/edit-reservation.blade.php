<div>
    <section class="space-y-5">
        @foreach ($pages as $page)
            <livewire:app.content.edit-hero wire:key='{{ $page->id }}' page="{{ $page->title }}" />
        @endforeach
    </section>
</form>