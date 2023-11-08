<template>
  <div class="row">
    <div class="row" v-if="mainAlert">
      <div class="col-lg-12 col-md-12 col-sm-12 px-4 mt-2">
        <b-alert class=" " :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}{{ redirectMessage == '' ? '' : redirectMessage + redirectMessageSeconds + ' segundos.' }}</b-alert>
      </div>
      <hr>
    </div>
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10 mb-4">
            <div class="card">
              <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                  <h5 class="mb-0">Perfiles Personales</h5>
                  <small class="text-muted">Persona natural</small>
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="earningReportsTabsId"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                
                  </button>
                  
                </div>
              </div>
              <div class="card-body">
                <ul class="nav nav-tabs widget-nav-tabs pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
                  <li class="nav-item" v-for="(personalAccount, index) in personalAccounts" :key="`personal_account_${index}`">
                    <a
                      href="javascript:void(0);"
                      class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                      v-b-modal.modal-selected-account 
                      :class="{ 'active': activeAccountType == 0 && personalAccount.id == activeAccountId}"
                      @click="setSelectedAccount(0, personalAccount.id)"
                    >
                      <div class="badge bg-label-secondary rounded p-2">
                        <i class="fa-solid fa-user-large"></i>
                      </div>
                      <h6 class="tab-widget-title mb-0 mt-2 text-uppercase">{{ personalAccount.label }}</h6>
                    </a>
                  </li>
                  <li v-if="personalAccounts.length < 4" class="nav-item">
                    <router-link to="/create-account" v-slot="{ href, navigate }" >
                      <a
                        :href="href"
                        class="nav-link btn d-flex align-items-center justify-content-center"
                        @click="navigate"
                      >
                        <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-plus ti-sm"></i></div>
                      </a>
                    </router-link>
                  </li>
                </ul>
                <div class="tab-content p-0 ms-0 ms-sm-2">
                  <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                    <div id="earningReportsTabsOrders"></div>
                  </div>
                  <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                    <div id="earningReportsTabsSales"></div>
                  </div>
                  <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                    <div id="earningReportsTabsProfit"></div>
                  </div>
                  <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                    <div id="earningReportsTabsIncome"></div>
                  </div>
                </div>
              </div>
            </div>
      </div>

        
      <!-- Earning Reports Tabs-->
      <div class="col-12 col-xl-10 mb-4">
          <div class="card">
          <div class="card-header d-flex justify-content-between">
              <div class="card-title mb-0">
              <h5 class="mb-0">Perfiles empresariales</h5>
              <small class="text-muted">Debes ser el representante legal o persona autorizada para cambiar.</small>
              </div>
              <div class="dropdown">
              <button
                  class="btn p-0"
                  type="button"
                  id="earningReportsTabsId"
              >
              
              </button>
              
              </div>
          </div>
          <div class="card-body">
              <ul class="nav nav-tabs widget-nav-tabs pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
              <li v-for="(companyAccount, index) in companyAccounts" :key="`company_account_${index}`" class="nav-item">
                  <a
                  href="javascript:void(0);"
                  class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                  v-b-modal.modal-selected-account 
                  :class="{ 'active': activeAccountType == 1 && companyAccount.id == activeAccountId}"
                  @click="setSelectedAccount(1, companyAccount.id)"
                  >
                  <div class="badge bg-label-secondary rounded p-2">
                      <i class="fa-solid fa-building"></i>
                  </div>
                  <h6 class="tab-widget-title mb-0 mt-2 text-uppercase">{{ companyAccount.label }}</h6>
                  </a>
              </li>
              <li v-if="companyAccounts.length < 4" class="nav-item">
                  <router-link to="/create-account" v-slot="{ href, navigate }" >
                  <a
                      :href="href"
                      class="nav-link btn d-flex align-items-center justify-content-center"
                      @click="navigate"
                  >
                      <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-plus ti-sm"></i></div>
                  </a>
                  </router-link>
              </li>
              </ul>
              <div class="tab-content p-0 ms-0 ms-sm-2">
              <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                  <div id="earningReportsTabsOrders"></div>
              </div>
              <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                  <div id="earningReportsTabsSales"></div>
              </div>
              <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                  <div id="earningReportsTabsProfit"></div>
              </div>
              <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                  <div id="earningReportsTabsIncome"></div>
              </div>
              </div>
          </div>
          </div>
      </div>
    </div>

    <!-- Modal selected account -->
    <div>
        <b-modal id="modal-selected-account" size="lg" title="Perfil seleccionado" ok-title="Guardar" cancel-title="Cerrar" @ok="updateAccount($event)" @hidden="resetSelectedAccount()">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <b-button v-if="!(activeAccountType == selectedAccountType && selectedAccount.id == activeAccountId)" variant="outline-success" class="m-2" @click="changeActiveAccount($event)">Seleccionar cuenta activa</b-button>
                    <b-button v-if="!(activeAccountType == selectedAccountType && selectedAccount.id == activeAccountId)" variant="outline-danger" class="m-2" @click="showDeleteConfirmation($event)">Eliminar</b-button>
                </div>
            </div>
            <hr>
            <template v-if="selectedAccountType == 0">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Nombre/s</label>
                            <input type="text" class="form-control" name="name" ref="name" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Apellido/s</label>
                            <input type="text" class="form-control" name="surname" ref="surname" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Tipo de documento</label>
                            <select class="form-control" name="document_type_id" ref="document_type_id" v-model="selectedAccount.document_type_id">
                                <option value="-1">Seleccione el tipo de documento</option>
                                <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Número de documento</label>
                            <input type="text" class="form-control" name="document_number" ref="document_number" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input type="date" class="form-control" name="birthdate" ref="birthdate" placeholder="Selecciona la fecha de nacimiento" v-model="selectedAccount.birthdate">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="phone" ref="phone" placeholder="Ingresa el número de telefono" v-model="selectedAccount.phone">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Celular</label>
                            <input type="text" class="form-control" name="cellphone1" ref="cellphone1" placeholder="Ingresa el número de celular" v-model="selectedAccount.cellphone1">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Otro celular</label>
                            <input type="text" class="form-control" name="cellphone2" ref="cellphone2" placeholder="Ingresa otro número de celular" v-model="selectedAccount.cellphone2">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>País</label>
                            <select class="select2 form-select" name="country_id" ref="country_id" disabled  v-model="selectedAccount.country_id" data-allow-clear="true">
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
                            <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" v-model="selectedAccount.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
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
                            <select class="select2 form-select" name="province_id" ref="province_id" v-model="selectedAccount.province_id" @change="getDistrictsByProvinceId($event.target.value)">
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
                            <select class="select2 form-select" name="district_id" ref="district_id" v-model="selectedAccount.district_id">
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
                            <input type="text" class="form-control" name="address" ref="address" placeholder="Ingresa el domicilio" v-model="selectedAccount.address">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Ocupación</label>
                            <select class="form-control" name="ocupation_id" ref="ocupation_id" v-model="selectedAccount.ocupation_id">
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
                                <b-form-radio v-model="selectedAccount.exposed_person" :checked="selectedAccount.exposed_person == 1" name="exposed_person" ref="exposed_person" size="lg" value="1">Si, soy</b-form-radio>
                                <b-form-radio v-model="selectedAccount.exposed_person" :checked="selectedAccount.exposed_person == 0" name="exposed_person" ref="exposed_person" size="lg" value="0">No, no soy</b-form-radio>
                            </b-form-group>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="selectedAccount.exposed_person == 1">
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Cargo</label>
                            <input type="text" class="form-control" name="position" ref="position" placeholder="Ingresa el cargo" v-model="selectedAccount.position">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Lugar de trabajo</label>
                            <input type="text" class="form-control" name="workplace" ref="workplace" placeholder="Ingresa el lugar de trabajo" v-model="selectedAccount.workplace">
                        </div>
                    </div>
                </div>
            </template>

            <template v-if="selectedAccountType == 1">
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Número de RUC</label>
                            <input type="text" class="form-control" name="ruc" ref="ruc" placeholder="Ingresa el número de RUC" v-model="selectedAccount.ruc">
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Razón social</label>
                            <input type="text" class="form-control" name="business_name" ref="business_name" placeholder="Ingresa la razón social" v-model="selectedAccount.business_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Giro de negocio</label>
                            <input type="text" class="form-control" name="business_turn" ref="business_turn" placeholder="Ingresa el giro de negocio" v-model="selectedAccount.business_turn">
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control" name="fiscal_address" ref="fiscal_address" placeholder="Ingresa la dirección fiscal" v-model="selectedAccount.fiscal_address">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>País</label>
                            <select class="select2 form-select" name="country_id" ref="country_id" disabled  v-model="selectedAccount.country_id" data-allow-clear="true">
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
                            <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" v-model="selectedAccount.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
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
                            <select class="select2 form-select" name="province_id" ref="province_id" v-model="selectedAccount.province_id" @change="getDistrictsByProvinceId($event.target.value)">
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
                            <select class="select2 form-select" name="district_id" ref="district_id" v-model="selectedAccount.district_id">
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
                            <input type="text" class="form-control" name="email" ref="email" placeholder="Ingresa el correo electrónico" v-model="selectedAccount.email">
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Telefono</label>
                            <input type="text" class="form-control" name="phone" ref="phone" placeholder="Ingresa el número de teléfono" v-model="selectedAccount.phone">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Nombre/s del representante legal</label>
                            <input type="text" class="form-control" name="name_lr" ref="name_lr" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name_lr">
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Apellido/s del representante legal</label>
                            <input type="text" class="form-control" name="surname_lr" ref="surname_lr" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname_lr">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Tipo de documento del representante legal</label>
                            <select class="form-control" name="document_type_lr_id" ref="document_type_lr_id" v-model="selectedAccount.document_type_lr_id">
                                <option value="-1">Seleccione el tipo de documento</option>
                                <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Número de documento del representante legal</label>
                            <input type="text" class="form-control" name="document_number_lr" ref="document_number_lr" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number_lr">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Nombre/s de contacto</label>
                            <input type="text" class="form-control" name="name_c" ref="name_c" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name_c">
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Apellido/s de contacto</label>
                            <input type="text" class="form-control" name="surname_c" ref="surname_c" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname_c">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Tipo de documento de contacto</label>
                            <select class="form-control" name="document_type_c_id" ref="document_type_c_id" v-model="selectedAccount.document_type_c_id">
                                <option value="-1">Seleccione el tipo de documento</option>
                                <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="form-group">
                            <label>Número de documento de contacto</label>
                            <input type="text" class="form-control" name="document_number_c" ref="document_number_c" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number_c">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 mb-3">
                        <div class="form-group">
                            <label>Teléfono de contacto</label>
                            <input type="text" class="form-control" name="phone_c" ref="phone_c" placeholder="Ingresa el número de teléfono" v-model="selectedAccount.phone_c">
                        </div>
                    </div>
                </div>
            </template>

            <div class="row" v-if="savedAlert">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                </div>
            </div>
        </b-modal>
    </div>

    <div>
        <b-modal id="modal-confirm-delete" title="Eliminar perfil" ok-title="Confirmar" cancel-title="Cancelar" @ok="deleteAccount($event)" @hidden="resetDeleteAccount();">
            <p class="my-4">Esta seguro que desea eliminar este perfil?</p>
        </b-modal>
    </div>
  </div>
