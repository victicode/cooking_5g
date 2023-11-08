<template>
  <div>
    <TransitionGroup name="fade-in-up">
      <!-- Deal Details -->
      <div id="deal-details" class="content fade" key="1">
        <div class="row g-3" >
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealTitle">Tipo de Documento</label>
            <select class="selectpicker w-100 select2 form-select" name="document_type_id" ref="document_type_id" @change="queryPersonByDocumentNumber($refs.document_number.value)">
              <option value="">Selecciona el tipo de documento</option>
              <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                  {{ documentType.name }}
              </option>
            </select>
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label" for="dealCode">Número de documento</label>
            <input
            type="text"
            id="numeral-mask"
            class="form-control numeral-mask"
            name="document_number" 
            ref="document_number" 
            placeholder="Ingresa el número de documento" 
            @change="queryPersonByDocumentNumber($event.target.value)"
          />
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
            <div class="row">
              <div class="col-12 mb-3">
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
              
            </div>
          </div>
          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer nombre</label>
            <input
              type="text"
              class="form-control"
              name="name" 
              ref="fname" 
              placeholder="Pablo"
              @input="checkInputText($event)"
            />
          </div>
          <div class="col-sm-6 valid-item">
            <label class="form-label">Segundo nombre</label>
            <div class="">
              <input
              type="text"
              class="form-control"
              placeholder="Daniel"
              name="sname" 
              ref="sname" 
              @input="checkInputText($event)"
              
            />
            </div>
          </div>

          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Primer Apellido</label>
            <input
                type="text"
                class="form-control"

                placeholder="Perez"
                name="surname" 
                ref="surname" 
                @input="checkInputText($event)"   
            />
          </div>

          <div class="col-sm-6 valid-item">
            
            <label class="form-label" for="basic-icon-default-fullname" >Segundo Apellido</label>
            <input
              type="text"
              class="form-control"
              placeholder="Flores"
              name="ssurname"
              ref="ssurname" 
              @input="checkInputText($event)"   
            />
          </div>

          <div class="col-sm-6 valid-item">
            <label for="dealDuration" class="form-label">Fecha de nacimiento</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="flatpickr-date" name="birthdate" ref="birthdate" />
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
            <label class="form-label" for="basic-default-phone">Número de celular</label>
            <input
              type="text"
              id="multicol-phone"
              class="form-control phone-mask"
              placeholder="658 799 8941"
              aria-label="658 799 8941"
              name="cellphone1" ref="cellphone1" 
              @input="checkInputPhone($event)"  
            />
          </div>

          <div class="col-sm-6 valid-item">
            <label class="form-label" for="basic-default-phone">Otro número de celular</label>
            <input
              type="text"
              id="multicol-phone"
              class="form-control phone-mask"
              placeholder="658 799 8941"
              aria-label="658 799 8941"
              name="cellphone2" ref="cellphone2"
              @input="checkInputPhone($event)"  
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
      <div id="deal-usage" class="content fade" key="2">
        <div class="row g-3">
          <div class="col-sm-6 input-s">
            <label class="form-label" for="multicol-country">Pais</label>
              <select class="select2 form-select" name="country_id" ref="country_id" disabled data-allow-clear="true">
                <option value="">Selecciona el país</option>
                <option v-for="country in countries" :value="country.id" :key="country.id" :selected="country.id == 139">
                  {{ country.name }}
                </option>
              </select>
          </div>
          <div class="col-sm-6 input-s">
            <label class="form-label" for="multicol-country">Departamento</label>
              <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                <option value="">Selecciona el departamento</option>
                <option v-for="department in departments" :value="department.id" :key="department.id">
                  {{ department.name }}
                </option>
              </select>
          </div>
          <div class="col-sm-6 input-s">
            <label class="form-label" for="multicol-country">Ciudad</label>
            <select class="select2 form-select" name="province_id" ref="province_id" @change="getDistrictsByProvinceId($event.target.value)">
              <option value="">Selecciona la Ciudad</option>
              <option v-for="province in provinces" :value="province.id" :key="province.id">
                  {{ province.name }}
              </option>
          </select>
          </div>
          <div class="col-sm-6 input-s">
            <label class="form-label" for="multicol-country">Distrito</label>
            <select class="select2 form-select" name="district_id" ref="district_id">
                <option value="">Selecciona el distrito</option>
                <option v-for="district in districts" :value="district.id" :key="district.id">
                    {{ district.name }}
                </option>
            </select>
          </div>
          <div class="col-sm-12 input-s">
            <label class="form-label" for="address-input">Dirección</label>
            <input
              type="text"
              class="form-control"
              id="address-input"
              placeholder="Ingrese su dirección"
              name="address" 
              ref="address"
              @input="checkInputDirection($event)"
            />
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
      <div id="review-complete" class="content fade" key="3">
        <div class="row g-3">
          <div class="col-lg-6 col-12">
            <div class="row">
              <div class="col-12 mb-0">
                <h3>!Ya casi terminamos! 🚀</h3>
              </div>
              <div class="col-12 mb-0">
                <table class="">
                  <tbody>
                    <tr class="input-s">
                      <label class="form-label" for="multicol-country">Ocupación</label>
                      <select class="select2 form-select" name="ocupation_id" ref="ocupation_id">
                        <option value="">Selecciona la ocupación</option>
                        <option v-for="ocupation in ocupations" :value="ocupation.id" :key="ocupation.id">
                            {{ ocupation.name }}
                        </option>
                      </select>
                    </tr>
                    <tr><br>
                      <label class="form-label">Confirme si es usted una persona politicamente expuesta</label>
                      <div class="col mt-2">
                        <div class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="exposed_person" 
                            ref="exposed_person" 
                            value="1"
                            v-model="exposed_person"
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
                          v-model="exposed_person"
                          />
                          <label class="form-check-label" for="collapsible-address-type-office">
                            NO SOY
                          </label>
                        </div>
                      </div>
                    </tr>
                    <template v-if="exposed_person==1">

                    
                    <tr class="input-s"><br>
                      <label class="form-label" for="basic-icon-default-fullname" >Cargo</label>
                        <input
                          type="text"
                          class="form-control"
          
                          placeholder="Ingrese su cargo"
                          name="position" ref="position"
                          @input="checkInputDirection($event)"
                          
                        />
                    </tr>
                    <tr class="input-s"><br>
                      <label class="form-label" for="basic-icon-default-fullname" >Lugar de trabajo</label>
                      <input
                        type="text"
                        class="form-control"
        
                        name="workplace" ref="workplace"
                        placeholder="Ingrese su lugar de trabajo"
                        @input="checkInputDirection($event)"
                      />
                    </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-lg-5 d-flex justify-content-center border rounded pt-3">
              <img
                class="img-fluid"
                :src="img.confirm"
                alt="deal image cap"
              />
            </div>
          <div class="col-md-12 input-s">
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
    </TransitionGroup>
  </div>
