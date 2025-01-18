/** @type {import('tailwindcss').Config} */
const tailwindcss = require("tailwindcss");
const autoprefixer = require("autoprefixer");
const cssnano = require("cssnano");

module.exports = {
    content: [
        "src/Views/**/*.twig",
    ],
    theme: {
        extend: {
            fontFamily: {
                libreBaskerville: ["Libre Baskerville", 'serif'],
                rasa: ['Merriweather Sans', 'serif'],
                merriWeather: ['Merriweather Sans', 'serif'],
            },
        },
    },
    plugins: [tailwindcss, autoprefixer, [cssnano]],
}

