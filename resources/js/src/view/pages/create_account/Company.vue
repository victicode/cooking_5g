<template>
    <div>
      <div class="row" v-if="dangerAlert">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <b-alert :show="dangerAlert" dismissible fade variant="danger" @dismissed="dangerAlert=false">{{ dangerAlertMessage }}</b-alert>
        </div>
        <hr>
      </div>
       <!-- Deal Details -->
       <div id="deal-details" class="content fade">
        <div class="row g-3" >
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealTitle">Tipo de Documento</label>
            <select class="selectpicker w-100 select2 form-select" name="document_type_id" ref="document_type_id"  @change="queryPersonByRuc($refs.document_number.value)">
              <option value="">Selecciona el tipo de documento</option>
              <template v-for="documentType in documentTypes">
                <option  :value="documentType.id" v-if="documentType.id == 4 || documentType.id == 3" >
                    {{ documentType.name }}
                </option>
              </template>
            </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealCode">Número de documento</label>
            <input
            type="text"
            id="numeral-mask"
            class="form-control numeral-mask"
            name="document_number" 
            ref="ruc"
            placeholder="Ingresa el número de documento" 
            @change="queryPersonByRuc($event.target.value)"
          />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealDescription">Adjunte ficha RUC</label>
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
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Razon Social</label>
            <input
              type="text"
              class="form-control"
              name="business_name" 
              ref="business_name"
              placeholder="CAMBIOS C.A"
              @input="checkInputText($event)"
            />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label">Giro de negocio</label>
            <div class="">
              <input
              type="text"
              class="form-control"
              placeholder="Empresa productora de alimentos"
              name="business_turn" 
              ref="business_turn"
              @input="checkInputDirection($event)"
              
            />
            </div>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="multicol-phone">Teléfono</label>
            <input
            type="text"
            id="multicol-phone"
            class="form-control phone-mask"
            placeholder="658 799 8941"
            aria-label="658 799 8941"
            name="phone" 
            ref="phone" 
            @input="checkInputPhone($event)"  

          />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="basic-default-phone">Email</label>
            <input
              type="email"
              id=""
              class="form-control"
              placeholder="remesas@gmail.com"
              name="email" ref="email" 
            />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Pais</label>
              <select class="select2 form-select" name="country_id" ref="country_id" disabled data-allow-clear="true">
                <option value="">Selecciona el país</option>
                <option v-for="country in countries" :value="country.id" :key="country.id" :selected="country.id == 139">
                  {{ country.name }}
                </option>
              </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Departamento</label>
              <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                <option value="">Selecciona el departamento</option>
                <option v-for="department in departments" :value="department.id" :key="department.id">
                  {{ department.name }}
                </option>
              </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Ciudad</label>
            <select class="select2 form-select" name="province_id" ref="province_id" @change="getDistrictsByProvinceId($event.target.value)">
              <option value="">Selecciona la Ciudad</option>
              <option v-for="province in provinces" :value="province.id" :key="province.id">
                  {{ province.name }}
              </option>
          </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="multicol-country">Distrito</label>
            <select class="select2 form-select" name="district_id" ref="district_id">
                <option value="">Selecciona el distrito</option>
                <option v-for="district in districts" :value="district.id" :key="district.id">
                    {{ district.name }}
                </option>
            </select>
          </div>
          <div class="col-sm-12 valid-item">
            <label class="form-label" for="address-input">Dirección</label>
            <input
              type="text"
              class="form-control"
              id="address-input"
              placeholder="Ingrese su dirección"
              name="fiscal_address" 
              ref="fiscal_address"
              @input="checkInputDirection($event)"
            />
          </div>
    
          <div class="col-12 d-flex justify-content-end mt-4">
            <button class="btn btn-primary btn-next" disabled id="step2" >
              <span class="align-middle d-sm-inline-block d-none me-sm-1 " >Siguiente</span>
              <i class="ti ti-arrow-right ti-xs"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- Deal Usage -->
      <div id="deal-usage" class="content fade">
        <div class="row g-3">
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealTitle">Tipo de Documento del representante legal</label>
            <select class="selectpicker w-100 select2 form-select" name="document_type_lr_id" ref="document_type_lr_id" @change="queryPersonByDocumentNumber($refs.document_number_lr.value,'lr')">
              <option value="">Selecciona el tipo de documento</option>
              <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                  {{ documentType.name }}
              </option>
            </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealCode">N° de documento de identidad del representante legal</label>
            <input
            type="text"
            id="numeral-mask"
            class="form-control numeral-mask"
            name="document_number_lr" 
            ref="document_number_lr" 
            placeholder="Ingresa el número de documento"
            @change="queryPersonByDocumentNumber($event.target.value,'lr')"
          />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealDescription">Adjunte foto frontal del documento de identidad del representante legal</label>
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
            <div class="row">
              <div class="col-12 mb-3">
                <label class="form-label" for="dealDescription">Adjunte foto dorsal del documento de identidad del representante legal</label>
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
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer nombre del represante legal</label>
            <input
              type="text"
              class="form-control"
              name="name_lr" ref="name_lr"
              placeholder="Pablo"
              @input="checkInputText($event)"
            />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label">Segundo nombre del represante legal</label>
            <div class="">
              <input
              type="text"
              class="form-control"
              placeholder="Daniel"
              name="sname_lr" ref="sname_lr" 
              @input="checkInputText($event)"
              
            />
            </div>
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer Apellido del represante legal</label>
            <input
                type="text"
                class="form-control"

                placeholder="Perez"
                name="surname_lr" ref="surname_lr"
                @input="checkInputText($event)"   
            />
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Segundo Apellido del represante legal</label>
            <input
              type="text"
              class="form-control"
              placeholder="Flores"
              name="ssurname_lr" ref="ssurname_lr"
              @input="checkInputText($event)"   
            />
          </div>
          <div class="col-sm-12 valid-item">
            <label class="form-label" for="multicol-country">Ocupación del represante legal</label>
            <select class="select2 form-select" name="ocupation_lr_id" ref="ocupation_lr_id">
              <option value="">Selecciona la ocupación</option>
              <option v-for="ocupation in ocupations" :value="ocupation.id" :key="ocupation.id">
                  {{ ocupation.name }}
              </option>
            </select> 
          </div>
          <div class="col-12 d-flex justify-content-between mt-4">
            <button class="btn btn-label-secondary btn-prev">
              <i class="ti ti-arrow-left ti-xs me-sm-1 me-0"></i>
              <span class="align-middle d-sm-inline-block d-none">Anterior</span>
            </button>
            <button class="btn btn-primary btn-next" disabled id="step3">
              <span class="align-middle d-sm-inline-block d-none me-sm-1" >Siguiente</span>
              <i class="ti ti-arrow-right ti-xs"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- Review & Complete -->
      <div id="review-complete" class="content fade">
        <div class="row g-3">
          <div class="col-12 mb-0">
            <h3>!Ya casi terminamos! 🚀</h3>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealTitle">Tipo de Documento del contacto</label>
            <select class="selectpicker w-100 select2 form-select" name="document_type_c_id" ref="document_type_c_id" @change="queryPersonByDocumentNumber($refs.document_number_c.value,'c')">
              <option value="">Selecciona el tipo de documento</option>
              <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                  {{ documentType.name }}
              </option>
            </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealCode">N° de documento de identidad del contacto</label>
            <input
            type="text"
            id="numeral-mask"
            class="form-control numeral-mask"
            name="document_number_c" 
            ref="document_number_c"
            placeholder="Ingresa el número de documento"
            @change="queryPersonByDocumentNumber($event.target.value,'c')"
          />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealDescription">Adjunte foto frontal del documento de identidad del contacto</label>
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
            <div class="row">
              <div class="col-12 mb-3">
                <label class="form-label" for="dealDescription">Adjunte foto dorsal del documento de identidad del contacto</label>
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
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer nombre del contacto</label>
            <input
              type="text"
              class="form-control"
              name="name_c" ref="name_c"
              placeholder="Pablo"
              @input="checkInputText($event)"
            />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label">Segundo nombre del contacto</label>
            <div class="">
              <input
              type="text"
              class="form-control"
              placeholder="Daniel"
              name="sname_c" ref="sname_c"
              @input="checkInputText($event)"
              
            />
            </div>
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer Apellido del contacto</label>
            <input
                type="text"
                class="form-control"

                placeholder="Perez"
                name="surname_c" ref="surname_c"
                @input="checkInputText($event)"   
            />
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Segundo Apellido del contacto</label>
            <input
              type="text"
              class="form-control"
              placeholder="Flores"
              name="ssurname_c" ref="ssurname_c"
              @input="checkInputText($event)"   
            />
          </div>
          <div class="col-sm-6 valid-item">
            <label for="dealDuration" class="form-label">Fecha de nacimiento</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="flatpickr-date" name="birthdate_c" ref="birthdate_c" />
          </div>

          <div class="col-sm-6 valid-item">
            <label class="form-label" for="multicol-phone">Teléfono</label>
            <input
              type="text"
              id="multicol-phone"
              class="form-control phone-mask"
              placeholder="658 799 8941"
              aria-label="658 799 8941"
              name="phone_c" 
              ref="phone_c" 
              @input="checkInputPhone($event)"  
  
            />
          </div>
          <div class="col-md-12 valid-item mt-4">
            <label class="switch">
              <input type="checkbox" class="switch-input" id="dealConfirmed" name="terms" checked />
              <span class="switch-toggle-slider">
                <span class="switch-on"></span>
                <span class="switch-off"></span>
              </span>
              <span class="switch-label"> Acepto los términos y condiciones.</span>
            </label>
          </div>
          <div class="col-12 d-flex justify-content-between mt-4">
            <button class="btn btn-label-secondary btn-prev">
              <i class="ti ti-arrow-left ti-xs me-sm-1 me-0"></i>
              <span class="align-middle d-sm-inline-block d-none">Anterior</span>
            </button>
            <button class="btn btn-success " @click="store()" disabled id="step4">
              <span class="align-middle d-sm-inline-block d-none me-sm-1 " >Registrar</span
              ><i class="ti ti-check ti-xs"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
