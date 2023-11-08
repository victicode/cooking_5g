<template>
    <div >
      <div class="row" v-if="mainAlert">
        <div class="col-lg-10 col-md-10 col-sm-12">
          <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
        </div>
        <hr>
      </div>
			
			<div v-if="loader" class="row">
        <div class="row px-4"> 
          <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inicio / </span> Mi perfil</h4>
          <ul class="nav nav-pills flex-column flex-md-row mb-2 ">
            <li class="nav-item" @click="goAccounts()"  >
              <a class="nav-link active" id="accountButton" href="javascript:void(0); "
                ><i class="ti-xs ti ti-users me-1"></i> Mi datos</a
              >
            </li>
            <li class="nav-item"  @click="goUser()"  >
              <a class="nav-link" id="userButton" href="javascript:void(0);"
                ><i class="ti-xs ti ti-lock me-1"></i> Seguridad</a
              >
            </li>
          </ul>
        </div>
				<div id="AccountSettings" class="col-12 exchange-form  ">
					
					<div>
						<div class="card mb-4" id="collapseExample">
							<h5 class="card-header">Detalles de mi cuenta</h5>
							<!-- Account -->
							<personal-setting v-if="selectedAccount.type==0" :selectedAccountP="selectedAccount" @click="updateToAccount"></personal-setting>
						
							<company-setting v-else-if="selectedAccount.type==1" :selectedAccountP="selectedAccount" @click="updateToAccount"></company-setting>
							<hr class="my-0" />
							<!-- /Account -->
						</div>
						<div class="card mb-4 " id="collapseExample2">
							<h5 class="card-header">Borrar cuenta</h5>
							<div class="card-body">
								<div class="mb-3 col-12 mb-0">
									<div class="alert alert-warning">
										<h5 class="alert-heading mb-1">¿Esta seguro que quiere borrar su cuenta?</h5>
										<p class="mb-0">Una vez que borre la cuenta no puede retroceder el proceso, tiene que estar seguro que quiere eliminarla.</p>
									</div>
								</div>
								<form id="" onsubmit="return false">
									<div class="form-check mb-4 valid-item" id="formAccountDeactivation">
										<input
											class="form-check-input"
											type="checkbox"
											name="agreeDelete"
											ref="agreeDelete"
											id="accountActivation"
											@change="validDelete()"
										/>
										<label class="form-check-label" for="accountActivation"
											>Confirmo que quiero borrar mi cuenta</label
										>
									</div>
									<button onsubmit="return false"  class="btn btn-danger deactivate-account text-white" disabled @click="showDeleteConfirmation($event)">Desactivar cuenta</button>
								</form>
							</div>
						</div>
						<div>
							<b-modal id="modal-confirm-delete" 
              title="Eliminar perfil" :no-close-on-backdrop="true" ok-title="Confirmar" cancel-title="Cancelar" @ok="" >
									
							</b-modal>
					  </div>
					</div>
					<div>
						
					</div>
				</div>
				<user-setting></user-setting>
				<div class="modal fade modal" id="next" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
							<div class="modal-content p-3 p-md-3">
								<div class="modal-body">
									<div class="content-header mb-2">
										<h4 class="fw-bold pt-3 mb-2 text-center">Tu cuenta ha sido desctivada de forma exitosa ¿ Que deseas hacer?</h4>
									
									</div>
									<div class="col-12 d-flex justify-content-between mt-3 ">
										<button class="btn btn-primary align-items-baseline " @click="restoreAccount()">
										<span class="align-middle d-sm-inline-block d-none">Restaurar Cuenta</span>
										</button>
										<button class="btn btn-danger btn-next align-items-baseline" id="declaracion-button" onsubmit="false" @click="onLogout()">
											<span class="align-middle d-sm-inline-block d-none">Continuar</span>
										</button>
									</div>
								</div>
							</div>
					</div>
				</div>
        <div class="modal fade modal" id="modal-confirm-delete" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
							<div class="modal-content p-3 p-md-3">
								<div class="modal-body">
									<div class="content-header mb-2">
										<h4 class="fw-bold pt-3 mb-2 text-center">¿Esta seguro que desea desactivar este perfil?</h4>
									
									</div>
									<div class="col-12 d-flex justify-content-between mt-3 ">
										<button class="btn btn-primary align-items-baseline " @click="openModal.hide()">
										<span class="align-middle d-sm-inline-block d-none">Cancelar</span>
										</button>
										<button class="btn btn-danger btn-next align-items-baseline"  onsubmit="false" @click="deleteAccount($event)">
											<span class="align-middle d-sm-inline-block d-none">Desactivar</span>
										</button>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div> 
			<div v-else class="row bg-white">
				<div class="col-12">
					<div class="w-100 border border-base border-end-0 border-start-0 d-flex justify-content-center p-4 align-items-center"  >
						<div class="spinner-border me-2 text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
						<h3 class=" text-center m-0 text-primary">Cargando</h3>
					</div>
				</div>
			</div>
			
    </div>
