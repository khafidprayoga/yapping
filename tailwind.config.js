/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    "src/Views/**/*.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        libreBaskerville: ["Libre Baskerville", 'serif'],
        rasa: ['Merriweather Sans','serif'],
        merriWeather: ['Merriweather Sans', 'serif'],
      },
    },
  },
  plugins: [],
}

