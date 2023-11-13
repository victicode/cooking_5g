<template>
  <div class="row">
    <h1>Bienvenido: {{user.email}}</h1>
    <img src="https://humanidades.com/wp-content/uploads/2017/03/pajaro-azul-e1563758291533.jpg" height="200" width="200" alt="">
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import { GET_USER } from "@/core/services/store/user.module";
import { LOGOUT } from "@/core/services/store/auth.module";

export default {
  name:'dashboard',
  data(){
    return{
      user: {},

      
    }
  },
  mounted(){
    this.getUser()
    // const ll = [...mapGetters(["currentAccount"])];
    setTimeout(() => {
      
      // console.log(this.$store.getters.currentUser)
    }, 2000);
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
};
</script>