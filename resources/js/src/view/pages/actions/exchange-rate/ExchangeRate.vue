<template>
  <div class="modal fade modal-xl" id="exchangeRate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
      
      <div class="modal-content p-3 p-md-5 py-md-3">
        
        <div class="modal-body py-md-0 px-0">
          <button type="button" class="btn-close close-3" @click="hiddeModal()"></button>
          <div class="">
            <div class="row" v-if="mainAlert">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
              </div>
            </div>
            <div class="card-header pt-3 mb-0  min-vh-0">
              <div class="row w-100 ms-0">
                  <div class="col-lg-5 col-md-12 col-sm-12 text-center">
                      <h3 class="pt-4"><strong>Tipos de cambio</strong></h3>
                  </div>
                  <div class="col-lg-5 offset-lg-2 offset-md-4 col-md-5 col-sm-12 border border-primary rounded py-3">
                    <h5 class="fw-bold text-center">Agregar nueva tasa de cambio</h5>
                    <div class="row align-items-center justify-content-center px-2">
                      
                      <div class="form-inline col-sm-12 col-md-6 flex-column justify-content-center align-items-center ps-md-0">
                        
                        <div class="form-group p-2">
                          <input type="text" name="purchase" ref="purchase" placeholder="Compra" class="form-control ">
                        </div> 
                        <div class="form-group p-2">
                          <input type="text" name="sale" ref="sale" placeholder="Venta" class="form-control ">
                        </div> 
                        
                      </div>
                      <button class="btn btn-primary font-weight-bold ml-2 col-sm-12 col-md-6 px-sm-2" onsubmit="false"  @click="storeExchangeRate()">Guardar</button>
                    </div>
                  </div>
              </div>
            </div>
            <div class="card-body">
                <div class="row ">
                  <div class="card-body">
                    <div class="row justify-content-center">
                      <div class="col-12">
                        <div class="row g-3  align-items-end">
                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label fw-bold">Fecha:</label>
                            <div class="mb-0">
                              <input
                                type="text"
                                class="form-control dt-date flatpickr-range dt-input"
                                data-column="2"
                                placeholder="Buscar tasa desde - hasta"
                                data-column-index="2"
                                name="dt_date"
                                ref="date_range"
                              />
                              <input
                                type="hidden"
                                ref="start_date"
                                class="form-control dt-date start_date dt-input"
                                data-column="2"
                                data-column-index="2"
                                name="value_from_start_date"
                              />
                              <input
                                type="hidden"
                                ref="end_date"
                                class="form-control dt-date end_date dt-input"
                                name="value_from_end_date"
                                data-column="2"
                                data-column-index="2"
                              />
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-lg-3" >
                             <button class="btn btn-primary font-weight-bold ml-2 col-sm-12 col-md-5 px-sm-2" onsubmit="false"  @click="resetFilter()">
                              Resetear
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-datatable table-responsive mt-4 overflow-visible">
                    <table class="datatables-basic table w-100 mt-3 mb-1" id="tablexx">
                      <thead>
                        <tr>
                          <th></th>
                          <th></th>
                          <th>Fecha</th>
                          <th>Compra</th>
                          <th>Venta</th>
                          <th>Creador</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                    </table>
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
  @import "@/assets/libs/datatables-responsive-bs5/responsive.bootstrap5.scss";
  @import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
  @import "@/assets/libs/flatpickr/flatpickr.scss";
