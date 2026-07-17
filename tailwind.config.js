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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b', 
                    600: '#d97706', 
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                accent: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444', 
                    600: '#dc2626',
                    700: '#b91c1c',
                },
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    500: '#22c55e', 
                    600: '#16a34a',
                }
            }
        },
    },

    plugins: [forms],
};