<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de Ordenes" class="pa-3 px-1 px-md-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0 pb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
            <VBtn v-if="isUser" @click=" showModal('createOrder')" color="primary" class="w-100 "><VIcon icon="bx-plus"/>
              {{ isUser ? 'Crear orden manual': 'Crear nueva orden'}}
            </VBtn>
          </VCol>
        </VRow>
        <VRow class="ma-0  justify-center align-center justify-md-start pa-2 px-0 mb-0 mb-md-2">
          <VCol cols="6" md="4" class="form-group px-2">
            <VTextField
              placeholder="Desde - Hasta"
              label="Buscar por fecha"
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
          <VCol cols="6" md="4" class="form-group px-2">
            <VTextField
              placeholder="Buscar por Track ID"
              label="Buscar por Track ID"
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
      </VCard>
      <VCard class="pt-8 pb-8 mt-5 px-2">
        <div class="card-datatable table-responsive ">
          <table class="datatables-basic table display nowrap order-table" id="data-table">
          </table>
        </div>
      </VCard>
    </VCol>
    <div v-if="Object.keys(selectedOrder).length > 1">
      <viewOrderModal :order="selectedOrder"  @actionModal="modalAction" />
      <viewCreateOutOrderModal :order="selectedOrder"  @hiddenModal="hideModal"  />
      <viewTimelineOrderModal :order="selectedOrder"  @actionModal="hideInternalModal" />
      <viewCancelOrder :order="selectedOrder"  @hiddenModal="hideModal"  />
      <viewConfirmOrder :order="selectedOrder"  @hiddenModal="hideModal" />
    </div>
    <viewCreateOrder @hiddenModal="hideModal"  />
  </VRow>
</template>
<style lang="scss" >
  table.order-table{
    width: 100%!important;
  }
  .copyText{
    cursor: pointer;
  }
  thead > tr > th.date{
    width: 15%!important;
  }
  .fv-plugins-message-container{
    position: absolute;
  }
  .small-delete-product-button{
      position: absolute;
      top: -10px;
      right: -10px;
    }
  @media screen and (max-width: 780px){
    .small-delete-product-button{
      position: absolute;
      top: -10px;
      right: 0px;
    }
    .copyText{
      font-size:15.5px
    }
    thead > tr > th:last-child{
      width: 10%!important;
    }
  }
