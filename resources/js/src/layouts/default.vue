
<script setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'

</script>

<template>
  <div>
    <DefaultLayoutWithVerticalNav >
      <router-view v-slot="{Component}" >
        <transition 
           mode="out-in" 
           enter-active-class="animate__animated animate__fadeIn" 
           leave-active-class="animate__animated animate__fadeOut"
         >
           <component  :is="Component"/>
        </transition>
     </router-view>
    </DefaultLayoutWithVerticalNav>
    <v-overlay
      :model-value="overlayLoad"
      :persistent="true"
      :close-on-back="true"
      class="align-center justify-center"
      id="overlay-load"
    >
      <div class="loader"></div>
    </v-overlay>
  </div>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to  be imported
@use "@layouts/styles/default-layout";
</style>
<style >
body{
  background: #9e9e9e23;
  width: 100%;
  overflow: hidden;
}
.animate__animated{
  animation-duration: 0.25s;
}
.loader {
  width: 16px;
  height: 16px;
  position: relative;
  left: calc(50% - 32px);
  border-radius: 50%;
  color: #fff;
  background: currentColor;
  box-shadow: 32px 0 , -32px 0 ,  64px 0;
}

.loader::after {
  content: '';
  position: absolute;
  left: -32px;
  top: 0;
  width: 16px;
  height: 16px;
  border-radius: 10px;
  background:#FF3D00;
  animation: move 3s linear infinite alternate;
}

@keyframes move {
  0% , 5%{
    left: -32px;
    width: 16px;
  }
  15% , 20%{
    left: -32px;
    width: 48px;
  }
  30% , 35%{
    left: 0px;
    width: 16px;
  }
  45% , 50%{
    left: 0px;
    width: 48px;
  }
  60% , 65%{
    left: 32px;
    width: 16px;
  }

  75% , 80% {
    left: 32px;
    width: 48px;
  }
  95%, 100% {
    left: 64px;
    width: 16px;
  }
}
</style>
<script>
import { GET_USER } from "@/core/services/store/user.module";
export default {
  data: () => ({
    overlayLoad: false,
    overlayModal: false,
  }),
  methods:{
    getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          if(data.code !== 200){
            return
          }
          this.user = data.user;
          this.$store.user = data.user;
          this.overlayLoad = false
        })
        .catch((e) => {
          this.logout()
        });
    },
    logout(){
      window.localStorage.removeItem("id_token");
      window.localStorage.removeItem("is_admin");
      window.localStorage.removeItem("is_S_admin");
      window.localStorage.removeItem("user_unique_id");
      setTimeout(() => {
        this.$router.go('/login')
      }, 200);
    }
  },
  
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