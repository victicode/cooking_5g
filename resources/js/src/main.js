/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
// import router from './router/index.js'
import './@core/scss/template/index.scss'
import './@layouts/styles/index.scss'
import './styles/styles.scss'
import { createApp } from 'vue'
 
import axios from "axios";
import VueAxios from "vue-axios";
import store from "@/core/services/store/index.js";
// import ApiService from "@/core/services/api.service";
import router from "./router.js";
import middlewarePipeline from './middlewares/middlewarePipeline';
// import 'bootstrap';

loadFonts()


// Create vue app

router.beforeEach(async (to, from, next) => {

  // const titlePage = {
  //   login: 'Inicio de sesion',
  //   dashboard: 'Bienvenidos',
  //   'create-account': 'Bienvenidos',
  // };


  if (!to.meta.middleware) {
    return next()
  }

  const middleware = to.meta.middleware

  const context = {
    to,
    from,
    next,
    store
  }
  console.log(to)
  // document.title =`${titlePage[to.name].charAt(0).toUpperCase()}${titlePage[to.name].substring(1)} | DLS Money Plataforma de cambio de dolares a soles online`
  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1)
  })
});

// 


// window.localStorage.clear()
// console.log(store)

const app = createApp(App)
app.use(VueAxios, axios);
app.use(vuetify)
app.use(router)
app.use(store)
app.axios.defaults.baseURL = import.meta.env.VUE_APP_BACKEND_URL 
console.log(app)
app.mount('#app')
