<template>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

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
    
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

  </div>
</template>

<script>
// import { mapGetters } from "vuex";

export default {
  name: "Layout",
};
</script>
