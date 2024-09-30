<div>
    @includeIf(data_get($setUp, 'footer.includeViewOnTop'))
    <div
        id="pg-footer"
        @class([
            'justify-between' => filled(data_get($setUp, 'footer.perPage')),
            'justify-end' => blank(data_get($setUp, 'footer.perPage')),
            'border-x border-b rounded-b-lg border-b border-pg-primary-200 dark:bg-pg-primary-700 dark:border-pg-primary-600',
            'md:flex md:flex-row w-full items-center py-3 bg-white overflow-y-auto pl-2 pr-2 relative dark:bg-pg-primary-900' => blank(
                data_get($setUp, 'footer.pagination')),
        ])
    >
        @if (filled(data_get($setUp, 'footer.perPage')) &&
                count(data_get($setUp, 'footer.perPageValues')) > 1 &&
                blank(data_get($setUp, 'footer.pagination')))
            <div class="flex flex-row justify-center mb-2 md:justify-start md:mb-0">
                <div class="relative flex items-center gap-2">
                    
                    <select
                        wire:model.live="setUp.footer.perPage"
                        id="rpp"
                        class="{{ data_get($theme, 'footer.selectClass') }}"
                    >
                        @foreach (data_get($setUp, 'footer.perPageValues') as $value)
                            <option value="{{ $value }}" class="text-xs">
                                @if ($value == 0)
                                    {{ trans('livewire-powergrid::datatable.labels.all') }}
                                @else
                                    {{ $value }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <label for="rpp" class="flex-shrink-0 w-full text-xs font-semibold">Records <br /> per page</label>
                </div>
                <div
                    class="hidden w-full pl-4 sm:block md:block lg:block"
                    style="padding-top: 6px;"
                >
                </div>
            </div>
        @endif

        @if (filled($data))
            <div>
                @if (method_exists($data, 'links'))
                    {!! $data->links(data_get($setUp, 'footer.pagination') ?: powerGridThemeRoot() . '.pagination', [
                        'recordCount' => data_get($setUp, 'footer.recordCount'),
                        'perPage' => data_get($setUp, 'footer.perPage'),
                        'perPageValues' => data_get($setUp, 'footer.perPageValues'),
                    ]) !!}
                @endif
            </div>
        @endif
    </div>
    @includeIf(data_get($setUp, 'footer.includeViewOnBottom'))
</div>
