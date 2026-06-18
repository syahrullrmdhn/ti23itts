/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
  ],
  theme: {
    extend: {
      colors: {
        'bright-yellow': '#FFD93D',
        'bright-blue': '#6BCF7F',
        'mint-green': '#A8E6CF',
        'fun-pink': '#FF6B9D',
        'soft-purple': '#C3AED6',
        'vibrant-orange': '#FF8C42',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        display: ['Poppins', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
