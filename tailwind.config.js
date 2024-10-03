/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    fontFamily: {
      normal: ['Poppins', 'sans-serif'],
    },
    extend: {
      colors: {
        'wgg-black': '#242424',
        'wgg-gray': '#646464',
        'wgg-white': '#FFFEFE',
        'wgg-border': 'rgba(100, 100, 100, 0.25)',
      }
    },
  },
  plugins: [],
}

