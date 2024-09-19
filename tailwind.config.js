import defaultTheme from 'tailwindcss/defaultTheme';
// import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Arial', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#007AC3',
                // primary: '#ef4444',
                fave: {
                    50: '#f0f8ff',
                    100: '#e0f1fe',
                    200: '#b9e4fe',
                    300: '#7ccffd',
                    400: '#36b7fa',
                    500: '#0c9eeb',
                    600: '#007ac3',
                    700: '#0163a3',
                    800: '#065586',
                    900: '#0b476f',
                    950: '#072d4a',
                },
                blue: {
                    50: '#f5f2ff',
                    100: '#ede8ff',
                    200: '#ded3ff',
                    300: '#c6b0ff',
                    400: '#ab83ff',
                    500: '#9251ff',
                    600: '#862dfa',
                    700: '#771ce5',
                    800: '#6a18cd',
                    900: '#53159d',
                    950: '#320a6b',
                },
            },
        },
    },

    // plugins: [forms],
};
