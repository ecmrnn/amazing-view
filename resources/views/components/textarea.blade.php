<textarea {{ $attributes->merge(['class' => 'block max-h-[200px] border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}
    cols="30"
    rows="8">{{ $slot }}</textarea>