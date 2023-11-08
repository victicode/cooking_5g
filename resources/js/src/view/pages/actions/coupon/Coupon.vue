<template>
  <div class="modal fade" id="couponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-enable-otp modal-dialog-centered">
      <div class="modal-content p-3 p-md-3" >
        <div class="modal-body py-md-4 px-3">
          <button type="button" class="btn-close close-2" @click="hiddeModal()"></button>
          <div class="">
            <div class="row alert-aboslute" v-if="mainAlert">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
              </div>
            </div>
            <div class="card-header pt-3  mb-0  min-vh-0">
              <div class="row w-100 ms-0">
                  <div class="col-lg-5 col-md-12 col-sm-12 text-center">
                      <h3 class="pt-4 mt-3"><strong>Cupones</strong></h3>
                  </div>
                  
              </div>
            </div>
            <div class="card-body p-3">
                <div class="row ">
                  <div class="card-body">
                    <div class="row justify-content-center">
                      <div class="col-md-7 col-sm-12 ">
                        <form class="dt_adv_search" method="POST">
                          <div class="row g-3 ">
                          <div class="col-12 col-sm-6 col-lg-7">
                            <label class="form-label fw-bold">Fecha:</label>
                            <div class="mb-0">
                              <input
                                type="text"
                                class="form-control dt-date flatpickr-range dt-input"
                                data-column="5"
                                placeholder="Buscar tasa desde - hasta"
                                data-column-index="4"
                                name="dt_date"
                              />
                              <input
                                type="hidden"
                                class="form-control dt-date start_date dt-input"
                                data-column="5"
                                data-column-index="4"
                                name="value_from_start_date"
                              />
                              <input
                                type="hidden"
                                class="form-control dt-date end_date dt-input"
                                name="value_from_end_date"
                                data-column="5"
                                data-column-index="4"
                              />
                            </div>
                          </div>
                          </div>
                        </form>
                      </div>
                      <div class="col-lg-5  col-md-5 col-sm-12 rounded py-3  ">
                        <div class="row align-items-center justify-content-end px-2">
                          <button class="btn btn-primary font-weight-bold ml-2 col-sm-12 col-md-6 px-sm-2" onsubmit="false"  @click="showModal('newCoupon')">
                            Agregar nuevo cupón
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-datatable table-responsive mt-4 ">
                    <table class="datatables-basic table w-100 mt-3 mb-1" id="tablexx">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Fecha</th>
                          <th>Nombre</th>
                          <th>Codigo</th>
                          <th>Tipo de descuento</th>
                          <th>Valor</th>
                          <th>Estado</th>
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
    <div class="modal fade " id="newCoupon" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog  modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-2 p-md-2">
          <div class="modal-body">
            <div class="row" v-if="couponAlert">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <b-alert :show="couponAlert" dismissible fade :variant="couponAlertVariant" @dismissed="couponAlert=false">{{ couponAlertMessage }}</b-alert>
              </div>
            </div>
            <div class=" col-sm-12 border border-primary rounded p-2">
              <h5 class="fw-bold text-center">Agregar nuevo cupón</h5>
              <div class="row align-items-center justify-content-center px-2">
              
              <div class="row col-sm-12 col-md-12 justify-content-center align-items-center ">
                
                <div class="form-group p-2 col-6" >
                  <input type="text" name="c_name" ref="c_name" placeholder="Nombre del cupón" class="form-control ">
                </div> 
                <div class="form-group p-2 col-6" >
                  <input type="text" name="c_code" ref="c_code" placeholder="Codigo del cupón" class="form-control ">
                </div> 
                <div class="form-group p-2 col-6" >
                  <select name="c_type" ref="c_type" class="form-control">
                    <option value="-1">Selecciona el tipo de descuento</option>
                    <option value="0">Descuento por porcentaje</option>
                    <option value="1">Descuento por monto</option>
                  </select>
                </div> 
                <div class="form-group p-2 col-6" >
                  <input type="text" name="c_value" ref="c_value" placeholder="Valor de descuento" class="form-control ">
                </div> 
                
              </div>
              <button class="btn btn-primary font-weight-bold ml-2 mt-4 col-sm-12 col-md-6 px-sm-2 " id="couponButton" onsubmit="false"  @click="storeCoupon()">Crear cupon</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade modal" id="deleteCoupon" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        
        <div class="modal-content p-3 p-md-3">
          <div class="modal-body">
            <div class="content-header mb-2">
              <h4 class="fw-bold pt-3 mb-2 text-center">¿Estas seguro que deseas eliminar este cupón?</h4>
            </div>
            <div class="col-12 d-flex justify-content-between mt-3 ">
              <button class="btn btn-label-secondary  align-items-baseline " @click="openModal.hide()">
              <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
              </button>
              <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" id="sendPref" @click="deleteCoupon(selectedCoupon.id)">
                <span class="align-middle d-sm-inline-block d-none" >Confirmar!</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade modal" id="statusCoupon" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        
        <div class="modal-content p-3 p-md-3">
          <div class="modal-body">
            <div class="content-header mb-2">
              <h4 class="fw-bold pt-3 mb-2 text-center">¿Estas seguro que deseas {{ statusCupon == 1 ? 'habilitar' : 'deshabilitar'}} este cupón?</h4>
            </div>
            <div class="col-12 d-flex justify-content-between mt-3 ">
              <button class="btn btn-label-secondary  align-items-baseline " @click="openModal.hide()">
              <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
              </button>
              <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" id="sendPref" @click="changeStatusCoupon(selectedCoupon)">
                <span class="align-middle d-sm-inline-block d-none" >Confirmar!</span>
              </button>
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
      import { COUPON_GET_ALL, COUPON_STORE, COUPON_UPDATE, COUPON_DELETE} from "@/core/services/store/coupon.module";
      import flatpickr from "flatpickr";
      import { Spanish } from "flatpickr/dist/l10n/es.js"
      import moment from 'moment';
      import { bootstrap } from "@/concept/bootstrap";

      export default {
        name:'Coupon',
        data() {
            return {
              coupons: [],
              mainAlert: false,
              mainAlertVariant: "",
              mainAlertMessage: "",
              couponAlert: false,
              couponAlertVariant: "",
              couponAlertMessage: "",
              selectedCoupon:'',
              tabled: '',
              openModal: '',
              loadSend1: false,
              statusCupon:''
            }
          },
          mounted() {
              this.datepicker();
              this.getCoupons();
          },
          methods: {
            storeCoupon(e) {
              this.resetCouponAlert()
                const name = this.$refs.c_name.value;
                const code = this.$refs.c_code.value;
                const type = this.$refs.c_type.value;
                const value = this.$refs.c_value.value;
                let inputError = false;
                let couponButton = document.getElementById('couponButton');
                couponButton.disabled = true;
                couponButton.textContent = 'Creando cupón....';
                
                if(!name){
                    this.showCouponAlert('danger', 'Debe ingresar el nombre del cupón')
                    inputError = true
                }
  
                if(!code){
                    this.showCouponAlert('danger', 'Debe ingresar el codigo del cupon')
                    inputError = true
                }
                if(type == -1){
                    this.showCouponAlert('danger', 'Debes selecionar un tipo de cupón valido')
                    inputError = true
                }
                if(!value){
                    this.showCouponAlert('danger', 'Debe ingresar el valor de descuento del cupón')
                    inputError = true
                }
                if(inputError){
                  couponButton.disabled = false;
                  couponButton.textContent = 'Crear cupón';
                  return;
                }
  
                const dataCupon = { name, code, type, value };
  
                this.$store
                    .dispatch(COUPON_STORE, dataCupon)
                    .then((data) => {
                        if (data.code != 201){
                            this.showMainAlert('danger', data.error)
                            return;
                        }
  
                        let milliseconds = Date.parse(data.data.updated_at);
                        let date = new Date(milliseconds);
                        let formatDate = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' - ' + date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
  
                        data.data.updated_at = formatDate
                        this.coupons = [data.data].concat(this.coupons)
  
                        this.showMainAlert('success', 'Tipo de cambio creado correctamente!')
                        this.$refs.c_name.value = "";
                        this.$refs.c_value.value = "";
                        this.$refs.c_code.value = "";
                        this.$refs.c_type.value = -1;
                        this.tabled.clear();
                        this.tabled.rows.add(this.coupons);
                        this.tabled.draw();
                        this.initOptions()
                        this.openModal.hide()
                        couponButton.disabled = false;
                        couponButton.textContent = 'Crear cupón';
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },
            getCoupons() {
                this.$store
                  .dispatch(COUPON_GET_ALL)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al obtener todos los tipos de cambio.')
                          return;
                      }  
                      this.coupons = data.data;
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
                data: this.coupons,
                columnDefs: [
                  { 
                    className: 'control',
                    orderable: false,
                    targets: 0,
                    
                    render: function (data, type, full, meta) {
                      return '';
                    }
                  },
                  { className: 'text-center',
                    targets: 1,
                    searchable: true,
                    visible: true,
                    type: 'date-uk',
                    responsivePriority: 1,
                    render: function (data, type, full, meta) {
                      let date = new Date(full.created_at)
                      return (
                        `${date.getMonth().length > 1 ? (date.getMonth()+1) : '0' + (date.getMonth()+1)}/${date.getDate()}/${date.getFullYear()}` 
                      )
                    }
                  },
                  {	  
                    className: 'text-center',										
                    targets: 2,
                    searchable: true,
                    responsivePriority: 2,
                    render: function (data, type, full, meta) {
                      return (
                        `${full.name} ` 
                      )
                    }
                  },
                  {	  
                    className: 'text-center',										
                    targets: 3,
                    searchable: true,
                    orderable: true,
                    responsivePriority: 3,
                    render: function (data, type, full, meta) {
                      return (
                        `${full.code}` 
                      )
                    }
                  },
                  { 
                    className: 'text-center',
                    targets: 4,
                    searchable: true,
                    visible: true,
                    render: function (data, type, full, meta) {
                      return (
                        `${full.type == '0' ? 'Descuento por procentaje (%)' : 'Descuento por monto (USD | S/)'}` 
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
                        `${full.value}` 
                      )
                    }
                  },
                  { 
                    className: 'text-center',
                    targets: 6,
                    searchable: true,
                    visible: true,
                    render: function (data, type, full, meta) {
                      
                      let html;
                      if(full.status == 1 ){
                        html = '<span class="badge bg-label-success"> Habilitado</span>'
                      }else{
                        html = '<span class="badge bg-label-danger">Deshabilitado</span>'
                      }
                      return html
                    }
                  },
                  {
                    // Actions
                    className: 'text-center',
                    targets: 7,
                    orderable: false,
                    render: function (data, type, full, meta) {
                      var html = `<div class="d-flex align-items-center justify-content-evenly">`
                                  
                      if(full.status == 1 ){
                        html += `<a href="javascript:;"  class="text-body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Deshabilitar Cupón" ><i data-operation=${full.id} data-status=${parseInt(full.status - 1)}  class="fas fa-times-circle  text-danger change-status"></i></a>`
                      }else if(full.status == 0){
                        html+= `<a href="javascript:;"  class="text-body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Habilitar Cupón"><i data-operation=${full.id} data-status=${parseInt(full.status) + 1}  class="far fa-check-circle text-success change-status"></i></a>`
                      }
                      html +=`
                        <a href="javascript:;"  class="text-body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar Cupón" ><i data-operation=${full.id}  class="fas fa-trash delete-coupon"></i></a>
                      </div>`

                      return html
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
                
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

                document.querySelectorAll('.change-status').forEach(item => {
                  item.addEventListener('click', event => {
                    this.updateSelectedCoupon(item.dataset.operation)
                    this.statusCupon = item.dataset.status
                    setTimeout(()=>{
                      this.showModal('statusCoupon')
                    },100)
                    
                  })	
                })
                document.querySelectorAll('input.dt-input').forEach(item => {
                item.addEventListener('keyup', event => {
                  this.filterColumn(event.target.dataset.column, [document.querySelector('.start_date').value, document.querySelector('.end_date').value])
                  })	
                })
                document.querySelectorAll('.delete-coupon').forEach(item => {
                  item.addEventListener('click', event => {
                    this.updateSelectedCoupon(item.dataset.operation)
                    setTimeout(()=>{    
                      this.showModal('deleteCoupon')
                    },100)
                    
                  })	
                })
              }, 500);
            },
            showMainAlert(variant, message) {
                this.mainAlertVariant = variant;
                this.mainAlertMessage = message;
                this.mainAlert = true;
                window.scrollTo(0,0);
            },
            showCouponAlert(variant, message) {
                this.couponAlertVariant = variant;
                this.couponAlertMessage = message;
                this.couponAlert = true;
                window.scrollTo(0,0);
            },
            resetCouponAlert() {
                this.couponAlertVariant = '';
                this.couponAlertMessage = '';
                this.couponAlert = false;
            },
            datepicker() {
              setTimeout(() => {
                let rangePickr = document.querySelector('.flatpickr-range');
                  flatpickr(rangePickr,{
                    mode: 'range',
                    dateFormat: 'm/d/Y',
                    orientation: 'auto left',
                    locale: Spanish,  
                    onClose: function (selectedDates, dateStr, instance) {
                      var startDate = '',
                        endDate = new Date();
                      if (selectedDates[0] != undefined) {
                        startDate = moment(selectedDates[0]).format('MM/DD/YYYY');
                        document.querySelector('.start_date').value = startDate;
                      }
                      if (selectedDates[1] != undefined) {
                        endDate = moment(selectedDates[1]).format('MM/DD/YYYY');
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
              if (i == 5) {
                if (startDate !== '' && endDate !== '') {
                  // console.log(this.tabled.dataTable().fnDraw())
                  $.fn.dataTableExt.afnFiltering.length = 0; // Reset datatable filter
                  // this.tabled.draw(); // Draw table after filter
                 
                  this.filterByDate(1, startDate, endDate); // We call our filter function
                }
                this.tabled.draw()
              } 
            },
            filterByDate(column, startDate, endDate) {
              // Custom filter syntax requires pushing the new filter to the global filter array
              $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
                var d1 = new Date(aData[column]),
                d2 =  new Date(startDate),
                d3 = new Date(endDate),
                rowDate = d1.getFullYear() + '' + ('0' + (d1.getMonth() + 1)).slice(-2) + '' + ('0' + d1.getDate()).slice(-2),
                start = d2.getFullYear() + '' + ('0' + (d2.getMonth() + 1)).slice(-2) + '' + ('0' + d2.getDate()).slice(-2),
                end = d3.getFullYear() + '' + ('0' + (d3.getMonth() + 1)).slice(-2) + '' + ('0' + d3.getDate()).slice(-2);
  
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
            updateSelectedCoupon(id) {
              this.selectedCoupon = Object.assign({}, this.coupons.find(coupon => coupon.id == id));
              console.log(this.selectedCoupon)
            },
            changeStatusCoupon(){
              const body = {
                id: this.selectedCoupon.id,
                status: this.selectedCoupon.status == 0 ? 1 : 0
              } 
              this.$store
                  .dispatch(COUPON_UPDATE, body)
                  .then((data) => {
                      if (data.code != 201){
                          this.showMainAlert('danger', 'Error desconocido al cambiar el estado del cupón.')
                          return;
                      }  
                      let milliseconds = Date.parse(data.data.updated_at);
                        let date = new Date(milliseconds);
                        let formatDate = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' - ' + date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
  
                        data.data.updated_at = formatDate;

                        let couponIndex = this.coupons.findIndex(coupons => coupons.id == data.data.id);

                        if (couponIndex > -1) {
                            this.coupons[couponIndex] = data.data;
                        }

                        this.showMainAlert('success', 'El estado del cupón fue actualizado!')
                        this.tabled.clear();
                        this.tabled.rows.add(this.coupons);
                        this.tabled.draw();
                        this.initOptions()
                        this.openModal.hide()
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
            },
            deleteCoupon(id){
              
              this.$store
                  .dispatch(COUPON_DELETE, id)
                  .then((data) => {
                      if (data.code != 200){
                          this.showMainAlert('danger', 'Error desconocido al cambiar el estado del cupón.')
                          return;
                      }  
                      console.log(data.data.id)

                        this.coupons = this.coupons.filter(coupons => coupons.id != data.data.id);

                        this.showMainAlert('success', 'El cupón ha sido eliminado!')
                        this.tabled.clear();
                        this.tabled.rows.add(this.coupons);
                        this.tabled.draw();
                        this.initOptions()
                        this.openModal.hide()
                  })
                  .catch((err) => {
                      this.showMainAlert('danger', err)
                  });
            },
            hiddeModal(){
              document.querySelector('.start_date').value = '';
              document.querySelector('.end_date').value= '';
              $.fn.dataTableExt.afnFiltering.length = 0
              this.$emit("click");

              
            },
            showModal(id) {
              setTimeout(() => {
                this.openModal = new bootstrap.Modal(document.getElementById(id), {
                  keyboard: false,
                })

                this.openModal.show()
              }, 50);
            }, 
            
          },
          created() {
              
          },
      };
  </script>