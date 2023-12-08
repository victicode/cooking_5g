
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

</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
<style >
body{
  background: #9e9e9e23;
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
    document.querySelector("body").style.background ="#9e9e9e23"
    this.emitter.on("displayOverlayLoad", (status) => {
      this.overlayLoad = status
    })
    this.emitter.on("displayOverlayModal", (status) => {
      this.overlayModal = status
    })
    this.getUser()
  }
};</script>