</template>

<script>
  import { ACCOUNTS_GET_ALL, ACCOUNTS_PERSONAL_UPDATE, ACCOUNTS_COMPANY_UPDATE, SET_ACTIVE_ACCOUNT, ACCOUNTS_PERSONAL_DELETE, ACCOUNTS_COMPANY_DELETE } from "@/core/services/store/account.module";
  import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
  import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
  import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
  import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
  import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
  import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
  export default {
      data() {
          return {
            mainAlert: false,
            mainAlertVariant: "",
            mainAlertMessage: "",
            savedAlert: false,
            savedAlertVariant: '',
            savedAlertMessage: '',
            personalAccounts: [],
            companyAccounts: [],
            activeAccountType: null,
            activeAccountId: null,
            selectedAccount: {},
            selectedAccountType: null,
            selectedAccountIdToDelete: null,
            selectedAccountTypeToDelete: null,
            documentTypes: [],
            ocupations: [],
            redirectMessageSeconds: 3,
            redirectMessage: '',
            countries: [],
            departments: [],
            provinces: [],
            districts: [],
          }
      },
      mounted() {
        
      },
      methods: {
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

                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
          },

          updateAccount (e) {
              e.preventDefault();

              // Validate fields
              if (this.selectedAccountType == 0) {
                  if(this.selectedAccount.name == null || this.selectedAccount.name == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el nombre/s.')
                      return;
                  }
                  if(this.selectedAccount.surname == null || this.selectedAccount.surname == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el apellido/s.')
                      return;
                  }
                  if(this.selectedAccount.document_type_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el tipo de documento.')
                      return;
                  }
                  if(this.selectedAccount.document_number == null || this.selectedAccount.document_number == ""){
                      this.showSavedAlert('danger', 'Debe ingresar número de documento.')
                      return;
                  }
                  if(this.selectedAccount.birthdate == null || this.selectedAccount.birthdate == ""){
                      this.showSavedAlert('danger', 'Debe ingresar la fecha de nacimiento.')
                      return;
                  }
                  if(this.selectedAccount.country_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el país.')
                      return;
                  }
                  if(this.selectedAccount.department_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el departamento.')
                      return;
                  }
                  if(this.selectedAccount.province_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar la provincia.')
                      return;
                  }
                  if(this.selectedAccount.district_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el distrito.')
                      return;
                  }
                  if(this.selectedAccount.address == null || this.selectedAccount.address == ""){
                      this.showSavedAlert('danger', 'Debe ingresar la dirección.')
                      return;
                  }
                  if(this.selectedAccount.ocupation_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar la ocupación.')
                      return;
                  }
                  if(this.selectedAccount.exposed_person == 1){
                      if(this.selectedAccount.position == null || this.selectedAccount.position == ""){
                          this.showSavedAlert('danger', 'Debe ingresar la posición.')
                          return;
                      }
                      if(this.selectedAccount.workplace == null || this.selectedAccount.workplace == ""){
                          this.showSavedAlert('danger', 'Debe ingresar el lugar de trabajo.')
                          return;
                      }
                  }
                  else {
                      this.selectedAccount.position = null;
                      this.selectedAccount.workplace = null;
                  }
              }
              else if (this.selectedAccountType == 1) {
                  if(this.selectedAccount.ruc == null || this.selectedAccount.ruc == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el RUC.')
                      return;
                  }
                  if(this.selectedAccount.business_name == null || this.selectedAccount.business_name == ""){
                      this.showSavedAlert('danger', 'Debe ingresar la razón social.')
                      return;
                  }
                  if(this.selectedAccount.business_turn == null || this.selectedAccount.business_turn == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el giro de negocio.')
                      return;
                  }
                  if(this.selectedAccount.fiscal_address == null || this.selectedAccount.business_turn == ""){
                      this.showSavedAlert('danger', 'Debe ingresar la dirección fiscal.')
                      return;
                  }
                  if(this.selectedAccount.country_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el país.')
                      return;
                  }
                  if(this.selectedAccount.department_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el departamento.')
                      return;
                  }
                  if(this.selectedAccount.province_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar la provincia.')
                      return;
                  }
                  if(this.selectedAccount.district_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el distrito.')
                      return;
                  }
                  if(this.selectedAccount.email == null || this.selectedAccount.email == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el email.')
                      return;
                  }
                  if(this.selectedAccount.phone == null || this.selectedAccount.phone == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el email.')
                      return;
                  }
                  if(this.selectedAccount.name_lr == null || this.selectedAccount.name_lr == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el nombre/s del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.surname_lr == null || this.selectedAccount.surname_lr == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el apellido/s del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.document_type_lr_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el tipo de documento del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.document_number_lr == null || this.selectedAccount.document_number_lr == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el número de docuemento del representante legal.')
                      return;
                  }
                  if(this.selectedAccount.name_c == null || this.selectedAccount.name_c == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el nombre/s del contacto.')
                      return;
                  }
                  if(this.selectedAccount.surname_c == null || this.selectedAccount.surname_c == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el apellido/s del contacto.')
                      return;
                  }
                  if(this.selectedAccount.document_type_c_id == -1){
                      this.showSavedAlert('danger', 'Debe seleccionar el tipo de documento del contacto.')
                      return;
                  }
                  if(this.selectedAccount.document_number_c == null || this.selectedAccount.document_number_c == ""){
                      this.showSavedAlert('danger', 'Debe ingresar el número de docuemento del contacto.')
                      return;
                  }
              }
              const DISPATCH_ACCOUNT_ACTION = this.selectedAccountType == 0 
              ? ACCOUNTS_PERSONAL_UPDATE 
              : ACCOUNTS_COMPANY_UPDATE ;
              this.$store
                  .dispatch(DISPATCH_ACCOUNT_ACTION, this.selectedAccount)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al actualizar el perfil.')
                          return;
                      }

                      let selectedAccountIndex = -1;
                      let label = '';

                      if (this.selectedAccountType == 0) {
                          selectedAccountIndex = this.personalAccounts.findIndex(account => account.id == this.selectedAccount.id);
                          label = data.data.name + ' ' + data.data.surname;
                      }
                      else if (this.selectedAccountType == 1) {
                          selectedAccountIndex = this.companyAccounts.findIndex(account => account.id == this.selectedAccount.id);
                          label = data.data.business_name;
                      }

                      let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
                      data.data.label = matches.join('.'); // J.S.O.N

                      if (selectedAccountIndex > -1) {
                          if (this.selectedAccountType == 0) {
                              this.personalAccounts[selectedAccountIndex] = data.data;
                          }
                          else if (this.selectedAccountType == 1) {
                              this.companyAccounts[selectedAccountIndex] = data.data;
                          }
                      }

                      // close modal
                      this.closeModal('modal-selected-account')
                      this.showMainAlert('success', 'Perfil actualizado correctamente!')
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
          },

          changeActiveAccount (e) {
              e.preventDefault();

              let data = {
                  active_account_type: this.selectedAccountType,
                  active_account: this.selectedAccount.id
              };

              this.$store
                  .dispatch(SET_ACTIVE_ACCOUNT, data)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al cambiar la cuenta activa.')
                          return;
                      }

                      // close modal
                      this.closeModal('modal-selected-account')
                      this.showMainAlert('success', 'Perfil activo cambiado correctamente!')
                      this.redirectTimer(' . Recargando página en: ');
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
          },

          deleteAccount (e) {
              e.preventDefault();

              const DISPATCH_ACCOUNT_ACTION = this.selectedAccountTypeToDelete == 0 ? ACCOUNTS_PERSONAL_DELETE : this.selectedAccountTypeToDelete == 1 ? ACCOUNTS_COMPANY_DELETE : '';

              this.$store
                  .dispatch(DISPATCH_ACCOUNT_ACTION, this.selectedAccountIdToDelete)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al eliminar el perfil.')
                          return;
                      }

                      if (this.selectedAccountTypeToDelete == 0) {
                          this.personalAccounts = this.personalAccounts.filter(account => account.id != this.selectedAccountIdToDelete);
                      }
                      else if (this.selectedAccountTypeToDelete == 1) {
                          this.companyAccounts = this.companyAccounts.filter(account => account.id != this.selectedAccountIdToDelete);
                      }

                      // close modal
                      this.closeModal('modal-confirm-delete')
                      this.showMainAlert('success', 'Perfil eliminardo correctamente!')
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
          },

          setSelectedAccount (accountType, accountId) {
              this.selectedAccountType = accountType;
              if (accountType == 0) {
                  this.selectedAccount = this. personalAccounts.find(account => account.id == accountId);
              }
              else if (accountType == 1) {
                  this.selectedAccount = this.companyAccounts.find(account => account.id == accountId);
             }
            setTimeout(() => {
              this.getProvincesByDepartmentId(this.selectedAccount.department_id);
              this.getDistrictsByProvinceId(this.selectedAccount.province_id);
            }, 500);
          },

          resetSelectedAccount () {
              this.selectedAccountType = null;
              this.selectedAccount = {};
          },

          resetDeleteAccount () {
              this.selectedAccountIdToDelete = null;
              this.selectedAccountTypeToDelete = null;
          },

          showDeleteConfirmation (e) {
              e.preventDefault();
              this.selectedAccountIdToDelete = this.selectedAccount.id;
              this.selectedAccountTypeToDelete = this.selectedAccountType;

              this.closeModal('modal-selected-account');
              this.showModal('modal-confirm-delete');
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

          redirectTimer (redirectMessage) {
              this.redirectMessage = redirectMessage;
              this.wizardActionsDisabled = true;
              let _this = this;

              let redirectTime = window.setInterval(function () {
                  if (_this.redirectMessageSeconds === 0) {
                      _this.redirectMessageSeconds = 0;
                      window.clearInterval(redirectTime);
                      location.reload();
                  } else {
                      _this.redirectMessageSeconds -= 1;
                  }
              }, 1000);
          }
      },
      created() {
        this.getAllAccounts();
        this.getDocumentTypes();
        this.getOcupations();
        this.getCountries();
        this.getDepartments();
      },
      computed: {
      },
  };
</script>