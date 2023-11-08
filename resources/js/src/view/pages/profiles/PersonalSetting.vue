<template>
  <div class="card-body">
    <div class="mt-3 d-flex justify-content-center" v-if="mainAlert">
      <div class="col-lg-11 col-md-11 col-sm-12">
        <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
      </div>
    </div>
    <form action="" id="company_data">
      <div class="row mb-3">
        <div class="col-sm-6 valid-item">
          <label class="form-label" for="dealTitle">Tipo de Documento</label>
          <select class="selectpicker w-100 select2 form-select" 
          name="document_type_id" ref="document_type_id" v-model="selectedAccount.document_type_id"  @change="queryPersonByDocumentNumber($refs.document_number.value)">
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
            name="document_number" ref="document_number" 
            placeholder="Ingresa el número de documento" 
            v-model="selectedAccount.document_number"
            @change="queryPersonByDocumentNumber($event.target.value)"
          />
        </div>
        <div class="mb-3 col-md-6 valid-item">
          <label for="firstName" class="form-label">Primer nombre</label>
          <input
            class="form-control"
            type="text"
            id="firstName"
            name="name" ref="fname"  disabled
            autofocus
            v-model="selectedAccount.fname"
          />
        </div>
        <div class="mb-3 col-md-6 valid-item">
          <label for="SecondName" class="form-label">Segundo nombre</label>
          <input class="form-control" type="text" name="sname" ref="sname"  disabled  v-model="selectedAccount.sname" id="SecondName"  />
        </div>
        <div class="mb-3 col-md-6 valid-item">
          <label for="lastName" class="form-label">Primer apellido</label>
          <input class="form-control" type="text"  name="surname" ref="fsurname" disabled  v-model="selectedAccount.fsurname"   />
        </div>
        <div class="mb-3 col-md-6 valid-item">
          <label for="lastName" class="form-label">Segundo apellido</label>
          <input class="form-control" type="text"  name="ssurname" ref="ssurname" disabled   v-model="selectedAccount.ssurname"   />
        </div>
        <div class="mb-3 col-sm-6 valid-item">
          <label for="dealDuration" class="form-label">Fecha de nacimiento</label>
          <input 
            type="text" class="form-control" 
            placeholder="YYYY-MM-DD" id="flatpickr-date" 
            name="birthdate" ref="birthdate" v-model="selectedAccount.birthdate"  
          />
        </div>
        <div class="mb-3 col-sm-6 valid-item">
          <label class="form-label" for="multicol-phone">Teléfono</label>
          <input
            type="text"
            class="form-control phone-mask"
            placeholder="658 799 8941"
            aria-label="658 799 8941"
            name="phone" ref="phone"
            v-model="selectedAccount.phone"
          />
        </div>
        <div class="mb-3 col-sm-6 valid-item">
          <label class="form-label" for="basic-default-phone">Número de celular</label>
          <input
            type="text"
            class="form-control phone-mask"
            placeholder="658 799 8941"
            aria-label="658 799 8941"
            name="cellphone1" ref="cellphone1" 
            v-model="selectedAccount.cellphone1"
          />
        </div>

        <div class="mb-3 col-sm-6 valid-item">
          <label class="form-label" for="basic-default-phone">Otro número de celular</label>
          <input
            type="text"
            class="form-control phone-mask"
            placeholder="658 799 8941"
            aria-label="658 799 8941"
            name="cellphone2" ref="cellphone2"
            v-model="selectedAccount.cellphone2" 
          />
        </div>
        <div class="mb-3 col-sm-6 valid-item">
          <label class="form-label" for="multicol-country">Pais</label>
            <select class="select2 form-select" name="country_id" ref="country_id" disabled  v-model="selectedAccount.country_id" data-allow-clear="true">
              <option value="">Selecciona el país</option>
              <option v-for="country in countries" :value="country.id" :key="country.id" >
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
            name="address" 
            ref="address"
            v-model="selectedAccount.address"
          />
        </div>
        <div class="mb-3 col-sm-6 valid-item">
          <label class="form-label" for="multicol-country">Ocupación</label>
            <select class="select2 form-select" name="ocupation_id" ref="ocupation_id" v-model="selectedAccount.ocupation_id">
              <option value="">Selecciona la ocupación</option>
              <option v-for="ocupation in ocupations" :value="ocupation.id" :key="ocupation.id">
                  {{ ocupation.name }}
              </option>
            </select>
        </div>
        <div class="mb-3 col-sm-12 valid-item">
          <label class="form-label">Confirme si es usted una persona politicamente expuesta</label>
          <div class="col mt-2">
            <div class="form-check form-check-inline">
              <input
                class="form-check-input"
                type="radio"
                name="exposed_person" 
                ref="exposed_person" 
                value="1"
                v-model="selectedAccount.exposed_person"
                :checked="selectedAccount.exposed_person == 1"
              />
              <label class="form-check-label" for="collapsible-address-type-home"
                >SI SOY</label
              >
            </div>
            <div class="form-check form-check-inline">
              <input
              class="form-check-input"
              type="radio"
              checked=""
              name="exposed_person" 
              ref="exposed_person" 
              value="0"
              v-model="selectedAccount.exposed_person"
              :checked="selectedAccount.exposed_person == 0" 
              />
              <label class="form-check-label" for="collapsible-address-type-office">
                NO SOY
              </label>
            </div>
          </div>
        </div>
        <template v-if="selectedAccount.exposed_person==1">
            <div class="mb-3 col-sm-6 valid-item">
              <label class="form-label" for="basic-icon-default-fullname" >Cargo</label>
                <input
                  type="text"
                  class="form-control"

                  placeholder="Ingrese su cargo"
                  name="position" ref="position"
                  v-model="selectedAccount.position"
                />
            </div>
            <div class="mb-3 col-sm-6 valid-item">
              <label class="form-label" for="basic-icon-default-fullname" >Lugar de trabajo</label>
              <input
                type="text"
                class="form-control"
                name="workplace" ref="workplace"
                placeholder="Ingrese su lugar de trabajo"
                v-model="selectedAccount.workplace"
              />
            </div>
          </template>
      </div>
    </form>
    <div class="mt-2">
      <button id="update-button"  onsubmit="false" class="btn btn-primary me-2 text-white" @click="updateToAccount($event)" >Guardar cambios</button>
    </div>

  </div>
