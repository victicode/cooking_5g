<template>
	<div>
		
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inicio / </span> Movimientos</h4>
			</div>
      <div class="row">
        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row   align-items-end ">
                <div class="col-6 col-lg-4">
                  <label class="form-label fw-bold">Fecha:</label>
                  <div class="mb-0">
                    <input
                      id="filter_table_date"
                      type="text"
                      class="form-control dt-date flatpickr-range dt-input"
                      data-column="1"
                      placeholder="Buscar operaciones desde - hasta"
                      data-column-index="1"
                      name="dt_date"
                      ref="date_range"
                    />
                    <input
                      type="hidden"
                      ref="start_date"
                      class="form-control dt-date start_date dt-input"
                      data-column="1"
                      data-column-index="1"
                      name="value_from_start_date"
                    />
                    <input
                      type="hidden"
                      ref="end_date"
                      class="form-control dt-date end_date dt-input"
                      name="value_from_end_date"
                      data-column="1"
                      data-column-index="1"
                    />
                  </div>
                </div>
                <div class="col-6 col-lg-3 mt-2">
                  <label class="form-label fw-bold">Banco:</label>
                  <div class="mb-0">

                    <select 
                      id="filter_table_bank"
                      class="form-control filter-operation"  
                      placeholder="Seleciona un banco"
                      data-column-index="11"
                      data-column="11"
                      name="bank_filter"
                      ref="bank_filter"
                      @change="filterColumn()">
                      <option value="">Seleciona un banco</option>
                      <template v-for="bank in banks" >
                        <option  :value="bank.id">{{ bank.name }}</option>
                      </template>
                    </select>
                  </div>
                </div>
                <div class="col-6 col-lg-3 mt-2">
                  <label class="form-label fw-bold">N° de referencia:</label>
                  <div class="mb-0">
                    <input
                      id="filter_table_operation_number"
                      type="text"
                      class="form-control filter-operation"
                      data-column="operation_number"
                      placeholder="Numero de referencia"
                      name="ref_num"
                      autocomplete="off"
                      ref="operation_reference_num"
                      @change="filterColumn()"
                    />
                  </div>
                </div>
                <div class="col-6 col-lg-2 mt-2 justify-content-center" >
                  <button class="btn btn-primary font-weight-bold ml-2 col-12  px-sm-2" onsubmit="false"  @click="resetFilter()">
                    Resetear
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-datatable table-responsive">
          <table class="datatables-basic table" id="table">
            <thead>
              <tr>
                <th class="text-center">id</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Número de operación</th>
                <th class="text-center">Cuenta || Banco donde envio</th>
                <th class="text-center">Monto enviado</th>
                <th class="text-center">Tasa de cambio</th>
                <th class="text-center">Cuenta || Banco donde recibo</th>
                <th class="text-center">Monto a recibir</th>
                <th class="text-center">Tipo de operación</th>
                <th class="text-center">Estado</th>
                <th class="text-center">banco</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
		</div>
		<div class="modal fade modal-lg" id="operationModal" v-if="showPass != 0" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-0 p-md-3 ">
          <div class="modal-body py-1 px-0 px-md-3">
            <div class="content-header mb-0">
							<div class="pt-3 mb-3 text-center">
								<h4 class="fw-bold mb-1">
									<span class="text-muted fw-light">Datos</span> 
									de la transacción
								</h4>
								<span :class="'badge '+ statusLabel[selectedOperation.status].class">{{statusLabel[selectedOperation.status].title}}</span>
							</div>
            </div>
            <div class="w-100 row justify-content-between mx-0 mt-2 mb-2">
              <h6 class="card-title text-center  text-md-start mb-2  col-12 col-md-6 px-md-0" v-if="selectedOperation.operation_number != '' ">
                Número de operacion:  #<strong>{{selectedOperation.operation_number}}</strong>
              </h6>
              <h6 class="card-title text-center mb-2  col-12 col-md-6 px-md-0 text-md-end"> Fecha:  <strong>{{selectedOperation.created_at}}</strong></h6>
            </div>		
            <div class="w-100 row justify-content-between mx-0 mt-2 mb-2">
              <h6 class="card-title text-center text-md-start  mb-2  col-12 col-md-6 px-md-0 " v-if="selectedOperation.operation_number != '' ">
                Origenes de fondo: <strong>{{ selectedOperation.other_fund_origin != null ? selectedOperation.other_fund_origin : selectedOperation.fund_origin.name}}</strong>
              </h6>
              <h6 class="card-title text-center  text-md-end mb-2 justify-content-center justify-content-md-end col-12 col-md-6 px-md-0 d-flex"> 
                <strong>
                  <a 
                    :href="'/public/'+selectedOperation.vaucher" 
                    class=" d-flex justify-content-center align-items-baseline mx-2" target="_blank"
                  > 
                    <i class="fa-regular fa-images fa-xl text-primary me-2"></i>
                    Ver vaucher
                  </a>
                </strong>
              </h6>
            </div>
            
            <div class="d-flex justify-content-between px-3 align-items-center amount_section mt-0 mb-2 py-1">
                <div class="col-8">
									<h6 class=" mb-0"><strong class="fw-bold text-primary">Enviaste:</strong></h6>
                  <h4 class="mb-0 text-primary">
                    {{ selectedOperation.bank_account_send.coin_type_id == 1 ? 'S/.' : '$' }} {{ $func.numberFormat(parseFloat(selectedOperation.amount)) }}
                  </h4>
                </div>
                <div class="col-4">
                  <h6 class="mt-2 mb-0 h8 fw-light row px-0 justify-content-center ">
										<strong class="fw-bold text-primary col-12 col-md-6 px-1 text-center text-md-end">Recibes:</strong> 
										<span class="col-12 w-auto fw-bold px-0 text-center text-md-start">
                      {{selectedOperation.bank_account_receive.coin_type_id == 1 ? 'S/.' : '$'}} {{ $func.numberFormat(parseFloat(selectedOperation.change_amount).toFixed(2))}} 
                    </span>
                      
									</h6>
                  <h6 class="mb-2 h8 fw-light row px-0 text-center mt-1 mt-md-0">
										<strong class="fw-bold text-primary col-12 col-md-6 px-1 text-center text-md-end">Tasa:</strong> 
                    <span class="col-12 w-auto fw-bold px-0 text-center text-md-start">
                      {{parseFloat(selectedOperation.exchange_rate).toFixed(3)}} 
                    </span>
									</h6>
                </div>
            </div>
            <div class="d-flex flex-column px-2 mb-3">
              <div class="d-flex flex-column justify-content-center align-items-center">
                <!-- <div class="col-11 px-0 py-1 my-1 card-bank-content "> -->
                <div class="col-12 px-0 py-1 my-1 ">
                  <div class="col-12  d-flex flex-column  align-items-center card-transfer2" style="">
                    <div class=""><h6 class="mb-0  mt-2">Cuenta desde la que realizaste la trasacción</h6></div>
                    <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                      <div>
                        <img class="img-convert" :src="selectedOperation.bank_account_send.bank.logo" />
                        <h6 class="text-img-converte  fw-bold">
                          {{ 
                          selectedOperation.bank_account_send.owner_name != null ? selectedOperation.bank_account_send.owner_name : 
                          selectedOperation.bank_account_send.personal_account != null ? selectedOperation.bank_account_send.personal_account.name +' '+selectedOperation.bank_account_send.personal_account.surname :
                          selectedOperation.bank_account_send.company_account.business_name
                          }}
                        </h6>
                      </div>
                      <div class="d-flex">
                        <div class="text-end">
                          <h6 class="mb-2  text-primary">{{ selectedOperation.bank_account_send.coin_type_id == 1  ? 'Cuenta Soles' : 'Cuenta dolares' }}</h6>
                          <h6 class="mb-0 ">{{ selectedOperation.bank_account_send.account_number }}</h6>
                        </div>
                      </div>
                    </div>
                  </div>  
                </div>
                <div class="col-12 px-0 py-1 my-1 ">
                  <div class="col-12  d-flex flex-column  align-items-center align-items-center card-transfer2" style="">
                    <div class=""><h6 class="mb-0  mt-2">Cuenta a la que realizaste la trasacción</h6></div>
                    <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                      <div>
                        <img class="img-convert" :src="selectedOperation.bank_account_transfer.bank.logo" />
                        <h6 class="text-img-converte  fw-bold">
                          {{ 
                          selectedOperation.bank_account_transfer.owner_name != null ? selectedOperation.bank_account_transfer.owner_name : 
                          selectedOperation.bank_account_transfer.personal_account != null ? selectedOperation.bank_account_transfer.personal_account.name +' '+ selectedOperation.bank_account_transfer.personal_account.surname :
                          selectedOperation.bank_account_receive.company_account.business_name
                          }}
                        </h6>
                      </div>
                      <div class="d-flex">
                        <div class="text-end">
                          <h6 class="mb-2  text-primary">{{ selectedOperation.bank_account_transfer.coin_type_id == 1 ? 'Cuenta Soles' : 'Cuenta dolares' }}</h6>
                          <h6 class="mb-0 ">{{ selectedOperation.bank_account_transfer.account_number }}</h6>
                        </div>
                      </div>
                    </div>
                  </div>  
                </div>
                <!-- <div class="col-11 px-0 py-1 my-1 card-bank-content2 "> -->
                <div class="col-12 px-0 py-1 my-1 ">
                  <div class="col-12  d-flex flex-column  align-items-center card-transfer2" style="">
                    <div class=""><h6 class="mb-0  mt-2">Cuenta a la que vas a recibir</h6></div>
                    <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                      <div>
                        <img class="img-convert" :src="selectedOperation.bank_account_receive.bank.logo" />
                        <h6 class="text-img-converte  fw-bold">
                          {{ 
                          selectedOperation.bank_account_receive.owner_name != null ? selectedOperation.bank_account_receive.owner_name : 
                          selectedOperation.bank_account_receive.personal_account != null ? selectedOperation.bank_account_receive.personal_account.name +' '+selectedOperation.bank_account_receive.personal_account.surname :
                          selectedOperation.bank_account_receive.company_account.business_name
                          }}
                        </h6>
                      </div>
                      <div class="d-flex">
                        <div class="text-end">
                          <h6 class="mb-2  text-primary">{{ selectedOperation.bank_account_receive.coin_type_id == 1 ? 'Cuenta Soles' : 'Cuenta dolares' }}</h6>
                          <h6 class="mb-0 ">{{ selectedOperation.bank_account_receive.account_number }}</h6>
                        </div>
                      </div>
                    </div>
                  </div>  
                </div>
                <div class="col-12 d-flex justify-content-center mt-3">
                  <button class="btn btn-label-secondary  align-items-baseline w-25" @click="hideModal()"> 
                  	<span class="align-middle d-inline-block">Cerrar</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>
