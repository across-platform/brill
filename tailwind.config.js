/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './routes/**/*.php',
    ],
    safelist: ['hidden'],
    theme: {
        extend: {
            colors: {
                navy: '#0b1b3d',
                'navy-2': '#071735',
                brill: {
                    blue: '#2378ff',
                    sky: '#1ba0ff',
                    cyan: '#18d5ff',
                    mint: '#19d3b3',
                    gold: '#FFC857',
                    ink: '#07112f',
                    muted: '#64748b',
                    surface: '#f7faff',
                },
            },
            borderRadius: {
                'ap': '26px',
            },
            boxShadow: {
                ap: '0 24px 70px rgba(20, 55, 120, .13)',
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
