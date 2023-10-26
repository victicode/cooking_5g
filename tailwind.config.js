// tailwind.config.js
module.exports = {
  purge: ['./public/index.html','./public/index.php', './resources/**/*.{vue,js,ts,jsx,tsx,blade.php,php}'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}