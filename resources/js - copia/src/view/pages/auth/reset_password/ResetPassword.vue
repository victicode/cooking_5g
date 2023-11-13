<template>
    <div  class="authentication-wrapper authentication-cover authentication-bg"  :style="{ backgroundImage: `url(${backgroundImage.background})` }">
      <div id="kt_login" class="login login-1  authentication-inner d-flex align-items-center login-forgot-on">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 p-0">
          <div id="leftSide" class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center" :style="{ backgroundImage: `url(${backgroundImage.leftSide})` }">
          </div>
        </div>
        <!-- /Left Text -->
  
        <!-- Forms Side -->
        <div class="col-12 col-lg-5 align-items-center p-sm-0 p-4 ">
          <div class="w-px-400 mx-auto  ">
              <!-- Logo -->
            <div class="app-brand mb-4">
              <a href="#" class="app-brand-link gap-2">
              
                <img :src="img.Logo">
                
              </a>
            </div>
            <!-- Forgot -->
            <div class="login-form login-forgot">
              <div class="row" v-if="linkToResetData.show">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <b-alert :show="linkToResetData.show" dismissible fade :variant="linkToResetData.label" @dismissed="linkToResetData.show=false">{{ linkToResetData.message }}</b-alert>
                  
                </div>
                <hr>
              </div>
              <h3 class="mb-1 fw-bold">Restablecer contraseña 🔐</h3>
              <p class="mb-4"> Ingresa tu nueva contraseña.</p>
              <form  class="mb-3" novalidate="novalidate" id="kt_login_forgot_form">
                <div class="mb-3 form-group form-password-toggle">
                  <label class="form-label" for="password">Contraseña</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      class="form-control"
                      placeholder="********"
                      name="password" 
                      ref="password" 
                      autocomplete="off"
                    />
                    <span class="input-group-text cursor-pointer" @click="initPasswordToggle($event)"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3 form-group form-password-toggle">
                  <label class="form-label" for="cpassword">Confirmar contraseña</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      class="form-control"
                      placeholder="Confirmar contraseña"
                      name="cpassword" 
                      ref="cpassword" 
                      autocomplete="off" 
                    />
                    <span class="input-group-text cursor-pointer" @click="initPasswordToggle($event)"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <button class="btn btn-primary d-grid w-100" ref="kt_login_forgot_submit" :disabled=kt_login_forgot_submit_disabled >Restablecer contraseña</button>
              </form>
              
            </div>
            <!-- /Forgot -->
          </div>
        </div>
        <!-- /Form Side -->
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
  <style lang="scss">
  @import "@/assets/sass/pages/page-auth.scss";
  </style>
  
  <script>
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  
  // FormValidation plugins
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  
  import KTUtil from "@/assets/js/components/util";
  import { mapGetters, mapState } from "vuex";
  import { LOGOUT, RESET_PASSWORD } from "@/core/services/store/auth.module";
  import Swal from "sweetalert2";
  import Logo from "@/assets/media/logos/logo-dls.png"
  import cuared from "@/assets/media/bg-shape-image-light.png"
  
  export default {
    data() {
      return {
        state: "forgot",
        formSubmit:false,
        linkToResetData: {
          show: false,
          status: null,
          message: null,
          label: null
        },
        kt_login_forgot_submit_disabled: true,

      };
    },
    computed: {
      ...mapState({
        errors: state => state.auth.errors
      }),
      ...mapGetters(["currentUser"]),
      img(){
        return({
          Logo : Logo,
          c:cuared
        })
      },
      backgroundImage() {
        return (
          {
            leftSide: process.env.BASE_URL + "media/backgrounds/fondo-recuperarpass.jpg",
            background: process.env.BASE_URL + "media/backgrounds/bg-blanco.jpg",
          }
        );
      }
    },
    watch: {
      'errors': {
        handler: function () {
          if (this.errors.length > 0) {
                this.kt_login_signin_submit_disabled = false;
                this.$refs["kt_login_signin_submit"].textContent = 'Ingresar';
                  }
              },
              deep: true
      },
    },
  
    mounted() {

      const forgot_form = KTUtil.getById("kt_login_forgot_form");
      
      this.fv2 = formValidation(forgot_form, {
        fields: {
          password: {
          validators: {
            notEmpty: {
              message: "La contraseña es requerida"
            },
            regexp: {
              regexp: /^[A-Za-z0-9.#-+*/@$]+$/i,
              message: 'La contraseña no debe contener espacios ni los siguientes caracteres: "[]{}!¡¿?=()&|',
            },
            stringLength: {
              min: 8,
              message: 'La contraseña debe contener minimo 8 caracteres',
            },
          }
        },
        cpassword: {
          validators: {
            notEmpty: {
              message: "La confirmación de contraseña es requerida"
            },
            identical: {
              compare: function() {
                return forgot_form.querySelector('[name="password"]').value;
              },
              message: "La contraseña y su confirmacion no coinciden"
            }
          }
        },
        },
        plugins: {
          trigger: new Trigger(),
          submitButton: new SubmitButton(),
          bootstrap: new Bootstrap()
        }
      });
  
  
      this.fv2.on("core.form.valid", () => {
        this.kt_login_signup_submit_disabled = true;
        // clear existing errors
        this.$store.dispatch(LOGOUT);
  
        // set spinner to submit button
        const submitButton = this.$refs["kt_login_forgot_submit"];
        submitButton.textContent = 'Cargando...'
        this.linkToResetData = {
          show: false,
          status: null,
          message: null,
          label: null
        };
       
        const password = {
						id:this.$route.query.id, 
						newPassword: this.$refs.password.value,
						token:this.$route.params.token,
					}
        // send login request
        this.$store
          .dispatch(RESET_PASSWORD, password)
          // go to which page after successfully login
          .then((data) => {
            console.log(data)
            this.linkToResetData.show = true;
            this.linkToResetData.status = data.status;
            this.linkToResetData.message = data.message;
            this.linkToResetData.label = data.status == 'ok' ? 'success' : 'danger'
            this.kt_login_signup_submit_disabled = data.status == 'ok' ? true : false;
            submitButton.textContent = data.status == 'ok' ? 'Te estamos redirigiendo!' : 'Enviar'

            if(data.status == 'ok'){

              setTimeout(() => {
                this.$router.push({ name: "login" })
              }, 2000);
            }
          })
          .catch(() => {
            submitButton.textContent = 'Enviar'
          });
      }).on("core.field.invalid", () => {
        this.kt_login_forgot_submit_disabled = true;
        this.$refs["kt_login_forgot_submit"].textContent = 'Enviar'
      }).on("core.form.invalid", () => {
        this.kt_login_signup_submit_disabled = true;
        // set spinner to submit button
        const submitButton = this.$refs["kt_login_forgot_submit"];
        submitButton.textContent = 'Cargando...'
        Swal.fire({
          title: "",
          text: "Por favor, proporcione los datos correctos y asegurate de aceptar los terminos y condiciones",
          icon: "error",
          confirmButtonClass: "btn btn-secondary",
          heightAuto: false
        });
        this.kt_login_forgot_submit_disabled = false;
        submitButton.textContent = 'Crear Cuenta'
      }).on("core.field.valid", () => {
        this.kt_login_forgot_submit_disabled = false;
        this.$refs["kt_login_forgot_submit"].textContent = 'Enviar'
      });
  
    },
    methods: {
      validarCharacter(input){
        input.value = input.value.replace(/[^a-zA-Z0-9]/,'')
      },
      initPasswordToggle(event) {
        let elx = event.srcElement;
          const formPasswordToggle = elx.closest('.form-password-toggle')
          const formPasswordToggleIcon = formPasswordToggle.querySelector('i')
          const formPasswordToggleInput = formPasswordToggle.querySelector('input')
          if (formPasswordToggleInput.getAttribute('type') == 'text') {
            formPasswordToggleInput.setAttribute('type', 'password')
            formPasswordToggleIcon.classList.replace('ti-eye', 'ti-eye-off')
          }else{
            formPasswordToggleInput.setAttribute('type', 'text')
            formPasswordToggleIcon.classList.replace('ti-eye-off', 'ti-eye')
          }
      },

    },

  };
  </script>
  