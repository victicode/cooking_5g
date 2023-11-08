<template>
    <div class="modal fade modal-xl" id="formEdit"  tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-3" id="form-edit-user-data">
          <div class="row align-items-center position-relative" v-if="mainAlert">
						<div class="col-lg-12 col-md-12 col-sm-12 p-0 ">
								<b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
						</div>
          </div>
          <div id="form-data" class="modal-body">
            <template v-if="selectedUser.type == 0">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                    <div class="form-group ">
                        <label>Nombre/s</label>
                        <input type="text" class="form-control" name="name" ref="name" placeholder="Ingresa el nombre/s" v-model="selectedUser.name">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Apellido/s</label>
                        <input type="text" class="form-control" name="surname" ref="surname" placeholder="Ingresa el apellido/s" v-model="selectedUser.surname">
                    </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Tipo de documento</label>
                          <select class="form-control" name="document_type_id" ref="document_type_id" v-model="selectedUser.document_type_id">
                              <option value="-1">Seleccione el tipo de documento</option>
                              <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Número de documento</label>
                          <input type="text" class="form-control" name="document_number" ref="document_number" placeholder="Ingresa el número de documento" v-model="selectedUser.document_number">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Fecha de nacimiento</label>
                          <input type="date" class="form-control" name="birthdate" ref="birthdate" placeholder="Selecciona la fecha de nacimiento" v-model="selectedUser.birthdate">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Teléfono</label>
                          <input type="text" class="form-control" name="phone" ref="phone" placeholder="Ingresa el número de telefono" v-model="selectedUser.phone">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Celular</label>
                          <input type="text" class="form-control" name="cellphone1" ref="cellphone1" placeholder="Ingresa el número de celular" v-model="selectedUser.cellphone1">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Otro celular</label>
                          <input type="text" class="form-control" name="cellphone2" ref="cellphone2" placeholder="Ingresa otro número de celular" v-model="selectedUser.cellphone2">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>País</label>
                          <select class="select2 form-select" name="country_id" ref="country_id" disabled  v-model="selectedUser.country_id" data-allow-clear="true">
                            <option value="">Selecciona el país</option>
                            <option v-for="country in countries" :value="country.id" :key="country.id" >
                              {{ country.name }}
                            </option>
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Departamento</label>
                          <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" v-model="selectedUser.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                            <option value="">Selecciona el departamento</option>
                            <option v-for="department in departments" :value="department.id" :key="department.id">
                              {{ department.name }}
                            </option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Provincia</label>
                          <select class="select2 form-select" name="province_id" ref="province_id" v-model="selectedUser.province_id" @change="getDistrictsByProvinceId($event.target.value)">
                            <option value="">Selecciona la Ciudad</option>
                            <option v-for="province in provinces" :value="province.id" :key="province.id">
                                {{ province.name }}
                            </option>
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Distrito</label>
                          <select class="select2 form-select" name="district_id" ref="district_id" v-model="selectedUser.district_id">
                            <option value="">Selecciona el distrito</option>
                            <option v-for="district in districts" :value="district.id" :key="district.id">
                                {{ district.name }}
                            </option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                      <div class="form-group">
                          <label>Dirección</label>
                          <input type="text" class="form-control" name="address" ref="address" placeholder="Ingresa el domicilio" v-model="selectedUser.address">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Ocupación</label>
                          <select class="form-control" name="ocupation_id" ref="ocupation_id" v-model="selectedUser.ocupation_id">
                              <option value="-1">Seleccione la ocupación</option>
                              <option v-for="(ocupation, index) in ocupations" v-bind:value="ocupation.id" :key="`ocupation_${index}`">{{ ocupation.name }}</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-12 mb-3">
                      <div class="form-group">
                          <b-form-group label="Confirme por favor si usted es una persona políticamente expuesta.">
                              <b-form-radio v-model="selectedUser.exposed_person" :checked="selectedUser.exposed_person == 1" name="exposed_person" ref="exposed_person" size="lg" value="1">Si, soy</b-form-radio>
                              <b-form-radio v-model="selectedUser.exposed_person" :checked="selectedUser.exposed_person == 0" name="exposed_person" ref="exposed_person" size="lg" value="0">No, no soy</b-form-radio>
                          </b-form-group>
                      </div>
                  </div>
              </div>
              <div class="row" v-if="selectedUser.exposed_person == 1">
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Cargo</label>
                          <input type="text" class="form-control" name="position" ref="position" placeholder="Ingresa el cargo" v-model="selectedUser.position">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                      <div class="form-group">
                          <label>Lugar de trabajo</label>
                          <input type="text" class="form-control" name="workplace" ref="workplace" placeholder="Ingresa el lugar de trabajo" v-model="selectedUser.workplace">
                      </div>
                  </div>
              </div>
            </template>
            <template v-else>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Número de RUC</label>
                          <input type="text" class="form-control" name="ruc" ref="ruc" placeholder="Ingresa el número de RUC" v-model="selectedUser.ruc">
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Razón social</label>
                          <input type="text" class="form-control" name="business_name" ref="business_name" placeholder="Ingresa la razón social" v-model="selectedUser.business_name">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Giro de negocio</label>
                          <input type="text" class="form-control" name="business_turn" ref="business_turn" placeholder="Ingresa el giro de negocio" v-model="selectedUser.business_turn">
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Dirección</label>
                          <input type="text" class="form-control" name="fiscal_address" ref="fiscal_address" placeholder="Ingresa la dirección fiscal" v-model="selectedUser.fiscal_address">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>País</label>
                          <select class="select2 form-select" name="country_id" ref="country_id" disabled  v-model="selectedUser.country_id" data-allow-clear="true">
                            <option value="">Selecciona el país</option>
                            <option v-for="country in countries" :value="country.id" :key="country.id" >
                              {{ country.name }}
                            </option>
                          </select>
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Departamento</label>
                          <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" v-model="selectedUser.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                            <option value="">Selecciona el departamento</option>
                            <option v-for="department in departments" :value="department.id" :key="department.id">
                              {{ department.name }}
                            </option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Provincia</label>
                          <select class="select2 form-select" name="province_id" ref="province_id" v-model="selectedUser.province_id" @change="getDistrictsByProvinceId($event.target.value)">
                            <option value="">Selecciona la Ciudad</option>
                            <option v-for="province in provinces" :value="province.id" :key="province.id">
                                {{ province.name }}
                            </option>
                          </select>
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Distrito</label>
                          <select class="select2 form-select" name="district_id" ref="district_id" v-model="selectedUser.district_id">
                            <option value="">Selecciona el distrito</option>
                            <option v-for="district in districts" :value="district.id" :key="district.id">
                                {{ district.name }}
                            </option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Correo electrónico</label>
                          <input type="text" class="form-control" name="email" ref="email" placeholder="Ingresa el correo electrónico" v-model="selectedUser.email">
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Telefono</label>
                          <input type="text" class="form-control" name="phone" ref="phone" placeholder="Ingresa el número de teléfono" v-model="selectedUser.phone">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Nombre/s del representante legal</label>
                          <input type="text" class="form-control" name="name_lr" ref="name_lr" placeholder="Ingresa el nombre/s" v-model="selectedUser.name_lr">
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Apellido/s del representante legal</label>
                          <input type="text" class="form-control" name="surname_lr" ref="surname_lr" placeholder="Ingresa el apellido/s" v-model="selectedUser.surname_lr">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Tipo de documento del representante legal</label>
                          <select class="form-control" name="document_type_lr_id" ref="document_type_lr_id" v-model="selectedUser.document_type_lr_id">
                              <option value="-1">Seleccione el tipo de documento</option>
                              <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Número de documento del representante legal</label>
                          <input type="text" class="form-control" name="document_number_lr" ref="document_number_lr" placeholder="Ingresa el número de documento" v-model="selectedUser.document_number_lr">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Nombre/s de contacto</label>
                          <input type="text" class="form-control" name="name_c" ref="name_c" placeholder="Ingresa el nombre/s" v-model="selectedUser.name_c">
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Apellido/s de contacto</label>
                          <input type="text" class="form-control" name="surname_c" ref="surname_c" placeholder="Ingresa el apellido/s" v-model="selectedUser.surname_c">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Tipo de documento de contacto</label>
                          <select class="form-control" name="document_type_c_id" ref="document_type_c_id" v-model="selectedUser.document_type_c_id">
                              <option value="-1">Seleccione el tipo de documento</option>
                              <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-xl-6 mb-3">
                      <div class="form-group">
                          <label>Número de documento de contacto</label>
                          <input type="text" class="form-control" name="document_number_c" ref="document_number_c" placeholder="Ingresa el número de documento" v-model="selectedUser.document_number_c">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-12 mb-3">
                      <div class="form-group">
                          <label>Teléfono de contacto</label>
                          <input type="text" class="form-control" name="phone_c" ref="phone_c" placeholder="Ingresa el número de teléfono" v-model="selectedUser.phone_c">
                      </div>
                  </div>
              </div>
            </template>

            <div class="col-12 d-flex justify-content-between mt-3 ">
              <button class="btn btn-label-secondary btn-backprev align-items-baseline " @click="closeModal()">
              <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
              </button>
              <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" id="sendPrefxx" @click="updateAccount()">
                <span class="align-middle d-sm-inline-block d-none" >Confirmar!</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<style lang="scss">
	@import "@/assets/libs/select2/select2.scss";
