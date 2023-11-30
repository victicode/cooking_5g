
<script setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
// import { mapGetters } from "vuex";

</script>

<template>
  <DefaultLayoutWithVerticalNav>
    <router-view v-slot="{Component}" >
      <transition 
         mode="out-in" 
         enter-active-class="animate__animated animate__fadeInLeft" 
         leave-active-class="animate__animated animate__fadeOutRight"
       >
         <component  :is="Component"/>
      </transition>
   </router-view>
  </DefaultLayoutWithVerticalNav>
  <v-overlay
    :model-value="overlayLoad"
    persistent="true"
    close-on-back="true"
    class="align-center justify-center"
    id="overlay-load"
  >
    <v-progress-circular
      color="primary"
      indeterminate
      size="64"
    />
  </v-overlay>
  <v-overlay
    :model-value="overlayModal"
    persistent="true"
    close-on-back="true"
    class="align-center justify-center"
    id="overlay-modal"
  >
      <div class="v-card v-theme--light v-card--density-default v-card--variant-elevated">
        <!---->
        <div class="v-card__loader">
          <div class="v-progress-linear v-theme--light v-locale--is-ltr" role="progressbar" aria-hidden="true" aria-valuemin="0" aria-valuemax="100" style="top: 0px; height: 0px; --v-progress-linear-height: 2px; left: 50%; transform: translateX(-50%);">
            <!----><div class="v-progress-linear__background" style="width: 100%;"></div>
            <div class="v-progress-linear__indeterminate">
              <div class="v-progress-linear__indeterminate long"></div>
              <div class="v-progress-linear__indeterminate short"></div>
            </div><!---->
          </div>
        </div><!----><!---->
        <header class="v-toolbar v-toolbar--density-default bg-primary v-theme--light v-locale--is-ltr">
          <div class="v-toolbar__content" style="height: 64px;">
            <div class="v-toolbar-title">
              <div class="v-toolbar-title__placeholder">
                Opening from the top<!---->
              </div>
            </div>
          </div>
        </header>
        <div class="v-card-text">
          <div class="text-h2 pa-12">
            Hello world!
          </div>
        </div>
        <div class="v-card-actions justify-end">
          <button type="button" class="v-btn v-theme--light text-primary v-btn--density-default v-btn--size-default v-btn--variant-text">
            <span class="v-btn__overlay"></span>
            <span class="v-btn__underlay"></span>
            <span class="v-btn__content" data-no-activator="">Close</span>
          </button>
        </div>
        <span class="v-card__underlay"></span>
      </div>
  </v-overlay>

</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
<style >
body{
  background: #9e9e9e23!important;
}
.animate__animated{
  animation-duration: 0.25s;
}
</style>
<script>
import { GET_USER } from "@/core/services/store/user.module";
import { LOGOUT } from "@/core/services/store/auth.module";
export default {
  methods:{
    getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          if(data.code !== 200){
            console.log('alert!!!')
          }
          this.user = data.user;
          this.overlayLoad = false
        })
        .catch((e) => {
          console.log(e)
          this.$store.dispatch(LOGOUT).then(() => { this.$router.go('/login')});
        });
    },
  },
  data: () => ({
    overlayLoad: false,
    overlayModal: false,
  }),
  created(){
    this.emitter.on("displayOverlayLoad", (status) => {
      this.overlayLoad = status
    })
    this.emitter.on("displayOverlayModal", (status) => {
      this.overlayModal = status
    })
    this.getUser()
  }
};</script>