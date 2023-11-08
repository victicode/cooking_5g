<template>
    <!-- MODAL SECTION -->
    <div>
      <div v-if="loadSend" >
        <!-- EMAIL NO VALIDATE -->
        <div class="modal fade modal-lg"  id="not-email" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
            <div class="modal-content p-2 px-md-2 py-md-4">
              <div class="modal-body">
                <div class=" mb-4">
                  <h3 class="text-justify mb-3">
                    Gracias por registrarte DLS Money🎉
                  </h3>
                  <h5 class="text-justify ">
                    Para completar el proceso de registro necesitamos que verifiques tu dirección de correo electrónico. 
                   Se te ha enviado un correo a <a href="#">{{currentAccount.user.email}}</a> 
                   con el enlace de verificación de tu cuenta. Por favor revisa tu bandeja de entrada y sigue el
                   enlace para activar tu cuenta.
                  </h5>
  
                  <h6 class="text-center mt-5 ">
                    Si nos has recibido ningún correo, te pedimos por favor que revises la carpeta de spam o correo no deseado. 
                    De igual forma puedes reenviar el correo de verficación haciendo click en el siguiente botón.
                  </h6>
                </div>
                <div class="d-flex justify-content-center w-100">
                  <div class="col-12 d-flex justify-content-around mt-0 ">
                    <button class="btn btn-primary  align-items-baseline " onsubmit="false" @click="sennn(currentAccount.user.id)">
                      <i class="ti ti-mail me-2 ti-sm"></i>
                      <span class="align-middle d-sm-inline-block d-none" >Reenviar correo</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- VALIDATE MODAL -->
        <!-- RESEND DOCUMENT MODAL -->
        <div class="modal fade modal-lg"  id="novalidate-user" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
            <div class="modal-content p-3 px-md-4 py-md-4">
              <div class="modal-body" id="">
                <template v-if="this.currentAccount.status == 0">
                  <div class="text-center mb-4">
                    <h4 class="mb-5">
                      En estos momentos tu cuenta esta siendo validada!
                    </h4>
                    <h6>
                      Tanto tu perfil como tu documento de identificación esta siendo verificados por nuestro departamento encargado!
                    </h6>
                    <h6>De haber suministrado tus datos de formar correcta, tendras acceso a los servcios de Dlsmoney.</h6>
                  </div>
                  <div class="col-12 d-flex justify-content-around mt-3 ">
                    <button class="btn btn-label-secondary btn-backprev align-items-baseline " onsubmit="false" v-if="loadSend" @click="openModal.hide(); onLogout()">
                      <i class="ti ti-logout me-2 ti-sm"></i>
                      <span class="align-middle d-sm-inline-block d-none" >Cerrar sesion</span>
                    </button>
                    <button class="btn btn-primary btn-next align-items-baseline " @click="openModal.hide();showSelectAccountModal()">
                      <i class="ti me-2 ti-sm ti-user"></i>
                      <span class="align-middle d-sm-inline-block d-none">Cambiar de cuenta</span>
                    </button>
                  </div>
                </template>
                <template v-else>
                  <div class="text-center mb-4">
                    <h4 class="mb-2">
                      Tus documentos fueron rechazados!
                    </h4>
                    <h6>
                      Vuelve a cargar una imagen de los siguientes documentos
                    </h6>
                  </div>
                  <div class="row" id="documentsUpload">
                    <template v-if="currentAccount.type == 1">
                      <div class="row mb-4">
                        <h4 class="text-center">Documentos de la empresa</h4>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealTitle">Tipo de Documento</label>
                          <h6>RUC</h6>
                        </div>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealCode">Número de documento</label>
                          <h6>{{ currentAccount.ruc }}</h6>
                        </div>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealTitle">Razón social</label>
                          <h6>{{ currentAccount.business_name }}</h6>
                        </div>
                        <div class="col-sm-12 valid-item">
                          <label class="form-label" for="dealDescription">Adjunte Ficha ruc</label>
                          <input 
                            type="file" 
                            name="ruc_card"
                            ref="ruc_card"
                            accept=".png, .jpg, .jpeg, .pdf" 
                            class="form-control" 
                            id="basic-default-upload-file"
                            placeholder="Elija un archivo o suéltelo aquí..."
                            drop-placeholder="Suelta el archivo aquí..." 
                          />
                        </div>
                      </div>
                      <div class="row border-top pt-3 mb-4">
                        <h4 class="text-center">Documentos del representante</h4>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealTitle">Tipo de Documento</label>
                          <h6>{{ currentAccount.document_type_lr.name }}</h6>
                        </div>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealCode">Número de documento</label>
                          <h6>{{ currentAccount.document_number_lr }}</h6>
                        </div>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealTitle">Nombre completo</label>
                          <h6>{{ currentAccount.name_lr }} {{ currentAccount.surname_lr }}</h6>
                        </div>
                        <div class="col-sm-6 valid-item">
                          <label class="form-label" for="dealDescription">Adjunte una foto frontal de su documento</label>
                          <input 
                            type="file" 
                            name="document_front_lr"
                            ref="document_front_lr"
                            accept=".png, .jpg, .jpeg, .pdf" 
                            class="form-control" 
                            id="basic-default-upload-file"
                            placeholder="Elija un archivo o suéltelo aquí..."
                            drop-placeholder="Suelta el archivo aquí..." 
                          />
                        </div>
                        <div class="col-sm-6 valid-item">
                          <label class="form-label" for="dealDescription">Adjunte una foto dorsal de su documento</label>
                          <input 
                            type="file" 
                            class="form-control" 
                            id="basic-default-upload-file" 
                            name="document_back_lr"
                            ref="document_back_lr"
                            accept=".png, .jpg, .jpeg, .pdf"
                            placeholder="Elija un archivo o suéltelo aquí..."
                            drop-placeholder="Suelta el archivo aquí..." 
                          />
                        </div>
                      </div>
                      <div class="row border-top pt-3 ">
                        <h4 class="text-center">Documentos del contacto</h4>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealTitle">Tipo de Documento</label>
                          <h6>{{ currentAccount.document_type_c.name }}</h6>
                        </div>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealCode">Número de documento</label>
                          <h6>{{ currentAccount.document_number_c }}</h6>
                        </div>
                        <div class="col-sm-4 valid-item text-center mb-3">
                          <label class="form-label" for="dealTitle">Nombre completo</label>
                          <h6>{{ currentAccount.name_c }} {{ currentAccount.surname_c }}</h6>
                        </div>
                        <div class="col-sm-6 valid-item">
                          <label class="form-label" for="dealDescription">Adjunte una foto frontal de su documento</label>
                          <input 
                            type="file" 
                            name="document_front_c"
                            ref="document_front_c"
                            accept=".png, .jpg, .jpeg, .pdf" 
                            class="form-control" 
                            id="basic-default-upload-file"
                            placeholder="Elija un archivo o suéltelo aquí..."
                            drop-placeholder="Suelta el archivo aquí..." 
                          />
                        </div>
                        <div class="col-sm-6 valid-item">
                          <label class="form-label" for="dealDescription">Adjunte una foto dorsal de su documento</label>
                          <input 
                            type="file" 
                            class="form-control" 
                            id="basic-default-upload-file" 
                            name="document_back_c"
                            ref="document_back_c"
                            accept=".png, .jpg, .jpeg, .pdf"
                            placeholder="Elija un archivo o suéltelo aquí..."
                            drop-placeholder="Suelta el archivo aquí..." 
                          />
                        </div>
                      </div>
                    </template>
                    <template v-else>
                      <div class="col-sm-4 valid-item text-center mb-3">
                        <label class="form-label" for="dealTitle">Tipo de Documento</label>
                        <h6>{{ currentAccount.document_type.name }}</h6>
                      </div>
                      <div class="col-sm-4 valid-item text-center mb-3">
                        <label class="form-label" for="dealCode">Número de documento</label>
                        <h6>{{ currentAccount.document_number }}</h6>
                      </div>
                      <div class="col-sm-4 valid-item text-center mb-3">
                        <label class="form-label" for="dealTitle">Nombre completo</label>
                        <h6>{{ currentAccount.fullName }}</h6>
                      </div>
                      <div class="col-sm-6 valid-item">
                        <label class="form-label" for="dealDescription">Adjunte una foto frontal de su documento</label>
                        <input 
                          type="file" 
                          name="document_front"
                          ref="document_front"
                          accept=".png, .jpg, .jpeg, .pdf" 
                          class="form-control" 
                          id="basic-default-upload-file"
                          placeholder="Elija un archivo o suéltelo aquí..."
                          drop-placeholder="Suelta el archivo aquí..." 
                        />
                      </div>
                      <div class="col-sm-6 valid-item">
                        <label class="form-label" for="dealDescription">Adjunte una foto dorsal de su documento</label>
                        <input 
                          type="file" 
                          class="form-control" 
                          id="basic-default-upload-file" 
                          name="document_back"
                          ref="document_back"
                          accept=".png, .jpg, .jpeg, .pdf"
                          placeholder="Elija un archivo o suéltelo aquí..."
                          drop-placeholder="Suelta el archivo aquí..." 
                        />
                      </div>
                    </template>
                  </div>
                  <div class="col-12 d-flex justify-content-around mt-5 ">
                    <button class="btn btn-label-secondary btn-backprev align-items-baseline w-25 " onsubmit="false" v-if="loadSend" @click="openModal.hide(); onLogout()">
                      <span class="align-middle d-sm-inline-block d-none" >Cerrar sesion</span>
                    </button>
                    <button class="btn btn-primary btn-next align-items-baseline w-25 " disabled id="uploadDocuments" @click="reloadDocument()">
                      <span class="align-middle d-sm-inline-block d-none">Subir</span>
                    </button>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>  
      <template v-if="loadSend2">
        <div class="modal fade modal-lg"  id="selectAccountActive" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
            <div class="modal-content p-3 p-md-5">
              <div class="modal-body">
                <div class="text-center mb-4">
                  <h5 class="mb-2">
                  {{ 
                    personalAccounts.length > 0 ? 'Selecciona una cuenta para realizar tu cambio!':
                    companyAccounts.length > 0 ? 'Selecciona una cuenta para realizar tu cambio!':
                    'No tienes mas cuenta registrada... Espera mientras validamos tu cuenta!'
                  }}
                  </h5>
                </div>
                <div class="d-flex justify-content-center w-100">
                  <ul style="list-style: none;" class="w-100 row justify-content-center">
                    <template v-if="personalAccounts.length > 0" >
                      <li class="w-100">
                        <div class="dropdown-divider"></div>
                      </li>
                      <li class="text-center w-100">
                        <h5><span class="fw-bold"> Cuentas personales</span></h5>
                      </li>
                    </template>
                    <template v-if="personalAccounts.length > 0" v-for="account in personalAccounts">
                      <li class="w-50 p-3 pt-0"> 
                        <div class="dropdown-item d-flex p-3 align-items-center btn btn-primary " @click="SelectActiveAccount($event, account.id, 0)">
                          <i class="fa-regular fa-user fa-lg me-2" ></i>
                          <span class="align-middle text-white" :class="{ 'active': activeAccountType == 0 && account.id == activeAccountId}">{{ account.name }} {{ account.surname }}</span>
                        </div>
                      </li>
                    </template>
                    <template v-if="companyAccounts.length > 0">
                      <li class="w-100">
                        <div class="dropdown-divider"></div>
                      </li>
                      <li class="text-center w-100" >
                        <h5><span class="fw-bold">Cuentas de Empresas</span></h5>
                      </li>
                    </template>
                    <template v-if="companyAccounts.length > 0"  v-for="account in companyAccounts">
                      <li class="w-50 p-3 pt-0">
                        <div class="dropdown-item d-flex p-3 align-items-center btn btn-primary " @click="SelectActiveAccount($event, account.id, 1)">
                          <i class="fa-solid fa-building-user fa-lg me-2"></i>
                          <span class="align-middle text-white" :class="{ 'active': activeAccountType == 1 && account.id == activeAccountId}" >{{ account.business_name }}</span>
                        </div>
                      </li>
                    </template>
                    <div class="col-12 d-flex justify-content-around mt-3 ">
                      <button class="btn btn-label-secondary btn-backprev align-items-baseline " onsubmit="false" v-if="loadSend2" @click="openModal.hide(); onLogout()">
                        <i class="ti ti-logout me-2 ti-sm"></i>
                        <span class="align-middle d-sm-inline-block d-none" >Cerrar sesion</span>
                      </button>
                    </div>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
