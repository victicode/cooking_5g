// import * as Vue from 'vue';
import App from "./App.vue";
import { createApp } from 'vue/dist/vue.esm-bundler'
import axios from "axios";
import VueAxios from "vue-axios";
import store from "@/src/core/services/store";
import ApiService from "@/src/core/services/api.service";

// Vue.prototype.$func = func
// Vue.prototype.$mainColor = '#1869A0'
// Vue.prototype.$secondaryColor = '#F19023'

// import { Helpers } from "./concept/helpers";

// window.Helpers = Helpers;

// API service init

// import { bootstrap } from "@/concept/bootstrap";

// router.beforeEach(async (to, from, next) => {

//   // const titlePage = {
//   //   login: 'Inicio de sesion',
//   //   dashboard: 'Bienvenidos',
//   //   'create-account': 'Bienvenidos',
//   // };


//   if (!to.meta.middleware) {
//     return next()
//   }

//   const middleware = to.meta.middleware

//   const context = {
//     to,
//     from,
//     next,
//     store
//   }
//   // document.title =`${titlePage[to.name].charAt(0).toUpperCase()}${titlePage[to.name].substring(1)} | DLS Money Plataforma de cambio de dolares a soles online`

//   return middleware[0]({
//     ...context,
//     next: middlewarePipeline(context, middleware, 1)
//   })
// });

// 
const app = createApp(App)

app.use(VueAxios, axios);
// if (process.env.MIX_ENVIROMENT == "production") {
//   Vue.axios.defaults.baseURL = process.env.APP_URL
// } else if (process.env.MIX_ENVIROMENT == "staging") {
//   Vue.axios.defaults.baseURL = process.env.APP_URL
// } else {
//   Vue.axios.defaults.baseURL = process.env.APP_URL
// }
app.axios.defaults.baseURL = import.meta.env.VUE_APP_BACKEND_URL 

app.use(store)
app.mount('#app')