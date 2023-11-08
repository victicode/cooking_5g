<template>
  <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card card-custom gutter-b example example-compact">
              <div class="card-header pt-4 pb-2 min-vh-0">
                  <div class="row w-100">
                      <div class="col-lg-4 col-md-12 col-sm-12">
                          <h3 class="pt-4"><strong>Mis Operaciones</strong></h3>
                      </div>
                  </div>
              </div>

              <div class="row" v-if="mainAlert">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
                  </div>
                  <hr>
              </div>

              <div class="card-body">
                  <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <!-- Set per-page to 0 to disable local pagination -->
                          <b-table
                              id="my-table"
                              :fields="fields"
                              :items="operations"
                              :per-page="0" 
                              :current-page="currentPage"
                              head-variant="light"
                              table-variant="light"
                              hover
                              show-empty
                          >
                              <template #cell(name)="data">
                                  <div v-if="data.item.bank_account_send.personal_account != null">
                                      {{ data.item.bank_account_send.personal_account.name }} {{ data.item.bank_account_send.personal_account.surname }}
                                  </div>
                                  <div v-else-if="data.item.bank_account_send.company_account != null">
                                      {{ data.item.bank_account_send.company_account.business_name }}
                                  </div>
                              </template>
                              <template #cell(amount)="data">
                                  {{ data.item.bank_account_send.coin_type.name }} {{ $func.numberFormat(data.item.amount.toFixed(2)) }}
                              </template>
                              <template #cell(change_amount)="data">
                                  {{ data.item.bank_account_receive.coin_type.name }} {{ $func.numberFormat(data.item.change_amount.toFixed(2)) }}
                              </template>
                              <template #cell(exchange_rate)="data">
                                  {{ $func.numberFormat(data.item.exchange_rate.toFixed(3)) }}
                              </template>
                              <template #cell(status)="data">
                                  <span class="label label-lg label-light-danger label-inline" v-if="data.item.status == -1">{{ data.item.status_label }}</span>
                                  <span class="label label-lg label-light-warning label-inline" v-else-if="data.item.status == 1">{{ data.item.status_label }}</span>
                                  <span class="label label-lg label-light-primary label-inline" v-else-if="data.item.status == 2">{{ data.item.status_label }}</span>
                                  <span class="label label-lg label-light-success label-inline" v-else-if="data.item.status == 3">{{ data.item.status_label }}</span>
                              </template>

                              <template #cell(actions)="data">
                                  <a class="m-2" v-b-modal.modal-operation-information @click="setSelectedOperation(data.item.id)" title="Ver operación"><i class="fas fa-eye text-dark"></i></a>
                              </template>

                              <template #empty="">
                                  <div role="alert" aria-live="polite"><div class="text-center my-2">No se encontraron resultados.</div></div>
                              </template>
                          </b-table>
                      </div>

                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <b-pagination
                              v-model="currentPage"
                              :total-rows="totalItems"
                              :per-page="perPage"
                              aria-controls="my-table"
                          ></b-pagination>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Modal operation data -->
      <div v-if="Object.keys(selectedOperation).length">
          <b-modal id="modal-operation-information" size="md" title="Datos de la operación" ok-only ok-title="Cerrar" @hidden="resetSelectedOperation();resetOperationDataAlert();">
              <div class="row justify-content-center">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Fecha de creación:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{selectedOperation.created_at}}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Fecha de actualización:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{selectedOperation.updated_at}}</strong></p>
                      </div>
                  </div>

                  <!-- Personal account data: START -->
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.personal_account != null">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Nombre:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ selectedOperation.bank_account_send.personal_account.name }} {{ selectedOperation.bank_account_send.personal_account.surname }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.personal_account != null">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">{{ selectedOperation.bank_account_send.personal_account.document_type.name }}:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ selectedOperation.bank_account_send.personal_account.document_number }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.personal_account != null">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Email:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ selectedOperation.bank_account_send.personal_account.user.email }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.personal_account != null && (selectedOperation.bank_account_send.personal_account.phone != null || selectedOperation.bank_account_send.personal_account.cellphone1 != null || selectedOperation.bank_account_send.personal_account.cellphone2 != null)">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Teléfono:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{ selectedOperation.bank_account_send.personal_account.phone != null ? selectedOperation.bank_account_send.personal_account.phone : '' }}
                                  {{ selectedOperation.bank_account_send.personal_account.phone != null ? ' | ' : '' }}{{ selectedOperation.bank_account_send.personal_account.cellphone1 != null ? selectedOperation.bank_account_send.personal_account.cellphone1 : '' }}
                                  {{ selectedOperation.bank_account_send.personal_account.cellphone1 != null && selectedOperation.bank_account_send.personal_account.cellphone2 != null ? ' | ' : '' }}{{ selectedOperation.bank_account_send.personal_account.cellphone2 != null ? selectedOperation.bank_account_send.personal_account.cellphone2 : '' }}
                              </strong>
                          </p>
                      </div>
                  </div>
                  <!-- Personal account data: END -->

                  <!-- Company account data: START -->
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.company_account != null">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Nombre:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ selectedOperation.bank_account_send.company_account.business_name }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.company_account != null">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">RUC:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ selectedOperation.bank_account_send.company_account.ruc }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.company_account != null">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Email:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ selectedOperation.bank_account_send.company_account.user.email }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12" v-if="selectedOperation.bank_account_send.company_account != null && (selectedOperation.bank_account_send.company_account.phone != null || selectedOperation.bank_account_send.company_account.phone_c != null)">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Teléfono:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{ selectedOperation.bank_account_send.company_account.phone != null ? selectedOperation.bank_account_send.company_account.phone : '' }}
                                  {{ selectedOperation.bank_account_send.company_account.phone != null ? ' | ' : '' }}{{ selectedOperation.bank_account_send.company_account.phone_c != null ? selectedOperation.bank_account_send.company_account.phone_c : '' }}
                              </strong>
                          </p>
                      </div>
                  </div>
                  <!-- Company account data: END -->

                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Tipo de cambio:</span>
                          <p class="text-hover-primary m-0 h6"><strong>{{ $func.numberFormat(selectedOperation.exchange_rate.toFixed(3)) }}</strong></p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Cuenta de envío:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{selectedOperation.bank_account_send.bank.name}}
                              </strong>
                          </p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Monto enviado:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{selectedOperation.bank_account_send.coin_type.name}} {{ $func.numberFormat(selectedOperation.amount.toFixed(2)) }}
                              </strong>
                          </p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Cuenta de destino:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{selectedOperation.bank_account_receive.bank.name}}
                                  | {{selectedOperation.bank_account_receive.account_number}}
                                  | {{selectedOperation.bank_account_receive.account_number_cci}}
                              </strong>
                          </p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Monto esperado:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{selectedOperation.bank_account_receive.coin_type.name}} {{ $func.numberFormat(selectedOperation.change_amount.toFixed(2)) }}
                              </strong>
                          </p>
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold mr-2 h6">Cuenta de destino:</span>
                          <p class="text-hover-primary m-0 h6">
                              <strong>
                                  {{selectedOperation.bank_account_transfer.bank.name}}
                                  | {{selectedOperation.bank_account_transfer.account_number}}
                                  | {{selectedOperation.bank_account_transfer.account_number_cci}}
                              </strong>
                          </p>
                      </div>
                  </div>
              </div>

              <div class="row" v-if="operationDataAlert">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <b-alert :show="operationDataAlert" dismissible fade :variant="operationDataAlertVariant" @dismissed="operationDataAlert=false">{{ operationDataAlertMessage }}</b-alert>
                  </div>
              </div>
          </b-modal>
      </div>
  </div>
