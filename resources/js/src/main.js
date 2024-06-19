
// import 'bootstrap/dist/css/bootstrap.min.css';
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import './@core/scss/template/index.scss'
import './@layouts/styles/index.scss'
import './styles/styles.scss'
import { createApp } from 'vue'
import { func } from '@/core/services/utils/utils.js'
import { CHECK_TOKEN, SET_TOKEN } from "@/core/services/store/auth.module";
import axios from "axios";
import VueAxios from "vue-axios";
import store from "@/core/services/store/index.js";
import router from "./router.js";
import middlewarePipeline from './middlewares/middlewarePipeline';
import "bootstrap";
import mitt from 'mitt'
loadFonts()
const emitter = mitt()
const app = createApp(App)

// Create vue app
router.beforeEach(async (to, from, next) => {
  // Caso 1
  document.title = to.meta.title + ' - Cooking 5G'

  if (!to.meta.middleware) {
    return next()
  }

  store.dispatch(CHECK_TOKEN).then((data)=>{
   if(data.data.code !== 200){
    store.commit(SET_TOKEN,data.data.new_token)
   } 
  });

  setTimeout(() => {
    let trashElement = [document.querySelectorAll('.modal-backdrop'), document.querySelectorAll('.tooltip')];

    trashElement.forEach((item)=>{
      item.forEach(element => document.querySelector('body').removeChild(element))
    })
  }, 200);

  if(from.path !== to.path) emitter.emit('displayOverlayLoad', true)

  return to.meta.middleware(to, from, next)
});

// 


// window.localStorage.clear()
// console.log(store)



app.use(vuetify)
app.use(router)
app.use(store)
app.use(func)
app.config.globalProperties.emitter = emitter


app.config.globalProperties.$helper = func;
app.mount('#app')
