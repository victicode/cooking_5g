<script setup >
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import Button from 'datatables.net-buttons';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import OrderProductsTables from '@/views/pages/tables/OrderProductsTables.vue'
  import ButtonPrint from 'datatables.net-buttons/js/buttons.print';
  import ButtonHTML5 from 'datatables.net-buttons/js/buttons.html5';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import * as bootstrap from 'bootstrap'
  import flatpickr from "flatpickr";
  import moment from 'moment';
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import { GET_ORDER_BY_ID } from "@/core/services/store/order.module";

  DataTable.use(DataTablesCore);
  DataTable.use(Button);
  DataTable.use(ButtonHTML5);
  DataTable.use(ButtonPrint); 
</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de chef" class="pa-3 px-1 px-md-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0 pb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
          <VBtn @click=" showModal('createProduct')" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo chef</VBtn>

          </VCol>
        </VRow>
        <VRow class="ma-0 justify-center align-center justify-md-start pa-2 px-0 mb-10 mb-md-2">
          <VCol cols="12" md="4" class="form-group">
            <VTextField
              placeholder="Desde - Hasta"
              label="Buscar por Fecha"
              type="text"
              name="date"
              ref="range_date"
              id="date-input"
            />
            <VTextField
              placeholder="Buscar por Track ID"
              type="hidden"
              name="start_date"
              ref="start_date"
              class="boder-0 d-none start_date"
            />
            <VTextField
              placeholder="Buscar por Track ID"
              type="hidden"
              name="end_date"
              ref="end_date"
              class="boder-0 d-none end_date"
            />
          </VCol>
          <VCol cols="12" md="4" class="form-group">
            <VTextField
              placeholder="Buscar por Track ID"
              label="Track ID"
              type="text"
              ref="tracker"
              name="tracker_id"
              @change="filterColumn()"
            />
          </VCol>
          <VCol cols="12" md="4" class="my-0 py-0">
            <VBtn
              color="white"
              class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
              @click="clearFilters()"
            >
              <span class="">Restaurar</span>
            </VBtn>
          </VCol>
        </VRow>
        <div class="card-datatable table-responsive">
          <table class="datatables-basic table" id="data-table">
          </table>
        </div>
      </VCard>
    </VCol>
    <div v-if="Object.keys(selectedOrder).length > 2">
      <div class="modal animate__animated animate__fadeInDown" id="viewOrder" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl mt-10">
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0"
            >
              <VCard>
                <div class="d-flex justify-space-between flex-wrap flex-md-nowrap flex-column pa-2 pa-md-5 ">
                  <VRow  class="mb-2 ma-0">
                    <VCol
                      cols="12"
                      md="6"
                      class="py-0"
                    >
                      <div class="my-md-4 my-2 text-center text-md-start">
                        <h2>Orden de productos</h2>
                      </div>    
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                      class="py-0"
                    >
                      <div class="my-md-4 my-0  d-none d-md-block text-center text-md-end ">
                        <h4 class="font-400">
                          Número de recetas: 
                          <b>
                            5
                          </b> 
                        </h4>
                      </div>
                      <div >
                        <div class="my-2  text-start text-md-end">
                          Años en la plataforma: 10años
                        </div>
                        <div class="my-2  text-start text-md-end">
                          Estilo: Americano
                        </div>
                      </div>
                    
                    </VCol>
                  </VRow>
                </div>
              </VCard>
            </VCol>
          </div>
        </div>
      </div>
      <!-- <div class="modal animate__animated animate__fadeInDown" id="cancelOrder" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl mt-10">
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0"
            >
              <VCard>
                <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                  <VRow  class="mb-2 ma-0">
                    <VCol
                      cols="12"
                      class="py-0"
                    >
                      <div class="my-md-4 my-2 text-center">
                        <h2>Cancelar orden #{{ orderNumberFormat(selectedOrder.id) }}</h2>
                        <h3 class="mt-2">
                          <v-chip :class="{'bg-error': selectedOrder.status == 0, 'bg-warning': selectedOrder.status == 1, 'bg-secondary': selectedOrder.status == 2, 'bg-success': selectedOrder.status == 3, }">
                            {{ selectedOrder.status_info.status }}
                          </v-chip>

                        </h3>
                      </div>
                    </VCol>
                    <VCol
                      cols="12"
                      class="px-md-10 px-0 text-center"
                      style=""
                    >
                      <h2>¿Seguro que deseas cancelar la orden #{{orderNumberFormat(selectedOrder.id)}}?</h2>
                    </VCol>
                  </VRow>
                    

                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  d-block">
                      <VBtn
                        color="white"
                        class="bg-error text-white w-30 mx-0 mx-md-5 my-2"
                        @click="modal.hide()"
                        v-if="selectedOrder.status == 1 || selectedOrder.status == 2"
                      >
                        <span class="">Cancelar</span>
                      </VBtn>
                      <VBtn
                        color="white"
                        class="bg-secondary text-white w-30 mx-0 mx-md-5 my-2"
                        @click="modal.hide()"
                      >
                        <span class="">Cerrar</span>
                      </VBtn>
                    </VCardActions>
                  </div>
                </div>
              </VCard>
            </VCol>
          </div>
        </div>
      </div> -->
    </div>
    
  </VRow>
