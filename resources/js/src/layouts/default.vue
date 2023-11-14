<script setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
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
  animation-duration: 0.3s;
}
</style>