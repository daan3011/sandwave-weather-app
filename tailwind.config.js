import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    // important: '#app',
    // mode: "jit",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    safelist: [
        {
          pattern: /bg-(green|red)-500/, // Matches `bg-green-500` and `bg-red-500`
        },
        {
          pattern: /(fixed|bottom-\d|right-\d|space-y-\d|z-\d|px-\d|py-\d|rounded-lg|shadow-md|text-white)/,
        },
      ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
