import { createApp } from 'vue'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import './style.css'
import App from './App.vue'
import router from './app/router'

const vuetify = createVuetify({
  theme: {
    defaultTheme: 'incaRail',
    themes: {
      incaRail: {
        dark: false,
        colors: {
          primary: '#173f47',
          secondary: '#a66835',
          surface: '#ffffff',
          background: '#f5f1e8',
          error: '#a32f2f',
        },
      },
    },
  },
})

createApp(App).use(router).use(vuetify).mount('#app')
