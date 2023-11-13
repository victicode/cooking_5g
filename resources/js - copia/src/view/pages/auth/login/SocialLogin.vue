<template>
    <div  class="authentication-wrapper authentication-cover authentication-bg vh-100 pt-4 ps-2"  :style="{ backgroundImage: `url(${backgroundImage.background})` }">
      <div id="kt_login" class="login login-1  authentication-inner d-flex align-items-center" :class="{'login-signin-on': this.state == 'signin', 'login-signup-on': this.state == 'signup', 'login-forgot-on': this.state == 'forgot'}">
        <h1>Inciando sesion... por favor espera</h1>
      </div>
      <div>
        <template v-if="formSubmit ">
              <div class="w-100 border border-base border-end-0 border-start-0 d-flex justify-content-center p-4 align-items-center"  >
                <div class="spinner-border me-2 text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <h3 class=" text-center m-0 text-primary">Cargando</h3>
              </div>
            </template>
      </div>
    </div>
  </template>
  
  <!-- Load login custom page styles -->

  
  <script>
  
  // FormValidation plugins
  import { mapGetters, mapState } from "vuex";
  import { LOGOUT, REGISTER_GOOGLE } from "@/core/services/store/auth.module";

  
  export default {
    data() {
      return {
        state: "signin",
        formSubmit:false,
        registerData: {
          show: false,
          status: null,
          message: null,
          errors: {}
        },
      };
    },
    computed: {
      ...mapState({
        errors: state => state.auth.errors
      }),
      backgroundImage() {
        return (
          {
            leftSide: process.env.BASE_URL + "media/illustrations/registrong.png",
            background: process.env.BASE_URL + "media/backgrounds/bg-blanco.jpg",
          }
        );
      }
    },

    mounted(){
      this.UserStore()
    },
    methods: {
      UserStore(){
        // clear existing errors
        this.$store.dispatch(LOGOUT);
        const code = this.$route.query.code;
        const provider = this.$route.params.provider;
        this.$store
          .dispatch(REGISTER_GOOGLE,{code: code, provider: provider})
          .then((data) => {
            this.registerData.show = true;
            this.registerData.status = data.status;
            if(data.errors){
              this.registerData.errors = data.errors;
            }
            else{
              this.registerData.message = data.message;
            }

            if(this.registerData.status === 'ok'){
              setTimeout(() => {
                window.opener.location.href = "/dashboard";
                window.close()
              }, 300);
            }
          });
      },
    }
  };
  </script>
  