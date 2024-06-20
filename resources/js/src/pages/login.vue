<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-md-4 row ">
    <v-row
      align="start"
    >
      <v-col cols="12" md="4" offset-md="7"
      >
        <VCard
          class="auth-card pa-md-4 pt-md-12 vh-auto d-flex flex-column"
        >
          <VCardItem class="justify-center w-100  py-2 mt-5 mt-md-0 py-md-0 py-6">
            <VCardTitle class="text-2xl font-weight-bold">
              <div class="card-title d-flex ">
                <div class="form-title__part1">Cooking</div> <div class="form-title__part2">5G</div>
              </div>
            </VCardTitle>
          </VCardItem>
          <VCardText class="w-100">
            <div class="py-2 w-100  mb-md-4 mb-6" >
              <h5 class="text-h5 mb-1">
                Bienvenido! 👋🏻
              </h5>
              <p class="mb-0">
                Inicia sesión con tu cuenta ahora
              </p>
            </div>
            <div class="   w-100 pa-0 px-md-0" :class="{'py-4 mb-md-0': !alertShow, 'mb-md-7 mb-8':alertShow}" >
              <v-alert
                v-if="alertShow"
                :color="alertType"
                :text="alertMessage"
              />
            </div>
            <VForm @submit.prevent="$router.push('/')" id="kt_login_signin_form">
              <VRow>
                <!-- email -->
                <VCol cols="12" class="form-group">
                  <VTextField
                    v-model="form.email"
                    autofocus
                    placeholder="cooking5g@cooking.com ✉"
                    label="Correo electrónico"
                    type="text"
                    name="email"
                    autocomplete="off"
                  />
                </VCol>

                <!-- password -->
                <VCol cols="12" class="form-group pb-0">
                  <VTextField
                    v-model="form.password"
                    label="Contraseña"
                    placeholder="Tu cooking-contraseña"
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
                      :loading="loading"
                    >
                      Iniciar sesion
                      <template v-slot:loader>
                        <loadingButton />
                      </template>
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
    :persistent="true"
    :close-on-back="true"
    class="align-center justify-center"
  >
    <v-progress-circular
      color="primary"
      indeterminate
      size="64"
    />
    <!-- <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div> -->
  </v-overlay>
  </div>
</template>

<style lang="scss" scoped>
  @use "@core/scss/template/pages/page-auth.scss";
  @import "@/assets/sass/login.scss";
  .vh-auto{
    height: 68vh;
  }
  @media screen and (max-width: 780px){
    .auth-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }
    .vh-auto{
      height: 100vh;
      padding: 0px!important;
    }
  }
</style>
<script>
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import { LOGOUT, LOGIN } from "@/core/services/store/auth.module";
import loadingButton from "@/layouts/components/loadingButton.vue";
export default {
  data: () => ({
    overlay: false,
    validation: '',
    alertShow:false,
    alertMessage:'',
    alertType:'',
    isPasswordVisible: false,
    loading:false,
    form:{
      email: '',
      password: '',
    }
  }),
  components: {
    loadingButton
  },
  mounted() {
    document.querySelector("body").style.background ="url('"+import.meta.env.VITE_VUE_APP_BACKEND_URL+"images/background/login/01.jpg')"
    this.formValidation()
  },
  methods: {
    showAlert(type, messagge){
      this.alertShow = true;
      this.alertType = type
      this.alertMessage = messagge
    },
    formValidation(){
      this.validation = formValidation(document.getElementById('kt_login_signin_form'), {
        fields: {
          email: {
            validators: {
              notEmpty: {
                message: "Por favor ingrese correo electrónico"
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
          bootstrap: new Bootstrap(),
        }
      });

      this.validation.on("core.form.valid", () => {
        this.login()
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
    login(){
      this.$store.dispatch(LOGOUT);
      this.loadingState(true)
      const submitButton = document.getElementById('kt_login_signin_submit')
      this.$store
      .dispatch(LOGIN, this.form)
        .then((data) => {
          if(data.code === 500 ){

            this.loadingState(false)
            this.showAlert('error',data.messagge)
            return
          }
          this.overlay = true
          this.showAlert('success','Acceso Exitoso')
          this.loadingState(true)
          setTimeout(() => {
            this.$router.push('/dashboard') 
          }, 500);
        })
        .catch((e) => {
          this.loadingState(false)
          this.showAlert('error','Error desconocido')
        });
    },
    loadingState(state){
      this.loading = state
    }
  }
};
</script>
