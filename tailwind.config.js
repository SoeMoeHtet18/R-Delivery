/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./resources/js/**/*.{vue,js}",
    "./resources/views/vue-pages/layout/master.blade.php"
  ],
  theme: {
    fontFamily: {
      'poppins': ['Poppins', 'sans-serif'],
      'lato' : ['Lato', 'sans-serif']
    },
    extend: {
      colors: {
        'main': '#116a5b',
        'soft': '#D4EFEB',
        'main-hover': '#8FE1D4',
        'active-item': '#07384D',
        'grey': '#AAAAAA',
        'white': '#ffffff',
        'side-bar': '#1C202E'
      },
      width: {
        '35/10': '35%',
        '70px': '70px'
      },
      flex: {
        '0': '1 0 0%'
      },
      margin: {
        '21': '84px',
        '13px': '13px',
        '1/6': '16.6667%',
      },
      padding: {
        '7.5': '30px'
      },
      height: {
        '11.25': '45px'
      }
    },
  },
  plugins: [],
}

