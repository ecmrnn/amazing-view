<input 
    type="date"
    x-bind:min="`${min.getFullYear()}-${String(min.getMonth() + 1).padStart(2, '0')}-${String(min.getDate()).padStart(2, '0')}`"
    {{ $attributes->merge(['class' => 'border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-50 disabled:bg-slate-50']) }} >