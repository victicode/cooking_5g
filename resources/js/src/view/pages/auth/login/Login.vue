<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-6 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Coockin 5G</h5>
            <form class="mb-3 form" novalidate="novalidate" id="kt_login_signin_form">
              <div class="login-form-content" id="login-form-content">
                <div class="form-floating mb-3">
                  <input type="text" ref="user" class="form-control" id="floatingInput" name="email"  placeholder="name@example.com">
                  <label for="floatingInput">Correo electronico o nombre de usuario</label>
                </div>
                <div class="form-floating mb-3 input-goup d-flex align-items-center row">
                  <input type="password" name="password" ref="password" class="form-control w-75 border-end-0 input-with-addons" id="floatingPassword" placeholder="Password">
                  <label for="floatingPassword">Contraseña</label>
                  <div class="w-25 addons-input form-control">
                    <i class="fa fa-eye fa-md" @click="viewPassword()"></i>
                  </div>
                </div>
  
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                  <label class="form-check-label" for="rememberPasswordCheck">
                    Recordar Contraseña
                  </label>
                </div>
                <div class="d-grid">
                  <button class="btn btn-primary btn-login text-uppercase fw-bold" disabled="true" ref="kt_login_signin_submit">Ingresar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Load login custom page styles -->
<style lang="scss">
  @import "@/assets/sass/login.scss";
 
</style>

<script>
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
// import 'bootstrap/scss/bootstrap'
// FormValidation plugins
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import { LOGOUT, REGISTER_GOOGLE, LOGIN } from "@/core/services/store/auth.module";
import remote from '../../../../assets/plugins/formvalidation/src/js/validators/remote';


export default {
  data() {
    return {
      state: "signin",
      fv: ""
    };
  },
  computed: {
  },
  mounted() {
    this.fv = formValidation(document.getElementById('kt_login_signin_form'), {
      fields: {
        email: {
          validators: {
            notEmpty: {
              message: "Por favor ingrese correo electrónico / nombre de usuario"
            },
            regexp: {
              regexp: /^[A-Za-z0-9.#-+*/@$_]+$/i,
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
              rowSelector: '.form-floating'
            }),
      }
    });

    this.fv.on("core.form.valid", () => {
      const email = this.$refs.user.value
      const password = this.$refs.password.value
      const remember = 'true'
      // clear existing errors
      // this.$store.dispatch(LOGOUT);
      
      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signin_submit"];
      submitButton.textContent = 'Cargando...'
      
      // dummy delay
      setTimeout(() => {
        // send login request
        console.log('seed')
        this.$store
          .dispatch(LOGIN, { email, password, remember })
          .then((data) => {
           
           // go to which page after successfully login
          })
          .catch((e) => {
            console.log('dañao')
            this.kt_login_signin_submit_disabled = false;
            submitButton.textContent = 'Ingresar';
          });
      }, 500);
   
    }).on("core.form.invalid", () => {
      this.$refs["kt_login_signin_submit"].disabled = true
    }).on("core.field.valid", () => {
      this.$refs["kt_login_signin_submit"].disabled = false
    }).on("core.field.invalid", () => {
      this.$refs["kt_login_signin_submit"].disabled = true
    });
  },
  methods: {
    viewPassword(){
      const input = document.querySelector('.input-goup');
      const formPasswordToggleIcon = input.querySelector('i')
      const formPasswordToggleInput = input.querySelector('input')
      if (formPasswordToggleInput.getAttribute('type') == 'text') {
        formPasswordToggleInput.setAttribute('type', 'password')
        formPasswordToggleIcon.classList.replace('ti-eye', 'ti-eye-off')
        return;
      }

      formPasswordToggleInput.setAttribute('type', 'text')
      formPasswordToggleIcon.classList.replace('ti-eye-off', 'ti-eye')
      
    },
    login(){
      
    }
  }
};
</script>