</template>
<script>
import KTTopbar from "@/view/layout/header/Topbar.vue";
import { ACCOUNTS_GET_ALL, SET_ACTIVE_ACCOUNT, ACCOUNTS_PERSONAL_RESEND, ACCOUNTS_COMPANY_RESEND} from "@/core/services/store/account.module";
import { LOGOUT } from "@/core/services/store/auth.module";
import { bootstrap } from "@/concept/bootstrap";
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import AutoFocus from "@/assets/plugins/formvalidation/dist/es6/plugins/AutoFocus";
import { SEND_EMAILS } from "@/core/services/store/account.module";

// import notificationTone from "@/assets/audio/notification.mp3";

export default {
  name: "general-modals",
  components: {
    KTTopbar,
    KTMenu
  },
  data(){
    return{
      personalAccounts: [],
      companyAccounts: [],
      currentAccount: {label:'0'},
      activeAccountType: null,
      activeAccountId: null,
      mainAlert: false,
      mainAlertVariant: "",
      mainAlertMessage: "",
      redirectMessageSeconds: 0,
      redirectMessage: '',
      isAdmin: false,
      openModal:'',
      loadSend: false,
      loadSend2:false,
      FormValidation:'',
      role: window.localStorage.user_role,
      
    }
  },
  methods:{
    getAllAccounts () {
      this.$store
        .dispatch(ACCOUNTS_GET_ALL)
        .then((data) => {
            if (data.code != 200){
                this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
                return;
            }
            this.personalAccounts = data.data.personalAccounts;
            for (let i = 0; i < this.personalAccounts.length; i++) {
                let label = this.personalAccounts[i].name + ' ' + this.personalAccounts[i].surname;
                let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
                this.personalAccounts[i].label = matches.join('.'); // J.S.O.N
            }

            this.companyAccounts = data.data.companyAccounts;
            for (let i = 0; i < this.companyAccounts.length; i++) {
                let label = this.companyAccounts[i].business_name;
                let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
                  this.companyAccounts[i].label = matches.join('.'); // J.S.O.N
            }
            this.activeAccountType = data.data.activeAccountType;
            this.activeAccountId = data.data.activeAccountId;
            this.getCurrentAccount()
        })
        .catch((err) => {
            this.showMainAlert('danger', err)
        });
    },
    getCurrentAccount(){

      if(this.activeAccountType == 1){
        this.companyAccounts.map((item) => {
          if(item.id == this.activeAccountId){
            this.currentAccount = item;
          }
        })
      }else{
        this.personalAccounts.map((item) => {
          if(item.id == this.activeAccountId){
            this.currentAccount = item;
            this.currentAccount.fullName = `${item.name.split(' ')[0]} ${item.surname.split(' ')[0]}`
          }
        })
      }
      this.currentAccount.type = this.activeAccountType;
      this.$store.currentAccount = this.currentAccount;
      if(this.activeAccountType == '0' && this.activeAccountId == '0'){
          this.loadSend2 = true;
          setTimeout(() => {
            this.showModal('selectAccountActive')
          }, 500);
          return
      }
      this.userDesactivate()
      
    },
    SelectActiveAccount (e, id, type) {
      // this.openModal.hide()

      let data = {
          active_account_type: type,
          active_account: id
      };

      this.$store
        .dispatch(SET_ACTIVE_ACCOUNT, data)
        .then((data) => {
            if (data.code != 200){
                this.showMainAlert('danger', 'Error desconocido al cambiar la cuenta activa.')
                return;
            }
            
            setTimeout(() => {
              location.reload();
            }, 100);
        })
        .catch((err) => {
            this.showMainAlert('danger', err)
        });
    },
    showSelectAccountModal(){
      if (this.currentAccount.type == 0) this.personalAccounts = this.personalAccounts.filter(account => account.id != this.currentAccount.id)
      if (this.currentAccount.type == 1) this.companyAccounts = this.companyAccounts.filter(account => account.id != this.currentAccount.id)
      this.loadSend2 = true;
      setTimeout(() => {
        
        this.showModal('selectAccountActive')
      }, 300);
    },
    userDesactivate(){
      const actualAccount = this.currentAccount;

      if(!actualAccount.user.email_verified_at){
        this.loadSend = true;
        setTimeout(() => {
          this.showModal('not-email')
          return
        },200)
      }

      if (actualAccount.status != 1) {
        this.loadSend = true;
        setTimeout(() => {
          this.showModal('novalidate-user')
          if (actualAccount.status == -1) {
            this.validate()
          }
        }, 200);
      }
    },
    showSavedAlert(variant, message) {
      this.savedAlertVariant = variant;
      this.savedAlertMessage = message;
      this.savedAlert = true;
    },
    reloadDocument() {
      this.FormValidation.validate().then((data)=>{
        if (data=='Valid') {
          document.getElementById('uploadDocuments').disabled = true
          let formData = new FormData(),
          action = this.currentAccount.type == 0 ? ACCOUNTS_PERSONAL_RESEND : ACCOUNTS_COMPANY_RESEND
          
          formData.append('id', this.currentAccount.id);

          if(this.currentAccount.type == 0){
            formData.append('document_front', this.$refs.document_front.files[0]);
            formData.append('document_back', this.$refs.document_back.files[0]);
          }else{
            formData.append('ruc_card', this.$refs.ruc_card.files[0]);
            formData.append('document_back_lr', this.$refs.document_back_lr.files[0]);
            formData.append('document_front_lr', this.$refs.document_front_lr.files[0]);
            formData.append('document_back_c', this.$refs.document_back_c.files[0]);
            formData.append('document_front_c', this.$refs.document_front_c.files[0]);
          }
          let datax = {
            id: this.currentAccount.id,
            data: formData,

          }
          this.$store
            .dispatch(action, datax)
            .then((data) => {
              if (data.code != 200){
                  // this.showDangerAlert('Error desconocido al subir los documentos.');
                  document.getElementById('uploadDocuments').disabled = false
                  
                  return;
              }
              
              this.currentAccount.status = data.data.status
              document.getElementById('uploadDocuments').disabled = 
              this.openModal.hide()
              setTimeout(() => {
                this.userDesactivate()
              }, 100);
              
            })
            .catch((err) => {
                this.showDangerAlert(err);
                document.getElementById('uploadDocuments').disabled = false
                document.getElementById('uploadDocuments').textContent = 'Subir'
            });
        }
      });
      


    },
    validate() {
      
      this.FormValidation = this.currentAccount.type == 0 ?
        formValidation(document.getElementById('documentsUpload'), {
          fields: {
            // * Validate the fields here based on your requirements
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
        }):
        formValidation(document.getElementById('documentsUpload'), {
          fields: {
            // * Validate the fields here based on your requirements
            ruc_card: {
              validators: {
                notEmpty: {
                  message: ' Tienes que subir una foto'
                },
                file: {
                  extension: 'jpeg,jpg,PNG,png,pdf,PDF',
                  type: 'image/jpeg,image/png,application/pdf',
                  message: 'Solo puedessss subir archivos .jpg, .jpeg, .png',
                }
              }
            },
            document_front_lr: {
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
            document_front_c: {
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
        });
        
        
        this.FormValidation.on('core.form.valid', () => {
          document.getElementById('uploadDocuments').disabled = false
        }).on('core.field.valid', () => {
          document.getElementById('uploadDocuments').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('uploadDocuments').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('uploadDocuments').disabled = true
        })
    },
    showMainAlert(variant, message) {
      this.mainAlertVariant = variant;
      this.mainAlertMessage = message;
      this.mainAlert = true;
      window.scrollTo(0,0);
    },
    showMainAlerts(variant, message) {
      this.mainAlertVariant = variant;
      this.mainAlertMessage = message;
      this.mainAlerts = true;
      window.scrollTo(0,0);
    },
    onLogout() {
      this.$store.dispatch(LOGOUT).then(() => this.$router.push({ name: "login" }));
    },
    getIsAdmin() {
      this.isAdmin = window.localStorage.getItem("is_admin") == "true" ? true : false;
    },
    showModal(id) {
      this.openModal = new bootstrap.Modal(document.getElementById(id), {
        keyboard: false,
        backdrop:'static'
      })
      this.openModal.show()
    },
    hideModal(){
      this.openModal.hide()     
    },
  },
  mounted() {
    // Init Desktop & Mobile Headers
    this.getAllAccounts();
  },
  created(){
  },
};
</script>