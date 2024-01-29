/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./public/js/**/*.js",
    ],
    theme: {
        extend: {
            borderRadius: {
                "x-core": "0.5rem",
            },
            fontWeight: {
                "x-core": "600",
            },
            boxShadow: {
                "x-core": "var(--black-blur, #0000) 0px 3px 12px, var(--black-blur, #0000) 0px 25px 20px -20px",
                "x-drop": "var(--black-blur, #0000) 0px 25px 20px -20px",
            },
            colors: {
                "x-prime": "#126E9E",
                "x-acent": "#6BA4C1",
                /** */
                "x-black-blur": "#1D1D1D15",
                "x-white-blur": "#FCFCFC40",
                "x-black": "#1D1D1D",
                "x-white": "#FCFCFC",
                "x-light": "#F5F5F5",
                "x-shade": "#D1D1D1",
            },
        },
    },
    plugins: [],
};