<template>
  <div class="container">
    <div class="row vh-100" >
      <div class="col-12 col-md-7 col-lg-5  offset-lg-7 offset-0 ">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body py-5 px-4 p-sm-5 row align-items-center">
            <div class="text-center d-flex justify-content-center">
              <div class="card-title d-flex ">
                <div class="form-title__part1">Cooking</div> <div class="form-title__part2">5G</div>
                
              </div>
            </div>
            <div :class="'alert alert-dismissible fade show ' + alertType" v-if="alertShow" role="alert">
              <strong>{{ alertMessage }}</strong> 
            </div>
            <form class="mb-3 form w-100" novalidate="novalidate" id="kt_login_signin_form">
              <div class="login-form-content" id="login-form-content">
                <div class="form-floating mb-3">
                  <input type="text" ref="user" class="form-control" id="floatingInput" name="email"  placeholder="name@example.com">
                  <label for="floatingInput">Correo electronico </label>
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
<style >
</style>

<script>
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
// import 'bootstrap/scss/bootstrap'
// FormValidation plugins
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import { LOGOUT, LOGIN } from "@/core/services/store/auth.module";


export default {
  data() {
    return {
      state: "signin",
      fv: "",
      alertShow:false,
      alertMessage:'',
      alertType:''
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
      // clear existing errors
      this.$store.dispatch(LOGOUT);
      
      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signin_submit"];
      submitButton.textContent = 'Cargando...'
      
      // dummy delay
        this.$store
        .dispatch(LOGIN, { email, password })
        .then((data) => {
          if(data.code === 500 ){
            submitButton.textContent = 'Ingresar';
            submitButton.blur();
            this.showAlert('alert-danger',data.messagge)
            return
          }
          this.showAlert('alert-success','Acceso Exitoso')
          submitButton.textContent = 'Acceso Exitoso'
          setTimeout(() => {
            
            this.$router.push('/dashboard') 
          }, 1000);
        })
        .catch((e) => {

          // console.log(e)
        });
   
    }).on("core.form.invalid", () => {
      this.$refs["kt_login_signin_submit"].disabled = true
      this.alertShow = false;
    }).on("core.field.valid", () => {
      this.$refs["kt_login_signin_submit"].disabled = false
      this.alertShow = false;
    }).on("core.field.invalid", () => {
      this.alertShow = false;
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
    showAlert(type, messagge){
      this.alertShow = true;
      this.alertType = type
      this.alertMessage = messagge
    }
  }
};
</script>
