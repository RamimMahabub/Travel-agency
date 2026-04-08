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
                sans: ['Inter', 'Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#B20600',
                dark: '#202020',
                light: '#F5F5F5',
                white: '#FFFFFF',
            }
        },
    },

    plugins: [forms],
};