</template>
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
    name: 'PersonalSetting',
    props: ['selectedAccountP'],
    data() {
      return {
        mainAlert: false,
        mainAlertVariant: "",
        mainAlertMessage: "",
        selectedAccount :'',
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
    //         this.selectedAccount = this.selectedAccountP
    //         this.formatName();
    //       },
    //       deep: true
    //   }
    // },
    mounted() {
      setTimeout(() => {
        this.formatName()
        this.getProvincesByDepartmentId(this.selectedAccount.department_id);
        this.getDistrictsByProvinceId(this.selectedAccount.province_id);
        this.phoneFormat()
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
      }, 200);
        
    },
    methods: {
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
      formatName() {
          this.selectedAccount.fname = this.selectedAccount.name.split(' ')[0];
          this.selectedAccount.sname = this.selectedAccount.name.split(' ')[1] == undefined 
          ? ' '
          : this.selectedAccount.name.split(' ')[1];

          this.selectedAccount.fsurname = this.selectedAccount.surname.split(' ')[0].length <= 3 
          ? this.selectedAccount.surname.split(' ')[0] +' '+this.selectedAccount.surname.split(' ')[1]
          : this.selectedAccount.surname.split(' ')[0];

          this.selectedAccount.ssurname =  this.selectedAccount.surname.slice(this.selectedAccount.fsurname.length);

      },
      updateToAccount(e) {
        this.selectedAccount.name = this.selectedAccount.fname+' '+this.selectedAccount.sname
        this.selectedAccount.surname = this.selectedAccount.fsurname + ' ' + this.selectedAccount.ssurname
        this.$emit("click",{data:this.selectedAccount, event: e.target});
      },
      queryPersonByDocumentNumber (documentNumber) {
        let documentType = this.$refs.document_type_id.value;
        if ((documentType == 1 || documentType == 3) && documentNumber != '') {
          this.loadData()
          if (documentType == 1) {
            
            this.$store
            .dispatch(QUERY_PERSON_BY_DNI, documentNumber)
            .then((data) => {
              if (data.code != 200){
                this.showMainAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
                console.log('afff')
                this.resetData()
                  return;
              }
              
              const personData = JSON.parse(data.data);
              console.log(personData)

                    
              
              this.selectedAccount.fname = personData.nombres.split(' ')[0];
              this.selectedAccount.sname = personData.nombres.split(' ')[1] ? personData.nombres.split(' ')[1]:' ';
              this.selectedAccount.fsurname = personData.apellidoPaterno ? personData.apellidoPaterno : ' ';
              this.selectedAccount.ssurname = personData.apellidoMaterno ? personData.apellidoMaterno : ' ';

              this.$refs.fname.value = this.selectedAccount.fname;
              this.$refs.sname.value = this.selectedAccount.sname
              this.$refs.fsurname.value = this.selectedAccount.fsurname
              this.$refs.ssurname.value = this.selectedAccount.ssurname


             console.log(this.$refs) 
            })
            .catch((err) => {
              console.log('afff')
              this.showMainAlert('danger',err)
              this.resetData()
              
            });
          } else {
            this.$store
            .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
            .then((data) => {
              if (data.code != 200){
                this.showMainAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
                this.resetData()
                  return;
              }
              const personData = JSON.parse(data.data);
              this.selectedAccount.fname = personData.razonSocial.split(' ')[2];
              this.selectedAccount.sname = personData.razonSocial.split(' ')[personData.razonSocial.split(' ').length - 1] ? personData.razonSocial.split(' ')[personData.razonSocial.split(' ').length - 1]:' '; ;
              this.selectedAccount.fsurname  = personData.razonSocial.split(' ')[0];
              this.selectedAccount.ssurname = personData.razonSocial.split(' ')[1];

              this.$refs.fname.value = this.selectedAccount.fname;
              this.$refs.sname.value = this.selectedAccount.sname
              this.$refs.fsurname.value = this.selectedAccount.fsurname
              this.$refs.ssurname.value = this.selectedAccount.ssurname
            })
            .catch((err) => {
              this.showMainAlert('danger',err)
              this.resetData()
            });
          }
          
        } else {
          
        }
      },
      resetData(){
        this.$refs.fname.disabled = false
        this.$refs.sname.disabled = false
        this.$refs.fsurname.disabled = false
        this.$refs.ssurname.disabled = false
        this.$refs.fname.value = '';
        this.$refs.sname.value = '';
        this.$refs.fsurname.value = '';
        this.$refs.ssurname.value = '';
      },
      loadData(){
        this.$refs.fname.disabled = true
        this.$refs.sname.disabled = true
        this.$refs.fsurname.disabled = true
        this.$refs.ssurname.disabled = true
        this.$refs.fname.value = 'Cargando...';
        this.$refs.sname.value = 'Cargando...';
        this.$refs.fsurname.value = 'Cargando...';
        this.$refs.ssurname.value = 'Cargando...';
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
      console.log(this.selectedAccount)
    },
    computed: {
      backgroundImage() {
        return (
          {
            background: process.env.BASE_URL + "media/avatars/"+this.$route.params.id+".png",
          }
        );
      }
    },
    
  };

</script>
