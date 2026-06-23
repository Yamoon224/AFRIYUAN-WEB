import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50:  '#fff1f2',
                    100: '#ffe4e6',
                    200: '#fecdd3',
                    300: '#fda4af',
                    400: '#fb7185',
                    500: '#D4132B',
                    600: '#AA0E22',
                    700: '#9f1239',
                    800: '#881337',
                    900: '#4c0519',
                },
                gold: {
                    400: '#fbbf24',
                    500: '#FFD700',
                    600: '#d97706',
                },
                china: {
                    red:  '#D4132B',
                    gold: '#FFD700',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                'card': '0 2px 15px -3px rgba(0,0,0,.07), 0 10px 20px -2px rgba(0,0,0,.04)',
                'soft': '0 2px 8px rgba(0,0,0,0.08)',
            },
        },
    },
    plugins: [forms, typography],
};
