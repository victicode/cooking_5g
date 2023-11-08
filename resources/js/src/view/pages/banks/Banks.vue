<template>
  <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card card-custom gutter-b example example-compact">
              <div class="card-header pt-4 pb-2 min-vh-0">
                  <div class="row align-items-center">
                      <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3 class="pt-4"><strong>Bancos</strong></h3>
                      </div>
                      <div class="col-lg-4 col-md-4 col-sm-12">
                          <div class="d-flex justify-content-end">
                              <button class="btn btn-primary font-weight-bold ms-2 w-100 " v-b-modal.modal-new-bank>Nuevo banco</button>
                          </div>
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
                      <div class="col-lg-3 col-md-3 col-sm-12 me-2 mb-3">
                          <div class="input-icon">
                              <input type="text" class="form-control" placeholder="Buscar..." v-model="bankSearch">
                              <span>
                                  <i class="flaticon2-search-1 text-muted"></i>
                              </span>
                          </div>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 me-2 mb-3">
                          <div class="d-flex align-items-center">
                              <label class="me-3 mb-0 d-none d-md-block">Estado:</label>
                              <select class="form-control" v-model="bankStatus">
                                  <option value="all">Todos</option>
                                  <option value="not_deleted">Activos</option>
                                  <option value="not_active">No activos</option>
                                  <option value="deleted">Eliminados</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                          <a class="btn btn-light-primary px-6 font-weight-bold cursor-pointer me-2" @click="filterBanks()">Buscar</a>
                          <a class="btn btn-light-primary px-6 font-weight-bold cursor-pointer me-2" @click="resetFilters()">Resetear</a>
                      </div>
                  </div>

                  <hr>

                  <div class="row" v-if="!showFilteredBanks">
                      <div class="col-lg-3 col-md-6 col-sm-12 mb-3" v-for="bank in banks" :key="bank.id">
                          <div class="card card-custom gutter-b card-stretch ">
                              <div class="card-body text-center px-2">
                                  <div >
                                    <div class="mt-2 bank__name">
                                        <a class="text-dark font-weight-bold text-hover-primary font-size-h4">{{ bank.name }}</a>
                                    </div>
                                    <div class="mt-4">
                                        <img :src="bank.logo" alt="Logo del banco" width="100">
                                    </div>
                                  </div>
                                  <div class="mt-4 ">
                                      <a v-if="bank.deleted_at == null" @click="setSelectedBank(bank)" class="btn btn-light-primary btn-sm mr-2 mt-2 cursor-pointer buttons-bank" v-b-modal.modal-update-bank >
                                          <i class="far fa-edit me-1"></i>
                                          Editar
                                      </a>
                                      <a v-if="bank.deleted_at == null" @click="deleteBank(bank.id)" class="btn btn-light-danger btn-sm ms-2 mt-2 cursor-pointer buttons-bank">
                                          <i class="fas fa-trash me-1"></i>
                                          Eliminar
                                      </a>
                                      <a v-if="bank.deleted_at != null" @click="restoreBank(bank.id)" class="btn btn-light-info btn-sm mr-2 mt-2 cursor-pointer">
                                          <i class="fas fa-trash-restore-alt me-1"></i>
                                          Restaurar
                                      </a>
                                  </div>
                                  <div v-if="bank.status == 0" class="banco-desabilitado d-flex flex-column justify-content-center align-items-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                      <div>
                                        <h3><i class="fa-solid fa-triangle-exclamation text-secondary"></i></h3>
                                      </div>
                                      <div class="mt-1 text-center">
                                        <h5 class="mb-0 text-white">Desabilitado</h5> 
                                      </div>
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="row" v-if="showFilteredBanks">
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-3" v-for="bank in filteredBanks" :key="bank.id">
                      <div class="card card-custom gutter-b card-stretch ">
                          <div class="card-body text-center px-2">
                              <div >
                                <div class="mt-2 bank__name">
                                    <a class="text-dark font-weight-bold text-hover-primary font-size-h4">{{ bank.name }}</a>
                                </div>
                                <div class="mt-4">
                                    <img :src="bank.logo" alt="Logo del banco" width="100">
                                </div>
                              </div>
                              <div class="mt-4 ">
                                  <a v-if="bank.deleted_at == null" @click="setSelectedBank(bank)" class="btn btn-light-primary btn-sm mr-2 mt-2 cursor-pointer buttons-bank" v-b-modal.modal-update-bank >
                                      <i class="far fa-edit me-1"></i>
                                      Editar
                                  </a>
                                  <a v-if="bank.deleted_at == null" @click="deleteBank(bank.id)" class="btn btn-light-danger btn-sm ms-2 mt-2 cursor-pointer buttons-bank">
                                      <i class="fas fa-trash me-1"></i>
                                      Eliminar
                                  </a>
                                  <a v-if="bank.deleted_at != null" @click="restoreBank(bank.id)" class="btn btn-light-info btn-sm mr-2 mt-2 cursor-pointer">
                                      <i class="fas fa-trash-restore-alt me-1"></i>
                                      Restaurar
                                  </a>
                              </div>
                              <div v-if="bank.status == 0" class="banco-desabilitado d-flex flex-column justify-content-center align-items-center">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                  <div>
                                    <h3><i class="fa-solid fa-triangle-exclamation text-secondary"></i></h3>
                                  </div>
                                  <div class="mt-1 text-center">
                                    <h5 class="mb-0 text-white">Desabilitado</h5> 
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Modal new bank -->
      <div>
          <b-modal id="modal-new-bank" title="Crear nuevo banco" ok-title="Guardar" cancel-title="Cancelar" @ok="storeBank($event)" @hidden="resetNewBankData();resetAlertMessage();">
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                      <div class="form-group">
                          <label>Nombre</label>
                          <input type="text" class="form-control" name="name" ref="name" v-model="name" placeholder="Ingresa el nombre" @change="resetAlertMessage()">
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                      <div class="form-group">
                          <label>Logo</label>
                          <input type="file" class="form-control" name="logo" ref="logo" v-if="uploadReady" accept=".png, .jpg, .jpeg" placeholder="Seleccione el logo" @change="onFileChange($event)"/>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                      <div class="symbol symbol-lg-100">
                          <img class="w-75 mt-2" :src="logo" alt="Logo del banco">
                      </div>
                  </div>
              </div>
              <div class="row" v-if="savedAlert">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                  </div>
              </div>
          </b-modal>
      </div>

      <!-- Modal edit bank -->
      <div v-if="Object.keys(selectedBank).length">
          <b-modal id="modal-update-bank" title="Actualizar banco" ok-title="Guardar" cancel-title="Cancelar" @ok="updateBank($event)" @hidden="resetUpdateBankData();resetAlertMessage();">
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                      <div class="form-group">
                          <label>Nombre</label>
                          <input type="text" class="form-control" name="name" ref="name" v-model="selectedBank.name" placeholder="Ingresa el nombre" @change="resetAlertMessage()">
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                      <div class="form-group">
                          <label>Logo</label>
                          <input type="file" class="form-control" name="logo" ref="logo" v-if="uploadReady" accept=".png, .jpg, .jpeg" placeholder="Seleccione el logo" @change="onFileChange($event)"/>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                      <label>Activo</label>
                      <select class="select2 form-select" name="status" ref="status" v-model="selectedBank.status">
                        <option value="" >Selecciona una opción</option>
                        <option value=1>SI</option>
                        <option value=0>NO</option>
                      </select>
                    </div>
                </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                      <img :src="logo" alt="Logo del banco" width="100">
                  </div>
              </div>
              <div class="row" v-if="savedAlert">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                  </div>
              </div>
          </b-modal>
      </div>
  </div>
