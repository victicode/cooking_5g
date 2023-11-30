<script setup>
import { useTheme } from 'vuetify'
// import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'
import VerticalDropdownNavLink from '@layouts/components/VerticalDropdownNavLink.vue'
import VBreadcums from '@layouts/components/Breadcums.vue'

import { mapGetters } from "vuex";

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'

const vuetifyTheme = useTheme()

</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center px-0 mx-0">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <!-- 👉 Search -->
        <VBreadcums class="d-none d-md-block" />
      
        <VSpacer />

        <IconBtn class="me-2">
          <VIcon icon="bx-bell" />
        </IconBtn>

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>
    <VBreadcums class="d-block d-md-none" />
    <template #vertical-nav-content>
      <VerticalNavLink
        @click="emitter.emit('displayOverlay', true)"
        class="mt-3"
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: '/dashboard',
        }"
      />
      <VerticalNavLink
        @click="emitter.emit('displayOverlay', true)"
        class="mt-3"
        :item="{
          title: 'Productos',
          icon: 'bx-package',
          to: '/products',
        }"
      />
      <VerticalDropdownNavLink 
        class="mt-3"
        :item="{
          title: 'Usuarios - Chefs',
          items:[
            {
              title: 'Usuarios',
              icon: 'mdi-users-group-outline',
              to: '/user-list',
            },
            {
              title: 'Chef',
              icon: 'icon-park-twotone:chef-hat',
              to: '/chef-list',
            }
          ],
        }"
      />
      <VerticalNavLink
        @click="emitter.emit('displayOverlay', true)"
        class="mt-3"
        :item="{
          title: 'Ordenes',
          icon: 'bx-task',
          to: '/orders',
        }"
      />
      <VerticalNavLink
        @click="emitter.emit('displayOverlay', true)"
        class="mt-3"
        :item="{
          title: currentUser.rol_id == 1 ? 'Recetas' : 'Tus Recetas',
          icon: 'bx-receipt',
          to: '/user-list',
        }"
      />

    </template>

    <!-- 👉 Pages -->
    
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
<script>
import { mapGetters } from "vuex";
  export default {
    methods:{
    },
    created(){
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>