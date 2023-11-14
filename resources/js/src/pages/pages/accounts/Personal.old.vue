<template>
  <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card card-custom gutter-b example example-compact">
              <div class="card-header pt-4 pb-2 min-vh-0">
                  <div class="row w-100">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <h3 class="pt-4"><strong>Cuentas Personales</strong></h3>
                      </div>
                  </div>
              </div>

              <div class="card-body">
                  <div class="row" v-if="mainAlert">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
                      </div>
                      <hr>
                  </div>

                  <div class="row m-2">
                      <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                          <div class="input-icon">
                              <input type="text" class="form-control" placeholder="Buscar..." v-model="personalAccountSearchText">
                              <span>
                                  <i class="flaticon2-search-1 text-muted"></i>
                              </span>
                          </div>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                          <div class="d-flex align-items-center">
                              <label class="mr-3 mb-0 d-none d-md-block">Estado:</label>
                              <select class="form-control" v-model="personalAccountStatus">
                                  <option value="all">Todos</option>
                                  <option value="not_deleted">Activos</option>
                                  <option value="deleted">Eliminados</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                          <a class="btn btn-light-primary px-6 font-weight-bold cursor-pointer mr-2" @click="filterPersonalAccounts()">Buscar</a>
                          <a class="btn btn-light-primary px-6 font-weight-bold cursor-pointer mr-2" @click="resetFilters()">Resetear</a>
                      </div>
                  </div>

                  <hr>

                  <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <b-table
                              id="my-table"
                              :fields="fields"
                              :items="personalAccounts"
                              :per-page="perPage"
                              :current-page="currentPage"
                              head-variant="light"
                              table-variant="light"
                              hover
                          >
                          <template #cell(preferential)="data">
                              <span class="label label-lg label-light-success label-inline" v-if="data.item.preferential == 1">Activo</span>
                              <span class="label label-lg label-light-danger label-inline" v-else>Inactivo</span>
                          </template>
                          <template #cell(deleted_at)="data">
                              <span class="label label-lg label-light-danger label-inline" v-if="data.item.deleted_at">Eliminado</span>
                              <span class="label label-lg label-light-success label-inline" v-else>Activo</span>
                          </template>
                          <template #cell(actions)="data">
                              <a v-if="data.item.deleted_at == null" class="m-2" v-b-modal.modal-edit-info @click="updateSelectedAccount(data.item.id)" title="Editar cuenta"><i class="far fa-edit"></i></a>
                              <a v-if="data.item.deleted_at == null" class="m-2" v-b-modal.modal-set-preferential @click="updateSelectedAccount(data.item.id)" title="Setear preferencial"><i class="fa fa-cog"></i></a>
                              <a v-if="data.item.deleted_at == null" class="m-2 cursor-pointer" @click="deleteAccount(data.item.id)" title="Eliminar cuenta"><i class="fas fa-trash"></i></a>
                              <a v-if="data.item.deleted_at != null" class="m-2 cursor-pointer" @click="restoreAccount(data.item.id)" title="Restaurar cuenta"><i class="fas fa-trash-restore-alt"></i></a>
                          </template>
                          </b-table>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <b-pagination
                              v-model="currentPage"
                              :total-rows="rows"
                              :per-page="perPage"
                              aria-controls="my-table"
                          ></b-pagination>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Modal account info -->
      <div v-if="Object.keys(selectedAccount).length">
          <b-modal id="modal-edit-info" size="lg" title="Información de la cuenta" ok-title="Actualizar" cancel-title="Cancelar" @ok="updateAccount($event)">
              <input type="hidden" name="id" ref="id" v-model="selectedAccount.id">
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Nombre/s</label>
                          <input type="text" class="form-control" name="name" ref="name" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Apellido/s</label>
                          <input type="text" class="form-control" name="surname" ref="surname" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Tipo de documento</label>
                          <select class="form-control" name="document_type_id" ref="document_type_id" v-model="selectedAccount.document_type_id">
                              <option value="">Selecciona el tipo de documento</option>
                              <option value="1">DNI</option>
                              <option value="AF">RELLENAR CON EL RESTO</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Número de documento</label>
                          <input type="text" class="form-control" name="document_number" ref="document_number" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Fecha de nacimiento</label>
                          <input type="date" class="form-control" name="birthdate" ref="birthdate" placeholder="Selecciona la fecha de nacimiento" v-model="selectedAccount.birthdate">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Teléfono</label>
                          <input type="text" class="form-control" name="phone" ref="phone" placeholder="Ingresa el número de telefono" v-model="selectedAccount.phone">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Celular</label>
                          <input type="text" class="form-control" name="cellphone1" ref="cellphone1" placeholder="Ingresa el número de celular" v-model="selectedAccount.cellphone1">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Otro celular</label>
                          <input type="text" class="form-control" name="cellphone2" ref="cellphone2" placeholder="Ingresa otro número de celular" v-model="selectedAccount.cellphone2">
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>País</label>
                          <select class="form-control" name="country_id" ref="country_id"  v-model="selectedAccount.country_id">
                              <option value="">Selecciona el país</option>
                              <option value="1">Perú</option>
                              <option value="AF">RELLENAR CON EL RESTO</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Departamento</label>
                          <select class="form-control" name="department_id" ref="department_id"  v-model="selectedAccount.department_id">
                              <option value="">Selecciona el departamento</option>
                              <option value="1">LIMA</option>
                              <option value="AF">RELLENAR CON EL RESTO</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Provincia</label>
                          <select class="form-control" name="province_id" ref="province_id" v-model="selectedAccount.province_id">
                              <option value="">Selecciona la provincia</option>
                              <option value="1">LIMA</option>
                              <option value="AF">RELLENAR CON EL RESTO</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Distrito</label>
                          <select class="form-control" name="district_id" ref="district_id" v-model="selectedAccount.district_id">
                              <option value="">Selecciona el distrito</option>
                              <option value="1">LIMA</option>
                              <option value="AF">RELLENAR CON EL RESTO</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="form-group">
                          <label>Dirección</label>
                          <input type="text" class="form-control" name="address" ref="address" placeholder="Ingresa el domicilio" v-model="selectedAccount.address">
                      </div>
                  </div>
              </div>
              
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Ocupación</label>
                          <select class="form-control" name="ocupation_id" ref="ocupation_id" v-model="selectedAccount.ocupation_id">
                              <option value="">Selecciona la ocupación</option>
                              <option value="1">Estudiante</option>
                              <option value="AF">RELLENAR CON EL RESTO</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-12">
                      <div class="form-group">
                          <b-form-group label="Confirme por favor si usted es una persona políticamente expuesta.">
                              <b-form-radio v-model="selectedAccount.exposed_person" :checked="selectedAccount.exposed_person == 1" name="exposed_person" ref="exposed_person" size="lg" value="1">Si, soy</b-form-radio>
                              <b-form-radio v-model="selectedAccount.exposed_person" :checked="selectedAccount.exposed_person == 0" name="exposed_person" ref="exposed_person" size="lg" value="0">No, no soy</b-form-radio>
                          </b-form-group>
                      </div>
                  </div>
              </div>
              <div class="row" v-if="selectedAccount.exposed_person == 1">
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Cargo</label>
                          <input type="text" class="form-control" name="position" ref="position" placeholder="Ingresa el cargo" v-model="selectedAccount.position">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="form-group">
                          <label>Lugar de trabajo</label>
                          <input type="text" class="form-control" name="workplace" ref="workplace" placeholder="Ingresa el lugar de trabajo" v-model="selectedAccount.workplace">
                      </div>
                  </div>
              </div>
              <div class="row" v-if="updateAlert">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <b-alert show :variant="updateAlertVariant">{{updateAlertMessage}}</b-alert>
                  </div>
              </div>
          </b-modal>

          <b-modal id="modal-set-preferential" title="Tipo de cambio preferencial" ok-title="Guardar" cancel-title="Cancelar" @ok="setPreferential($event)">
              <input type="hidden" name="id" ref="id" v-model="selectedAccount.id">
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="form-group">
                          <label>Compra</label>
                          <input type="text" class="form-control" name="preferential_purchase" ref="preferential_purchase" placeholder="Ingresa el valor de compra" v-model="selectedAccount.preferential_purchase">
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="form-group">
                          <label>Venta</label>
                          <input type="text" class="form-control" name="preferential_sale" ref="preferential_sale" placeholder="Ingresa el valor de venta" v-model="selectedAccount.preferential_sale">
                      </div>
                  </div>
              </div>
              <b-button class="mt-3" variant="outline-danger" block @click="deletePreferential()" v-if="selectedAccount.preferential == 1">Eliminar tipo de cambio preferencial</b-button>
              <div class="row" v-if="preferentialAlert">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <b-alert show :variant="preferentialAlertVariant" v-bind:class="{ 'mt-2': selectedAccount.preferential == 1 }">{{preferentialAlertMessage}}</b-alert>
                  </div>
              </div>
          </b-modal>
      </div>
  </div>
