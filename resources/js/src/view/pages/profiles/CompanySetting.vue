<template>
    <div class="card-body">
      <div class="mt-3 d-flex justify-content-center" v-if="mainAlert">
        <div class="col-sm-12">
          <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
        </div>
      </div>
      <form action="" id="company_data">
        <div class="row mb-3" >
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="dealTitle">Tipo de Documento</label>
            <select class="selectpicker w-100 select2 form-select" disabled  v-model="selectedDocument" name="document_type_id" ref="document_type_id" @change="queryPersonByRuc($refs.document_number.value)">
              <option value="">Selecciona el tipo de documento</option>
              <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                  {{ documentType.name }}
              </option>
            </select>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="dealCode">Número de documento</label>
            <input
            type="text"
            id="numeral-mask"
            class="form-control numeral-mask"
            name="ruc_card" 
            ref="ruc"
            placeholder="Ingresa el número de documento" 
            v-model="selectedAccount.ruc"
            @change="queryPersonByRuc($event.target.value)"
          />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
              
              <label class="form-label" for="basic-icon-default-fullname" >Razon Social</label>
              <input
                type="text"
                class="form-control "
                name="business_name" 
                ref="business_name"
                placeholder="CAMBIOS C.A"
                disabled
                v-model="selectedAccount.business_name"
              />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label">Giro de negocio</label>
            <div class="">
              <input
              type="text"
              class="form-control"
              placeholder="Empresa productora de alimentos"
              name="business_turn" 
              ref="business_turn"
              v-model="selectedAccount.business_turn"
            />
            </div>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="multicol-phone">Teléfono</label>
            <input
            type="text"
            class="form-control phone-mask"
            placeholder="658 799 8941"
            aria-label="658 799 8941"
            name="phone" 
            ref="phone" 
            v-model="selectedAccount.phone"
          />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="basic-default-phone">Correo electrónico</label>
            <input
              type="email"
              class="form-control"
              placeholder="remesas@gmail.com"
              name="email" ref="email" 
              v-model="selectedAccount.email"
            />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Pais</label>
              <select class="select2 form-select" name="country_id" ref="country_id" disabled data-allow-clear="true" v-model="selectedAccount.country_id">
                <option value="">Selecciona el país</option>
                <option v-for="country in countries" :value="country.id" :key="country.id">
                  {{ country.name }}
                </option>
              </select>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Departamento</label>
              <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" v-model="selectedAccount.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                <option value="">Selecciona el departamento</option>
                <option v-for="department in departments" :value="department.id" :key="department.id">
                  {{ department.name }}
                </option>
              </select>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Ciudad</label>
            <select class="select2 form-select" name="province_id" ref="province_id" v-model="selectedAccount.province_id" @change="getDistrictsByProvinceId($event.target.value)">
              <option value="">Selecciona la Ciudad</option>
              <option v-for="province in provinces" :value="province.id" :key="province.id">
                  {{ province.name }}
              </option>
          </select>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Distrito</label>
            <select class="select2 form-select" name="district_id" ref="district_id" v-model="selectedAccount.district_id">
                <option value="">Selecciona el distrito</option>
                <option v-for="district in districts" :value="district.id" :key="district.id">
                    {{ district.name }}
                </option>
            </select>
          </div>
          <div class="mb-3 col-sm-12 valid-item">
            <label class="form-label" for="address-input">Dirección</label>
            <input
              type="text"
              class="form-control"
              id="address-input"
              placeholder="Ingrese su dirección"
              name="fiscal_address" 
              ref="fiscal_address"
              v-model="selectedAccount.fiscal_address"
            />
          </div>
          <hr class="mt-2 mb-4" />
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" >Tipo de Documento del representante legal</label>
            <select class="selectpicker w-100 select2 form-select" name="document_type_lr_id" ref="document_type_lr_id" v-model="selectedAccount.document_type_lr_id" @change="queryPersonByDocumentNumber(selectedAccount.document_number_lr,'lr')">
              <option value="">Selecciona el tipo de documento</option>
              <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                  {{ documentType.name }}
              </option>
            </select>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="dealCode">Número de documento de identidad del representante legal</label>
            <input
            type="text"
            class="form-control numeral-mask"
            name="document_number_lr" 
            ref="document_number_lr" 
            placeholder="Ingresa el número de documento"
            v-model="selectedAccount.document_number_lr"
            @change="queryPersonByDocumentNumber($event.target.value,'lr')"
          />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer nombre del represante legal</label>
            <input
              type="text"
              class="form-control "
              name="name_lr" ref="name_lr"
              placeholder="Pablo"
              disabled
              :value="selectedAccount.fname_lr"
              @input="checkInputText($event)"
            />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label">Segundo nombre del represante legal</label>
            <div class="">
              <input
              type="text"
              class="form-control "
              placeholder="Daniel"
              name="sname_lr" ref="sname_lr" 
              disabled
              @input="checkInputText($event)"
              :value="selectedAccount.sname_lr"
            />
            </div>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="basic-icon-default-fullname" >Primer Apellido del represante legal</label>
            <input
                type="text"
                class="form-control "
  
                placeholder="Perez"
                name="surname_lr" ref="surname_lr"
                :value="selectedAccount.fsurname_lr"
                disabled
                @input="checkInputText($event)"   
            />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="basic-icon-default-fullname" >Segundo Apellido del represante legal</label>
            <input
              type="text"
              class="form-control "
              placeholder="Flores"
              name="ssurname_lr" ref="ssurname_lr"
              disabled
              :value="selectedAccount.ssurname_lr"
              @input="checkInputText($event)"   
            />
          </div>
          <hr class="mt-2 mb-4" />
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" >Tipo de Documento del contacto</label>
            <select class="selectpicker w-100 select2 form-select" name="document_type_c_id" ref="document_type_c_id" v-model="selectedAccount.document_type_c_id" @change="queryPersonByDocumentNumber(selectedAccount.document_number_c,'c')">
              <option value="">Selecciona el tipo de documento</option>
              <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                  {{ documentType.name }}
              </option>
            </select>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="dealCode">Número de documento de identidad del contacto</label>
            <input
            type="text"
            class="form-control numeral-mask"
            name="document_number_c" 
            ref="document_number_c"
            placeholder="Ingresa el número de documento"
            v-model="selectedAccount.document_number_c"
            @change="queryPersonByDocumentNumber($event.target.value,'c')"
          />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer nombre del contacto</label>
            <input
              type="text"
              class="form-control "
              name="name_c" ref="name_c"
              placeholder="Pablo"
              disabled
              @input="checkInputText($event)"
              v-model="selectedAccount.fname_c"
            />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label">Segundo nombre del contacto</label>
            <div class="">
              <input
              type="text"
              class="form-control "
              placeholder="Daniel"
              name="sname_c" ref="sname_c"
              disabled
              @input="checkInputText($event)"
              v-model="selectedAccount.sname_c"
            />
            </div>
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer Apellido del contacto</label>
            <input
                type="text"
                class="form-control "
  
                placeholder="Perez"
                name="surname_c" ref="surname_c"
                disabled
                @input="checkInputText($event)"  
                v-model="selectedAccount.fsurname_c" 
            />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Segundo Apellido del contacto</label>
            <input
              type="text"
              class="form-control "
              placeholder="Flores"
              name="ssurname_c" ref="ssurname_c"
              disabled
              v-model="selectedAccount.ssurname_c" 
              @input="checkInputText($event)"   
            />
          </div>
          <div class="mb-3 col-sm-6 valid-item">
            <label class="form-label" for="multicol-phone">Teléfono</label>
            <input
              type="text"
  
              class="form-control phone-mask"
              placeholder="658 799 8941"
              aria-label="658 799 8941"
              name="phone_c" 
              ref="phone_c" 
              v-model="selectedAccount.phone_c"
              @input="checkInputPhone($event)"  
  
            />
          </div>
            
        </div>
        
      </form>
      <div class="mt-2">
        <button id="update-button" onsubmit="false" class="btn btn-primary me-2 text-white" @click="updateToAccount($event)" >Guardar cambios</button>
      </div>
    </div>
  </template>
  <style lang="scss">
    @import "@/assets/sass/pages/page-auth.scss";
  </style>
  <script>
    import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
    import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
    import { QUERY_PERSON_BY_DNI, QUERY_COMPANY_BY_RUC } from "@/core/services/store/peru_consult.module";
    import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
    import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
    import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
    import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
    import Cleave from 'cleave.js';
    import flatpickr from "flatpickr";
    export default {
      name: 'CompanySetting',
      props: ['selectedAccountP'],
      data() {
        return {
          searchDocument: true,
          selectedDocument:'4',
          mainAlert: false,
          mainAlertVariant: "",
          mainAlertMessage: "",
          savedAlert: false,
          savedAlertVariant: '',
          savedAlertMessage: '',
          selectedAccount: '',
          documentTypes: [],
          ocupations: [],
          countries: [],
          departments: [],
          provinces: [],
          districts: [],
  
        }
      },
      // watch: {
      //   'selectedAccountP': {
      //       handler: function (after, before) {
      //         this.selectedAccount = this.selectedAccountP;
      //         this.formatName()
      //       },
      //       deep: true
      //   }
      // },
      mounted() {
        setTimeout(() => {
          this.formatName()
          this.getProvincesByDepartmentId(this.selectedAccount.department_id);
          this.getDistrictsByProvinceId(this.selectedAccount.province_id);
          flatpickr("#flatpickr-date", {
            locale: {
              firstDayOfWeek: 1,
              weekdays: {
                shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],         
              }, 
              months: {
                shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
              },
            },  
          });
        }, 500);
          
      },
      methods: {
        checkInputText(e) {
          let input = e.srcElement;
          // let valt = input.value.match(/[0-9\^*@!"#$%&/()=?¡!¿'\\]/gi)
          // console.log(valt)
          // if (valt != null) {
          //   this.showMainAlert('danger','Estas intentando introducir un caracter invalido!');
          //   input.value = input.value.replace(/[^a-zA-Z ]/, '');

          // } 
          input.value = input.value.replace(/[^a-zA-ZÀ-ÿ .,]/, '');
        }, 
        checkInputPhone(e) {
          let input = e.srcElement;
          input.value = input.value.replace(/[^0-9]/, '');
        }, 
        formatName() {

          this.selectedAccount.fname_lr = this.selectedAccount.name_lr.split(' ')[0];
          this.selectedAccount.sname_lr = this.selectedAccount.name_lr.split(' ')[1] == undefined 
          ? ''
          : this.selectedAccount.name_lr.split(' ')[1];
          this.selectedAccount.fsurname_lr = this.selectedAccount.surname_lr.split(' ')[0];
          this.selectedAccount.ssurname_lr =  this.selectedAccount.surname_lr.split(' ')[1] == undefined 
          ? ''
          : this.selectedAccount.surname_lr.split(' ')[1];



          this.selectedAccount.fname_c = this.selectedAccount.name_c.split(' ')[0];
          this.selectedAccount.sname_c = this.selectedAccount.name_c.split(' ')[1] == undefined 
          ? ''
          : this.selectedAccount.name_c.split(' ')[1];
          this.selectedAccount.fsurname_c = this.selectedAccount.surname_c.split(' ')[0];
          this.selectedAccount.ssurname_c =  this.selectedAccount.surname_c.split(' ')[1] == undefined 
          ? ''
          : this.selectedAccount.surname_c.split(' ')[1];

        },
        checkInputDirection(e) {
          let input = e.srcElement;
          input.value = input.value.replace(/[^a-zA-Z0-9#-.+*/@ ]/, '');
        }, 
        phoneFormat(){
          const editAccount = document.querySelectorAll('.phone-mask');
          if (editAccount) {
            editAccount.forEach(element => {
              new Cleave(element, {
                blocks: [3, 3, 4],
              });
            });
          }  
        },
        updateToAccount(e) {
          this.selectedAccount.name_lr= this.selectedAccount.fname_lr +' '+ this.selectedAccount.sname_lr
          this.selectedAccount.surname_lr = this.selectedAccount.fsurname_lr + ' ' + this.selectedAccount.ssurname_lr
          this.selectedAccount.name_c= this.selectedAccount.fname_c +' '+ this.selectedAccount.sname_c
          this.selectedAccount.surname_c = this.selectedAccount.fsurname_c + ' ' + this.selectedAccount.ssurname_c
          
          this.$emit("click",{data:this.selectedAccount, event: e.target});
        },
        queryPersonByRuc (documentNumber) {
          if (documentNumber != '') {
            this.loadDataRuc()
              this.$store
              .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
              .then((data) => {
                if (data.code != 200){
                  
                  this.showMainAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
                  this.resetDataRuc()
                    return;
                }
                const personData = JSON.parse(data.data);
                let name = personData.razonSocial;
                this.selectedAccount.business_name = name;
                this.$refs.business_name.value = name;
                
              })
              .catch((err) => {
                this.showMainAlert('danger','Hemos tenido un error, vuelve a intentar')
                this.resetDataRuc()
              });
          
            
          } else {
            this.resetDataRuc()
          }
        },
        queryPersonByDocumentNumber (documentNumber, block) {
          let documentType = parseInt(this.selectedAccount['document_type_' + block + '_id']);
          
          if ((documentType == 1 || documentType == 3) && documentNumber != '') {
            this.loadData(block)
            if (documentType == 1) {
              
              this.$store
              .dispatch(QUERY_PERSON_BY_DNI, documentNumber)
              .then((data) => {
                if (data.code != 200){
                  this.showMainAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
                  this.resetData(block)
                    return;
                }
                const personData = JSON.parse(data.data);
                this.selectedAccount['fname_' + block] = personData.nombres.split(' ')[0];
                this.selectedAccount['sname_' + block] = personData.nombres.split(' ')[1] ? personData.nombres.split(' ')[1]:' ';
                this.selectedAccount['fsurname_' + block] = personData.apellidoPaterno ? personData.apellidoPaterno : ' ';
                this.selectedAccount['ssurname_' + block] = personData.apellidoMaterno ? personData.apellidoMaterno : ' ';

                this.$refs['name_' + block].value = this.selectedAccount['fname_' + block];
                this.$refs['sname_' + block].value = this.selectedAccount['sname_' + block]
                this.$refs['surname_' + block].value = this.selectedAccount['fsurname_' + block]
                this.$refs['ssurname_' + block].value = this.selectedAccount['ssurname_' + block]

              })
              .catch((err) => {
                this.showMainAlert('danger','Hemos tenido un error, vuelve a intentar')
                this.resetData(block)
              });
            } else {
              this.$store
              .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
              .then((data) => {
                if (data.code != 200){
                  this.showMainAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
                  this.resetData(block)
                    return;
                }
                const personData = JSON.parse(data.data);
                this.selectedAccount['fname_' + block] = personData.razonSocial.split(' ')[2];
                this.selectedAccount['sname_' + block] = personData.razonSocial.split(' ')[personData.razonSocial.split(' ').length - 1];
                this.selectedAccount['fsurname_' + block] = personData.razonSocial.split(' ')[0];
                this.selectedAccount['ssurname_' + block] = personData.razonSocial.split(' ')[1];

                this.$refs['name_' + block].value = this.selectedAccount['fname_' + block];
                this.$refs['sname_' + block].value = this.selectedAccount['sname_' + block]
                this.$refs['surname_' + block].value = this.selectedAccount['fsurname_' + block]
                this.$refs['ssurname_' + block].value = this.selectedAccount['ssurname_' + block]
              })
              .catch((err) => {
                this.showMainAlert('danger','Hemos tenido un error, vuelve a intentar')
                this.resetData(block)
              });
            }
            
          } else {
            this.resetData(block)
          }
      
        },
        resetDataRuc(){
          this.$refs.business_name.disabled = false
          this.$refs.business_name.value ='';
          this.selectedAccount.business_name = '';
        },
        loadDataRuc(){
          this.$refs.business_name.disabled = true
          this.$refs.business_name.value = 'Cargando...';
        },
        resetData(block){
          this.$refs['name_'+ block].disabled = false
          this.$refs['sname_'+ block].disabled = false
          this.$refs['surname_'+ block].disabled = false
          this.$refs['ssurname_'+ block].disabled = false
          this.$refs['name_'+ block].value = ''
          this.$refs['sname_'+ block].value = ''
          this.$refs['surname_'+ block].value = ''
          this.$refs['ssurname_'+ block].value = ''
        },
        loadData(block) {
          this.$refs['name_'+ block].value = 'Cargando...';
          this.$refs['sname_'+ block].value = 'Cargando...';
          this.$refs['surname_'+ block].value = 'Cargando...';
          this.$refs['ssurname_'+ block].value = 'Cargando...';
          this.$refs['name_'+ block].disabled = true
          this.$refs['sname_'+ block].disabled = true
          this.$refs['surname_'+ block].disabled = true
          this.$refs['ssurname_'+ block].disabled = true
          
        },
        getCountries () {
          this.$store
            .dispatch(COUNTRIES_GET_ALL)
            .then((data) => {
              if (data.code != 200){
                this.showMainAlert('danger','Error desconocido al obtener todos los países.')
                return;
              }
              this.countries = data.data;
            })
            .catch((err) => {
              this.showMainAlert('danger',err)
            });
        },
        getDepartments () {
          this.$store
            .dispatch(DEPARTMENTS_GET_ALL)
            .then((data) => {
              if (data.code != 200){
                this.showMainAlert('danger','Error desconocido al obtener todos los departamentos.')
                return;
              }
  
              this.departments = data.data;
            })
            .catch((err) => {
              this.showMainAlert('danger',err)
            });
        },
        getProvincesByDepartmentId (departmentId) {
          this.$store
            .dispatch(PROVINCES_GET_ALL_BY_DEPARTMENT_ID, departmentId)
            .then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger','Error desconocido al obtener todas las provincias.')
                    return;
                }
  
                this.provinces = data.data;
            })
            .catch((err) => {
                this.showMainAlert('danger',err)
            });
        },
        getDistrictsByProvinceId (provinceId) {
          this.$store
            .dispatch(DISTRICTS_GET_ALL_BY_PROVINCE_ID, provinceId)
            .then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger','Error desconocido al obtener todos los distritos.')
                    return;
                }
  
                this.districts = data.data;
            })
            .catch((err) => {
                this.showMainAlert('danger',err)
            });
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
  
        showSavedAlert(variant, message) {
            this.savedAlertVariant = variant;
            this.savedAlertMessage = message;
            this.savedAlert = true;
        },
  
        closeModal(modalId){
            this.$root.$emit('bv::hide::modal', modalId);
        },
  
        showModal(modalId){
            this.$root.$emit('bv::show::modal', modalId);
        },
  
        showMainAlert(variant, message) {
            this.mainAlertVariant = variant;
            this.mainAlertMessage = message;
            this.mainAlert = true;
            window.scrollTo(0,0);
        },
      },
      created() {
        this.selectedAccount = this.selectedAccountP;
        this.getCountries();
        this.getDepartments();
        
        this.getDocumentTypes();
        this.getOcupations();
      },
    };
  
  </script>
  