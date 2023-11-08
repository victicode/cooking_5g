<template>
  <div class="layout-wrapper layout-content-navbar" v-if="isAuthenticated">
    <div class="layout-container">
      <!-- begin:: Header Mobile -->
      <!-- <KTHeaderMobile></KTHeaderMobile> -->
      <!-- end:: Header Mobile -->
      <!-- begin:: Aside Left -->
      <KTAside v-if="asideEnabled"></KTAside>
      <!-- end:: Aside Left -->

      <!-- <Loader v-if="loaderEnabled" v-bind:logo="loaderLogo"></Loader> -->

      <!-- begin::Body -->
      <div id="kt_wrapper" class="layout-page">
          <!-- begin:: Header -->
          <KTHeader></KTHeader>
          <!-- end:: Header -->
          <!-- begin:: Content -->
          <div id="kt_content" class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y" :style="{ backgroundImage: `url('${img.backgroundImage}')` }">
                <transition name="fade-in-up">
                  <router-view />
                </transition>
            </div>
            <KTFooter></KTFooter>
          </div>
          <!-- end:: Content -->
      </div>
      <modals></modals>
      <div class="content-backdrop fade"></div>
    </div>
    
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import KTAside from "@/view/layout/aside/Aside.vue";
import KTHeader from "@/view/layout/header/Header.vue";
import KTHeaderMobile from "@/view/layout/header/HeaderMobile.vue";
import KTFooter from "@/view/layout/footer/Footer.vue";
import Modals from "@/view/layout/modals/GeneralModals.vue";
import HtmlClass from "@/core/services/htmlclass.service";
import KTScrollTop from "@/view/layout/extras/ScrollTop";
import Loader from "@/view/content/Loader.vue";
import backgroundImage from "@/assets/media/backgrounds/bg-blanco.jpg"
import { LOGOUT } from "@/core/services/store/auth.module";
import {
  ADD_BODY_CLASSNAME,
  REMOVE_BODY_CLASSNAME
} from "@/core/services/store/htmlclass.module.js";

export default {
  name: "Layout",
  data(){
    return {
      timerx:0,
      isDesactivate: '',
      openModal:'',
      isAdmin: false,
    }
  },
  components: {
    KTAside,
    KTHeader,
    KTHeaderMobile,
    KTFooter,
    KTScrollTop,
    Loader,
    Modals
  },
  beforeMount() {
    
    // show page loading
    window.localStorage.removeItem("has_account")
    this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");
    this.$store.dispatch(ADD_BODY_CLASSNAME, "p-0");
    // initialize html element classes
    HtmlClass.init(this.layoutConfig());
  },
  mounted() {
    document.querySelector('body').addEventListener('click', event => {
        this.timeOfSesion()
        
    })	
    this.timeOfSesion();
    // check if current user is authenticated
    if (!this.isAuthenticated) {
      this.$router.push({ name: "login" });
    }
    // Simulate the delay page loading
    setTimeout(() => {
      // Remove page loader after some time
      this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
    }, 2000);
  },
  methods: {
    timeOfSesion(){
      let timeSesion = window.localStorage.is_admin == 'false' ? 14 : 600;
      let _this = this
      if(this.timerx == 0 ){

      }else{
        clearInterval(this.timerx)
      }
      this.timerx = setInterval(function () {
          if ( timeSesion == 0) {
            clearInterval(this.timer)
            _this.$store.dispatch(LOGOUT).then(() =>{
              let trashElement = document.querySelectorAll('.modal-backdrop');
							trashElement.forEach((item)=>{
								document.querySelector('body').removeChild(item);
							})
              _this.$router.push({ name: "login" })
              window.localStorage.removeItem("id_token");
              window.localStorage.removeItem("has_account");
              window.localStorage.removeItem("is_admin");
              window.localStorage.removeItem("session_on");
            });
            
          }else{
            timeSesion -= 1
          }
          console.log(timeSesion)
      }, 60000);
    },
  },
  computed: {
    ...mapGetters([
      "isAuthenticated",
      "breadcrumbs",
      "pageTitle",
      "layoutConfig"
    ]),

    /**
     * Check if the page loader is enabled
     * @returns {boolean}
     */
    loaderEnabled() {
      return !/false/.test(this.layoutConfig("loader.type"));
    },

    /**
     * Check if container width is fluid
     * @returns {boolean}
     */
    img(){
      return({
        backgroundImage : backgroundImage
      })
    },
    contentFluid() {
      return this.layoutConfig("content.width") === "fluid";
    },

    /**
     * Page loader logo image using require() function
     * @returns {string}
     */
    loaderLogo() {
      return process.env.BASE_URL + this.layoutConfig("loader.logo");
    },

    /**
     * Check if the left aside menu is enabled
     * @returns {boolean}
     */
    asideEnabled() {
      return !!this.layoutConfig("aside.self.display");
    },

    /**
     * Set the right toolbar display
     * @returns {boolean}
     */
    toolbarDisplay() {
      // return !!this.layoutConfig("toolbar.display");
      return true;
    },

    /**
     * Set the subheader display
     * @returns {boolean}
     */
    subheaderDisplay() {
      return !!this.layoutConfig("subheader.display");
    }
  }
};
</script>
