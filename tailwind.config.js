/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    "src/Views/**/*.php",
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

