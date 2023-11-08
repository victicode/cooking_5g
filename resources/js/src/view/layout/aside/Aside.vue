<template>
  <aside class="layout-menu menu-vertical menu bg-menu-theme" id="layout-menu" data-bg-class="bg-menu-theme" >
    <div class="app-brand demo">
      <router-link to="/" class="app-brand-link gap-2">
          <img :src="img.Logo"> 
      </router-link>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
      </a>
    </div>
    <div class="menu-inner-shadow"></div>
      <KTMenu @change="statusEmit"></KTMenu>
  </aside>
</template>

<script>
import { mapGetters } from "vuex";
import KTBrand from "@/view/layout/brand/Brand.vue";
import Logo from "@/assets/media/logos/logo-dls.png"
import KTLayoutAside from "@/assets/js/layout/base/aside.js";
import KTLayoutAsideMenu from "@/assets/js/layout/base/aside-menu.js";
import KTMenu from "@/view/layout/aside/Menu.vue";
import { Menu } from "@/concept/menu";

export default {
  name: "KTAside",
  data() {
    return {
      insideTm: 0,
      outsideTm: 0
    };
  },
  components: {
    KTBrand,
    KTMenu
  },
  mounted() {
    new Menu(document.getElementById('layout-menu'))
    // this.$nextTick(() => {
    //   // Init Aside
    //   KTLayoutAside.init(this.$refs["kt_aside"]);

    //   // Init Aside Menu
    //   KTLayoutAsideMenu.init(this.$refs["kt_aside_menu"]);
    // });
    this.menu()
  },
  methods: {
    /**
     * Use for fixed left aside menu, to show menu on mouseenter event.
     */
    menu(){
      let menuToggler = document.querySelectorAll('.layout-menu-toggle');
      menuToggler.forEach(item => {
        item.addEventListener('click', event => {
          event.preventDefault();
          window.Helpers.toggleCollapsed();
          // Enable menu state with local storage support if enableMenuLocalStorage = true from config.js
          if (config.enableMenuLocalStorage && !window.Helpers.isSmallScreen()) {
            try {
              localStorage.setItem(
                'templateCustomizer-vertical-menu-template--LayoutCollapsed',
                String(window.Helpers.isCollapsed())
              );
            } catch (e) {}
          }
        });
      });
    },
    mouseEnter() {
      if (this.layoutConfig("aside.self.minimize.hoverable")) {
        // check if the left aside menu is fixed
        if (document.body.classList.contains("aside-fixed")) {
          if (this.outsideTm) {
            clearTimeout(this.outsideTm);
            this.outsideTm = null;
          }

          // if the left aside menu is minimized
          if (document.body.classList.contains("aside-minimize")) {
            document.body.classList.add("aside-minimize-hover");

            // show the left aside menu
            document.body.classList.remove("aside-minimize");
          }
        }
      }
    },
    /**
     * Use for fixed left aside menu, to show menu on mouseenter event.
     */
    mouseLeave() {
      if (this.layoutConfig("aside.self.minimize.hoverable")) {
        if (document.body.classList.contains("aside-fixed")) {
          if (this.insideTm) {
            clearTimeout(this.insideTm);
            this.insideTm = null;
          }

          if (document.querySelector(".aside-menu .scroll")) {
            document.querySelector(".aside-menu .scroll").scrollTop = 0;
          }

          // if the left aside menu is expand
          if (document.body.classList.contains("aside-minimize-hover")) {
            // hide back the left aside menu
            document.body.classList.remove("aside-minimize-hover");
            document.body.classList.add("aside-minimize");
          }
        }
      }
    },
    statusEmit(status){
      
    },
  },
  computed: {
    ...mapGetters(["layoutConfig", "getClasses"]),
    img(){
      return({
        Logo : Logo
      })
    },
    /**
     * Get extra classes for menu based on the options
     */
    asideMenuClass() {
      const classes = this.getClasses("aside_menu");
      if (typeof classes !== "undefined") {
        return classes.join(" ");
      }
      return null;
    }
  }
};
</script>