</template>

<script>
  import { ACCOUNTS_PERSONAL_GET_ALL, ACCOUNTS_PERSONAL_DELETE, ACCOUNTS_PERSONAL_RESTORE, ACCOUNTS_PERSONAL_UPDATE, ACCOUNTS_PERSONAL_SET_PREFERENTIAL, ACCOUNTS_PERSONAL_DELETE_PREFERENTIAL } from "@/core/services/store/account.module";

  export default {
      data() {
          return {
              mainAlert: false,
              mainAlertVariant: "",
              mainAlertMessage: "",
              updateAlert: false,
              updateAlertVariant: '',
              updateAlertMessage: '',
              preferentialAlert: false,
              preferentialAlertVariant: '',
              preferentialAlertMessage: '',
              perPage: 10,
              currentPage: 1,
              allPersonalAccounts: [],
              personalAccounts: [],
              selectedAccount: {},
              fields: [
                  {
                      key: 'id',
                      label: 'ID',
                  },
                  {
                      key: 'name',
                      label: 'Nombre',
                  },
                  {
                      key: 'surname',
                      label: 'Apellido',
                  },
                  {
                      key: 'document_type',
                      label: 'Tipo de documento',
                  },
                  {
                      key: 'document_number',
                      label: 'Número de documento',
                  },
                  {
                      key: 'phone',
                      label: 'Teléfono',
                  },
                  {
                      key: 'address',
                      label: 'Domicilio',
                  },
                  {
                      key: 'preferential',
                      label: 'Preferencial',
                  },
                  {
                      key: 'deleted_at',
                      label: 'Estado',
                  },
                  {
                      key: 'actions',
                      label: 'Acciones',
                  },
              ],
              personalAccountSearchText: '',
              personalAccountStatus: 'all',
          }
      },
      mounted() {
          
      },
      methods: {
          getPersonalAccounts () {
              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_GET_ALL, '?with_deleted=true')
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas peronales.')
                          return;
                      }

                      this.allPersonalAccounts = data.data;

                      this.personalAccounts = this.allPersonalAccounts.map((personalAccount) => {
                          return {
                              ...personalAccount,
                              document_type: personalAccount.document_type.name
                          };
                      });
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
          },

          updateAccount (e) {
              // Prevent modal hide
              e.preventDefault();

              const id = this.$refs.id.value;
              const name = this.$refs.name.value;
              const surname = this.$refs.surname.value;
              const document_type_id = this.$refs.document_type_id.value;
              const document_number = this.$refs.document_number.value;
              const birthdate = this.$refs.birthdate.value;
              const phone = this.$refs.phone.value;
              const cellphone1 = this.$refs.cellphone1.value;
              const cellphone2 = this.$refs.cellphone2.value;
              const country_id = this.$refs.country_id.value;
              const department_id = this.$refs.department_id.value;
              const province_id = this.$refs.province_id.value;
              const district_id = this.$refs.district_id.value;
              const address = this.$refs.address.value;
              const ocupation_id = this.$refs.ocupation_id.value;
              const exposed_person = this.selectedAccount.exposed_person;

              let data;
              if(exposed_person == 1){
                  const position = this.$refs.position.value;
                  const workplace = this.$refs.workplace.value;
                  data = { id, name, surname, document_type_id, document_number, birthdate, phone, cellphone1, cellphone2, country_id, department_id, province_id, district_id, address, ocupation_id, exposed_person, position, workplace }
              }
              else{
                  data = { id, name, surname, document_type_id, document_number, birthdate, phone, cellphone1, cellphone2, country_id, department_id, province_id, district_id, address, ocupation_id, exposed_person }
              }

              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_UPDATE, data)
                  .then((data) => {
                      if (data.code != 200){
                          this.showUpdateAlert('danger', data.error)
                          return;
                      }

                      let allPersonalAccountIndex = this.allPersonalAccounts.findIndex(personalAccount => personalAccount.id == data.data.id);

                      if (allPersonalAccountIndex > -1) {
                          this.allPersonalAccounts[allPersonalAccountIndex] = data.data;
                      }

                      let personalAccountIndex = this.personalAccounts.findIndex(personalAccount => personalAccount.id == data.data.id);

                      if (personalAccountIndex > -1) {
                          this.personalAccounts[personalAccountIndex] = {
                              ...this.allPersonalAccounts[allPersonalAccountIndex],
                              document_type: data.data.document_type.name,
                          };
                      }

                      // Slice method is used to 'refresh' the personalAccounts variable to vuejs detect a change.
                      this.personalAccounts = this.personalAccounts.slice();

                      // close modal
                      this.closeModal('modal-edit-info')
                      this.showMainAlert('success', 'Cuenta personal actualizada correctamente!')
                      return;
                  })
                  .catch((err) => {
                      this.showUpdateAlert('danger', err)
                      return;
                  });
          },

          deleteAccount (personalAccountId) {
              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_DELETE, personalAccountId)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', data.error)
                          return;
                      }

                      let allPersonalAccountIndex = this.allPersonalAccounts.findIndex(personalAccount => personalAccount.id == data.data.id);

                      if (allPersonalAccountIndex > -1) {
                          this.allPersonalAccounts[allPersonalAccountIndex].deleted_at = data.data.deleted_at;
                      }

                      let personalAccountIndex = this.personalAccounts.findIndex(personalAccount => personalAccount.id == data.data.id);

                      if (personalAccountIndex > -1) {
                          this.personalAccounts[personalAccountIndex].deleted_at = data.data.deleted_at;
                      }

                      this.showMainAlert('success', 'Cuenta personal eliminada correctamente!')
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                      return;
                  });
          },

          restoreAccount (personalAccountId) {
              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_RESTORE, personalAccountId)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', data.error)
                          return;
                      }

                      let allPersonalAccountIndex = this.allPersonalAccounts.findIndex(personalAccount => personalAccount.id == data.data.id);

                      if (allPersonalAccountIndex > -1) {
                          this.allPersonalAccounts[allPersonalAccountIndex].deleted_at = null;
                      }

                      let personalAccountIndex = this.personalAccounts.findIndex(personalAccount => personalAccount.id == data.data.id);

                      if (personalAccountIndex > -1) {
                          this.personalAccounts[personalAccountIndex].deleted_at = null;
                      }

                      this.showMainAlert('success', 'Cuenta personal restaurada correctamente!')
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                      return;
                  });
          },

          setPreferential (e) {
              e.preventDefault();

              const id = this.selectedAccount.id;
              const preferential_purchase = this.$refs.preferential_purchase.value;
              const preferential_sale = this.$refs.preferential_sale.value;

              if(!preferential_purchase){
                  this.showPreferentialAlert('danger', 'Debe completar el valor de compra.')
                  return;
              }

              if(!preferential_sale){
                  this.showPreferentialAlert('danger', 'Debe completar el valor de venta.')
                  return;
              }

              let data;
              data = { id, preferential_purchase, preferential_sale }

              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_SET_PREFERENTIAL, data)
                  .then((data) => {
                      if (data.code != 200){
                          this.showPreferentialAlert('danger', data.error)
                          return;
                      }

                      let allPersonalAccountIndex = this.allPersonalAccounts.findIndex((personalAccount => personalAccount.id == data.data.id));
                      this.allPersonalAccounts[allPersonalAccountIndex].preferential = 1;
                      this.allPersonalAccounts[allPersonalAccountIndex].preferential_purchase = data.data.preferential_purchase;
                      this.allPersonalAccounts[allPersonalAccountIndex].preferential_sale = data.data.preferential_sale;

                      let personalAccountIndex = this.personalAccounts.findIndex((personalAccount => personalAccount.id == data.data.id));
                      this.personalAccounts[personalAccountIndex].preferential = 1;
                      this.personalAccounts[personalAccountIndex].preferential_purchase = data.data.preferential_purchase;
                      this.personalAccounts[personalAccountIndex].preferential_sale = data.data.preferential_sale;

                      // close modal
                      this.closeModal('modal-set-preferential')
                      this.showMainAlert('success', 'Preferencial seteado correctamente!')
                      return;
                  })
                  .catch((err) => {
                      this.showPreferentialAlert('danger', err)
                      return;
                  });
          },

          deletePreferential () {
              this.$store
                  .dispatch(ACCOUNTS_PERSONAL_DELETE_PREFERENTIAL, this.selectedAccount.id)
                  .then((data) => {
                      if (data.code != 200){
                          this.showPreferentialAlert('danger', data.error)
                          return;
                      }

                      let allPersonalAccountIndex = this.allPersonalAccounts.findIndex((personalAccount => personalAccount.id == data.data.id));
                      this.allPersonalAccounts[allPersonalAccountIndex].preferential = 0;
                      this.allPersonalAccounts[allPersonalAccountIndex].preferential_purchase = null;
                      this.allPersonalAccounts[allPersonalAccountIndex].preferential_sale = null;

                      let personalAccountIndex = this.personalAccounts.findIndex((personalAccount => personalAccount.id == data.data.id));
                      this.personalAccounts[personalAccountIndex].preferential = 0;
                      this.personalAccounts[personalAccountIndex].preferential_purchase = null;
                      this.personalAccounts[personalAccountIndex].preferential_sale = null;

                      // close modal
                      this.closeModal('modal-set-preferential')
                      this.showMainAlert('success', 'Preferencial eliminado correctamente!')
                      return;
                  })
                  .catch((err) => {
                      this.showPreferentialAlert('danger', err)
                      return;
                  });
          },

          updateSelectedAccount (id) {
              this.updateAlert = false;
              this.updateAlertMessage = '';
              this.updateAlertVariant = '';
              this.preferentialAlert = false;
              this.preferentialAlertVariant = '';
              this.preferentialAlertMessage = '';

              this.selectedAccount = Object.assign({}, this.allPersonalAccounts.find(account => account.id === id));
          },

          showMainAlert(variant, message) {
              this.mainAlertVariant = variant;
              this.mainAlertMessage = message;
              this.mainAlert = true;
              window.scrollTo(0,0);
          },

          showUpdateAlert(variant, message) {
              this.updateAlertVariant = variant;
              this.updateAlertMessage = message;
              this.updateAlert = true;
          },

          showPreferentialAlert(variant, message) {
              this.preferentialAlertVariant = variant;
              this.preferentialAlertMessage = message;
              this.preferentialAlert = true;
          },

          closeModal(modalId){
              this.$root.$emit('bv::hide::modal', modalId);
          },

          filterPersonalAccounts() {
              if (this.personalAccountSearchText == '' && this.personalAccountStatus == 'all') {
                  this.resetFilters();
                  return;
              }

              this.personalAccounts = [];

              for (let i = 0; i < this.allPersonalAccounts.length; i++) {
                  let includeFilters = false;
                  if (this.personalAccountSearchText != '') {
                      if (this.allPersonalAccounts[i].name.toLowerCase().includes(this.personalAccountSearchText.toLowerCase())) {
                          includeFilters = true;
                      }
                      if (this.allPersonalAccounts[i].surname.toLowerCase().includes(this.personalAccountSearchText.toLowerCase())) {
                          includeFilters = true;
                      }
                      if (this.allPersonalAccounts[i].document_number.includes(this.personalAccountSearchText.toLowerCase())) {
                          includeFilters = true;
                      }
                      if (this.allPersonalAccounts[i].phone.includes(this.personalAccountSearchText.toLowerCase())) {
                          includeFilters = true;
                      }
                      if (this.allPersonalAccounts[i].address.toLowerCase().includes(this.personalAccountSearchText.toLowerCase())) {
                          includeFilters = true;
                      }
                  }
                  if (this.personalAccountStatus != 'all') {
                      if (this.personalAccountStatus == 'deleted') {
                          if (this.allPersonalAccounts[i].deleted_at != null) {
                              includeFilters = true;
                          }
                      }
                      else if (this.personalAccountStatus == 'not_deleted') {
                          if (this.allPersonalAccounts[i].deleted_at == null) {
                              includeFilters = true;
                          }
                      }
                  }

                  if (includeFilters) {
                      this.personalAccounts.push({
                          ...this.allPersonalAccounts[i],
                          document_type: this.allPersonalAccounts[i].document_type.name
                      })
                  }
              }
          },

          resetFilters() {
              this.personalAccountSearchText = '';
              this.personalAccountStatus = 'all';
              this.personalAccounts = this.allPersonalAccounts.map((personalAccount) => {
                  return {
                      ...personalAccount,
                      document_type: personalAccount.document_type.name
                  };
              });
          }
      },
      computed: {
          rows() {
              return this.personalAccounts.length
          },
      },
      created() {
          this.getPersonalAccounts();
      },
  };
</script>