</template>

<style lang="scss">
  @import "@/assets/sass/pages/page-auth.scss";
  @import "@/assets/libs/bs-stepper/bs-stepper.scss";
  @import "@/assets/libs/flatpickr/flatpickr.scss";
</style>

<script>
  import KTWizard from "@/assets/js/components/wizard";
  import Swal from "sweetalert2";
  import { ACCOUNTS_PERSONAL_STORE } from "@/core/services/store/account.module";
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
      name:'Personal',
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
          }
      },
      components: {

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
          input.value = input.value.replace(/[^a-zA-Z ]/, '');
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
          input.value = input.value.replace(/[^0-9]/, '');
        }, 
        checkInputDirection(e) {
          let input = e.srcElement;
          input.value = input.value.replace(/[^a-zA-Z0-9#-.+*/@ ]/, '');
        }, 
        store() {
              document.getElementById('step4').disabled = true
              document.getElementById('step4').textContent = 'Cargando...'
              let formData = new FormData();

              formData.append('document_type_id', this.$refs.document_type_id.value);
              formData.append('document_number', this.$refs.document_number.value);
              formData.append('document_front', this.$refs.document_front.files[0]);
              formData.append('document_back', this.$refs.document_back.files[0]);
              formData.append('name', this.$refs.fname.value +' '+ this.$refs.sname.value);
              formData.append('surname', this.$refs.surname.value +' '+ this.$refs.ssurname.value);
              formData.append('birthdate', this.$refs.birthdate.value);
              formData.append('phone', this.$refs.phone.value);
              formData.append('cellphone1', this.$refs.cellphone1.value);
              formData.append('cellphone2', this.$refs.cellphone2.value);
              formData.append('country_id', this.$refs.country_id.value);
              formData.append('department_id', this.$refs.department_id.value);
              formData.append('province_id', this.$refs.province_id.value);
              formData.append('district_id', this.$refs.district_id.value);
              formData.append('address', this.$refs.address.value);
              formData.append('ocupation_id', this.$refs.ocupation_id.value);
              formData.append('exposed_person', this.exposed_person);

              if(this.exposed_person == 1){
                  formData.append('position', this.$refs.position.value);
                  formData.append('workplace', this.$refs.workplace.value);
              }
              if (this.manual == 'si') {
                formData.append('manual', 'si');
                formData.append('username', this.dataUser.username);
                formData.append('useremail', this.dataUser.email);
                formData.append('password', this.dataUser.password);
              }
              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_STORE, formData)
                  .then((data) => {
                      if (data.code != 201){
                          this.showDangerAlert('Error desconocido al guardar el perfil.');
                          document.getElementById('step4').disabled = false
                          document.getElementById('step4').textContent = 'Registrar'
                          return;
                      }
                      document.getElementById('step4').disabled = true
                      document.getElementById('step4').textContent = 'Cuenta creada con exito!'
                      Swal.fire({
                          title: 'Perfil creado correctamente!',
                          text: this.manual == 'si'?'Hemos creado su perfil correctamente' :'Hemos creado su perfil correctamente, lo estamos redireccionando al inicio!',
                          icon: "success",
                          confirmButtonClass: "btn btn-primary",
                      });

                      window.localStorage.setItem("has_account",  true);

                    setTimeout(() => {
                      if (this.manual == 'si') {
                        this.$emit("hiddeModalCreate");
                      } else {
                        this.$router.push({ name: "dashboard" })
                      }
                    }, 500);

                  })
                  .catch((err) => {
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

          queryPersonByDocumentNumber (documentNumber) {
            let documentType = this.$refs.document_type_id.value;
            if ((documentType == 1 || documentType == 3) && documentNumber != '') {
              this.loadData()
              if (documentType == 1) {
                
                this.$store
                .dispatch(QUERY_PERSON_BY_DNI, documentNumber)
                .then((data) => {
                  if (data.code != 200){
                    this.showDangerAlert('Tu número de documento de identidad no se encuentra en el registro')
                    this.resetData()
                      return;
                  }
                  const personData = JSON.parse(data.data);
                  let name = personData.nombres.split(' ')[0];
                  let sname = personData.nombres.split(' ')[1];
                  let surname  = personData.apellidoPaterno;
                  let ssurname = personData.apellidoMaterno;
                        
                  this.$refs.fname.value = name;
                  this.$refs.sname.value = sname;
                  this.$refs.surname.value = surname;
                  this.$refs.ssurname.value = ssurname;
                })
                .catch((err) => {
                  this.showDangerAlert('Error al intentar buscar el número de documento de identidad en el registro, ingresa tu datos de forma manual')
                  this.resetData()
                });
              } else {
                this.$store
                .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
                .then((data) => {
                  if (data.code != 200){
                    this.showDangerAlert('Tu número de documento de identidad no se encuentra en el registro')
                    this.resetData()
                      return;
                  }
                  const personData = JSON.parse(data.data);
                  let name = personData.razonSocial.split(' ')[2];
                  let sname = personData.razonSocial.split(' ')[personData.razonSocial.split(' ').length - 1];
                  let surname  = personData.razonSocial.split(' ')[0];
                  let ssurname = personData.razonSocial.split(' ')[1];
                        
                  this.$refs.fname.value = name;
                  this.$refs.sname.value = sname;
                  this.$refs.surname.value = surname;
                  this.$refs.ssurname.value = ssurname;
                })
                .catch((err) => {
                  this.showDangerAlert('Error al intentar buscar el número de documento de identidad en el registro, ingresa tu datos de forma manual')
                  this.resetData()
                });
              }
              
            } else {
              this.resetData()
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
          resetData(){
            this.$refs.fname.readOnly = false
            this.$refs.sname.readOnly = false
            this.$refs.surname.readOnly = false
            this.$refs.ssurname.readOnly = false
            this.$refs.fname.classList.remove('readOnly')
            this.$refs.sname.classList.remove('readOnly')
            this.$refs.surname.classList.remove('readOnly')
            this.$refs.ssurname.classList.remove('readOnly')
            this.$refs.fname.value = '';
            this.$refs.sname.value = '';
            this.$refs.surname.value = '';
            this.$refs.ssurname.value ='';
          },
          loadData(){
            this.$refs.fname.classList.add('readOnly')
            this.$refs.sname.classList.add('readOnly')
            this.$refs.surname.classList.add('readOnly')
            this.$refs.ssurname.classList.add('readOnly')
            this.$refs.fname.readOnly = true
            this.$refs.sname.readOnly = true
            this.$refs.surname.readOnly = true
            this.$refs.ssurname.readOnly = true
            this.$refs.fname.value = 'Cargando...';
            this.$refs.sname.value = 'Cargando...';
            this.$refs.surname.value = 'Cargando...';
            this.$refs.ssurname.value = 'Cargando...';
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