<template>
  <div class="layout-wrapper d-flex">
    <aside class="  content__aside"></aside>
    <div class="content_page ">
      <kt-header class=""></kt-header>
      <div class="layout-container" style="">
  
          <div id="kt_wrapper" class="layout-page">
              <div id="kt_content" class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y" >
                    <transition name="fade-in-up">
                      <router-view />
                    </transition>
                </div>
  
          </div>
        </div>
      </div>
    </div>
    
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

  </div>
</template>
<style lang="scss">
  @import "@/assets/sass/index.scss";
 
</style>
<script>
// import { mapGetters } from "vuex";
import Header from './header/Header.vue';

export default {
  name: "Layout",
  components:{
    ktHeader:Header
  }
};
</script>
