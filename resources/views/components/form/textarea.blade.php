<textarea {{ $attributes->merge(['class' => 'block max-h-[200px] border-1 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md text-sm border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 transition-colors ease-in-out duration-200']) }}
    cols="30"
    rows="8">{{ $slot }}</textarea>