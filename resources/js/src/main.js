
import '@/@iconify/icons-bundle'
import 'bootstrap/dist/css/bootstrap.min.css';
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import './@core/scss/template/index.scss'
import './@layouts/styles/index.scss'
import './styles/styles.scss'
import { createApp } from 'vue'
import { func } from '@/core/services/utils/utils.js'
import axios from "axios";
import VueAxios from "vue-axios";
import store from "@/core/services/store/index.js";
import router from "./router.js";
import middlewarePipeline from './middlewares/middlewarePipeline';

import mitt from 'mitt'
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
  // document.title =`${titlePage[to.name].charAt(0).toUpperCase()}${titlePage[to.name].substring(1)} | DLS Money Plataforma de cambio de dolares a soles online`
  document.title = to.meta.title + ' - Cooking 5G'
  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1)
  })
});

// 


// window.localStorage.clear()
// console.log(store)


const emitter = mitt()
const app = createApp(App)
app.use(VueAxios, axios);
app.use(vuetify)
app.use(router)
app.use(store)
app.config.globalProperties.emitter = emitter


// app.axios.defaults.baseURL = import.meta.env.VUE_APP_BACKEND_URL 
app.config.globalProperties.$helper = func;
// console.log(app)
app.mount('#app')