</template>
<style lang="scss" >
  thead > tr > th.date{
    width: 15%!important;
  }
  .modal{
    animation-duration: 1.2s; /* don't forget to set a duration! */
  }
  .v-timeline-divider__inner-dot > svg{
    color: #fff!important;
  }
  @media screen and (max-width: 780px){
    .timelapse{
      max-width: 800px!important;
      width: 480px!important;
    }
    .overflow-scroll{
      overflow: scroll;
    }
    .w-30{
      width:  100%!important;
    }
  }
</style>
<script>

  export default {
    data: () => ({
      modal: '',
      inputDate: '',
      selectedOrder:{},
      table:'',
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-chefs",
          "type": "POST",
          data: function ( data ) {
            // data.filter_start_date = document.querySelector('[name="start_date"]').value;
            // data.filter_end_date = document.querySelector('[name="end_date"]').value;

            // data.order_sort_date = data.order[0].column == 0 ? data.order[0].dir : ''
            // data.order_sort_status = data.order[0].column ==  0 ? '' : data.order[0].dir
          },
          "crossDomain": true,
          "beforeSend": function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer" + window.localStorage.getItem('id_token'))
          },
        },
        dataType:'json',
        processing: true,
        serverSide: true,
        columns: [
          { 
            title: 'Nombre',  
            class:'text-start date',
            render: ( data, type, row, meta ) =>{ 
              return row.name
              
            }   
          },
          { 
            title: 'Email',
            class:'text-justify',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return row.email
            } 
          },
          { 
            title: 'Ubicación',
            class:'text-justify',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return row.user_address
            } 
          },
          { 
            title: '<span class="d-none d-md-block">Acciones</span> <span class="d-flex justify-center d-md-none text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#8c8c8c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4H7.2c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C4 5.52 4 6.08 4 7.2v9.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h9.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.987.218-2.105V14m-4-9l-6 6v3h3l6-6m-3-3l3-3l3 3l-3 3m-3-3l3 3"/></svg></span>',
            orderable: false, 
            searchable: false, 
            class:'text-center  px-1 px-md-3',
            render: ( data, type, row, meta ) =>{ 
              let html = `
                <div class="d-md-flex d-none justify-center ">
                  <span data-id="${row.id}" class="view mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver Ficha">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                  </span>
                  <span data-id="${row.id}" class="edit mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar chef">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path data-id="${row.id}" fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z"/></svg>
                  </span>
                  <span data-bs-toggle="tooltip" data-id="${row.id}" class="recipes mx-2" data-bs-placement="top" data-bs-title="Ver recetas">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                      <defs data-id="${row.id}">
                        <mask data-id="${row.id}" id="ipTChefHat0">
                          <g data-id="${row.id}" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path fill="#555" d="M12 34h24v8H12z"/>
                            <path data-id="${row.id}" d="M29 34V20m-7 14v-8m-10-1v9h24v-9s5-3 5-8s-4-7-9-7c0-2-3-6-8-6s-8 4-8 6c-4 0-9 2-9 7s5 8 5 8"/></g></mask></defs><path fill="currentColor" d="M0 0h48v48H0z" mask="url(#ipTChefHat0)"/></svg>
                  </span>
                  <span data-id="${row.id}" class="delete mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar chef">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path  data-id="${row.id}" fill="currentColor" d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4zm2 2h6V4H9zM6.074 8l.857 12H17.07l.857-12zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1"/></svg>
                  </span>
              `
              html +=`</div>`

              html +=`
                <div class="d-md-none d-flex justify-center position-relative relative ">
                  <div class="dropdown dropup ">
                    <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                    </button>
                    <div class="dropdown-menu animate__animated animate__rubberBand">
                      <span  data-id="${row.id}" class="view mx-3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver Ficha">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                      </span>
                      <span  data-id="${row.id}" class="edit mx-3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar chef">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z"/></svg>
                      </span>
                      <span data-id="${row.id}" data-bs-toggle="tooltip" data-id="${row.id}" class="recipes mx-2" data-bs-placement="top" data-bs-title="Ver recetas">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                          <defs data-id="${row.id}" >
                            <mask data-id="${row.id}" id="ipTChefHat0">
                              <g data-id="${row.id}" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path fill="#555" d="M12 34h24v8H12z"/>
                                <path data-id="${row.id}" d="M29 34V20m-7 14v-8m-10-1v9h24v-9s5-3 5-8s-4-7-9-7c0-2-3-6-8-6s-8 4-8 6c-4 0-9 2-9 7s5 8 5 8"/></g></mask></defs><path fill="currentColor" d="M0 0h48v48H0z" mask="url(#ipTChefHat0)"/></svg>
                      </span>
                      <span  data-id="${row.id}" class="delete mx-3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar chef">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="currentColor" d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4zm2 2h6V4H9zM6.074 8l.857 12H17.07l.857-12zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1"/></svg>
                      </span>
              `
              html +=`
                    </div>
                  </div>
                </div> 
              `

              return html
            } 
          },

        ],
        pageLength: 25,
        lengthChange: false,
        dom:
          '<"v-row mx-0 mb-md-5"' +
          // '<"v-col v-col-md-6 v-col-12 mb-5 mb-md-0"f>' +
          // '<"v-col v-col-md-6 v-col-12 "<" justify-center justify-md-end  d-flex "B>>' +
          '>t' +
          '<"v-row  mt-2 mx-2"' +
          '<"v-col v-col-md-6 v-col-12 mt-0 text-primary"i>' +
          '<"v-col v-col-md-6 v-col-12 mt-0 text-primary"p>' +
          '>',	   
        language: {
          sLengthMenu: '_MENU_',
          search: '',
          searchPlaceholder: 'Ingresa el Tracker ID',
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
        buttons: [
          {
            extend: 'collection',
            className: 'v-btn v-btn--elevated v-theme--light bg-secondary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100',
            text: '<i class="ti ti-screen-share me-1 ti-xs text-white"></i>Exportar',
            buttons: [
              {
                extend: 'csv',
                text: '<i class="ti ti-file-text me-2" ></i>Csv',
                className: 'dropdown-item',
                title: 'Reporte de movimientos',
              },
              {
                extend: 'excel',
                text: '<i class="ti ti-file-text me-2" ></i>Excel',
                className: 'dropdown-item',
                title: 'Reporte de movimientos',
              },
              {
                extend: 'pdf',
                text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                className: 'dropdown-item',
                title: 'Reporte de movimientos',
              },
            ]
          },
        ],
        drawCallback: function( settings ) {
          const TableElement = document.getElementById('data-table');
          const event = new Event("OptionsActionTable")
          TableElement.dispatchEvent(event);
        },
      },
    }),
    methods:{
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('viewOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.change').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('changeStatusOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.cancel').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('cancelOrder')
              }, 500);
            })
          })	
        })
      },
      initFlatpickr(){
        this.inputDate = flatpickr(document.querySelector('#date-input'), {
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
              document.querySelector('[name="start_date"]').value = startDate+ ' 00:00:00';

            }
            if (selectedDates[1] != undefined) {
              endDate = moment(selectedDates[1]).format('YYYY-MM-DD');
              document.querySelector('[name="end_date"]').value= endDate + ' 23:59:00';

            }
            let event = new Event("selectDates")
            document.querySelector('#date-input').dispatchEvent(event);
          }
        });

        document.querySelector('#date-input').addEventListener('selectDates', event => {
          this.filterColumn()
        });
      },
      async selectOrder(idAccount){
        this.$store
          .dispatch(GET_ORDER_BY_ID, idAccount)
          .then((response) => {
            this.selectedOrder = Object.assign({}, response.data);
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
      bootstrapOptions(){
        setTimeout(() => {
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
          const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
          const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
          const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
        }, 2000);
      },
      filterColumn(){
        this.table.clear();
        this.table.draw('full-hold');
      },
      clearFilters(){
        document.querySelector('[name="tracker_id"]').value = '';
        document.querySelector('[name="start_date"]').value = '';
        document.querySelector('[name="end_date"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showModal(modal) {
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      }
    },
    mounted(){
      this.initOptionsTable()
      this.table = new DataTablesCore('#data-table', this.tableData)
      this.initFlatpickr();
      this.bootstrapOptions();
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>