</template>

<script>
  import { BANKS_GET_ALL, BANKS_STORE, BANKS_DELETE, BANKS_RESTORE, BANKS_UPDATE } from "@/core/services/store/bank.module";

  export default {
      data() {
          return {
              mainAlert: false,
              mainAlertVariant: "",
              mainAlertMessage: "",
              savedAlert: false,
              savedAlertVariant: '',
              savedAlertMessage: '',
              uploadReady: true,
              default_logo: "media/images/banks/default-logo.png",
              current_logo: null,
              file_logo: null,
              name: '',
              banks: [],
              filteredBanks: [],
              bankSearch: '',
              bankStatus: 'all',
              showFilteredBanks: false,
              selectedBank: {},
          }
      },
      mounted() {
          
      },
      methods: {
          storeBank: function(e) {
              e.preventDefault();

              const name = this.$refs.name.value;

              if(!name){
                  this.showSavedAlert('danger', 'Debe ingresar el nombre del banco')
                  return;
              }

              if(!this.file_logo){
                  this.showSavedAlert('danger', 'Debe ingresar el logo del banco')
                  return;
              }

              const data = new FormData();
              data.append('logo', this.file_logo);
              data.append('name', name);

              this.$store
                  .dispatch(BANKS_STORE, data)
                  .then((data) => {
                      if (data.code != 201){
                          this.showSavedAlert('danger', data.error)
                          return;
                      }

                      this.banks = this.banks.concat(data.data);

                      // close modal
                      this.closeModal('modal-new-bank')
                      this.showMainAlert('success', 'Banco creado correctamente!')
                  })
                  .catch((err) => {
                      this.showSavedAlert('danger', err)
                      return;
                  });
          },

          getBanks () {
            this.$store
              .dispatch(BANKS_GET_ALL, '?with_deleted=true')
              .then((data) => {
                  if (data.code != 200){
                      this.showMainAlert('danger', 'Error desconocido al obtener todos los bancos.')
                      return;
                  }

                  this.banks = data.data
              })
              .catch((err) => {
                  this.showMainAlert('danger', err)
              });
          },

          deleteBank(bankId) {
              this.$store
                  .dispatch(BANKS_DELETE, bankId)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', data.error)
                          return;
                      }

                      for (let i = 0; i < this.banks.length; i++) {
                          if (this.banks[i].id == bankId) {
                              this.banks[i].deleted_at = data.data.deleted_at;
                              this.showMainAlert('success', 'Banco eliminado correctamente!')
                              return;
                          }
                      }
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                      return;
                  });
          },

          restoreBank(bankId) {
              this.$store
                  .dispatch(BANKS_RESTORE, bankId)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', data.error)
                          return;
                      }

                      for (let i = 0; i < this.banks.length; i++) {
                          if (this.banks[i].id == bankId) {
                              this.banks[i].deleted_at = null;
                              this.showMainAlert('success', 'Banco restaurado correctamente!')
                              return;
                          }
                      }
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                      return;
                  });
          },

          updateBank: function(e) {
              e.preventDefault();

              if(!this.selectedBank.name){
                  this.showSavedAlert('danger', 'Debe ingresar el nombre del banco')
                  return;
              }

              const data = new FormData();
              if(this.file_logo){
                  data.append('logo', this.file_logo);
              }
              data.append('name', this.selectedBank.name);
              data.append('bank_id', this.selectedBank.id);
              data.append('status_bank', this.selectedBank.status);

              this.$store
                  .dispatch(BANKS_UPDATE, data)
                  .then((data) => {
                      if (data.code != 200){
                          this.showSavedAlert('danger', data.error)
                          return;
                      }

                      for(let i = 0; i < this.banks.length; i++){
                          if(this.banks[i].id == data.data.id){
                              this.banks[i] = data.data;

                              // close modal
                              this.closeModal('modal-update-bank')
                              this.showMainAlert('success', 'Banco actualizado correctamente!')
                              return;
                          }
                      }
                  })
                  .catch((err) => {
                      this.showSavedAlert('danger', err)
                      return;
                  });
          },

          onFileChange(e) {
              this.file_logo = e.target.files[0];

              if (typeof FileReader === "function") {
                  const reader = new FileReader();

                  reader.onload = event => {
                      this.current_logo = event.target.result;
                  };

                  reader.readAsDataURL(this.file_logo);
                  this.resetAlertMessage()
              } else {
                  alert("Sorry, FileReader API not supported");
              }
          },

          setSelectedBank(bank) {
              this.selectedBank = Object.assign({}, bank);
              this.current_logo = this.selectedBank.logo;
          },

          resetNewBankData(){
              this.uploadReady = false;
              this.$nextTick(() => {
                  this.uploadReady = true;
              })
              this.current_logo = null;
              this.file_logo = null;
              this.name = '';
          },

          resetUpdateBankData(){
              this.selectedBank = {};
              this.uploadReady = false;
              this.$nextTick(() => {
                  this.uploadReady = true;
              })
              this.current_logo = null;
              this.file_logo = null;
          },

          resetAlertMessage(){
              this.savedAlertVariant = '';
              this.savedAlertMessage = '';
              this.savedAlert = false;
          },

          showMainAlert(variant, message) {
              this.mainAlertVariant = variant;
              this.mainAlertMessage = message;
              this.mainAlert = true;
              window.scrollTo(0,0);
          },

          showSavedAlert(variant, message) {
              this.savedAlertVariant = variant;
              this.savedAlertMessage = message;
              this.savedAlert = true;
          },

          closeModal(modalId){
              this.$root.$emit('bv::hide::modal', modalId);
          },

          filterBanks() {
                if (this.bankSearch == '' && this.bankStatus == 'all') {
                    this.showFilteredBanks = false;
                    return;
                }

                this.filteredBanks = [];

                for (let i = 0; i < this.banks.length; i++) {
                    let includeFilters = true;
                    if (this.bankSearch != '') {
                        if (!this.banks[i].name.toLowerCase().includes(this.bankSearch.toLowerCase())) {
                            includeFilters = false;
                        }
                    }
                    if (this.bankStatus != 'all') {
                        if (this.bankStatus == 'deleted') {
                            if (this.banks[i].deleted_at == null) {
                                includeFilters = false;
                            }
                        }
                        else if (this.bankStatus == 'not_deleted') {
                            if (this.banks[i].deleted_at != null) {
                                includeFilters = false;
                            }
                            if (this.banks[i].status != 1) {
                                includeFilters = false;
                            }
                        }
                        else if (this.bankStatus == 'not_active') {
                            if (this.banks[i].deleted_at != null) {
                                includeFilters = false;
                            }
                            if (this.banks[i].status != 0) {
                                includeFilters = false;
                            }
                        }
                    }

                    if (includeFilters) {
                        this.filteredBanks.push(this.banks[i])
                    }
                }

                this.showFilteredBanks = true;
          },

          resetFilters() {
              this.bankSearch = '';
              this.bankStatus = 'all';
              this.showFilteredBanks = false;
              this.filteredBanks = [];
          }
      },
      computed: {
          logo() {
              return this.current_logo == null
                  ? this.default_logo
                  : this.current_logo;
          },
      },
      created() {
          this.getBanks();
      },
  };
</script>