</style>
<script>
    import { EXCHANGE_RATES_GET_ALL, EXCHANGE_RATES_STORE, EXCHANGE_RATES_DELETE } from "@/core/services/store/exchange_rate.module";
    import flatpickr from "flatpickr";
    import { Spanish } from "flatpickr/dist/l10n/es.js"
    import moment from 'moment';
    export default {
      name:'ExchangeRate',
      data() {
          return {
            exchangeRates: [],
            mainAlert: false,
            mainAlertVariant: "",
            mainAlertMessage: "",
            tabled: '',
          }
        },
        mounted() {
            this.datepicker()
        },
        methods: {
          storeExchangeRate: function(e) {
              const purchase = this.$refs.purchase.value;
              const sale = this.$refs.sale.value;

              if(!purchase){
                  this.showMainAlert('danger', 'Debe ingresar el valor de compra')
                  return;
              }

              if(!sale){
                  this.showMainAlert('danger', 'Debe ingresar el valor de venta')
                  return;
              }

              let data;
              data = { purchase, sale };

              this.$store
                  .dispatch(EXCHANGE_RATES_STORE, data)
                  .then((data) => {
                      if (data.code != 201){
                          this.showMainAlert('danger', data.error)
                          return;
                      }

                      let milliseconds = Date.parse(data.data.updated_at);
                      let date = new Date(milliseconds);
                      let formatDate = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' - ' + date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();

                      data.data.updated_at = formatDate
                      this.exchangeRates = [data.data].concat(this.exchangeRates)

                      this.showMainAlert('success', 'Tipo de cambio creado correctamente!')
                      this.$refs.purchase.value = ""
                      this.$refs.sale.value = ""
                      this.tabled.clear();
                      this.tabled.rows.add(this.exchangeRates);
                      this.tabled.draw();
                      this.initOptions()
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                      console.log(err)
                      return;
                  });
          },

          getExchangeRates () {
              this.$store
                  .dispatch(EXCHANGE_RATES_GET_ALL)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al obtener todos los tipos de cambio.')
                          return;
                      }

                      for(let i = 0; i < data.data.length; i++){
                          let milliseconds = Date.parse(data.data[i].updated_at);
                          let date = new Date(milliseconds);
                          let formatDate = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()

                          data.data[i].updated_at = formatDate;
                      }

                      this.exchangeRates = data.data;
                      setTimeout(() => {
                        this.initTable()
                      }, 1000);
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
          },

          deleteExchangeRate(exchangeRateId) {
              this.$store
                  .dispatch(EXCHANGE_RATES_DELETE, exchangeRateId)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', data.error)
                          return;
                      }

                      this.exchangeRates = this.exchangeRates.filter(exchangeRate => exchangeRate.id != exchangeRateId);
                      this.showMainAlert('success', 'Tipo de cambio eliminado correctamente!')
                      this.tabled.clear();
                      this.tabled.rows.add(this.exchangeRates);
                      this.tabled.draw();
                      this.initOptions()
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                      return;
                  });
          },
          initTable(){
            this.tabled = new DataTable('#tablexx', {
              data: this.exchangeRates,
              columnDefs: [
                { 
                  className: 'control',
                  orderable: false,
                  targets: 0,
                  
                  render: function (data, type, full, meta) {
                    return '';
                  }
                },
                { 
                  visible: false,
                  orderable: false,
                  targets: 1,
                  
                  render: function (data, type, full, meta) {
                    return full.id;
                  }
                },
                { className: 'text-center',
                  targets: 2,
                  searchable: true,
                  visible: true,
                  type: 'date-uk',
                  responsivePriority: 1,
                  render: function (data, type, full, meta) {
                    let date = new Date(full.created_at)
                    return (
                      `${date.getDate() > 10 ? date.getDate() : '0'+date.getDate()}/${date.getMonth().length > 1 ? (date.getMonth()+1) : '0' + (date.getMonth()+1)}/${date.getFullYear()}` 
                    )
                  }
                },
                {	  
                  className: 'text-center',										
                  targets: 3,
                  searchable: true,
                  responsivePriority: 2,
                  render: function (data, type, full, meta) {
                    return (
                      `${parseFloat(full.purchase).toFixed(3) } ` 
                    )
                  }
                },
                {	  
                  className: 'text-center',										
                  targets: 4,
                  searchable: true,
                  orderable: true,
                  responsivePriority: 3,
                  render: function (data, type, full, meta) {
                    return (
                      `${parseFloat(full.sale).toFixed(3)}` 
                    )
                  }
                },
                { 
                  className: 'text-center',
                  targets: 5,
                  searchable: true,
                  visible: true,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.created_by}` 
                    )
                  }
                },
                {
                  // Actions
                  className: 'text-center',
                  targets: 6,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return (` <div class="d-flex align-items-center justify-content-around position-relative">
                                <a href="javascript:;"  class="text-body" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"><i data-operation =${full.id}  class="fas fa-trash view-op"></i></a>
                                <div class="dropdown-menu exchange-delete">
                                  <div>
                                      <div><h6>¿Estas seguro que deseas borrar este registro?</h6></div>
                                      <div class="d-flex  justify-content-center align-items-center">
                                        <div class="col-6 d-flex justify-content-center mt-3">
                                          <button class="btn btn-label-secondary  align-items-baseline " > 
                                            <span class="align-middle d-sm-inline-block d-none">Cerrar</span>
                                          </button>
                                        </div>
                                        <div class="col-6 d-flex justify-content-center mt-3">
                                          <button class="btn btn-primary  align-items-baseline delete-rate" data-rate=${full.id} > 
                                            <span class="align-middle d-sm-inline-block d-none">Continuar</span>
                                          </button>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                              </div>`)
                  }
                }

              ],
              order: [[1, 'desc']],
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
              dom:
                '<"row me-2"' +
                '<"col-md-2"<"me-3"l>>' +
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
                      title: 'Tasas de cambio',
                      exportOptions: {
                        columns: [1, 2, 3, 4],
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
                      extend: 'excelHtml5',
                      text: '<i class="ti ti-file-text me-2" ></i>Excel',
                      className: 'dropdown-item',
                      title: 'Tasas de cambio',
                      exportOptions: {
                        columns: [1, 2, 3, 4],
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
                      title: 'Tasas de cambio',
                      exportOptions: {
                        columns: [1, 2, 3, 4],
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
              responsive: {
                details: {
                  
                  type: 'column',
                  renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                      return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                        ? '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>'
                        : '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                  }
                }
              }										
                        
            });
            this.initOptions()
          },
          initOptions(){
            setTimeout(() => {
              document.querySelectorAll('input.dt-input').forEach(item => {
                item.addEventListener('keyup', event => {
                  this.filterColumn(event.target.dataset.column, [document.querySelector('.start_date').value, document.querySelector('.end_date').value])
                })	
              })
              document.querySelectorAll('.delete-rate').forEach(item => {
                item.addEventListener('click', event => {
                  let idOperation = item.dataset.rate
                  this.deleteExchangeRate(idOperation)
                })	
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
                  maxDate: "today",
                  dateFormat: 'd/m/Y',
                  orientation: 'auto left',
                  locale: Spanish,  
                  onClose: function (selectedDates, dateStr, instance) {
                    var startDate = '',
                      endDate = new Date();
                    if (selectedDates[0] != undefined) {
                      startDate = moment(selectedDates[0]).format('DD/MM/YYYY');
                      document.querySelector('.start_date').value = startDate;
                    }
                    if (selectedDates[1] != undefined) {
                      endDate = moment(selectedDates[1]).format('DD/MM/YYYY');
                      document.querySelector('.end_date').value= endDate;
                    }
                    let event = new Event("keyup")
                    rangePickr.dispatchEvent(event);
                  }
                });
            }, 300);
            
          },
          filterColumn(i, val = 0) {
            let startDate = val[0],
              endDate = val[1]
            if (i == 2) {
              if (startDate !== '' && endDate !== '') {
                $.fn.dataTableExt.afnFiltering.length = 0; // Reset datatable filter
                this.tabled.draw()
                this.filterByDate(2, this.format(startDate), this.format(endDate)); // We call our filter function
              }
              this.tabled.draw()
            } 
          },
          filterByDate(column, startDate, endDate) {

            // Custom filter syntax requires pushing the new filter to the global filter array
            $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
              let ItemDateValue = aData[column].split('/');
              let ItemDateValueFormat =`${ItemDateValue[1]}/${ItemDateValue[0]}/${ItemDateValue[2]}`
              
              var itemDate = new Date(ItemDateValueFormat),
              startItemDate =  new Date(startDate),
              endItemDate = new Date(endDate),

              rowDate = itemDate.getFullYear() + '' + ('0' + (itemDate.getMonth() + 1)).slice(-2) + '' + ('0' + itemDate.getDate()).slice(-2),
              start = startItemDate.getFullYear() + '' + ('0' + (startItemDate.getMonth() + 1)).slice(-2) + '' + ('0' + startItemDate.getDate()).slice(-2),
              end = endItemDate.getFullYear() + '' + ('0' + (endItemDate.getMonth() + 1)).slice(-2) + '' + ('0' + endItemDate.getDate()).slice(-2);
              
              // If our date from the row is between the start and end
              if (start <= rowDate && rowDate <= end) {
                return true;
              } else if (rowDate >= start && end === '' && start !== '') {
                return true;
              } else if (rowDate <= end && start === '' && end !== '') {
                return true;
              } else {
                return false;
              }
            });
          },
          resetFilter(){
            $.fn.dataTableExt.afnFiltering.length = 0;
            this.tabled.clear();
            this.tabled.rows.add(this.exchangeRates);
            this.tabled.draw();
            this.$refs.start_date.value = '';
            this.$refs.end_date.value = '';
            this.$refs.date_range.value = '';
          },
          format(date){
            let dateFormated = date.split('/');
            return `${dateFormated[1]}/${dateFormated[0]}/${dateFormated[2]}`
          },          
          hiddeModal(){
            $.fn.dataTableExt.afnFiltering.length = 0
            this.$emit("click");
          },
        },
        created() {
            this.getExchangeRates();
        },
    };
</script>