</style>
<script>
	import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
	import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
	import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
	import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
	import AutoFocus from "@/assets/plugins/formvalidation/dist/es6/plugins/AutoFocus";
  import { ACCOUNTS_PERSONAL_DELETE, ACCOUNTS_PERSONAL_RESTORE, ACCOUNTS_PERSONAL_UPDATE,  ACCOUNTS_COMPANY_DELETE, ACCOUNTS_COMPANY_UPDATE } from "@/core/services/store/account.module";
  import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
  import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
  import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
  import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
  import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
  import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";

  export default {
    name:'editUserForm',
		props:['selectedUser'],
    data() {
        return {
          mainAlert: false,
          mainAlertVariant: "",
          mainAlertMessage: "",
          viewAtReady:false,
          viewModals: false,
          formType: 0,
          select2ShareProject: '',
          documentTypes: [],
          ocupations: [],
          countries: [],
          departments: [],
          provinces: [],
          districts: [],
          buttonRestoreAccount: false,
          restoreAcc: {},
          tableOperationLoader:false,
          statusLabel: {
            '-1':	{ title: 'Documentos rechazado', class: ' bg-label-danger' },
            '0':	{ title: 'Documentos no verificado', class: ' bg-label-warning' },
            '1':	{ title: 'Documentos verificado', class: ' bg-label-success' },
          }
        }
    },
    mounted() {
      this.getDocumentTypes();
      this.getOcupations();
      this.getCountries();
      this.getDepartments();

			this.getProvincesByDepartmentId(this.selectedUser.department_id);
			this.getDistrictsByProvinceId(this.selectedUser.province_id);
      this.validateForm()

    },
    methods: {
      updateAccount () {
        // Prevent modal hide
        // 
        this.validateForm().validate().then((data) => {
        
          if(data !='Invalid'){
            let action = this.selectedUser.type === 0 ? ACCOUNTS_PERSONAL_UPDATE : ACCOUNTS_COMPANY_UPDATE
            this.$store
              .dispatch(action, this.selectedUser)
              .then((data) => {
                  if (data.code != 200){
                      this.showMainAlert('danger', data.error)
                      return;
                  }
  
                  // let AllAccountIndex = this.AllAccount.findIndex(account => account.globalkey == data.data.globalkey);
  
                  // if (AllAccountIndex > -1) {
                  //   this.AllAccount[AllAccountIndex].deleted_at = data.data.deleted_at;
                  // }
                
                  
                  
                  
                  setTimeout(() => {
                    this.closeModal(1)
                    
                    
                    return;
                  }, 500);
                  
              })
              .catch((err) => {
                this.showMainAlert('danger', err)
                return;
              });
          }
          document.getElementById('sendPrefxx').disabled = true
        })
      },
      showMainAlert(variant, message) {
				document.getElementById('form-edit-user-data').scrollTo(0,0);
				this.mainAlertVariant = variant;
				this.mainAlertMessage = message;
				this.mainAlert = true;
      },
      getDocumentTypes () {
        this.$store
          .dispatch(DOCUMENT_TYPES_GET_ALL)
          .then((data) => {
              if (data.code != 200){
                  this.showMainAlert('danger', 'Error desconocido al obtener todos tipos de documento.')
                  return;
              }

              this.documentTypes = data.data
          })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
      },
      getOcupations () {
        this.$store
          .dispatch(OCUPATIONS_GET_ALL)
          .then((data) => {
              if (data.code != 200){
                  this.showMainAlert('danger', 'Error desconocido al obtener todas las ocupaciones.')
                  return;
              }

              this.ocupations = data.data
          })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
      },
      getCountries () {
        this.$store
          .dispatch(COUNTRIES_GET_ALL)
          .then((data) => {
            if (data.code != 200){
              this.showDangerAlert('Error desconocido al obtener todos los países.')
              return;
            }
            this.countries = data.data;
          })
          .catch((err) => {
            this.showDangerAlert(err)
          });
      },
      getDepartments () {
        this.$store
          .dispatch(DEPARTMENTS_GET_ALL)
          .then((data) => {
            if (data.code != 200){
              this.showDangerAlert('Error desconocido al obtener todos los departamentos.')
              return;
            }

            this.departments = data.data;
          })
          .catch((err) => {
            this.showDangerAlert(err)
          });
      },
      getProvincesByDepartmentId (departmentId) {
        this.$store
          .dispatch(PROVINCES_GET_ALL_BY_DEPARTMENT_ID, departmentId)
          .then((data) => {
              if (data.code != 200){
                  this.showDangerAlert('Error desconocido al obtener todas las provincias.')
                  return;
              }

              this.provinces = data.data;
          })
          .catch((err) => {
              this.showDangerAlert(err)
          });
      },
      getDistrictsByProvinceId (provinceId) {
        this.$store
          .dispatch(DISTRICTS_GET_ALL_BY_PROVINCE_ID, provinceId)
          .then((data) => {
              if (data.code != 200){
                  this.showDangerAlert('Error desconocido al obtener todos los distritos.')
                  return;
              }

              this.districts = data.data;
          })
          .catch((err) => {
              this.showDangerAlert(err)
          });
      },
			validateForm(){
        let fieldsToValidate = {};
        if (this.selectedUser.type===1) {
          fieldsToValidate = {
            // * Validate the fields here based on your requirements
            ruc: {
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
            country_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Pais'
                },
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

          }
        }else{
          fieldsToValidate = {
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
          
        }
        
				const FormValidation2 = formValidation(document.querySelector('#form-data'), {
          fields: fieldsToValidate,
          plugins: {
            trigger: new Trigger(),
            bootstrap: new Bootstrap({
              // Use this for enabling/changing valid/invalid class
              // eleInvalidClass: '',
              eleValidClass: '',
              // rowSelector: '.valid-item'
            }),
            autoFocus: new AutoFocus(),
            submitButton: new SubmitButton()
          }
        }).on('core.form.valid', () => {
          document.getElementById('sendPrefxx').disabled = false
        }).on('core.field.valid', () => {
          document.getElementById('sendPrefxx').disabled = false
        }).on('core.form.invalid', () => {
          document.getElementById('sendPrefxx').disabled = true
        }).on('core.field.invalid', () => {
          document.getElementById('sendPrefxx').disabled = true
        });
        return FormValidation2;
			},
			closeModal(id=0){
				this.$emit('hiddeModal',id)
			}

    },
  };
</script>