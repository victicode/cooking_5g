<template>
 <div class="modal fade modal-xl" id="createdUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row"> 
    <div class="modal-content p-3  p-md-3"> 
      <div class="modal-body py-md-0 px-0">
        <button type="button" class="btn-close close-2" @click="hiddeModal()"></button>
        <div class="row" v-if="dangerAlert">
          <div class="col-lg-12 col-md-12 col-sm-12">
              <b-alert :show="dangerAlert" dismissible fade variant="danger" @dismissed="dangerAlert=false">{{ dangerAlertMessage }}</b-alert>
          </div>
          <hr>
        </div>
        <!-- Create Deal Wizard -->
        <div id="wizard-create-deal" class="bs-stepper shadow-none  vertical mt-2">
            <div class="bs-stepper-header">
              <div class="stepx" >
                  <!-- Logo -->
                  <div class="app-brand mb-4">
                  <a href="index.html" class="app-brand-link gap-2">
                  <img :src="logo"> </a>
                  </div>
                  <!-- /Logo -->
                  <!-- <button type="button" class="step-trigger">
                  <span class="bs-stepper-circle"><i class=" align-baseline ti ti-users ti-sm"></i></span>
                  <span class="bs-stepper-label">
                      <span class="bs-stepper-title">Tipo de cuenta</span>
                      <span class="bs-stepper-subtitle">Elije que tipo de cuenta eres</span>
                  </span>
                  </button> -->
              </div>
              <div class="line"></div>
              <div class="step" data-target="#deal-details">
                  <button type="button" class="step-trigger">
                  <span class="bs-stepper-circle"><i class=" align-baseline ti ti-id ti-sm"></i></span>
                  <span class="bs-stepper-label">
                      <span class="bs-stepper-title">Datos</span>
                      <span class="bs-stepper-subtitle" data-change="Empresa">Personales</span>
                  </span>
                  </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#deal-usage">
                  <button type="button" class="step-trigger">
                  <span class="bs-stepper-circle"><i class=" align-baseline ti ti-credit-card ti-sm"></i></span>
                  <span class="bs-stepper-label">
                      <span class="bs-stepper-title" data-change="Legal">Domicilio</span>
                      <span class="bs-stepper-subtitle">Dirección</span>
                  </span>
                  </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#review-complete">
                  <button type="button" class="step-trigger">
                  <span class="bs-stepper-circle"><i class=" align-baseline ti ti-checkbox ti-sm"></i></span>
                  <span class="bs-stepper-label">
                      <span class="bs-stepper-title" data-change="Contacto">Ocupación</span>
                      <span class="bs-stepper-subtitle">A que se dedica</span>
                  </span>
                  </button>
              </div>
            </div>
            <div class="bs-stepper-content p-3">
              <form id="wizard-create-deal-form" onSubmit="return false">
                <!-- Deal Type -->
                <div id="deal-type" class="content d-block visible">
                  <div class="row g-3" id="user-manual">
                    <div class="col-sm-12 text-center" ><h4>Datos del usuario</h4></div>
                    <div class="col-sm-6 valid-item">
                    
                      <label class="form-label" for="basic-icon-default-fullname" >Nombre de usuario</label>
                      <input
                        type="text"
                        class="form-control"
                        name="username"  
                        placeholder="Pablo.01"
                        v-model="userData.username"
                        @input="checkInputText($event)"
                        @change="validateUserExist()"
                      />
                    </div>
                    <div class="col-sm-6 valid-item">
                    
                      <label class="form-label" for="basic-icon-default-fullname" >Correo electrónico</label>
                      <input
                        type="text"
                        class="form-control"
                        name="email"  
                        placeholder="user@email.com"
                        v-model="userData.email"
                        @change="validateUserExist()"
                      />
                    </div>
                    <div class="col-sm-6 ">
                      <label class="form-label" for="basic-icon-default-fullname" >Contraseña</label>
                      <div class="input-group valid-item">
                        <input
                          type="text"
                          class="form-control input-coupon border-end-0"
                          name="password"  
                          placeholder="**********"
                          v-model="userData.password"
                          @input="checkInputText($event)"
                        />
                        <div class="input-group-append">
                              <div class="btn btn-coupon border-start-0 rounded-end" ref="setcoupon"  onSubmit="false" onsubmit="false" @click="GeneratePassword()">Generar</div>
                            </div>
                        </div>
                      </div>
                    <div class="col-12 pb-2">
                      <div class="row h-150">
                        <div class="col-md mb-md-0 mb-2" id="personal">
                          <div class="form-check custom-option custom-option-icon" :class="{ 'checked': TypeofAccount == 0 }">
                            <label class="form-check-label custom-option-content" for="customRadioPercentage">
                              <span class="custom-option-body">

                                  <i class="fa-regular fa-user"></i>
                                <span class="custom-option-title">Registrarme como persona natural</span>
                                <small>Para uso personal</small>
                              </span>
                              <input
                                name="typeAccount"
                                class="form-check-input"
                                type="radio"
                                value= 0
                                id="customRadioPercentage"
                                checked
                                v-model="TypeofAccount"
                                @input="stepperChange()"
                              />
                            </label>
                          </div>
                        </div>
                        <div class="col-md mb-md-0 mb-2" id="company">
                          <div class="form-check custom-option custom-option-icon" :class="{ 'checked': TypeofAccount == 1 }">
                            <label class="form-check-label custom-option-content" for="customRadioFlat">
                              <span class="custom-option-body">
          
                              <i class="fa-solid fa-building-user"></i>

                                <span class="custom-option-title"> Registrarme como Empresa </span>
                                <small>Soy representante de una empresa</small>
                              </span>
                              <input
                                name="typeAccount"
                                class="form-check-input"
                                type="radio"
                                value= 1
                                id="customRadioFlat"
                                v-model="TypeofAccount"
                                @input="stepperChange()"
                              />
                            </label>
                          </div>
                        </div>

                      </div>
                    </div>
                  
                  
                    <div class="col-12 d-flex justify-content-end mt-4">
                      <button class="btn btn-primary btn-nexxt" id="step1" disabled @click="wizardCreated()">
                        <span class="align-middle d-sm-inline-block d-none me-sm-1" >Siguiente</span>
                        <i class="ti ti-arrow-right ti-xs"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <Personal v-if="TypeofAccount == 0 " :manual="'si'" :dataUser="userData" @hiddeModalCreate="hideModal"></Personal>
                <Company v-else-if="TypeofAccount == 1 " :manual="'si'" :dataUser="userData" @hiddeModalCreate="hideModal" ></Company>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<style lang="scss">
    @import "@/assets/sass/pages/page-auth.scss";
    @import "@/assets/libs/bs-stepper/bs-stepper.scss";
