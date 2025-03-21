@php
    $value = (int) $row->{$column->field};

    $trueValue = $column->toggleable['default'][0];
    $falseValue = $column->toggleable['default'][1];

    $params = [
        'id' => data_get($row, $this->realPrimaryKey),
        'isHidden' => !$showToggleable,
        'tableName' => $tableName,
        'field' => $column->field,
        'toggle' => $value,
        'trueValue' => $trueValue,
        'falseValue' => $falseValue,
    ];
@endphp
<div x-data="pgToggleable(@js($params))">
    <div class="flex flex-row">
        @if ($showToggleable === true)
            <div
                :class="{
                    'relative rounded-full w-12 h-6 p-1 transition duration-200 ease-linear': true,
                    'bg-slate-200 ': toggle,
                    'bg-blue-500': !toggle
                }">
                <label
                    :class="{
                        'absolute left-2 bg-white border-2  w-4 h-4 rounded-full transition transform duration-100 ease-linear cursor-pointer': true,
                        'translate-x-full border-pg-primary-600': toggle,
                        'translate-x-0 border-pg-primary-200': !toggle
                    }"
                    x-on:click="save"
                ></label>
                <input
                    type="checkbox"
                    class="w-full h-full opacity-0 appearance-none active:outline-none focus:outline-none"
                    x-on:click="save"
                >
            </div>
    @else
            <div @class([
                'text-xs px-4 w-auto py-1 text-center rounded-md',
                'bg-red-200 text-red-800' => $value === 0,
                'bg-blue-200 text-blue-800' => $value === 1,
            ])>
                {{ $value === 0 ? $falseValue : $trueValue }}
            </div>
    @endif
    </div>
</div>
