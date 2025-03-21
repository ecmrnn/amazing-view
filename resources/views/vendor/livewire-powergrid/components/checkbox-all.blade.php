@props([
    'theme' => null,
])
<div>
    @php
        $responsiveCheckboxColumnName = PowerComponents\LivewirePowerGrid\Responsive::CHECKBOX_COLUMN_NAME;

        $isCheckboxFixedOnResponsive = isset($this->setUp['responsive']) && in_array($responsiveCheckboxColumnName, data_get($this->setUp, 'responsive.fixedColumns')) ? true : false;
    @endphp
    <th
        @if ($isCheckboxFixedOnResponsive) fixed @endif
        scope="col"
        class="{{ data_get($theme, 'thClass') }}"
        style="{{ data_get($theme, 'thStyle') }}"
        wire:key="{{ md5('checkbox-all') }}"
    >
        <div class="{{ data_get($theme, 'divClass') }}">
            <label class="{{ data_get($theme, 'labelClass') }}">
                <input 
                    wire:click="selectCheckboxAll"
                    wire:model="checkboxAll"
                    id="remember_me" type="checkbox" class="text-blue-600 rounded shadow-sm border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600" name="remember">
            </label>
        </div>
    </th>
</div>
