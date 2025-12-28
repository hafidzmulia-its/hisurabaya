import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Avenir', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'auth-bg': '#D9D9D9',
                'golden': '#F8DEC3',
                'dark-gray': '#3A3A3A',
            },
        },
    },

    plugins: [forms],
};