</style>
<script>
  import DataTablesCore from 'datatables.net';
  import 'datatables.net-responsive-dt';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';

  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue';
  import viewOrderModal from '@/views/orders/viewOrderModal.vue';
  import viewCancelOrder from '@/views/orders/viewCancelOrder.vue';
  import viewConfirmOrder from '@/views/orders/viewConfirmOrder.vue';
  import viewCreateOutOrderModal from '@/views/orders/viewCreateOutOrderModal.vue';
  import viewTimelineOrderModal from '@/views/pages/modals/viewTimelineOrderModal.vue';
  import viewCreateOrder from '@/views/orders/viewCreateOrder.vue';
  import * as bootstrap from 'bootstrap';

  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css';
  import { Spanish } from "flatpickr/dist/l10n/es.js";

  import { GET_ORDER_BY_ID } from "@/core/services/store/order.module";
  import { GET_ALL_USER, GET_USER } from "@/core/services/store/user.module";

  export default {
    components:{
      viewCancelOrder,
      viewConfirmOrder,
      viewCreateOrder,
      viewCreateOutOrderModal,
      viewOrderModal,
      viewTimelineOrderModal,
    },
    data: () => ({
      modal: '',
      internalModal:'',
      inputDate: '',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:5000,
      selectedOrder:{},
      assignedProduct:{},
      table:'',
      isUser:false,
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-orders",
          "type": "POST",
          data: function ( data ) {
            data.filter_tracker_id = document.querySelector('[name="tracker_id"]').value;
            data.filter_start_date = document.querySelector('[name="start_date"]').value;
            data.filter_end_date = document.querySelector('[name="end_date"]').value;

            data.order_sort_date = data.order[0].column == 0 ? data.order[0].dir : ''
            data.order_sort_status = data.order[0].column ==  0 ? '' : data.order[0].dir
          },
          "crossDomain": true,
          "beforeSend": function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer" + window.localStorage.getItem('id_token'))
          },
          error: function() {
            alert('Sesión caducada')
            location.reload()
          }
        },
        dataType:'json',
        processing: true,
        serverSide: true,
        responsive: true,
        columnDefs: [
          {
            defaultContent: "",
            targets: "_all"
          },
        ],
        order:[[0, 'desc']],
        columns: [
          { 
            title: '<span class="d-none d-md-block">Fecha de pedido</span> <span class="d-flex justify-center d-md-none text-center">Pedido</span>',  
            class:'text-center date ',

            render: ( data, type, row, meta ) =>{ 
              return `
              ${ moment(row.created_at).format('DD-MM-YYYY') }
              `
            }   
          },
      
          { 
            title: 'Track ID',
            class:'text-md-center text-start ',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return `
              
              <span class="justify-center text-decoration-underline copyText">
                #${row.trancker}
              </span>
              `
            } 

          },
          { 
            title: 'Orden N°',
            class:'text-md-center text-start',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return `
              
              <span class="justify-center">
                #${window.$orderFormat(row.id)}
              </span>
              `
            } 

          },
          { 
            title: 'Creada por',
            class:'text-md-center text-start',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return ` ${row.user.name} `
            } 

          },
          { 
            title: 'Estado ',
            class:'text-center px-0',
            responsivePriority: 3,

            render: ( data, type, row, meta ) =>{ 
              return `
              <span 
                class="  v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${row.status == 1 ? 'bg-warning' : row.status == 2 ? 'bg-secondary' :row.status == 3 ? 'bg-success' : 'bg-error' }" 
                draggable="false"
                >
                  <span class="v-chip__underlay"></span>
                  <div class="v-chip__content ">
                    <span class="d-md-flex d-none">
                      ${row.status_label.status}
                    </span>
                    <span class="d-flex d-md-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${row.status_label.status}">
                      <img src="${row.status_label.svg}" alt="Mi SVG feliz" height="24" width="24"/>
                    </span>  
                  </div>
              </span>                  
              `
            }

          },
          { 
            title: '<span class="d-none d-md-block">Acciones</span> <span class="d-flex justify-center d-md-none text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#8c8c8c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4H7.2c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C4 5.52 4 6.08 4 7.2v9.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h9.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.987.218-2.105V14m-4-9l-6 6v3h3l6-6m-3-3l3-3l3 3l-3 3m-3-3l3 3"/></svg></span>',
            orderable: false, 
            searchable: false, 
            responsivePriority: 2,
            class:'text-center  px-0 px-md-3',
            render: ( data, type, row, meta ) =>{ 
              let html = `
                <div class="d-md-flex d-none justify-center ">
                  <span data-id="${row.id}" class="view mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-id="${row.id}" fill="#808080" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0"/>
                    </svg>
                  </span>
              `
              if(row.status == 1 ){
                if(window.localStorage.is_admin !='false'){
                  html += `
                      <span data-bs-toggle="tooltip" data-id="${row.id}" class="process mx-2" data-bs-placement="top" data-bs-title="Procesar salida">
                        <svg data-id="${row.id}"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="#808080" d="M18 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5m1.5-9H17V12h4.46zM6 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5M20 8l3 4v5h-2c0 1.66-1.34 3-3 3s-3-1.34-3-3H9c0 1.66-1.34 3-3 3s-3-1.34-3-3H1V6c0-1.11.89-2 2-2h14v4zM3 6v9h.76c.55-.61 1.35-1 2.24-1s1.69.39 2.24 1H15V6zm7 1l3.5 3.5L10 14v-2.5H5v-2h5z"/>
                        </svg>
                      </span>`
                }
              }
              if(row.status == 2 ){
                html += `
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="confirm mx-2" data-bs-placement="top" data-bs-title="Confirmar recepción">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="#808080" d="M10.591 2.513a3.75 3.75 0 0 1 2.818 0l7.498 3.04A1.75 1.75 0 0 1 22 7.175v5.635a6.5 6.5 0 0 0-1.5-1.077v-3.96l-7.75 2.992v2.298a6.5 6.5 0 0 0-1.5 2.645v-4.944L3.5 7.75v9.078a.25.25 0 0 0 .156.231l7.499 3.04q.047.02.095.036l.189.076q.088.036.179.06c.248.526.565 1.014.94 1.451a3.75 3.75 0 0 1-1.967-.233l-7.498-3.04A1.75 1.75 0 0 1 2 16.827V7.176a1.75 1.75 0 0 1 1.093-1.622zm2.254 1.39a2.25 2.25 0 0 0-1.69 0L9.24 4.68l7.527 2.927l2.67-1.03zM4.59 6.564l7.411 2.883l2.69-1.04L7.216 5.5zM17.5 23.001a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11m-1-4.207l3.646-3.647a.5.5 0 0 1 .708.707l-4 4a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.707z"/>
                      </svg>
                    </span>`
              }
              if(row.status != 0 && row.status != 3){
                html += `
                <span data-bs-toggle="tooltip"  data-id="${row.id}" class="cancel mx-2" data-bs-placement="top" data-bs-title="Cancelar Orden">
                  <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="#808080" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"/>
                  </svg>
                </span>

                `
              }
              html +=`</div>`

              html +=`
                <div class="d-md-none d-flex justify-center position-relative relative ">
                  <div class="dropdown dropup ">
                    <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                    </button>
                    <div class="dropdown-menu animate__animated animate__rubberBand">
                      <span  data-id="${row.id}" class="view mx-3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="#808080" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0"/>
                        </svg>
                      </span>
              `
              if(row.status == 1 ){
                if(window.localStorage.is_admin !='false'){
                  
                  html += `
                  <span data-bs-toggle="tooltip" data-id="${row.id}" class="process mx-3" data-bs-placement="top" data-bs-title="Procesar salida">
                    <svg data-id="${row.id}"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-id="${row.id}"  fill="#808080" d="M18 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5m1.5-9H17V12h4.46zM6 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5M20 8l3 4v5h-2c0 1.66-1.34 3-3 3s-3-1.34-3-3H9c0 1.66-1.34 3-3 3s-3-1.34-3-3H1V6c0-1.11.89-2 2-2h14v4zM3 6v9h.76c.55-.61 1.35-1 2.24-1s1.69.39 2.24 1H15V6zm7 1l3.5 3.5L10 14v-2.5H5v-2h5z"/>
                    </svg>
                  </span>`
                }
              }
              if(row.status == 2 ){
                html += `
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="confirm mx-3" data-bs-placement="top" data-bs-title="Confirmar recepción">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="#808080" d="M10.591 2.513a3.75 3.75 0 0 1 2.818 0l7.498 3.04A1.75 1.75 0 0 1 22 7.175v5.635a6.5 6.5 0 0 0-1.5-1.077v-3.96l-7.75 2.992v2.298a6.5 6.5 0 0 0-1.5 2.645v-4.944L3.5 7.75v9.078a.25.25 0 0 0 .156.231l7.499 3.04q.047.02.095.036l.189.076q.088.036.179.06c.248.526.565 1.014.94 1.451a3.75 3.75 0 0 1-1.967-.233l-7.498-3.04A1.75 1.75 0 0 1 2 16.827V7.176a1.75 1.75 0 0 1 1.093-1.622zm2.254 1.39a2.25 2.25 0 0 0-1.69 0L9.24 4.68l7.527 2.927l2.67-1.03zM4.59 6.564l7.411 2.883l2.69-1.04L7.216 5.5zM17.5 23.001a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11m-1-4.207l3.646-3.647a.5.5 0 0 1 .708.707l-4 4a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.707z"/>
                      </svg>
                    </span>`
              }
              if(row.status != 0 && row.status!= 3){
                  html += `
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="cancel mx-3" data-bs-placement="top" data-bs-title="Cancelar Orden">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="#808080" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"/>
                      </svg>
                    </span>
                  `
                }
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
        document.querySelectorAll('.copyText').forEach(item => {
          item.addEventListener('click', event => {
            this.copyOrderNumber(event.target)
          })	
        })
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectOrder(event.target.dataset.id).finally((data)=>{
              this.showModal('viewOrder')
            })
          })	
        })
        document.querySelectorAll('.process').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectOrder(event.target.dataset.id).finally((data)=>{
              this.showModal('createOutOrder')
            })
          })	
        })
        document.querySelectorAll('.confirm').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectOrder(event.target.dataset.id).finally((data)=>{
              this.showModal('confirmOrder')
            })
          })	
        })
        document.querySelectorAll('.cancel').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectOrder(event.target.dataset.id).finally((data)=>{
              this.showModal('cancelOrder')
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
      selectOrder(idAccount){
        this.emitter.emit('displayOverlayLoad', true)
        return new Promise((resolve) => {
          this.$store
            .dispatch(GET_ORDER_BY_ID, idAccount)
            .then((response) => {
              this.selectedOrder = Object.assign({}, response.data);
              setTimeout(() => { 
                this.emitter.emit('displayOverlayLoad', false)
                resolve(response.data);
              }, 200);
            })
          })
          .catch((err) => {
            console.log(err)
              resolve(false);
          });
      },
      getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          if(data.code !== 200){
            console.log('alert!!!')
            return
          }
          if(data.user.rol_id == 3) this.isUser = true
        })
        .catch((e) => {
        });
      },
      // getUsers(){
      //   this.$store
      //     .dispatch(GET_ALL_USER)
      //     .then((data) => {
      //       // console.log(data)
      //       this.userForOrder = data
      //     })
      //     .catch((err) => {
      //       console.log(err)
        
      //     });
      // },
      
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
        this.inputDate.clear()
        document.querySelector('[name="tracker_id"]').value = '';
        document.querySelector('[name="start_date"]').value = '';
        document.querySelector('[name="end_date"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showModal(modal) {
        try {
          this.modal.hide()
        } catch (error) {
          
        }
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
      hideModal(){
        this.modal.hide()
        setTimeout(() => {
          let trashElement = document.querySelectorAll('.modal-backdrop');
          trashElement.forEach((item)=>{
            document.querySelector('body').removeChild(item);
          })
        }, 200);
        this.filterColumn()
      },
      modalAction(action){ 
        if(action == 'close') {this.hideModal(); return}

        if(action == 'show:out_order') { this.showInternalModal('viewOutOrder'); return}
        if(action == 'show:history') { this.showInternalModal('timeLineOrder'); return}

       
      },
      showInternalModal(modal) {
        this.modal.hide()

        this.internalModal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.internalModal.show()
      },
      hideInternalModal() {
        
        this.internalModal.hide()
        this.modal.show()
      },
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      sendingButton(id){
        document.getElementById(id).disabled = true
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      copyOrderNumber(element){
        const texto = element.innerHTML.trim().substring(1);
        const textArea = document.createElement('textarea');
        textArea.value = texto;
        textArea.style.opacity = 0;
        document.body.appendChild(textArea);
        textArea.select();
        try {
          const success = document.execCommand('copy');
          this.showSnackbar('success', 'Tracker ID copiado exitosamente')
        } catch (err) {
          console.error(err.name, err.message);
        }
        document.body.removeChild(textArea);
        
      }
    },
    mounted(){
      // this.getUsers();
      this.initOptionsTable()
      this.table = new DataTablesCore('#data-table', this.tableData)
      this.initFlatpickr();
      this.bootstrapOptions();
      this.getUser();
    },
    created(){
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>