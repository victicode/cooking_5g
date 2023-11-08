<template>
  <div  class="authentication-wrapper authentication-cover authentication-bg"  :style="{ backgroundImage: `url(${backgroundImage.background})` }">
    <div id="kt_login" class="login login-1  authentication-inner d-flex align-items-center" :class="{'login-signin-on': this.state == 'signin', 'login-signup-on': this.state == 'signup', 'login-forgot-on': this.state == 'forgot'}">
      <!-- /Left Text -->
      <div class="d-none d-lg-flex col-lg-7 p-0">
        <div id="leftSide" class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center" :style="{ backgroundImage: `url(${backgroundImage.leftSide})` }">
          <img
            src="media/bg-shape-image-light.png"
            alt="auth-login-cover"
            class="platform-bg"
            data-app-light-img="illustrations/bg-shape-image-light.png"
            data-app-dark-img="illustrations/bg-shape-image-dark.png"
          />
        </div>
      </div>
      <!-- /Left Text -->

      <!-- Forms Side -->
      <div class="col-12 col-lg-5 align-items-center p-sm-0 p-4 ">
        <div class="w-px-400 mx-auto  ">
            <!-- Logo -->
          <div class="app-brand mb-4">
            <a href="#" class="app-brand-link gap-2">
            
              <img src="media/logos/logo-dls.png">
              
            </a>
          </div>
            <!-- /Logo -->
          <!-- Login -->
          <div class="login-form login-signin">
            <p class="mb-4">Si ya tienes una cuenta, solo ingresa.<br> ¡Te estabamos esperando!
            </p>
            <div class="">
              <form class="mb-3 form" novalidate="novalidate" id="kt_login_signin_form">
                <div v-if="errors.length > 0" role="alert" v-bind:class="{ show: errors.length }" class="alert fade alert-danger">
                  <div class="alert-text" v-for="(error, i) in errors" :key="i">
                    {{ error }}
                  </div>
                </div>
                <div class="mb-3 form-group">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <input
                    type="text"
                    class="form-control"
                    autocomplete="off"
                    placeholder="Ingresa tu correo electrónico"
                    autofocus
                    name="email" 
                    ref="email" 
                    v-model="form.email"
                  />
                </div>
                <div class="mb-3 form-password-toggle form-group">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Contraseña</label>
                    <a id="kt_login_forgot" class="forgot" @click="showForm('forgot')">
                      <small>¿Olvidó su contraseña?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      class="form-control"
                      name="password"
                      placeholder="**********"
                      ref="password" 
                      v-model="form.password" 
                      autocomplete="off" 
                    />
                    <span class="input-group-text cursor-pointer" @click="showPasswordToggle($event)"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" v-model="form.remember" :selected="{true:form.remember==true}" value=1 />
                    <label class="form-check-label" for="remember-me" > Recordar mis datos </label>
                  </div>
                </div>
                <button ref="kt_login_signin_submit"  :disabled="kt_login_signin_submit_disabled" class="btn btn-primary d-grid w-100">Ingresar</button>
              </form>
            </div>
            <p class="text-center">
              <span>¿Aún no tienes cuenta?</span>
              <a id="kt_login_signup" @click="showForm('signup')" class="text-primary font-weight-bolder cursor-pointer">
                <span>Crea una aquí</span>
              </a>
            </p>
          </div>
          <!-- /Login -->
          <!-- Register -->
          <div class="login-form login-signup">
            <p class="mb-3">Solo regístrate y descubre la manera más rápida de cambiar tus soles y dólares en un clic</p>

            <form class="form mb-3" novalidate="novalidate" id="kt_login_signup_form">
              <div role="alert" v-if="registerData.message" v-bind:class="{show: registerData.show && registerData.message, 'alert-success': registerData.status == 'ok', 'alert-danger': registerData.status == 'error'}" class="alert fade">
                <div class="alert-text">
                  {{ registerData.message }}
                </div>
              </div>
              <div role="alert" v-if="Object.values(registerData.errors).length" v-bind:class="{show: registerData.show && Object.values(registerData.errors).length}" class="alert fade alert-danger">
                <div class="alert-text" v-for="(error, i) in Object.values(registerData.errors)" :key="i">
                  {{ error[0] }}
                </div>
              </div>
              <div class="mb-3 form-group ">
                <label for="username" class="form-label">Usuario</label>
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  placeholder="Ingrese un usuario"
                  autofocus
                  name="username" 
                  ref="username" 
                  autocomplete="off"
                />
              </div>
              <div class="mb-3 form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="Ingresar correo electrónico" ref="remail" autocomplete="off"/>
              </div>
              <div class="mb-3 form-group form-password-toggle">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    class="form-control"
                    placeholder="********"
                    name="password" 
                    ref="rpassword" 
                    autocomplete="off"
                  />
                  <span class="input-group-text cursor-pointer" @click="showPasswordToggle($event)"><i class="ti ti-eye-off"></i></span>
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
                  <span class="input-group-text cursor-pointer" @click="showPasswordToggle($event)"><i class="ti ti-eye-off"></i></span>
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox"  name="terms" />
                  <label class="form-check-label" for="terms-conditions">
                  <small>Autorizo recibir noticias y promociones de parte de DLS Money.</small> 
                      
                  </label>
                </div>

                <div class="form-check form-group">
                  <input class="form-check-input" type="checkbox"  name="agree"  ref="agree"/>
                  <label class="form-check-label" for="agree">
                  <small> Declaro que he leído y acepto sus
                    <a href="javascript:void(0);">políticas de privacidad</a> y sus  <a href="javascript:void(0);">Términos y condiciones</a>
                  </small> 
                  </label>
                </div>
              </div>
              <button :disabled="kt_login_signup_submit_disabled" ref="kt_login_signup_submit"  class="btn btn-primary d-grid w-100">Crear cuenta</button>
            </form>

            <p class="text-center">
              <span>¿Ya tienes una cuenta?</span>
              <a id="kt_login_signup_cancel" @click="showForm('signin')" class="text-primary font-weight-bolder cursor-pointer">
                <span>Inicia sesión</span>
              </a>
            </p>
          </div>
          <!-- /Register -->
          <!-- Forgot -->
          <div class="login-form login-forgot">
            <div class="row" v-if="linkToResetData.show">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-alert :show="linkToResetData.show" dismissible fade :variant="linkToResetData.label" @dismissed="linkToResetData.show=false">{{ linkToResetData.message }}</b-alert>
                
              </div>
              <hr>
            </div>
            <h3 class="mb-1 fw-bold">¿Olvidaste tu contraseña? 🔒</h3>
            <p class="mb-4"> Ingresa tu correo y te enviaremos un link para que puedas crear una nueva contraseña.</p>
            <form  class="mb-3" novalidate="novalidate" id="kt_login_forgot_form">
              <div class="mb-3 form-group">
                <label for="email" class="form-label">Email</label>
                <input
                  type="text"
                  class="form-control"
                  id="email"
                  ref="femail"
                  name="email"
                  placeholder="Ingresa tu correo electrónico aquí"
                  autofocus
                />
              </div>
              <button class="btn btn-primary d-grid w-100" ref="kt_login_forgot_submit" :disabled=kt_login_forgot_submit_disabled >Enviar</button>
            </form>
            <div class="text-center">
              <a href="javascript:;" @click="showForm('signin')" class="d-flex align-items-center justify-content-center " >
                <i class="ti ti-chevron-left scaleX-n1-rtl" style="margin-top: -8px;" ></i>
                Volver al inicio
              </a>
            </div>
          </div>
          <!-- /Forgot -->
          <div :class="{'d-none': this.state == 'forgot'}">

            <div class="divider my-4">
              <div class="divider-text">O ingresa con tu cuenta de</div>
            </div>
  
            <div class="d-flex justify-content-center">
              <a href="javascript:;" @click="googleRegister('facebook')"  class="btn btn-icon btn-label-facebook me-3">
                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
              </a>
  
              <a href="javascript:;" @click="googleRegister('google')" target="_parent" class="btn btn-icon btn-label-google-plus me-3">
                <i class="tf-icons fa-brands fa-google fs-5"></i>
              </a>
          </div>

             
          </div>
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
import { LOGIN, LOGOUT, REGISTER, RESET_PASSWORD_EMAIL, REDIRECT_REGISTER_GOOGLE } from "@/core/services/store/auth.module";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      state: "signin",
      formSubmit:false,
      linkToResetData: {
        show: false,
        status: null,
        message: null,
        label: null
      },
      kt_login_signup_submit_disabled: true,
      kt_login_signin_submit_disabled: true,
      kt_login_forgot_submit_disabled: true,
      registerData: {
        show: false,
        status: null,
        message: null,
        errors: {}
      },
      // Remove this dummy login info
      form: {
        email: "",
        password: "",
        remember:0
      }
    };
  },
  computed: {
    ...mapState({
      errors: state => state.auth.errors
    }),
    ...mapGetters(["currentUser"]),

    backgroundImage() {
      return (
        {
          leftSide: process.env.BASE_URL + "media/illustrations/registrong.png",
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
    const signin_form = KTUtil.getById("kt_login_signin_form");
    const signup_form = KTUtil.getById("kt_login_signup_form");
    const forgot_form = KTUtil.getById("kt_login_forgot_form");
    if (localStorage.remember == 'true' || localStorage.remember == true) {
      this.form.email = localStorage.emailRemember;
      this.form.password = localStorage.passwordRemember;
      this.form.remember = localStorage.remember;
      this.kt_login_signin_submit_disabled = false;
    }
    this.fv = formValidation(signin_form, {
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
        bootstrap: new Bootstrap()
      }
    });

    this.fv1 = formValidation(signup_form, {
      fields: {
        username: {
          validators: {
            notEmpty: {
              message: "El usuario es requerido"
            },
            regexp: {
              regexp: /^[A-Za-z0-9.#-+*/@$_]+$/i,
              message: 'El nombre de usuario no debe contener espacios ni los siguientes caracteres: "[]{}!¡¿?=()&|',
            },
          }
        },
        email: {
          validators: {
            notEmpty: {
              message: "El email es requerido"
            },
            emailAddress: {
              message: "El valor ingresado no es un email válido"
            }
          }
        },
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
                return signup_form.querySelector('[name="password"]').value;
              },
              message: "La contraseña y su confirmacion no coinciden"
            }
          }
        },
        agree: {
          validators: {
            notEmpty: {
              message: "Debes aceptar los términos y condiciones"
            }
          }
        }
      },
      plugins: {
        trigger: new Trigger(),
        submitButton: new SubmitButton(),
        bootstrap: new Bootstrap()
      }
    });

    this.fv2 = formValidation(forgot_form, {
      fields: {
        email: {
          validators: {
            notEmpty: {
              message: "El email es requerido"
            },
            emailAddress: {
              message: "El valor ingresado no es un email válido"
            }
          }
        }
      },
      plugins: {
        trigger: new Trigger(),
        submitButton: new SubmitButton(),
        bootstrap: new Bootstrap()
      }
    });

    this.fv.on("core.form.valid", () => {
      const email = this.form.email;
      const password = this.form.password;
      const remember = this.form.remember;

      // clear existing errors
      this.$store.dispatch(LOGOUT);
      
      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signin_submit"];
      this.kt_login_signin_submit_disabled = true;
      submitButton.textContent = 'Cargando...'
      
      // dummy delay
      setTimeout(() => {
        // send login request
        this.$store
          .dispatch(LOGIN, { email, password, remember })
          .then(() => {
            this.kt_login_signin_submit_disabled = true;
            submitButton.textContent = 'Acceso Exitoso'
            this.$router.push('/dashboard')  // go to which page after successfully login
          })
          .catch((e) => {
            this.kt_login_signin_submit_disabled = false;
            submitButton.textContent = 'Ingresar';
          });
      }, 500);
   
    }).on("core.form.invalid", () => {
      Swal.fire({
        title: "",
        text: "Por favor, proporcione los datos correctos.",
        icon: "error",
        confirmButtonClass: "btn btn-secondary",
        heightAuto: false
      });
    }).on("core.field.valid", () => {
      this.kt_login_signin_submit_disabled = false;
    }).on("core.field.invalid", () => {
      this.kt_login_signin_submit_disabled = true;
    });
     

    this.fv1.on("core.field.invalid", () => {
      this.kt_login_signup_submit_disabled = true;
    }).on("core.field.valid", () => {
      this.kt_login_signup_submit_disabled = false;
      
    }).on("core.form.valid", () => {
      const username = this.$refs.username.value;
      const email = this.$refs.remail.value;
      const password = this.$refs.rpassword.value;
      const password_confirmation = this.$refs.cpassword.value;

      this.kt_login_signup_submit_disabled = true;
      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signup_submit"];
      submitButton.textContent = 'Cargando...'

      this.registerData = {
        show: false,
        status: null,
        message: null,
        errors: {}
      };

      // clear existing errors
      this.$store.dispatch(LOGOUT);

      // dummy delay
      setTimeout(() => {
        // send register request
        this.$store
          .dispatch(REGISTER, {
            username: username,
            email: email,
            password: password,
            password_confirmation: password_confirmation
          })
          .then((data) => {
            this.registerData.show = true;
            this.registerData.status = data.status;
            if(data.errors){
              
              this.kt_login_signup_submit_disabled = false;
              submitButton.textContent = 'Crear Cuenta';
              this.registerData.errors = data.errors;
            }
            else{
              this.registerData.message = data.message;
              submitButton.textContent = 'Crear Cuenta';
            }

            if(this.registerData.status === 'ok'){
              this.kt_login_signup_submit_disabled = true;
              submitButton.textContent = 'Cuenta creada con exito! Serás redirigido'
              setTimeout(() => {
                  this.$router.push({ name: "dashboard" })
              }, 1500);
            }
          });
      }, 500);
    }).on("core.form.invalid", () => {
      this.kt_login_signup_submit_disabled = true;
      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signup_submit"];
      submitButton.textContent = 'Cargando...'
      
      Swal.fire({
        title: "",
        text: "Por favor, proporcione los datos correctos y asegurate de aceptar los terminos y condiciones",
        icon: "error",
        confirmButtonClass: "btn btn-secondary",
        heightAuto: false
      });
      this.kt_login_signup_submit_disabled = false;
      submitButton.textContent = 'Crear Cuenta'
    });

    this.fv2.on("core.form.valid", () => {
      const email = this.$refs.femail.value;
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

      // send login request
      this.$store
        .dispatch(RESET_PASSWORD_EMAIL, { email })
        // go to which page after successfully login
        .then((data) => {
          console.log(data)
          this.linkToResetData.show = true;
          this.linkToResetData.status = data.status;
          this.linkToResetData.message = data.message;
          this.linkToResetData.label = data.status == 'ok' ? 'success' : 'danger'
          this.kt_login_signup_submit_disabled = data.status == 'ok' ? true : false;
          submitButton.textContent = data.status == 'ok' ? 'Revisa tu correo!' : 'Enviar'
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
    showForm(form) {
      this.state = form;
      var form_name = "kt_login_" + form + "_form";
      KTUtil.animateClass(
        KTUtil.getById(form_name),
        "animate__animated animate__bounceInRight"
      );
      KTUtil.animateClass(
        KTUtil.getById('leftSide'),
        "animate__animated animate__bounceInLeft"
      );
      this.backgroundImage.leftSide = form == 'signin' ? 
        process.env.BASE_URL + "media/illustrations/registrong.png" : 
        form == 'signup' ? 
        process.env.BASE_URL + "media/backgrounds/fondo-registro01.jpg" : 
        process.env.BASE_URL + "media/backgrounds/fondo-recuperarpass.jpg"
    },
    showPasswordToggle(event) {
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
    googleRegister(provider){
      this.$store
        .dispatch(REDIRECT_REGISTER_GOOGLE, provider)
        .then((data) => {
          this.openWindow(data, 'message')
        })
        .catch((e) => {
          console.log('fallo')
        });
    },
    openWindow (url, title, options = {}) {
      if (typeof url === 'object') {
        options = url
        url = ''
      }

      options = { url, title, width: 600, height: 720, ...options }

      const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screen.left
      const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screen.top
      const width = window.innerWidth || document.documentElement.clientWidth || window.screen.width
      const height = window.innerHeight || document.documentElement.clientHeight || window.screen.height

      options.left = ((width / 2) - (options.width / 2)) + dualScreenLeft
      options.top = ((height / 2) - (options.height / 2)) + dualScreenTop

      const optionsStr = Object.keys(options).reduce((acc, key) => {
        acc.push(`${key}=${options[key]}`)
        return acc
      }, []).join(',')

      const newWindow = window.open(url, title, optionsStr)

      if (window.focus) {
        newWindow.focus()
      }

      return newWindow
    }

  }
};
</script>