</template>
<style lang="scss">
	@import "@/assets/libs/datatables-bs5/datatables.bootstrap5.scss";
		@import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
		@import "@/assets/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.scss";
		@import "@/assets/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.scss";
		@import "@/assets/libs/datatables-responsive-bs5/responsive.bootstrap5.scss";
		@import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
    @import "@/assets/libs/flatpickr/flatpickr.scss";

</style>
<script>
	import { GET_OPERATION_BY_ID} from "@/core/services/store/operation.module";
  import { BANKS_GET_ALL } from "@/core/services/store/bank.module";
  import { GET_SYSTEM_CONFIGURATION_VALUE } from "@/core/services/store/configuration.module";

  import { bootstrap } from "@/concept/bootstrap";
  import flatpickr from "flatpickr";
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import moment from 'moment';

	
	// DataTable.use(DataTablesCore);
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
							openModal:'',
							showPass: 0,
							tabled:'',
              banks:{},
							statusLabel: {
								'-1':	{ title: 'Cancelado', class: ' bg-label-danger' },
								1:	{ title: 'Pendiente', class: ' bg-label-warning' },
								2: 	{ title: 'Procesando', class: 'bg-label-primary' },
								3: 	{ title: 'Finalizado', class: ' bg-label-success' },
							}
							// Table variables
					}
			},
			methods: {
        async selectAccountToAction(idAccount){
            this.$store
              .dispatch(GET_OPERATION_BY_ID, idAccount)
              .then((response) => {
                this.selectedOperation = Object.assign({}, response.data);
                return new Promise((resolve) => {
                   resolve(response.data);
                });
              })
              .catch((err) => {
                console.log(err)
                return new Promise((resolve) => {
                  resolve(false);
                });
              });
        },
        getBanks() {
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
        getOperationsDataTable(){
          const dataForSecretKey = new FormData();
          dataForSecretKey.append('id', 2) // GET KEY FOR SECURE API;

          this.$store
          .dispatch(GET_SYSTEM_CONFIGURATION_VALUE, dataForSecretKey).then((keyData)=>{
            this.tabled = new DataTable('#table', {
              ajax: {
                url: 'https://cambio.dlsmoney.com/api/operations-data-table',
                // url: 'http://127.0.0.1:8000/api/operations-data-table',
                type: "POST",
                data: function ( data ) {
                    data.keyForApi = keyData.data.value
                    data.filter_bank_id = document.getElementById('filter_table_bank').value
                    data.filter_operation_number = document.getElementById('filter_table_operation_number').value
                    data.filter_start_date = document.querySelector('.start_date').value;
                    data.filter_end_date = document.querySelector('.end_date').value;
                }
              },
              dataType:'json',
              processing: true,
              serverSide: true,
              scrollX: true,
              drawCallback: function( settings ) {
                const TableElement = document.getElementById('table');
                const event = new Event("OptionsActionTable")
                TableElement.dispatchEvent(event);
              },
              columnDefs: [
                { className: 'text-center',
                  targets: 0,
                  searchable: true,
                  visible: false,
                  
                  render: function (data, type, full, meta) {
                    return full.id
                  }
                },
                {
                  className: 'text-center',
                  targets: 1,
                  searchable: true,
                  visible: true,
                  sorteable:false,
                  
                  responsivePriority: 0,
                  render: function (data, type, full, meta) {
                    let date = new Date(full.created_at)
                    return (
                      `${date.getDate()}/${date.getMonth() >= 9 ? (date.getMonth()+1) : '0' + (date.getMonth()+1)}/${date.getFullYear()} ` 
                    )
                  }
                },
                {	  
                  className: 'text-center',										
                  targets: 2,
                  searchable: true,
                  responsivePriority: 1,
                  render: function (data, type, full, meta) {


                    return (
                      `#${full.operation_number} ` 
                    )
                  }
                },
                {
                  className: 'text-center',
                  targets: 3,
                  searchable: false,
                  visible: false,
                  render: function (data, type, full, meta) {

                    return (
                      `${full.bank_account_send.account_number} || ${full.bank_account_send.bank.name} ` 
                    )
                  }
                },
                {	  
                  className: 'text-center',										
                  targets: 4,
                  searchable: true,
                  render: function (data, type, full, meta) {
                    var $coinType = full.bank_account_send.coin_type;

                    return (
                      `${$coinType.abbreviation} ${$number_format(full.amount.toFixed(2))}` 
                    )
                  }
                  
                  
                },
                { 
                  className: 'text-center',
                  targets: 5,
                  searchable: true,
                  visible: true,
                  
                  responsivePriority: 3,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.exchange_rate.toFixed(3)}` 
                    )
                  }
                },
                {
                  className: 'text-center',
                  targets: 6,
                  searchable: false,
                  visible: false,
                  
                  responsivePriority: 5,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.bank_account_receive.account_number} || ${full.bank_account_receive.bank.name} ` 
                    )
                  }
                },
                { 
                  className: 'text-center',
                  targets: 7,
                  searchable: true,
                  
                  responsivePriority: 4,
                  render: function (data, type, full, meta) {
                    var $coinType = full.bank_account_receive.coin_type;

                    return (
                      `${$coinType.abbreviation} ${$number_format(full.change_amount.toFixed(2))}` 
                    )
                  }
                },
                {
                  className: 'text-center',
                  targets: 8,
                  searchable: false,
                  visible: false,
                  
                  responsivePriority: 5,
                  render: function (data, type, full, meta) {
                    var $coinType = full.bank_account_receive.coin_type_id == 1 ? 'Compra de dólares' : 'Venta de dólares';
                    return (
                      `${$coinType }` 
                    )
                  }
                },
                {         
                  className: 'text-center',
                  targets: 9,
                  responsivePriority: 6,
                  render: function (data, type, full, meta) {
                    var $status_number = full['status'];
                    var $status = {
                      0:	{ title: 'Cancelado', class: ' bg-label-danger' },
                      1:	{ title: 'Pendiente', class: ' bg-label-warning' },
                      2: 	{ title: 'Procesando', class: 'bg-label-primary' },
                      3: 	{ title: 'Finalizado', class: ' bg-label-success' },
                    };
                    if (typeof $status[$status_number] === 'undefined') {
                      return ('<span class="badge ' + $status[0].class + '">' + $status[0].title + '</span>')
                    }
                    return (
                      '<span class="badge ' + $status[$status_number].class + '">' + $status[$status_number].title + '</span>'
                    );
                  }
                },
                {         
                  className: 'text-center',
                  targets: 10,
                  visible: false,
                  render: function (data, type, full, meta) {
                    return full.bank_account_send.bank.id
                  }
                },
                {
                  // Actions
                  className: 'text-center',
                  targets: 11,
                  responsivePriority: 7,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return (
                      `<div class="d-flex align-items-center justify-content-center">
                        <a href="javascript:;"  class="text-body"><i data-operation =${full.id}  class="ti ti-eye ti-sm view-op"></i></a>
                      </div>`
                    );
                  }
                }

              ],
              language: {
                sLengthMenu: '_MENU_',
                search: '',
                searchPlaceholder: 'Buscar...',
                processing: "Procesando...",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                info: "Mostrando _END_ de _TOTAL_ registros",
                paginate: {
                  next: "Siguiente",
                  previous: "Anterior"
                },
                
              },
              order: [[0, 'desc']],
              dom:
                '<"row me-2"' +
                '<"col-md-2">' +
                '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"B>>' +
                '>t' +
                '<"row mx-2"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',													
              buttons: [
                {
                  extend: 'collection',
                  className: 'btn btn-primary dropdown-toggle mx-3 text-white',
                  text: '<i class="ti ti-screen-share me-1 ti-xs text-white"></i>Exportar',
                  buttons: [
                    {
                      extend: 'csv',
                      text: '<i class="ti ti-file-text me-2" ></i>Csv',
                      className: 'dropdown-item',
                      title: 'Reporte de movimientos',
                      exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        // prevent avatar to be display
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                              if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                              } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                              } else result = result + item.innerText;
                            });
                            return result;
                          }
                        }
                      }
                    },
                    {
                      extend: 'excel',
                      text: '<i class="ti ti-file-text me-2" ></i>Excel',
                      className: 'dropdown-item',
                      title: 'Reporte de movimientos',
                      exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        // prevent avatar to be display
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                              if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                              } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                              } else result = result + item.innerText;
                            });
                            return result;
                          }
                        }
                      }
                    },
                    {
                      extend: 'pdf',
                      text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                      className: 'dropdown-item',
                      title: 'Reporte de movimientos',
                      exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        // prevent avatar to be display
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                              if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                              } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                              } else result = result + item.innerText;
                            });
                            return result;
                          }
                        }
                      }
                    },
                  ]
                },
              ],
                    
            });
            this.initOptionsTable()
          })
        },
        initOptionsTable(){
          document.querySelector('.dt-buttons').classList.add('justify-content-end')
          const TableElementContent = document.getElementById('table');
          this.activeFilterInputs()
          TableElementContent .addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
        },
        activeOptionsTable() {
          document.querySelectorAll('.view-op').forEach(item => {
            item.addEventListener('click', event => {
              this.selectAccountToAction(event.target.dataset.operation).then(() => {
                setTimeout(()=>{
                  this.showModal('operationModal')
                },500)
              })
            })	
          })
        },
        activeFilterInputs(){
          document.querySelector('.flatpickr-range').addEventListener('selectDates', event => {
              this.filterColumn()
          });
          
        },
        showModal(modal) {
          this.showPass = 1;
          setTimeout(() => {
            this.openModal = new bootstrap.Modal(document.getElementById(modal), {
              keyboard: false,
              backdrop:'static'
            })
            this.openModal.show()

          }, 500);
          
        },
        hideModal(){
          this.showPass = 0;
          this.openModal.hide();
          setTimeout(() => {
            let trashElement = document.querySelectorAll('.modal-backdrop');
            trashElement.forEach((item)=>{
              document.querySelector('body').removeChild(item);
            })
          }, 200);
        
        },
        showMainAlert(variant, message) {
            this.mainAlertVariant = variant;
            this.mainAlertMessage = message;
            this.mainAlert = true;
            window.scrollTo(0,0);
        },
        datepicker() {
          setTimeout(() => {
            let rangePickr = document.querySelector('.flatpickr-range');
              flatpickr(rangePickr,{
                mode: 'range',
                dateFormat: 'd/m/Y',
                maxDate: "today",
                orientation: 'auto left',
                locale: Spanish,  
                onClose: function (selectedDates,) {
                  var startDate = '',
                  endDate = new Date();

                  if (selectedDates[0] != undefined) {
                    startDate = moment(selectedDates[0]).format('YYYY-MM-DD');
                    document.querySelector('.start_date').value = startDate+ ' 00:00:00';

                  }
                  if (selectedDates[1] != undefined) {
                    endDate = moment(selectedDates[1]).format('YYYY-MM-DD');
                    document.querySelector('.end_date').value= endDate + ' 23:59:00';

                  }
                  let event = new Event("selectDates")
                  rangePickr.dispatchEvent(event);
                }
              });
              
          }, 300);
          
        },
        filterColumn() {
          this.tabled.clear();
          this.tabled.draw('full-hold');
        },
        resetFilter(){
          this.$refs.date_range.value = '';
          this.$refs.start_date.value = '';
          this.$refs.end_date.value = '';
          this.$refs.bank_filter.value = '';
          this.$refs.operation_reference_num.value = '';
          this.tabled.clear();
          this.tabled.draw('full-hold');
        },
			},
			computed: {

			},
      mounted() {
        this.getOperationsDataTable()
			},
			created() {
        this.getBanks()
        this.datepicker();
			},
	};
</script>