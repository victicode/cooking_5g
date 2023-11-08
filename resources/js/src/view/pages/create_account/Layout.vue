<template>
  <div class="container-xxl flex-grow-1 container-p-y mw-100 px-5" :style="{ backgroundImage: `url(${img.background2})`, height: '100vh' }">
  
    <div class="row" v-if="dangerAlert">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <b-alert :show="dangerAlert" dismissible fade variant="danger" @dismissed="dangerAlert=false">{{ dangerAlertMessage }}</b-alert>
            </div>
            <hr>
  </div>

  <!-- Create Deal Wizard -->
    <div id="wizard-create-deal" class="bs-stepper vertical mt-2">
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
      <div class="bs-stepper-content ">
        <form id="wizard-create-deal-form" onSubmit="return false">
          <!-- Deal Type -->
          <div id="deal-type" class="content d-block visible">
            <div class="row g-3">
              <div class="col-12 d-flex justify-content-center border rounded pt-4" :style="{ backgroundImage: `url(${img.background})` }" >
                

                <img
                  :src="img.block1"
                  alt="wizard-create-deal"
                  data-app-light-img="illustrations/wizard-create-deal-girl-with-laptop-light.png"
                  data-app-dark-img="illustrations/wizard-create-deal-girl-with-laptop-dark.png"
                  width="650"
                  class="img-fluid"
                />
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
                <button class="btn btn-primary btn-nexxt" id="step1" @click="wizarCreated()">
                  <span class="align-middle d-sm-inline-block d-none me-sm-1" >Siguiente</span>
                  <i class="ti ti-arrow-right ti-xs"></i>
                </button>
              </div>
            </div>
          </div>
          <Personal v-if="TypeofAccount == 0 " :manual="'no'" :dataUser="''"></Personal>
          <Company v-else-if="TypeofAccount == 1 " :manual="'no'"  :dataUser="''" ></Company>
        </form>
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
import HtmlClass from "@/core/services/htmlclass.service";
import Personal from "@/view/pages/create_account/Personal.vue";
import Company from "@/view/pages/create_account/Company.vue";
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import AutoFocus from "@/assets/plugins/formvalidation/dist/es6/plugins/AutoFocus";
import Stepper from 'bs-stepper';
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "@/core/services/store/htmlclass.module.js";

export default {
  name: "Layout",
  data() {
    return {
      
      TypeofAccount: 0,
      dangerAlert: false,
      dangerAlertMessage: "",
      wizardCreateDeal: "",
      wizardCreateDealForm:"",
      wizardCreateDealFormStep2:"",
      wizardCreateDealFormStep3:"",
      wizardCreateDealFormStep4:"",
      wizardCreateDealNext:"",
      wizardCreateDealPrev:"",
        
    }
  },
  components: {
    Personal: Personal,
    Company: Company
  },
  beforeMount() {
      // show page loading
      this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");

      // initialize html element classes
      HtmlClass.init(this.layoutConfig());
  },
  mounted() {
      // Simulate the delay page loading
      setTimeout(() => {
        this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
        
      }, 1000);
  },
  methods: {
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
    wizarCreated() {
      
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
                  regexp: /^[A-Za-z0-9À-ÿ& . ]+$/i,
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
                  regexp: /^[A-Za-z0-9À-ÿ .]+$/i,
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