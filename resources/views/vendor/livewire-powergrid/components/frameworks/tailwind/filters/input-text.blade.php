@props([
    'theme' => '',
    'enabledFilters' => [],
    'column' => null,
    'inline' => null,
    'filter' => null,
])
<div>
    @php
        $fieldClassName = data_get($filter, 'className');

        $field = strval(data_get($filter, 'field'));
        $title = strval(data_get($column, 'title'));
        $operators = (array) data_get($filter, 'operators', []);
        $placeholder = strval(data_get($filter, 'placeholder'));
        $componentAttributes = (array) data_get($filter, 'attributes', []);

        $inputTextOptions = $fieldClassName::getInputTextOperators();
        $inputTextOptions = count($operators) > 0 ? $operators : $inputTextOptions;
        $showSelectOptions = !(count($inputTextOptions) === 1 && in_array('contains', $inputTextOptions));

        $defaultPlaceholder = data_get($column, 'placeholder') ?: data_get($column, 'title');
        $overridePlaceholder = $placeholder ?: $defaultPlaceholder;

        unset($filter['placeholder']);

        $defaultAttributes = $fieldClassName::getWireAttributes($field, $title);

        $selectClasses = \Illuminate\Support\Arr::toCssClasses([
            'power_grid',
            data_get($theme, 'selectClass'),
        ]);
        $inputClasses = \Illuminate\Support\Arr::toCssClasses(['power_grid', data_get($theme, 'inputClass')]);

        $params = array_merge(
            [
                'showSelectOptions' => $showSelectOptions,
                'placeholder' => ($placeholder = $componentAttributes['placeholder'] ?? $overridePlaceholder),
                ...data_get($filter, 'attributes'),
                ...$defaultAttributes,
            ],
            $filter,
        );
    @endphp

    @if ($params['component'])
        @unset($params['operators'], $params['attributes'])

        <x-dynamic-component
            :component="$params['component']"
            :attributes="new \Illuminate\View\ComponentAttributeBag($params)"
        />
    @else
        <div
            @class([data_get($theme, 'baseClass'), 'space-y-1' => !$inline])
            style="{{ data_get($theme, 'baseStyle') }}"
        >
            @if (!$inline)
                <label class="block text-xs font-semibold">
                    {{ $title }}
                </label>
            @endif
            <div @class([
                'w-full space-y-2 sm:flex sm:space-y-0' => !$inline && $showSelectOptions,
                'flex flex-col space-y-1.5' => $inline && $showSelectOptions,
            ])>
                @if ($showSelectOptions)
                    <div @class([
                        'pl-0 w-full sm:pr-3 sm:w-1/2' => !$inline,
                    ])>
                        <div class="relative">
                            <select
                                class="w-full py-2 text-sm transition duration-150 ease-in-out border rounded-lg border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25"
                                style="{{ data_get($column, 'headerStyle') }}"
                                data-cy="input_text_options_{{ $tableName }}_{{ $field }}"
                                {{ $defaultAttributes['selectAttributes'] }}
                            >
                                @foreach ($inputTextOptions as $key => $value)
                                    <option
                                        class="text-xs"
                                        wire:key="input-text-options-{{ $tableName }}-{{ $key . '-' . $value }}"
                                        value="{{ $value }}"
                                    >{{ trans('livewire-powergrid::datatable.input_text_options.' . $value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div @class([
                    'pl-0 w-full sm:w-1/2' => !$inline && $showSelectOptions,
                    'pt-1' => !$showSelectOptions,
                ])>
                    <input
                        data-cy="input_text_{{ $tableName }}_{{ $field }}"
                        wire:key="input-{{ $field }}"
                        data-id="{{ $field }}"
                        @if (isset($enabledFilters[$field]['disabled']) && boolval($enabledFilters[$field]['disabled']) === true) disabled
                            @else
                                {{ $defaultAttributes['inputAttributes'] }} @endif
                        type="text"
                        class="w-full py-2 text-sm transition duration-150 ease-in-out border rounded-lg border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25"
                        placeholder="{{ $placeholder }}"
                    />
                </div>
            </div>
        </div>
    @endif
</div>
