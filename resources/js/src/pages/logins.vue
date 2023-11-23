<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4 row">
    <v-row
      align="start"
    >
      <v-col cols="12" md="4" offset-md="7"
      >
        <VCard
      class="auth-card pa-4 pt-7 vh-70 d-flex flex-column"
      
    >
      <VCardItem class="justify-center w-100">

        <VCardTitle class="text-2xl font-weight-bold">
          <div class="card-title d-flex ">
            <div class="form-title__part1">Cooking</div> <div class="form-title__part2">5G</div>
            
          </div>
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2 w-100">
        <h5 class="text-h5 mb-1">
          Bienvenido! 👋🏻
        </h5>
        <p class="mb-0">
         Inicia sesion con tu cuenta ahora
        </p>
      </VCardText>
      <VCardText class="pt-2 w-100 pa-1" v-if="alertShow">
        <v-alert
          :color="alertType"
          :text="alertMessage"
        ></v-alert>
      </VCardText>
      <VCardText class="w-100">
        <VForm @submit.prevent="$router.push('/')" id="kt_login_signin_form">
          <VRow>
            <!-- email -->
            <VCol cols="12" class="form-group">
              <VTextField
                v-model="form.email"
                autofocus
                placeholder="johndoe@email.com"
                label="Email"
                type="text"
                name="email"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12" class="form-group pb-0">
              <VTextField
                v-model="form.password"
                label="Password"
                placeholder="············"
                autocomplete="off"
                name="password"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />
              </VCol>
              <VCol cols="12" class="pt-0 mt-8">
                <!-- login button -->
                <VBtn
                  block
                  type="submit"
                  id="kt_login_signin_submit"
                >
                  Iniciar sesion
                </VBtn>
              </VCol>
          </VRow>
        </VForm>
      </VCardText>
        </VCard>
      </v-col>
    </v-row>
    <v-overlay
    :model-value="overlay"
    persistent="true"
    close-on-back="true"
    class="align-center justify-center"
  >
    <v-progress-circular
      color="primary"
      indeterminate
      size="64"
    />
  </v-overlay>
  </div>
</template>

<style lang="scss" scoped>
@use "@core/scss/template/pages/page-auth.scss";
@import "@/assets/sass/login.scss";
// @import "@/assets/plugins/formvalidation/src/css/index.scss";
</style>

<script>
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";

import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import { LOGOUT, LOGIN } from "@/core/services/store/auth.module";

export default {
  data: () => ({
    overlay: false,
    fv: "",
    alertShow:false,
    alertMessage:'',
    alertType:'',
    isPasswordVisible: false,
    form:{
      email: '',
      password: '',
      remember: false,
    }
  }),
  computed: {
  },
  mounted() {
    document.querySelector("body").style.background ="url('http://192.168.42.226:8005/images/background/login/01.jpg')"
    this.fv = formValidation(document.getElementById('kt_login_signin_form'), {
      fields: {
        email: {
          validators: {
            notEmpty: {
              message: "Por favor ingrese correo electrónico / nombre de usuario"
            },
            regexp: {
              regexp: /^[A-Za-z0-9.*-+/@$_]+$/i,
              message: 'El Correo no debe contener espacios ni los siguientes caracteres: "[]{}!¡¿?=()&|',
            },
          }
        },
        password: {
          validators: {
            notEmpty: {
              message: "Por favor, introduzca su contraseña"
            },
            regexp: {
              regexp: /^[A-Za-z0-9.#-+*/@$ñ_€]+$/i,
              message: 'La contraseña no debe contener espacios ni los siguientes caracteres: "[]{}!¡¿?=()&|',
            },
            stringLength: {
              min: 8,
              message: 'La contraseña debe contener minimo 8 caracteres',
            },
          }
        }
      },
      plugins: {
        trigger: new Trigger(),
        submitButton: new SubmitButton(),
        bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
            }),
      }
    });

    this.fv.on("core.form.valid", () => {
      const email = this.form.email
      const password = this.form.password
      const remember = 'true'


      // console.log(email)
      // clear existing errors
      this.$store.dispatch(LOGOUT);
      
      // set spinner to submit button
      const submitButton = document.getElementById('kt_login_signin_submit')
      submitButton.textContent = 'Cargando...'
      
      // dummy delay
        this.$store
        .dispatch(LOGIN, { email, password, remember })
        .then((data) => {
          if(data.code === 500 ){
            submitButton.textContent = 'Ingresar';
            submitButton.blur();
            this.showAlert('error',data.messagge)
            return
          }
          this.overlay = true
          this.showAlert('success','Acceso Exitoso')
          submitButton.textContent = 'Acceso Exitoso'
          setTimeout(() => {
            
            this.$router.push('/dashboard') 
          }, 500);
        })
        .catch((e) => {

          console.log(e)
          submitButton.textContent = 'Ingresar';
          submitButton.blur();
          this.showAlert('error','Error desconocido')
        });
    
    }).on("core.form.invalid", () => {
      document.getElementById('kt_login_signin_submit').disabled = true
      this.alertShow = false;
    }).on("core.field.valid", () => {
      document.getElementById('kt_login_signin_submit').disabled = false
      this.alertShow = false;
    }).on("core.field.invalid", () => {
      this.alertShow = false;
      document.getElementById('kt_login_signin_submit').disabled = true
    });
  },
  methods: {
    showAlert(type, messagge){
      this.alertShow = true;
      this.alertType = type
      this.alertMessage = messagge
    }
  }
};
</script>
