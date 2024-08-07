/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./templates/*.html.twig",
    "./src/Form/*.php",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      darkMode: 'selector',
      // colors: {
      //   'blue': '#1fb6ff',
      //   'pink': '#ff49db',
      //   'orange': '#ff7849',
      //   'green': '#365E32',
      //   'gray-dark': '#273444',
      //   'gray': '#8492a6',
      //   'gray-light': '#d3dce6',
      // },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