</template>
    
<style lang="scss">
  @import "@/assets/sass/pages/page-auth.scss";
  @import "@/assets/libs/bs-stepper/bs-stepper.scss";
  @import "@/assets/libs/flatpickr/flatpickr.scss";
</style>

<script>
  import Swal from "sweetalert2";
  import { ACCOUNTS_COMPANY_STORE } from "@/core/services/store/account.module";
  import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
  import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
  import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
  import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
  import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
  import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
  import { QUERY_PERSON_BY_DNI, QUERY_COMPANY_BY_RUC } from "@/core/services/store/peru_consult.module";
  import Cleave from 'cleave.js';
  import flatpickr from "flatpickr";
  export default {
  name: 'Company',
  props:['manual', 'dataUser'],
  data() {
    return {
      exposed_person: "0",
      countries: [],
      departments: [],
      provinces: [],
      districts: [],
      documentTypes: [],
      ocupations: [],
      dangerAlert: false,
      dangerAlertMessage: "",
      selected:'4'
    }
  },
  mounted() {
    flatpickr("#flatpickr-date", {
      locale: {
        firstDayOfWeek: 1,
        weekdays: {
          shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],         
        }, 
        months: {
          shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
          longhand: ['Enero', 'Febrero', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        },
      },  
    });
    console.log('company')
    this.phoneFormat()
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
      input.value = input.value.replace(/[^a-zA-ZÀ-ÿ& .,]/, '');
    }, 
    phoneFormat(){
      const editAccount = document.querySelectorAll('.phone-mask');
      if (editAccount) {
        setTimeout(function () {
          editAccount.forEach(element => {
            new Cleave(element, {
              blocks: [3, 3, 4],
            });
          });
        },2000)
      }  
    },
    checkInputPhone(e) {
      let input = e.srcElement;
      console.log(e);
      input.value = input.value.replace(/[^0-9]/, '');
    }, 
    checkInputDirection(e) {
      let input = e.srcElement;
      console.log(e);
      input.value = input.value.replace(/[^a-zA-Z0-9#-.+*/@ ]/, '');
    }, 
    store() {
      
      document.getElementById('step4').disabled = true
      document.getElementById('step4').textContent = 'Cargando...'
      let formData = new FormData();
      console.log(this.$refs)
      formData.append('ruc', this.$refs.ruc.value);
      formData.append('ruc_card', this.$refs.ruc_card.files[0]);
      formData.append('business_name', this.$refs.business_name.value);
      formData.append('business_turn', this.$refs.business_turn.value);
      formData.append('fiscal_address', this.$refs.fiscal_address.value);
      formData.append('country_id', this.$refs.country_id.value);
      formData.append('department_id', this.$refs.department_id.value);
      formData.append('province_id', this.$refs.province_id.value);
      formData.append('district_id', this.$refs.district_id.value);
      formData.append('email', this.$refs.email.value);
      formData.append('phone', this.$refs.phone.value);
      formData.append('document_type_lr_id', this.$refs.document_type_lr_id.value);
      formData.append('document_number_lr', this.$refs.document_number_lr.value);
      formData.append('document_front_lr', this.$refs.document_front_lr.files[0]);
      formData.append('document_back_lr', this.$refs.document_back_lr.files[0]);
      formData.append('name_lr', this.$refs.name_lr.value +' '+ this.$refs.sname_lr.value);
      formData.append('surname_lr', this.$refs.surname_lr.value +' '+ this.$refs.ssurname_lr.value);
      formData.append('ocupation_lr_id', this.$refs.ocupation_lr_id.value);
      formData.append('document_type_c_id', this.$refs.document_type_c_id.value);
      formData.append('document_number_c', this.$refs.document_number_c.value);
      formData.append('document_front_c', this.$refs.document_front_c.files[0]);
      formData.append('document_back_c', this.$refs.document_back_c.files[0]);
      formData.append('name_c', this.$refs.name_c.value +' '+ this.$refs.sname_c.value);
      formData.append('surname_c', this.$refs.surname_c.value +' '+ this.$refs.ssurname_c.value);
      formData.append('birthdate_c', this.$refs.birthdate_c.value);
      formData.append('phone_c', this.$refs.phone_c.value);
      if (this.manual == 'si') {
        formData.append('manual', 'si');
        formData.append('username', this.dataUser.username);
        formData.append('useremail', this.dataUser.email);
        formData.append('password', this.dataUser.password);
      }

      this.$store
          .dispatch(ACCOUNTS_COMPANY_STORE, formData)
          .then((data) => {
            console.log(data)
              // if (data.code != 201){
              //   this.showDangerAlert('Error desconocido al guardar el perfil.');
              //   document.getElementById('step4').disabled = false
              //   document.getElementById('step4').textContent = 'Registrar'
              //   return;
              // }
              // document.getElementById('step4').textContent = 'Registro exitoso!'
              // Swal.fire({
              //     title: 'Perfil creado correctamente!',
              //     text: this.manual == 'si' ? 'Hemos creado su perfil correctamente':'Hemos creado su perfil correctamente, lo estamos redireccionando al inicio!',
              //     icon: "success",
              //     confirmButtonClass: "btn btn-primary",
              // });
              

              // window.localStorage.setItem("has_account",  true);

              setTimeout(() => {
                if (this.manual == 'si') { 
                  this.$emit("hiddeModalCreate");
                } else {
                  this.$router.push({ name: "dashboard" })
                }
              }, 1000);

          })
          .catch((err) => {
            console.log(err)
            console.log('sapitoooo')
              this.showDangerAlert(err);
              document.getElementById('step4').disabled = false
              document.getElementById('step4').textContent = 'Registrar'
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

    getDocumentTypes () {
        this.$store
            .dispatch(DOCUMENT_TYPES_GET_ALL)
            .then((data) => {
                if (data.code != 200){
                    this.showDangerAlert('Error desconocido al obtener todos los tipos de documento.')
                    return;
                }

                this.documentTypes = data.data;
            })
            .catch((err) => {
                this.showDangerAlert(err)
            });
    },

    getOcupations () {
        this.$store
            .dispatch(OCUPATIONS_GET_ALL)
            .then((data) => {
                if (data.code != 200){
                    this.showDangerAlert('Error desconocido al obtener todas las ocupaciones.')
                    return;
                }

                this.ocupations = data.data;
            })
            .catch((err) => {
                this.showDangerAlert(err)
            });
    },

    queryPersonByRuc (documentNumber) {
      if (documentNumber != '') {
        this.loadDataRuc()
          this.$store
          .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
          .then((data) => {
            if (data.code != 200){
              this.showDangerAlert('Tu número de documento de identidad no se encuentra en el registro')
              this.resetDataRuc()
                return;
            }
            const personData = JSON.parse(data.data);
            let name = personData.razonSocial;

            this.$refs.business_name.value = name;
          })
          .catch((err) => {
            this.showDangerAlert('Error al intentar buscar el número de documento de identidad en el registro, ingresa tu datos de forma manual')
            this.resetDataRuc()
          });
      
        
      } else {
        this.resetDataRuc()
      }
    },
    queryPersonByDocumentNumber (documentNumber, block) {
        let documentType = this.$refs['document_type_'+ block +'_id'].value;
        if ((documentType == 1 || documentType == 3) && documentNumber != '') {
          this.loadData(block)
          if (documentType == 1) {
            
            this.$store
            .dispatch(QUERY_PERSON_BY_DNI, documentNumber)
            .then((data) => {
              if (data.code != 200){
                this.showDangerAlert('Tu número de documento de identidad no se encuentra en el registro')
                this.resetData(block)
                  return;
              }
              const personData = JSON.parse(data.data);
              console.log(personData)
              let name = personData.nombres.split(' ')[0];
              let sname = personData.nombres.split(' ')[1] == undefined ? '' : personData.nombres.split(' ')[1];
              let surname  = personData.apellidoPaterno;
              let ssurname = personData.apellidoMaterno;
                    
              this.$refs['name_' + block].value = name;
              this.$refs['sname_' + block].value = sname;
              this.$refs['surname_' + block].value = surname;
              this.$refs['ssurname_' + block].value = ssurname;
            })
            .catch((err) => {
              this.showDangerAlert('Error al intentar buscar el número de documento de identidad en el registro, ingresa tu datos de forma manual')
              this.resetData(block)
            });
          } else {
            this.$store
            .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
            .then((data) => {
              if (data.code != 200){
                this.showDangerAlert('Tu número de documento de identidad no se encuentra en el registro')
                this.resetData(block)
                  return;
              }
              const personData = JSON.parse(data.data);
              let name = personData.razonSocial.split(' ')[2];
              let sname = personData.razonSocial.split(' ')[personData.razonSocial.split(' ').length - 1];
              let surname  = personData.razonSocial.split(' ')[0];
              let ssurname = personData.razonSocial.split(' ')[1];
                    
              this.$refs['name_' + block].value = name;
              this.$refs['sname_' + block].value = sname;
              this.$refs['surname_' + block].value = surname;
              this.$refs['ssurname_' + block].value = ssurname;
            })
            .catch((err) => {
              this.showDangerAlert('Hemos tenido un error, vuelve a intentar')
              this.resetData(block)
            });
          }
          
        } else {
          this.resetData(block)
        }
      
    },
    showDangerAlert(message) {
        this.dangerAlertMessage = message;
        this.dangerAlert = true;
    },
    hideDangerAlert() {
        this.dangerAlertMessage = '';
        this.dangerAlert = false;
    },
    resetDataRuc(){
      this.$refs.business_name.readOnly = false
      this.$refs.business_name.classList.remove('readOnly')
      this.$refs.business_name.value ='';
    },
    loadDataRuc(){
      this.$refs.business_name.classList.add('readOnly')
      this.$refs.business_name.readOnly = true
      this.$refs.business_name.value = 'Cargando...';
    },
    resetData(block){
      console.log(this.$refs['name_lr'])
      this.$refs['name_'+ block].readOnly = false
      this.$refs['sname_'+ block].readOnly = false
      this.$refs['surname_'+ block].readOnly = false
      this.$refs['ssurname_'+ block].readOnly = false
      this.$refs['name_'+ block].classList.remove('readOnly')
      this.$refs['sname_'+ block].classList.remove('readOnly')
      this.$refs['surname_'+ block].classList.remove('readOnly')
      this.$refs['ssurname_'+ block].classList.remove('readOnly')
      this.$refs['name_'+ block].value = '';
      this.$refs['sname_'+ block].value = '';
      this.$refs['surname_'+ block].value = '';
      this.$refs['ssurname_'+ block].value ='';
    },
    loadData(block){
      this.$refs['name_'+ block].classList.add('readOnly')
      this.$refs['sname_'+ block].classList.add('readOnly')
      this.$refs['surname_'+ block].classList.add('readOnly')
      this.$refs['ssurname_'+ block].classList.add('readOnly')
      this.$refs['name_'+ block].readOnly = true
      this.$refs['sname_'+ block].readOnly = true
      this.$refs['surname_'+ block].readOnly = true
      this.$refs['ssurname_'+ block].readOnly = true
      this.$refs['name_'+ block].value = 'Cargando...';
      this.$refs['sname_'+ block].value = 'Cargando...';
      this.$refs['surname_'+ block].value = 'Cargando...';
      this.$refs['ssurname_'+ block].value = 'Cargando...';
    },
  },
  created() {
      this.getDocumentTypes();
      this.getCountries();
      this.getDepartments();
      this.getProvincesByDepartmentId(15);
      this.getDistrictsByProvinceId(128);
      this.getOcupations();
  },
  computed: {
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