</template>

<script>
  import { OPERATIONS_GET_PAGINATED } from "@/core/services/store/operation.module";

  export default {
      data() {
          return {
              mainAlert: false,
              mainAlertVariant: "",
              mainAlertMessage: "",
              operationDataAlert: false,
              operationDataAlertVariant: '',
              operationDataAlertMessage: '',
              allOperations: [],
              operations: [],
              selectedOperation: {},
              // Table variables
              currentPage: 1,
              totalItems : 0,
              perPage: 15,
              fields: [
                  {
                      key: 'created_at',
                      label: 'Fecha',
                  },
                  {
                      key: 'name',
                      label: 'Nombre',
                  },
                  {
                      key: 'amount',
                      label: 'Monto enviado',
                  },
                  {
                      key: 'change_amount',
                      label: 'Monto a recibir',
                  },
                  {
                      key: 'exchange_rate',
                      label: 'Tipo de cambio',
                  },
                  {
                      key: 'status',
                      label: 'Estado',
                  },
                  {
                      key: 'actions',
                      label: 'Acciones',
                      thStyle: {width: '150px !important'}
                  },
              ],
          }
      },
      mounted() {
          
      },
      methods: {
          getOperations () {
              let query = '?page=' + this.currentPage;
              setTimeout(()=>{
                  this.$store
                  .dispatch(OPERATIONS_GET_PAGINATED, query)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al obtener las operaciones.')
                          return;
                      }

                      this.allOperations = data.data.data;
                      this.operations = this.allOperations;

                      // Table data
                      this.totalItems = data.data.total;
                      this.perPage = data.data.per_page
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
              },600)
              
          },

          setSelectedOperation(operationId) {
              this.selectedOperation = Object.assign({}, this.allOperations.find(operation => operation.id == operationId));
          },

          resetSelectedOperation(){
              this.selectedOperation = {};
          },

          resetOperationDataAlert(){
              this.operationDataAlertVariant = '';
              this.operationDataAlertMessage = '';
              this.operationDataAlert = false;
          },

          showMainAlert(variant, message) {
              this.mainAlertVariant = variant;
              this.mainAlertMessage = message;
              this.mainAlert = true;
              window.scrollTo(0,0);
          },

          closeModal(modalId){
              this.$root.$emit('bv::hide::modal', modalId);
          },
      },
      watch: {
          currentPage: {
              handler: function() {
                  this.getOperations();
              }
          }
      },
      computed: {

      },
      created() {
          this.getOperations();
      },
  };
</script>