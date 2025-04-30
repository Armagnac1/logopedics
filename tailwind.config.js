import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import preline from 'preline/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Laravel framework views
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        // Application views and scripts
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        // Preline plugin scripts
        'node_modules/preline/dist/*.js',
    ],
    darkMode: 'selector', // Enable dark mode with a specific selector
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans], // Extend default sans font
            },
        },
    },
    plugins: [
        forms, // Tailwind CSS forms plugin
        typography, // Tailwind CSS typography plugin
        preline, // Preline plugin
    ],
};
