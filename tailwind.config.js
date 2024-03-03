/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./build/*.html'],
  theme: {
    extend: {
      backgroundImage: {
        'hero-pattern': "url('/img/bg-image.jpg')",
      }
    },
  },
  plugins: [],
}
