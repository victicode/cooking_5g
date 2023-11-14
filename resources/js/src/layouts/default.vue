
<script setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
// import { mapGetters } from "vuex";

</script>

<template>
  <DefaultLayoutWithVerticalNav>
    <router-view v-slot="{Component}">
      <transition 
         mode="out-in" 
         enter-active-class="animate__animated animate__fadeInLeft" 
         leave-active-class="animate__animated animate__fadeOutRight"
       >
         <component :is="Component"/>
      </transition>
   </router-view>
  </DefaultLayoutWithVerticalNav>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
<style>
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
          console.log(data)
          if(data.code !== 200){
            console.log('alert!!!')
          }
          this.user = data.user;
        })
        .catch((e) => {
          this.$store.dispatch(LOGOUT);
        });
    }
  },
  created(){
    this.getUser()
  }
};</script>