</style>

<script>
import { mapGetters } from "vuex";
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
import Personal from "@/view/pages/create_account/Personal.vue";
import Company from "@/view/pages/create_account/Company.vue";
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import AutoFocus from "@/assets/plugins/formvalidation/dist/es6/plugins/AutoFocus";
import Stepper from 'bs-stepper';
import { VALIDATE_USER_EXIST } from "@/core/services/store/auth.module";


export default {
  name:'CreatedUser',
  data() {
    return {
      
      TypeofAccount: 0,
      dangerAlert: false,
      dangerAlertMessage: "",
      wizardCreateDeal: "",
      wizardCreateDealForm:"",
      wizardCreateDealFormStep1:"",
      wizardCreateDealFormStep2: "",
      wizardCreateDealFormStep3:"",
      wizardCreateDealFormStep4:"",
      wizardCreateDealNext:"",
      wizardCreateDealPrev:"",
      userData:{
        email:'',
        username:'',
        password:''
      }
        
    }
  },
  components: {
    Personal: Personal,
    Company: Company
  },
  beforeMount() {

  },
  mounted() {
    this.validateUser()
  },
  methods: {
    checkInputText(e) {
      let input = e.srcElement;
      // let valt = input.value.match(/[0-9\^*@!"#$%&/()=?¡!¿'\\]/gi)
      // console.log(valt)
      // if (valt != null) {
      //   this.showDangerAlert('Estas intentando introducir un caracter invalido!');
      //   input.value = input.value.replace(/[^a-zA-Z ]/, '');
      // } 
      input.value = input.value.replace(/[^a-zA-Z ]/, '');
    }, 
    hiddeModal(){
      this.$emit("click");
    },
    stepperChange() {
      setTimeout(() => {
        document.querySelector("[data-change='Legal']").textContent = this.TypeofAccount == 0 ?
          'Domicilio' :
          'Legal';
        document.querySelector("[data-change='Legal']").nextSibling.nextSibling.textContent = this.TypeofAccount == 0 ?
          'Dirección' :
          'Representante Legal';
        document.querySelector("[data-change='Empresa']").textContent = this.TypeofAccount == 0 ?
          'Personales' :
          'Empresa';
        document.querySelector("[data-change='Contacto']").textContent = this.TypeofAccount == 0 ?
          'Ocupacíon' :
          'Contacto';
        document.querySelector("[data-change='Contacto']").nextSibling.nextSibling.textContent = this.TypeofAccount == 0 ?
          'A que se dedica' :
          'Datos de contacto';
        
      },20)
        
    },
      
    showDangerAlert(message) {
      this.dangerAlertMessage = message;
      this.dangerAlert = true;
    },
    hideDangerAlert() {
      this.dangerAlertMessage = '';
      this.dangerAlert = false;
    },
    wizardCreated() {
      if(!this.validateUserExist()){
        let elSel = this.TypeofAccount == 1 ? document.getElementById('company') : document.getElementById('personal')
        let noSel = this.TypeofAccount == 1 ? document.getElementById('personal') : document.getElementById('company')
        elSel.classList.add('selected-check')
        noSel.classList.add('not-selected-check')

        // Wizard form
        setTimeout(() => {
          document.getElementById('deal-type').classList.remove("d-block", "visibility",)  
          this.wizardCreateDeal = document.querySelector('#wizard-create-deal');
          this.wizardCreateDealForm = this.wizardCreateDeal.querySelector('#wizard-create-deal-form');
          // Wizard steps

          this.wizardCreateDealFormStep2 = this.wizardCreateDealForm.querySelector('#deal-details');
          this.wizardCreateDealFormStep3 = this.wizardCreateDealForm.querySelector('#deal-usage');
          this.wizardCreateDealFormStep4 = this.wizardCreateDealForm.querySelector('#review-complete');
          // Wizard next prev button
          this.wizardCreateDealNext = [].slice.call(this.wizardCreateDealForm.querySelectorAll('.btn-next'));
          this.wizardCreateDealPrev = [].slice.call(this.wizardCreateDealForm.querySelectorAll('.btn-prev'));
          let validationStepper = new Stepper(this.wizardCreateDeal, {
            linear: true,
            animation: true
          });
          if (typeof this.wizardCreateDeal !== undefined && this.wizardCreateDeal !== null) {
            // Deal Type
            this.validationByForm(validationStepper, this.TypeofAccount)
          
          }
        }, 1500);
      }
      
        
      
    },
    validateUser() {
      this.wizardCreateDealFormStep1 = document.querySelector('#user-manual');
      const FormValidation2 = formValidation(this.wizardCreateDealFormStep1, {
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
      },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.valid-item'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step1').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step1').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step1').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step1').disabled = true
        });
    },
    GeneratePassword() {
      let result = "";
      var i = 0;
      const abc = "a b c d e f g h i j k l m n o p q r s t u v w x y z A B C D E F G H I J K L M N O P Q R S T U V W X Y Z 1 2 3 4 5 6 7 8 9 0 . / + * - ! @ $ #".split(" "); // Espacios para convertir cara letra a un elemento de un array
      for(i;i<=8;i++) {
        const random = Math.floor(Math.random() * abc.length);
        result += abc[random]
      }
      this.userData.password = result;
    },
    validationByForm(stepper, form) {
      if (form == 0) {
        const FormValidation2 = formValidation(this.wizardCreateDealFormStep2, {
          fields: {
            // * Validate the fields here based on your requirements
            document_type_id: {
              validators: {
                notEmpty: {
                  message: 'El Tipo de documento debe ser seleccionado'
                },
              }
            },
            document_number: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            document_front: {
              validators: {
                notEmpty: {
                  message: ' Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,PNG,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            document_back: {
              validators: {
                notEmpty: {
                  message: 'Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            name: {
              validators: {
                notEmpty: {
                  message: 'El nombre no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            sname: {
              validators: {
                notEmpty: {
                  message: 'El segundo nombre no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            surname: {
              validators: {
                notEmpty: {
                  message: 'El apellido no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            ssurname: {
              validators: {
                notEmpty: {
                  message: 'El segundo apellido no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            birthdate: {
              validators: {
                notEmpty: {
                  message: 'La fecha de naciemiento no puede quedar vacio'
                },
                date: {
                  format: 'YYYY-MM-DD',
                  message: 'Fecha no valida',
                },
              }
            },
            phone: {
              validators: {
                notEmpty: {
                  message: 'El Teléfono no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            cellphone1: {
              validators: {
                notEmpty: {
                  message: 'El Numero de celular no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            cellphone2: {
              validators: {
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },

          },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.valid-item'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step2').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step2').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step2').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step2').disabled = true
        });


        // Deal Usage
        const FormValidation3 = formValidation(this.wizardCreateDealFormStep3, {
          fields: {
            department_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Departamento'
                },
              }
            },
            province_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar una Provincia'
                },
              }
            },
            district_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Distrito'
                },
              }
            },
            address: {
              validators: {
                notEmpty: {
                  message: 'Debes colocar tu dirección'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ .#,/°]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                },
              }
            },
          },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.input-s'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step3').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step3').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step3').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step3').disabled = true
        });

        // Deal Usage
        const FormValidation4 = formValidation(this.wizardCreateDealFormStep4, {
          fields: {
            ocupation_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar tu ocupación'
                },
              }
            },
            terms: {
              validators: {
                notEmpty: {
                  message: 'Debes aceptar los términos y condiciones'
                },
              }
            }
          },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.input-s'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step4').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step4').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step4').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step4').disabled = true
        });
        this.wizardCreateDealNext.forEach(item => {
          item.addEventListener('click', event => {
            // When click the Next button, we will validate the current step
            switch (stepper._currentIndex) {
              case 0:
                FormValidation2.validate();
                break;

              case 1:
                FormValidation3.validate();
                break;

              case 2:
                FormValidation4.validate();
                break;

              default:
                break;
            }
          });
        });

        this.wizardCreateDealPrev.forEach(item => {
          item.addEventListener('click', event => {
            switch (stepper._currentIndex) {
              case 3:
                stepper.previous();
                break;

              case 2:
                stepper.previous();
                break;

              case 1:
                stepper.previous();
                break;

              case 0:

              default:
                break;
            }
          });
        });
      } else {
        const FormValidation2 = formValidation(this.wizardCreateDealFormStep2, {
          fields: {
            // * Validate the fields here based on your requirements
            document_number: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            ruc_card: {
              validators: {
                notEmpty: {
                  message: 'Tienes que subir una foto o pdf'
                },
                file: {
                  extension: 'jpeg,jpg,png,pdf',
                  type: 'image/jpeg,image/png,application/pdf',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png, .pdf',
                }
              }
            },
            business_name: {
              validators: {
                notEmpty: {
                  message: 'La razón social no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            business_turn: {
              validators: {
                notEmpty: {
                  message: 'El giro del negocio no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ& .]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            department_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Departamento'
                },
              }
            },
            province_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar una Provincia'
                },
              }
            },
            district_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Distrito'
                },
              }
            },
            fiscal_address: {
              validators: {
                notEmpty: {
                  message: 'Debes colocar tu dirección'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ .#,/°]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                },
              }
            },
            phone: {
              validators: {
                notEmpty: {
                  message: 'El Teléfono no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            email: {
              validators: {
                emailAddress: {
                  message: 'Debes colocar una dirección de correo valida',
                },
                notEmpty: {
                  message: 'Debes colocar tu dirección'
                },
              }
            },

          },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.valid-item'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step2').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step2').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step2').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step2').disabled = true
        });


        // Deal Usage
        const FormValidation3 = formValidation(this.wizardCreateDealFormStep3, {
          fields: {
            document_type_lr_id: {
              validators: {
                notEmpty: {
                  message: 'El Tipo de documento debe ser seleccionado'
                },
              }
            },
            document_number_lr: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            document_front_lr: {
              validators: {
                notEmpty: {
                  message: ' Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,PNG,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            document_back_lr: {
              validators: {
                notEmpty: {
                  message: 'Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            name_lr: {
              validators: {
                notEmpty: {
                  message: 'El nombre del representante legal no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            sname_lr: {
              validators: {
                notEmpty: {
                  message: 'El segundo nombre del representante legal no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            surname_lr: {
              validators: {
                notEmpty: {
                  message: 'El apellido de representantel legal no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            ssurname_lr: {
              validators: {
                notEmpty: {
                  message: 'El segundo apellido del representante legal no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            ocupation_lr_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar tu ocupación'
                },
              }
            },
            
          },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.valid-item'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step3').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step3').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step3').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step3').disabled = true
        });

        // Deal Usage
        const FormValidation4 = formValidation(this.wizardCreateDealFormStep4, {
          fields: {
            document_type_c_id: {
              validators: {
                notEmpty: {
                  message: 'El Tipo de documento debe ser seleccionado'
                },
              }
            },
            document_number_c: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            document_front_c: {
              validators: {
                notEmpty: {
                  message: ' Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,PNG,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            document_back_c: {
              validators: {
                notEmpty: {
                  message: 'Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            name_c: {
              validators: {
                notEmpty: {
                  message: 'El nombre del contacto no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            sname_c: {
              validators: {
                notEmpty: {
                  message: 'El segundo nombre del contacto no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            surname_c: {
              validators: {
                notEmpty: {
                  message: 'El apellido del contacto no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            ssurname_c: {
              validators: {
                notEmpty: {
                  message: 'El segundo apellido del contacto no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            phone_c:{
              validators: {
                notEmpty: {
                  message: 'El Teléfono no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            terms: {
              validators: {
                notEmpty: {
                  message: 'Debes aceptar los términos y condiciones'
                },
              }
            }
          },
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              rowSelector: '.valid-item'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('step4').disabled = false
          stepper.next();
        }).on('core.field.valid', () => {
          document.getElementById('step4').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('step4').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('step4').disabled = true
        });

        this.wizardCreateDealNext.forEach(item => {
          item.addEventListener('click', event => {
            // When click the Next button, we will validate the current step
            switch (stepper._currentIndex) {
              case 0:
                FormValidation2.validate();
                break;

              case 1:
                FormValidation3.validate();
                break;

              case 2:
                FormValidation4.validate();
                break;

              default:
                break;
            }
          });
        });

        this.wizardCreateDealPrev.forEach(item => {
          item.addEventListener('click', event => {
            switch (stepper._currentIndex) {
              case 3:
                stepper.previous();
                break;

              case 2:
                stepper.previous();
                break;

              case 1:
                stepper.previous();
                break;

              case 0:

              default:
                break;
            }
          });
        });
      }
    },
    hideModal() {
      this.$emit("click");
    },
    validateUserExist(){
      let query = `?email=${this.userData.email}&username=${this.userData.username}&`
      
      this.$store
          .dispatch(VALIDATE_USER_EXIST, query)
          .then((data) => {
              if (data.data.status) {
                this.showDangerAlert(data.data.message)
                document.getElementById('step1').disabled = true
              }else{
                this.hideDangerAlert()
                document.getElementById('step1').disabled = false
              }
              return data.data.status
          })
          .catch((err) => {
            console.log(err)
          });
    }
  },
  computed: {
      ...mapGetters([
          "layoutConfig"
      ]),
      /**
       * Check if the page loader is enabled
       * @returns {boolean}
       */
      loaderEnabled() {
          return !/false/.test(this.layoutConfig("loader.type"));
      },
      /**
       * Page loader logo image using require() function
       * @returns {string}
       */
      loaderLogo() {
          return process.env.BASE_URL + this.layoutConfig("loader.logo");
    },
    logo() {
      return process.env.BASE_URL + "media/branding/logo-dls.png";
    },
    img() {
      return ({
        block1: process.env.BASE_URL + 'media/illustrations/wizard-create-deal-girl-with-laptop-light.png',
        confirm: process.env.BASE_URL + 'media/illustrations/wizard-create-deal-confirm.png',
        background: process.env.BASE_URL + 'media/backgrounds/registro.jpg',
        background2: process.env.BASE_URL + "media/backgrounds/bg-blanco.jpg",
      });
    }
  }
};
</script>