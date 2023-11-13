<template>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <v-aside class="  content__aside"></v-aside>
      <div class="layout-page">
        <v-header class="" />
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <router-view v-slot="{ Component }">
              <transition 
                enter-active-class="animate__animated animate__zoomIn" 
                leave-active-class="animate__animated animate__zoomOut"
                mode="out-in"
              >
                <component class="" :is="Component" />
              </transition>
            </router-view>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<style lang="scss">
//@import "@/assets/sass/index.scss";
//@import "@/assets/sass/core.scss";
a{
  text-decoration: none;
}
 .animate__animated{
  --animate-duration: 0.5s;
 }
</style>
<script>
// import { mapGetters } from "vuex";
import Header from './header/Header.vue';
import Aside from './aside/Aside.vue'
import { GET_USER } from "@/core/services/store/user.module";
import { LOGOUT } from "@/core/services/store/auth.module";
export default {
  name: "Layout",
  components:{
    vHeader : Header,
    vAside  : Aside
  },
  methods:{
    getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          console.log(data)
          if(data.data.code !== 200){
            console.log('alert!!!')
          }
          this.user = data.data.user;
        })
        .catch((e) => {
          this.$store.dispatch(LOGOUT);
        });
    }
  },
  created(){
    this.getUser()
  }
};
</script>
