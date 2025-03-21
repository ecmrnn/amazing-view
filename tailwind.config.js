import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*Table.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
    ],

    theme: {
        extend: {
            animation: {
                'spin-slow': 'spin 3s linear infinite',
            },
            fontFamily: {
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xxs: '0.5rem'
            },
            colors: {
                "pg-primary": colors.white, 
            },
        },
    },

    plugins: [
        require('@butterfail/tailwindcss-inverted-radius'),
        forms,
    ],
};