</template>
<style lang="scss">
  @import "@/assets/libs/flatpickr/flatpickr.scss";
</style>
<script>
    import { ACCOUNTS_GET_ALL, ACCOUNTS_PERSONAL_UPDATE, ACCOUNTS_COMPANY_UPDATE, SET_ACTIVE_ACCOUNT, ACCOUNTS_PERSONAL_DELETE, ACCOUNTS_COMPANY_DELETE, ACCOUNTS_PERSONAL_RESTORE, ACCOUNTS_COMPANY_RESTORE } from "@/core/services/store/account.module";
		import { mapGetters } from "vuex";
    import PersonalSetting from "@/view/pages/profiles/PersonalSetting.vue";
    import CompanySetting from "@/view/pages/profiles/CompanySetting.vue";
    import UserSetting from "@/view/pages/profiles/UserSetting.vue";
		import { bootstrap } from "@/concept/bootstrap";
    import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";

    // FormValidation plugins
    import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
    import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
    import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
		export default {
        data() {
          return {
            mainAlert: false,
            mainAlertVariant: "",
            mainAlertMessage: "",
            personalAccounts: [],
            companyAccounts: [],
            selectedAccount: {},
            redirectMessageSeconds: 3,
            loader: false,
            formValidated:'',
            selectedAccount:'',
            personalAccounts:'',
            companyAccounts:'',
            activeAccountType:'',
            activeAccountId:'',
          }
        },
        components: {
          PersonalSetting: PersonalSetting,
          CompanySetting: CompanySetting,
          UserSetting: UserSetting
        },
				mounted() {
          this.getAllAccounts()
        },
				methods: {
					onLogout() {
		
						this.$store.dispatch(ACCOUNTS_GET_ALL).then((data) => {
								if (data.code != 200){
									this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
									return;
								}
								let newActive =""
								if (this.selectedAccount.type == 0) {
									if (data.data.personalAccounts.length > 0) {
										newActive = data.data.personalAccounts[0].id
									} else if (data.data.companyAccounts.length > 0) {
										newActive = data.data.companyAccounts[0].id
									}
								} else {
									if (data.data.companyAccounts.length > 0) {
										newActive = data.data.personalAccounts[0].id
									} else if (data.data.personalAccounts.length > 0) {
										newActive = data.data.companyAccounts[0].id
									} 
								}


								let datax = {
										active_account_type: this.selectedAccount.type,
										active_account: newActive
								};

								this.$store.dispatch(SET_ACTIVE_ACCOUNT, datax).then((data) => {
									
									if (data.code != 200){
											return;
									}
									this.openModal.hide()
									this.$router.replace({ path: "/dashboard" });
									setTimeout(() => {
										location.reload();
									}, 1000);
									
                })
						})				
					},
					changeActiveAccount (id, type) {

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

									// close modal
									this.showMainAlert('success', 'Perfil activo cambiado correctamente!')
									this.redirectTimer(' . Recargando página en: ');
							})
							.catch((err) => {
									
							});
          },	
          goUser(){
            let ex = document.querySelector('#AccountSettings')
            ex.classList.add('out-convert')
            let accountButton = document.querySelector('#accountButton')
            let userButton = document.querySelector('#userButton')

            accountButton.classList.remove('active');
            userButton.classList.add('active');
            
            setTimeout(() => {
              let form = document.querySelector('#userSettings')
              ex.classList.add('position-absolute')
              form.classList.add('active')
            },500)
          },	
          goAccounts() {
            let ex = document.querySelector('#AccountSettings')
            let form = document.querySelector('#userSettings')
            let accountButton = document.querySelector('#accountButton')
            let userButton = document.querySelector('#userButton')

            accountButton.classList.add('active');
            userButton.classList.remove('active');

            form.classList.remove('active')
            setTimeout(() => {
              ex.classList.remove('position-absolute')
              ex.classList.remove('out-convert')
              
             
            },500)
          },
					restoreAccount() {
						const DISPATCH_ACCOUNT_ACTION = this.selectedAccount.type == 0 ? ACCOUNTS_PERSONAL_RESTORE : this.selectedAccount.type == 1 ? ACCOUNTS_COMPANY_RESTORE : '';
              this.$store
								.dispatch(DISPATCH_ACCOUNT_ACTION, this.selectedAccount.id)
								.then((data) => {
										if (data.code != 200){
												this.showMainAlert('danger', 'Error desconocido al eliminar el perfil.')
												return;
									}	
									this.openModal.hide()
								})
								.catch((err) => {
										this.showMainAlert('danger', err)
								});
					},
					showModal(id) {
						this.openModal = new bootstrap.Modal(document.getElementById(id), {
							keyboard: false,
							backdrop:'static'
						})
						this.openModal.show()
					},
					updateToAccount(info) { 
            this.selectedAccount = info.data;
            setTimeout(() => {
              this.updateAccount(info.event)
            }, 200);
          },
          updateAccount (e) {
              // Validate fields
              if (this.selectedAccount.type == 0) {
                  if(this.selectedAccount.name == null || this.selectedAccount.name == ""){
                      this.showMainAlert('danger', 'Debe ingresar el nombre/s.')
                      return;
                  }
                  if(this.selectedAccount.surname == null || this.selectedAccount.surname == ""){
                      this.showMainAlert('danger', 'Debe ingresar el apellido/s.')
                      return;
                  }
                  if(this.selectedAccount.document_type_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el tipo de documento.')
                      return;
                  }
                  if(this.selectedAccount.document_number == null || this.selectedAccount.document_number == ""){
                      this.showMainAlert('danger', 'Debe ingresar número de documento.')
                      return;
                  }
                  if(this.selectedAccount.birthdate == null || this.selectedAccount.birthdate == ""){
                      this.showMainAlert('danger', 'Debe ingresar la fecha de nacimiento.')
                      return;
                  }
                  if(this.selectedAccount.country_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el país.')
                      return;
                  }
                  if(this.selectedAccount.department_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el departamento.')
                      return;
                  }
                  if(this.selectedAccount.province_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar la provincia.')
                      return;
                  }
                  if(this.selectedAccount.district_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el distrito.')
                      return;
                  }
                  if(this.selectedAccount.address == null || this.selectedAccount.address == ""){
                      this.showMainAlert('danger', 'Debe ingresar la dirección.')
                      return;
                  }
                  if(this.selectedAccount.ocupation_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar la ocupación.')
                      return;
                  }
                  if(this.selectedAccount.exposed_person == 1){
                      if(this.selectedAccount.position == null || this.selectedAccount.position == ""){
                          this.showMainAlert('danger', 'Debe ingresar la posición.')
                          return;
                      }
                      if(this.selectedAccount.workplace == null || this.selectedAccount.workplace == ""){
                          this.showMainAlert('danger', 'Debe ingresar el lugar de trabajo.')
                          return;
                      }
                  }
                  else {
                      this.selectedAccount.position = null;
                      this.selectedAccount.workplace = null;
                  }
              }
              else if (this.selectedAccount.type == 1) {
                  if(this.selectedAccount.ruc == null || this.selectedAccount.ruc == ""){
                      this.showMainAlert('danger', 'Debe ingresar el RUC.')
                      return;
                  }
                  if(this.selectedAccount.business_name == null || this.selectedAccount.business_name == "" ||this.selectedAccount.business_name.length < 3){
                      this.showMainAlert('danger', 'Debe ingresar la razón social.')
                      return;
                  }
                  if(this.selectedAccount.business_turn == null || this.selectedAccount.business_turn == ""){
                      this.showMainAlert('danger', 'Debe ingresar el giro de negocio.')
                      return;
                  }
                  if(this.selectedAccount.fiscal_address == null || this.selectedAccount.business_turn == ""){
                      this.showMainAlert('danger', 'Debe ingresar la dirección fiscal.')
                      return;
                  }
                  if(this.selectedAccount.country_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el país.')
                      return;
                  }
                  if(this.selectedAccount.department_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el departamento.')
                      return;
                  }
                  if(this.selectedAccount.province_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar la provincia.')
                      return;
                  }
                  if(this.selectedAccount.district_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el distrito.')
                      return;
                  }
                  if(this.selectedAccount.email == null || this.selectedAccount.email == ""){
                      this.showMainAlert('danger', 'Debe ingresar el email.')
                      return;
                  }
                  if(this.selectedAccount.phone == null || this.selectedAccount.phone == ""){
                      this.showMainAlert('danger', 'Debe ingresar el email.')
                      return;
                  }
                  if(this.selectedAccount.name_lr == null || this.selectedAccount.name_lr == ""){
                      this.showMainAlert('danger', 'Debe ingresar el nombre/s del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.surname_lr == null || this.selectedAccount.surname_lr == ""){
                      this.showMainAlert('danger', 'Debe ingresar el apellido/s del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.document_type_lr_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el tipo de documento del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.document_number_lr == null || this.selectedAccount.document_number_lr == ""){
                      this.showMainAlert('danger', 'Debe ingresar el número de docuemento del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.name_c == null || this.selectedAccount.name_c == ""){
                      this.showMainAlert('danger', 'Debe ingresar el nombre/s del contacto.')
                      return;
                  }
                  if(this.selectedAccount.surname_c == null || this.selectedAccount.surname_c == ""){
                      this.showMainAlert('danger', 'Debe ingresar el apellido/s del contacto.')
                      return;
                  }
                  if(this.selectedAccount.document_type_c_id == -1){
                      this.showMainAlert('danger', 'Debe seleccionar el tipo de documento del contacto.')
                      return;
                  }
                  if(this.selectedAccount.document_number_c == null || this.selectedAccount.document_number_c == ""){
                      this.showMainAlert('danger', 'Debe ingresar el número de docuemento del contacto.')
                      return;
                  }
              }

              this.formValidated.validate().then((data)=>{
                const button = document.getElementById('update-button');
                const DISPATCH_ACCOUNT_ACTION = this.selectedAccount.type == 0 ? ACCOUNTS_PERSONAL_UPDATE : this.selectedAccount.type == 1 ? ACCOUNTS_COMPANY_UPDATE : '';
                if(data == 'Valid'){
                  button.setAttribute('disabled', 'disabled');
                  button.textContent='Cargando...'
                  this.$store
                    .dispatch(DISPATCH_ACCOUNT_ACTION, this.selectedAccount)
                    .then((data) => {
                        if (data.code != 200) {
                          this.showMainAlert('danger', 'Error desconocido al actualizar el perfil.')
                          return;
                        }
                        let label = '';
    
                        if (this.selectedAccount.type == 0) {
                            label = data.data.name + ' ' + data.data.surname;
                        }
                        else if (this.selectedAccount.type == 1) {
                            label = data.data.business_name;
                        }
    
                        let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
                        data.data.label = matches.join('.'); // J.S.O.N
    
    
                        if (this.selectedAccount.type == 0) {
                            data.data.type = 0
                            this.selectedAccount = data.data;
                        }
                        else if (this.selectedAccount.type == 1) {
                            data.data.type = 1
                            this.selectedAccount = data.data;
                        }
                        
    
                        // close modal
                        // this.closeModal('modal-selected-account')
                      this.showMainAlert('success', 'Perfil actualizado correctamente!')
                      button.removeAttribute('disabled')
                      button.textContent = 'Guardar Cambios'
                    })
                    .catch((err) => {
                      this.showMainAlert('danger', 'Error en datos del formulario.')
                      button.removeAttribute('disabled')
                      button.textContent='Guardar Cambios'
                    });
                }else{
                  window.scrollTo(0,200);
                }
              })
									
          },
          deleteAccount (e) {

              const DISPATCH_ACCOUNT_ACTION = this.selectedAccount.type == 0 ? ACCOUNTS_PERSONAL_DELETE : this.selectedAccount.type == 1 ? ACCOUNTS_COMPANY_DELETE : '';
							this.openModal.hide()
              this.$store
								.dispatch(DISPATCH_ACCOUNT_ACTION, this.selectedAccount.id)
								.then((data) => {
										if (data.code != 200){
												this.showMainAlert('danger', 'Error desconocido al eliminar el perfil.')
												return;
										}
                    
							      this.showModal('next')
								})
								.catch((err) => {
										this.showMainAlert('danger', err)
								});
          },
          showDeleteConfirmation (e) {
              e.preventDefault();
              this.showModal('modal-confirm-delete');
          },
          showMainAlert(variant, message) {
              this.mainAlertVariant = variant;
              this.mainAlertMessage = message;
              this.mainAlert = true;
              window.scrollTo(0,0);
					},
					validDelete() {
						setTimeout(() => {
							if (this.$refs.agreeDelete.checked) {
								document.querySelector('.deactivate-account').removeAttribute('disabled');
							} else {
								document.querySelector('.deactivate-account').setAttribute('disabled','disabled');
							}
						}, 50);
					},
          validateItem(){
            
           const fields = this.selectedAccount.type==0 
           ? 
           {
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
                  // notEmpty: {
                  //   message: 'El segundo apellido no puede quedar vacio'
                  // },
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
              ocupation_id: {
                validators: {
                  notEmpty: {
                    message: 'Tienes que selecionar tu ocupación'
                  },
                }
              },
            }
           : 
            {
              ruc_card: {
                validators: {
                  notEmpty: {
                    message: 'El número de documento no puede quedar vacio'
                  },
                  numeric: {
                    message: 'Este campo tiene que ser númerico'
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
            };
           const password_change_form = document.getElementById('company_data');

            
           this.formValidated = formValidation(password_change_form, {
              fields: fields,
              plugins: {
                trigger: new Trigger(),
                bootstrap: new Bootstrap({
                  eleValidClass: '',
                  rowSelector: '.valid-item'
                }),
                submitButton: new SubmitButton()
              }
            }).on("core.field.invalid", () => {
              document.getElementById('update-button').disabled = true;
            }).on("core.field.valid", () => {
              document.getElementById('update-button').disabled = false;
            }).on("core.form.valid", () => {
              document.getElementById('update-button').disabled = false;
            }).on("core.form.invalid", () => {
              document.getElementById('update-button').disabled = true;
              window.scrollTo(0,50);

            })
          },
          getAllAccounts() {
            this.$store
              .dispatch(ACCOUNTS_GET_ALL)
              .then((data) => {
                  if (data.code != 200){
                      this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
                      return;
                  }
                  this.personalAccounts = data.data.personalAccounts;
                  this.companyAccounts = data.data.companyAccounts;
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
                  this.selectedAccount = item;
                }
              })
            }else{
              this.personalAccounts.map((item) => {
                if(item.id == this.activeAccountId){
                  this.selectedAccount = item;
                  this.selectedAccount.fullName = `${item.name.split(' ')[0]} ${item.surname.split(' ')[0]}`
                }
              })
            }
            this.selectedAccount.type = this.activeAccountType;
            this.loader = true;
            setTimeout(() => {
              if (this.selectedAccount.status == "1") {
                
                this.validateItem();
              }
            }, 500);
          },

        },
        created() {

        },
				// computed: {
				// 	...mapGetters(["selectedAccount"]),
					
				// },
    };
</script>