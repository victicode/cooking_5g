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
  import eCommerce2 from '@images/eCommerce/2.png'

  DataTable.use(DataTablesCore);
  DataTable.use(Button);
  DataTable.use(ButtonHTML5);
  DataTable.use(ButtonPrint); 
</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de Ordenes" class="pa-3 px-1 px-md-3">
        <VRow class="ma-0  justify-center justify-md-start pa-2 px-0 mb-10 mb-md-2">
          <VCol cols="12" md="4" class="form-group">
            <VTextField
              placeholder="Desde - Hasta"
              label="Buscar por Fecha"
              type="text"
              name="date"
              id="date-input"
              @change="filterColumn()"
            />
            <VTextField
              placeholder="Buscar por Track ID"
              type="hidden"
              name="email"
              class="boder-0 d-none start_date"
            />
            <VTextField
              placeholder="Buscar por Track ID"
              type="hidden"
              name="email"
              class="boder-0 d-none end_date"
            />
          </VCol>
          <VCol cols="12" md="4" class="form-group">
            <VTextField
              placeholder="Buscar por Track ID"
              label="Track ID"
              type="text"
              ref="palo"
              name="email"
              @change="filterColumn()"
            />
          </VCol>
        </VRow>
        <DataTable
          id="data-table"
          ref="table"
          :columns="table.columns"
          :options="table.options"
          :ajax= "table.ajax" 
          class=" display"
        />
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
                        <h3 class="mt-2">
                          <v-chip :class="{'bg-error': selectedOrder.status == 0, 'bg-warning': selectedOrder.status == 1, 'bg-secondary': selectedOrder.status == 2, 'bg-success': selectedOrder.status == 3, }">
                            {{ selectedOrder.status_info.status }}
                          </v-chip>

                        </h3>
                      </div>
                      <div class="my-3 my-md-0  d-block d-md-none text-center text-md-end ">
                        <h4 class="font-400">
                          Orden N°: 
                          <b>
                            {{ '0000000'.slice(0, 6-selectedOrder.id)+ selectedOrder.id }}
                          </b> 
                        </h4>
                      </div>
                      <div >
                        <div class="my-2  text-start">
                          Solicitante: {{ selectedOrder.user.name.toUpperCase() }}
                        </div>
                        <div class="my-2 text-start">
                          Dirección de destino: {{ selectedOrder.other_address }}
                        </div>
                      </div>
                    
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                      class="py-0"
                    >
                      <div class="my-md-4 my-0  d-none d-md-block text-center text-md-end ">
                        <h4 class="font-400">
                          Orden N°: 
                          <b>
                            {{ orderNumberFormat(selectedOrder.id) }}
                          </b> 
                        </h4>
                      </div>
                      <div >
                        <div class="my-2  text-start text-md-end">
                          Fecha: {{ moment(selectedOrder.created_at).format('DD/MM/YYYY HH:mm:ss') }}
                        </div>
                        <div class="my-2  text-start text-md-end">
                          Tracker ID: {{ selectedOrder.trancker }}
                        </div>
                      </div>
                    
                    </VCol>
                  </VRow>
                  <div>
                    <OrderProductsTables :products="selectedOrder.products" />
                  </div>
                  <VDivider  />
                  <div class="mt-5 w-100 d-flex  justify-center">
                    <VCardActions class=" justify-center w-75">
                      <VBtn
                        color="white"
                        class="bg-secondary text-white w-50"
                        @click="modal.hide()"
                      >
                        <VIcon icon="mingcute:close-fill" />
                        <span class="ms-2">Cerrar</span>
                      </VBtn>
                    </VCardActions>
                  </div>
                </div>
              </VCard>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="changeStatusOrder" tabindex="-1" aria-labelledby="changeStatusOrderLabel" aria-hidden="true">
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
                        <h2>Linea de tiempo Orden #{{ orderNumberFormat(selectedOrder.id) }}</h2>
                        <h3 class="mt-2">
                          <v-chip :class="{'bg-error': selectedOrder.status == 0, 'bg-warning': selectedOrder.status == 1, 'bg-secondary': selectedOrder.status == 2, 'bg-success': selectedOrder.status == 3, }">
                            {{ selectedOrder.status_info.status }}
                          </v-chip>

                        </h3>
                      </div>
                    </VCol>
                    <VCol
                      cols="12"
                      class="px-md-10 px-0 overflow-scroll"
                      style=""
                    >
                      <v-timeline direction="horizontal"  class="timelapse ">
                        <v-timeline-item
                          dot-color="#d06427"
                          icon="ic:outline-pending-actions"
                          fill-dot
                          size="large"
                        >
                          <template v-slot:opposite>
                            <h3>
                              Orden creada
                            </h3>
                          </template>
    
                          <div class="text-h6">
                            <h4>
                              {{  moment(selectedOrder.created_at).format('DD/MM/YYYY') }}
                            </h4>
                          </div>
    
                        </v-timeline-item>
                        <v-timeline-item
                          :dot-color="selectedOrder.status > 1 ? '#d06427' :'#fff' "
                          icon="carbon:delivery-parcel"
                          fill-dot
                          size="large"
                        >
                          <template v-slot:opposite v-if="selectedOrder.status > 1">
                            <h3>
                              En transito
                            </h3>
                          </template>
                            <div class="text-h6" v-if="selectedOrder.status == 2">
                              <h4>
                                {{  moment(selectedOrder.updated_at).format('DD/MM/YYYY') }}
                              </h4>
                            </div>
                        </v-timeline-item>
                        <v-timeline-item
                          :dot-color="selectedOrder.status == 3 ? '#d06427' :'#fff' "
                          icon="line-md:confirm-circle"
                          fill-dot
                          size="large"
                        >
                          <template v-slot:opposite v-if="selectedOrder.status == 3">
                            <h3>
                              Completado
                            </h3>
                          </template>
                            <div class="text-h6" v-if="selectedOrder.status == 3">
                              <h4>
                                {{  moment(selectedOrder.updated_at).format('DD/MM/YYYY') }}
                              </h4>  
                            </div>
                        </v-timeline-item>
                        <v-timeline-item
                          dot-color="#ff5538"
                          icon="ic:outline-cancel"
                          fill-dot
                          size="large"
                          v-if="selectedOrder.status == 0"
                        >
                          <template v-slot:opposite>
                            <h3>
                              Cancelada
                            </h3>
                          </template>
                            <div class="text-h6">
                              <h4>
                                {{  moment(selectedOrder.updated_at).format('DD/MM/YYYY') }}
                              </h4>  
                            </div>
                        </v-timeline-item>
                    
                      </v-timeline>
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
                      <VBtn
                        color="white"
                        class=" text-white w-30 bg-success mx-0 mx-md-5 my-2"
                        @click="modal.hide()"
                        v-if="selectedOrder.status == 1 || selectedOrder.status == 2 "
                      >
                        <span class="">{{ selectedOrder.status == 1 ? 'En transito' : selectedOrder.status == 2 ? 'Entregado' : ''}}</span>
                      </VBtn>
                    </VCardActions>
                  </div>
                </div>
              </VCard>
            </VCol>
          </div>
        </div>
      </div>
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
      table:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-orders",
          "contentType": "application/json",
          "type": "POST",
          data: function ( data ) {
            data.filter_start_date = document.querySelector('.start_date').value;
            data.filter_end_date = document.querySelector('.end_date').value;
          },
          "crossDomain": true,
          "beforeSend": function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer" + window.localStorage.getItem('id_token'))
          },
        },
        columns: [
          { 
            title: 'Fecha',  
            class:'text-center date',
            render: ( data, type, row, meta ) =>{ 
              return `
              ${ moment(row.created_at).format('DD-MM-YYYY') }
              `
            }   
          },
          { 
            title: 'Track ID',
            class:'text-center justify-center px-2 px-md-3',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return `
              
              <span class="d-none d-md-flex justify-center">
                #${row.trancker}
              </span>
              <span class="d-flex d-md-none justify-center">
                #${row.trancker.slice(0,2)}...${row.trancker.slice(-3)}
              </span>
              `
            } 

          },
          { 
            title: 'Creada por',
            class:'text-center d-md-table-cell d-none',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return ` ${row.user.name} `
            } 

          },
          { 
            title: 'Estado ',
            class:'text-center',
            orderable: true,
            render: ( data, type, row, meta ) =>{ 
              return `
              <span 
                class="  v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${row.status == 1 ? 'bg-warning' : row.status == 2 ? 'bg-secondary' :row.status == 3 ? 'bg-success' : 'bg-error' }" 
                draggable="false"
                >
                  <span class="v-chip__underlay"></span>
                  <div class="v-chip__content ">
                    <span class="d-md-flex d-none">
                      ${row.status_info.status}
                    </span>
                    <span class="d-flex d-md-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${row.status_info.status}">
                      <img src="${row.status_info.svg}" alt="Mi SVG feliz" height="24" width="24"/>
                    </span>  
                  </div>
              </span>                  
              `
            }

          },
          { 
            title: 'Acciones',
            orderable: false, 
            searchable: false, 
            class:'text-center px-0 px-md-3',
            render: ( data, type, row, meta ) =>{ 
              return `
              <div class="d-md-flex d-none justify-center ">
                <span data-id="${row.id}" class="view" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                  <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" class="change" data-bs-placement="top" data-bs-title="Actualizar estado">
                  <svg data-id="${row.id}"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48">
                    <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancelar Orden">
                  <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                </span>
              </div>
              <!- Vista en moviles ->
              <div class="d-md-none d-flex justify-center position-relative relative ">
                <div class="dropdown dropup ">
                  <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                  </button>
                  <div class="dropdown-menu animate__animated animate__rubberBand">
                    <span  data-id="${row.id}" class="view" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-8 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip"  class="change"  data-bs-placement="top" data-bs-title="Actualizar estado">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-8 iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48">
                        <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancelar Orden">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                    </span>
                  </div>
                </div>
              </div> 

              
              
              
              `
            } 
          },

        ],
        options : {
          pageLength: 25,
          lengthChange: false,
          // scrollX:true,
          // scrollY:false,
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
        
      },
    }),
    methods:{
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            this.selectAccountToAction(event.target.dataset.id).finally((data)=>{
              console.log(data)
              setTimeout(() => {
                this.showModal('viewOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.change').forEach(item => {
          item.addEventListener('click', event => {
            this.selectAccountToAction(event.target.dataset.id).finally((data)=>{
              console.log(data)
              setTimeout(() => {
                this.showModal('changeStatusOrder')
              }, 500);
            })
          })	
        })
      },
      initFlatpickr(){
        flatpickr(document.getElementById('date-input'), {
        });

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
              document.querySelector('.start_date').value = startDate+ ' 00:00:00';

            }
            if (selectedDates[1] != undefined) {
              endDate = moment(selectedDates[1]).format('YYYY-MM-DD');
              document.querySelector('.end_date').value= endDate + ' 23:59:00';

            }
            // console.log(`
            //   {
            //     start_date: ${startDate},
            //     end_date: ${endDate}
            // `)
          }
        });
      },
      selectedAccount(){

      },
      async selectAccountToAction(idAccount){
        this.$store
          .dispatch(GET_ORDER_BY_ID, idAccount)
          .then((response) => {
            this.selectedOrder = Object.assign({}, response.data);
            console.log(response.data)
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
      filterColumn() {
        // this.$refs.table.clear();
        // this.$refs.table.draw('full-hold');
        console.log(this.$refs.table)
      },
      showModal(modal) {
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
      orderNumberFormat(id){
        console.log(id.length)
        return '0000000'.slice( 0, 6 - id.length ) + id 
      }
    },
    mounted(){
      this.initOptionsTable()
      this.initFlatpickr();
      this.bootstrapOptions();
      
    },
    created(){
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>