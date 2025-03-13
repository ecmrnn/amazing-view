<?php

namespace App\PowerGridThemes;

use PowerComponents\LivewirePowerGrid\Themes\Components\{Actions,
    Checkbox,
    Cols,
    Editable,
    FilterBoolean,
    FilterDatePicker,
    FilterInputText,
    FilterMultiSelect,
    FilterNumber,
    FilterSelect,
    Footer,
    Radio,
    SearchBox,
    Table};
use PowerComponents\LivewirePowerGrid\Themes\Tailwind;
use PowerComponents\LivewirePowerGrid\Themes\Theme;

class AmazingTheme extends Tailwind
{
    public string $name = 'tailwind';

    public function table(): Table
    {
        return Theme::table('')
            ->container('overflow-x-auto')
            ->base('')
            ->div('relative border-x border-t rounded-t-lg')
            ->thead('border-b') /* Not gonna work */
            ->thAction('!font-bold')
            ->tdAction('')
            ->tr('')
            ->trFilters('')
            ->th('font-semibold px-5 pr-4 py-3 text-left')
            ->tbody('text-sm')
            ->trBody('border-b border-dashed hover:border-solid hover:bg-slate-50')
            ->tdBody('px-5 py-2')
            ->tdBodyEmpty('p-5')
            ->trBodyClassTotalColumns('')
            ->tdBodyTotalColumns('p-2 text-sm text-pg-primary-600 text-right space-y-2');
    }

    public function footer(): Footer
    {
        return Theme::footer()
            ->view($this->root() . '.footer')
            ->select('rounded-lg py-1 border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150');
    }

    public function actions(): Actions
    {
        return Theme::actions()
            ->headerBtn('block w-full bg-pg-primary-50 text-pg-primary-700 border border-pg-primary-200 rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white focus:border-pg-primary-600 dark:bg-pg-primary-800 dark:text-pg-primary-200 dark:placeholder-pg-primary-300 dark:border-pg-primary-600')
            ->rowsBtn('focus:outline-none text-sm py-2.5 px-5 rounded border');
    }

    public function cols(): Cols
    {
        return Theme::cols()
            ->div('select-none flex items-center gap-2')
            ->clearFilter('', '');
    }

    public function editable(): Editable
    {
        return Theme::editable()
            ->view($this->root() . '.editable')
            ->span('flex justify-between')
            ->input('focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 text-gray-600 ring-gray-300 dark:bg-pg-primary-800 bg-white dark:placeholder-pg-primary-400 w-full rounded-md border-0 bg-transparent py-1.5 px-2 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6 w-full');
    }

    public function checkbox(): Checkbox
    {
        return Theme::checkbox()
            ->th('px-6 py-3 text-left text-xs font-medium text-pg-primary-500 tracking-wider')
            ->label('flex items-center space-x-3')
            ->input('form-checkbox dark:border-dark-600 border-1 dark:bg-dark-800 rounded border-slate-200 bg-white transition duration-100 ease-in-out h-4 w-4 text-primary-500 focus:ring-primary-500 dark:ring-offset-dark-900');
    }

    public function radio(): Radio
    {
        return Theme::radio()
            ->th('px-6 py-3 text-left text-xs font-medium text-pg-primary-500 tracking-wider')
            ->label('flex items-center space-x-3')
            ->input('form-radio rounded-full transition ease-in-out duration-100');
    }

    public function filterBoolean(): FilterBoolean
    {
        return Theme::filterBoolean()
            ->view($this->root() . '.filters.boolean')
            ->base('min-w-[5rem]')
            ->select('appearance-none !bg-none focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 text-gray-600 ring-gray-300 dark:bg-pg-primary-800 bg-white dark:placeholder-pg-primary-400 w-full rounded-md border-0 bg-transparent py-1.5 px-2 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6 w-full');
    }

    public function filterDatePicker(): FilterDatePicker
    {
        return Theme::filterDatePicker()
            ->base()
            ->view($this->root() . '.filters.date-picker')
            ->input('flatpickr flatpickr-input focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 text-gray-600 ring-gray-300 dark:bg-pg-primary-800 bg-white dark:placeholder-pg-primary-400 w-full rounded-md border-0 bg-transparent py-1.5 px-2 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6 w-auto');
    }

    public function filterMultiSelect(): FilterMultiSelect
    {
        return Theme::filterMultiSelect()
            ->base('inline-block relative w-full')
            ->select('mt-1')
            ->view($this->root() . '.filters.multi-select');
    }

    public function filterNumber(): FilterNumber
    {
        return Theme::filterNumber()
            ->view($this->root() . '.filters.number')
            ->input('w-full py-2 text-sm transition duration-150 ease-in-out border rounded-lg border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25');
    }

    public function filterSelect(): FilterSelect
    {
        return Theme::filterSelect()
            ->view($this->root() . '.filters.select')
            ->base('min-w-[9.5rem]')
            ->select('w-full py-2 text-sm transition duration-150 ease-in-out border rounded-lg border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25');
    }

    public function filterInputText(): FilterInputText
    {
        return Theme::filterInputText()
            ->view($this->root() . '.filters.input-text')
            ->base('min-w-[9.5rem]')
            ->select('appearance-none !bg-none focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 text-gray-600 ring-gray-300 dark:bg-pg-primary-800 bg-white dark:placeholder-pg-primary-400 w-full rounded-md border-0 bg-transparent py-1.5 px-2 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6 w-full')
            ->input('focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150 flex rounded-lg transition bg-white w-full');
    }

    public function searchBox(): SearchBox
    {
        return Theme::searchBox()
            ->input('focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex items-center rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 text-gray-600 ring-gray-300 dark:bg-pg-primary-800 bg-white dark:placeholder-pg-primary-400 w-full rounded-md border-0 bg-transparent py-1.5 px-2 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6 w-full pl-8')
            ->iconClose('text-pg-primary-400 dark:text-pg-primary-200')
            ->iconSearch('text-pg-primary-300 mr-2 w-5 h-5 dark:text-pg-primary-200');
    }
}
