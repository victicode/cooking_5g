
<script setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
// import { mapGetters } from "vuex";

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
/* .loader {
  width: 100px;
  height: 56px;
  --_g: no-repeat radial-gradient(farthest-side,#cf6123 94%,#0000);
  background:
    var(--_g) 50%  0,
    var(--_g) 100% 0;
  background-size: 24px 24px;
  position: relative;
  animation: l23-0 1.5s linear infinite;
}
.loader:before {
  content: "";
  position: absolute;
  height: 24px;
  aspect-ratio: 1;
  border-radius: 50%;
  background: #cf6123;
  left:0;
  top:0;
  animation: 
    l23-1 1.5s linear infinite,
    l23-2 0.5s cubic-bezier(0,200,.8,200) infinite;
}
@keyframes l23-0 {
  0%,31%  {background-position: 50% 0   ,100% 0}
  33%     {background-position: 50% 100%,100% 0}
  43%,64% {background-position: 50% 0   ,100% 0}
  66%     {background-position: 50% 0   ,100% 100%}
  79%     {background-position: 50% 0   ,100% 0}
  100%    {transform:translateX(calc(-100%/3))}
}
@keyframes l23-1 {
  100% {left:calc(100% + 7px)}
}
@keyframes l23-2 {
  100% {top:-0.1px}
} */
/* .loader {
    position: relative;
    width: 100px;
    height: 100px;
  }
  .loader:before{
    content: '';
    position: absolute;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    top: 50%;
    left: 0;
    transform: translate(-5px, -50%);
    background: linear-gradient(to right, #fff 50%, #de3500 50%) no-repeat;
    background-size: 200% auto;
    background-position: 100% 0;
    animation: colorBallMoveX 1.5s linear infinite alternate;
  }
 .loader:after{
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    transform: translateX(-50%);
    width: 2px;
    height: 100%;
    background: #de3500;
 }
@keyframes colorBallMoveX {
  0%  {
    background-position: 0% 0;
    transform: translate(-15px, -50%);
  }
  15%  , 25% {
    background-position: 0% 0;
    transform: translate(0px, -50%);
  }
  75% , 85% {
    background-position: 100% 0;
    transform: translate(50px, -50%);
  }
  100% {
    background-position: 100% 0;
    transform: translate(65px, -50%);
  }
} */

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
import { LOGOUT, PURGE_AUTH } from "@/core/services/store/auth.module";
export default {
  methods:{
    getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          if(data.code !== 200){
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
      window.localStorage.removeItem("has_account");
      window.localStorage.removeItem("is_admin");
      window.localStorage.removeItem("session_on");
      setTimeout(() => {
        this.$router.go('/login')
      }, 200);
    }
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