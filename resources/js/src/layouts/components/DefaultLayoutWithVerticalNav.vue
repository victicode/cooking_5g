<script setup>
import { useTheme } from 'vuetify'
import { GET_ALL_UNREAD_MESSAGES } from "@/core/services/store/chat.module"
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
import NotificationArea from '@/layouts/components/NotificationArea.vue'
import MessageArea from '@/layouts/components/MessageArea.vue'


const vuetifyTheme = useTheme()

</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center px-0 mx-0">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <div  class="position-relative ps-1 menu-content" >
          <IconBtn
            class="ms-n3 d-lg-none"
            @click="toggleVerticalOverlayNavActive(true)"
            >
            <VIcon icon="bx-menu" />
          </IconBtn>
          <div class="unReadMessage-acitve bg-error"  v-if="UnreadMessage > 0" />
        </div>

        <!-- 👉 Search -->
        <VBreadcums class="d-none d-md-block" />
      
        <VSpacer />

        
        <NotificationArea />
        <MessageArea v-if="getCurrentAccount.rol_id !== 3"/>

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>
    <VBreadcums class="d-block d-md-none" v-if="$route.href !=='/support' "/>
    <template #vertical-nav-content>
      <VerticalNavLink
        class="mt-3"
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: '/dashboard',
        }"
      />
      <VerticalNavLink
        v-if="getCurrentAccount.rol_id !== 3 "
        class="mt-3"
        :item="{
          title: 'Productos',
          icon: 'bx-package',
          to: currentUser.rol_id !== 3  ? '/products' : '/products_client', 
        }"
      />
      <VerticalDropdownNavLink 
        class="mt-3"
        v-if="getCurrentAccount.rol_id == 1 "
        :item="{
          title: 'Chefs',
          items:[
            {
              title: 'Chefs usuario',
              icon: 'mdi-users-group-outline',
              to: '/user-list',
            },
            {
              title: 'Chef Master',
              icon: 'icon-park-twotone:chef-hat',
              to: '/chef-list',
            }
          ],
        }"
      />
      <VerticalNavLink

        class="mt-3"
        :item="{
          title:  'Recetas',
          icon: 'bx-receipt',
          to: currentUser.rol_id !== 3 ? '/recipes' : '/recipes',
        }"
      />
      <VerticalNavLink
      
        class="mt-3"
        :item="{
          title: currentUser.rol_id !== 3 ?'Ordenes' : 'Mis Ordenes',
          icon: 'bx-task',
          to: '/orders',
        }"
      />
      <VerticalNavLink
      
        class="mt-16"
        :item="{
          title: 'Soporte',
          icon: 'ic:sharp-support',
          to: '/support',
        }"
      />
    </template>

    <!-- 👉 Pages -->
    
    <slot />

    <!-- 👉 Footer -->
    <!-- <template #footer>
      <Footer />
    </template> -->
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.unReadMessage-acitve{
  position: absolute;
  width: 12px;
  height: 12px;
  top:5px;
  right: 40%;
  border-radius: 50%;
}
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
  export default {
    data(){
      return{
        UnreadMessage:0,
      }
    },
    methods:{
      getUnReadMessages(){
        this.$store
        .dispatch(GET_ALL_UNREAD_MESSAGES)
        .then((data) => {
          this.UnreadMessage = data.data
        }).catch((error)=>{
          console.log(error)
        })
      }
    },
    mounted(){
      this.getUnReadMessages()
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>