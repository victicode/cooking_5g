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
      <VCard title="Listado de Ordenes" class="pa-3 px-1 px-md-3">
        <VRow class="ma-0  justify-center align-center justify-md-start pa-2 px-0 mb-10 mb-md-2">
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
        <div class="modal-dialog modal-xl mt-10" >
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
            
              <VCol
                cols="12"
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col  class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
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
                          @click="hideModal()"
                        >
                          <VIcon icon="mingcute:close-fill" />
                          <span class="ms-2">Cerrar</span>
                        </VBtn>
                      </VCardActions>
                    </div>
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="changeStatusOrder" tabindex="-1" aria-labelledby="changeStatusOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl mt-10" >
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
              <VCol
                cols="12"
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col  class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
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
                        class="px-md-10 px-0 overflow-scroll relative position-relative py-10 "
                        style=""
                      >
                        <div class="my-8">
                          <div class="">
                            <div class="timeline__content">
                                <div class="d-flex justify-center align-center timeline__content--items">
                                  <div class="timeline__divider"></div>
                                  <div class="timeline__content--item-center bg-primary">
                                    <div class="timeline__content--item-text up">
                                      <h3>
                                        Orden creada
                                      </h3>
                                    </div>
      
                                    <v-icon size="large" color="white" icon="ic:outline-pending-actions"></v-icon>  
                                    
                                    <div class="text-h6 timeline__content--item-text down">
                                      <h4>
                                        {{  moment(selectedOrder.created_at).format('DD/MM/YYYY') }}
                                      </h4>
                                    </div>
                                  </div>
                                  <div class="timeline__divider"></div>
                                </div>
                                <div class="d-flex justify-center align-center timeline__content--items" v-if="selectedOrder.status >= 1 ">
                                  <div class="timeline__divider"></div>
                                  <div class="timeline__middle">
                                    <input type="checkbox" value="2" id="in-transit" name="newStatus" ref="newStatus" class="d-none" v-if="selectedOrder.status == 1"  @input="validateChangeStatus()">
                                    <label for="in-transit">
                                      <div class="timeline__content--item-center " :class="selectedOrder.status > 1 ? 'bg-primary' :'bg-unavailable'">
                                        <div class="timeline__content--item-text up" :class="{'opacity-0' : selectedOrder.status == 1 } " >
                                          <h3>
                                            En transito
                                          </h3>
                                        </div>
          
                                        <v-icon size="large" color="white" icon="carbon:delivery-parcel"></v-icon>
                                        
                                        <div class="text-h6 timeline__content--item-text down" v-if="selectedOrder.status == 2">
                                          <h4>
                                            {{  moment(selectedOrder.updated_at).format('DD/MM/YYYY') }}
                                          </h4>
                                        </div>
                                      </div>
                                    </label>
                                  </div>
                                  <div class="timeline__divider"></div>
                                </div>
                                <div class="d-flex justify-center align-center timeline__content--items" v-if="selectedOrder.status >= 1" >
                                  <div class="timeline__divider"></div>
                                  <div class="timeline__middle">
                                    <input type="checkbox" value="3" id="complete" name="newStatus" ref="newStatus" class="d-none" v-if="selectedOrder.status == 2"  @input="validateChangeStatus()">
                                    <label for="complete">
                                      <div class="timeline__content--item-center " :class="selectedOrder.status > 2 ? 'bg-primary' :'bg-unavailable'">
                                        <div class="timeline__content--item-text up" :class="{'opacity-0' : selectedOrder.status <= 2 } " >
                                          <h3>
                                            Orden entregada
                                          </h3>
                                        </div>
                                        <v-icon size="large" color="white" icon="line-md:confirm-circle"></v-icon>
                                        <div class="text-h6 timeline__content--item-text down" v-if="selectedOrder.status == 3">
                                          <h4>
                                            {{  moment(selectedOrder.updated_at).format('DD/MM/YYYY') }}
                                          </h4>
                                        </div>
                                      </div>
                                    </label>
                                  </div>
                                  <div class="timeline__divider"></div>
                                </div>
                                <div class="d-flex justify-center align-center timeline__content--items" v-if="selectedOrder.status == 0" >
                                  <div class="timeline__divider"></div>
                                  <div class="timeline__content--item-center bg-error">
                                    
                                    <div class="timeline__content--item-text up"  >
                                          <h3>
                                            Orden Cancelada
                                          </h3>
                                        </div>
                                        <v-icon size="large" icon="ic:outline-cancel"></v-icon>
                                        <div class="text-h6 timeline__content--item-text down">
                                          <h4>
                                            {{  moment(selectedOrder.updated_at).format('DD/MM/YYYY') }}
                                          </h4>
                                        </div>
                                  </div>
                                  <div class="timeline__divider"></div>
                                </div>
                            </div>
                          </div>
                        </div>
                      </VCol>
                    </VRow>
                    <VDivider  />
                    <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                      <VCardActions class=" justify-center w-100 d-md-flex  d-block">
                        <VRow class="ma-0 pa-0  mt-0 align-center">
                          <VCol cols="12" md="6" offset-md="3" class="mt-0 py-0 px-0">
                            <v-col cols="auto" class="">
                              <VBtn
                                color="white"
                                class=" text-white w-100 bg-success mx-0  my-2 "
                                :class='selectedOrder.status >= 1  ? "v-btn--disabled" : "" '
                                
                                @click="orderChangeStatus()"
                                :disabled="selectedOrder.status >= 2  ? true : false"
                                id="change-status-order-button"
                                ref="changeStatusOrderButton"
                                v-if="selectedOrder.status == 1 || selectedOrder.status == 2 "
                              >
                                <span class="">Cambiar estado</span>
                              </VBtn>
                            </v-col>
                          </VCol>
                        </VRow>
                      </VCardActions>
                    </div>
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="cancelOrder" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
            
              <VCol
                cols="12"
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col  class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
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
                          @click="cancelOrder()"
                          v-if="selectedOrder.status == 1 || selectedOrder.status == 2"
                        >
                          <span class="">Cancelar</span>
                        </VBtn>
                      </VCardActions>
                    </div>
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
    </div>
    <v-snackbar
      v-model="snackShow"
      :color="snackType"
      rounded="pill"
      :timeout="snacktimeOut"
      width="max-content"
      class="text-center"
    >
     <h4 class="text-white w-100 text-center">

       {{snackMessage}}
     </h4>
        <template
          v-slot:actions
        >
        <VBtn  color="white" class="text-white" @click="snackShow=false"> Cerrar</VBtn>
        </template>
    </v-snackbar>
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
  .timeline__content{
    width: 100%;
    display: flex;
    justify-content: center;
  }
  .timeline__divider{
    height: 1px; width: calc(50% - 25px);position:relative;  background:#9b9b9b6e
  }
  .timeline__content--item-center{
    display: flex;
    height: 50px; 
    width: 50px;
    border-radius: 50%; 
    justify-content: center;
    align-items: center;
    transition: all 0.2s ease-in;
    &:hover{
      cursor: pointer;
    }
  }
  
  .timeline__content--items {
      width: 100%;
      & > div > input:checked + label > .timeline__content--item-center{
        background-color: #d06427!important;
      }
      & > div > input:checked + label > .timeline__content--item-center > .up{
        opacity: 1;
      }
  }
  .timeline__content--item-text{
    width: 160px;
    position: absolute;
    text-align: center;
    transition: all 0.2s ease-in;
    &.up{
      top: 15%;
    }
    &.down{
      bottom: 15%;
    }

  }
  .opacity-0{
    opacity: 0;
  }
  .opacity-1{
    opacity: 1;
  }
  @media screen and (max-width: 780px){
    .timeline__content--items{

      width: 180px;
    }
    .timelapse, .timeline__content{
      max-width: 800px!important;
      width: max-content;
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
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:5000,
      selectedOrder:{},
      table:'',
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-orders",
          "type": "POST",
          data: function ( data ) {
            data.filter_tracker_id = document.querySelector('[name="tracker_id"]').value;
            data.filter_start_date = document.querySelector('[name="start_date"]').value;
            data.filter_end_date = document.querySelector('[name="end_date"]').value;
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
            class:'text-center justify-center px-0 px-md-3',
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
            class:'text-center px-0',
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
              let html = `
                <div class="d-md-flex d-none justify-center ">
                  <span data-id="${row.id}" class="view mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                  </span>
                  <span data-bs-toggle="tooltip" data-id="${row.id}" class="change mx-2" data-bs-placement="top" data-bs-title="Actualizar estado">
                  <svg data-id="${row.id}"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48">
                    <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
                      <path data-id="${row.id}" d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path>
                      <path data-id="${row.id}" d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                </span>
              `
              if(row.status != 0 && row.status != 3){
                html += `
                <span data-bs-toggle="tooltip"  data-id="${row.id}" class="cancel mx-2" data-bs-placement="top" data-bs-title="Cancelar Orden">
                  <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
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
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                      </span>
                      <span data-bs-toggle="tooltip" data-id="${row.id}" class="change mx-3"  data-bs-placement="top" data-bs-title="Actualizar estado">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48">
                          <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
                            <path data-id="${row.id}" d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path>
                            <path data-id="${row.id}" d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                      </span>
              `
              if(row.status != 0 && row.status!= 3){
                  html += `
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="cancel mx-3" data-bs-placement="top" data-bs-title="Cancelar Orden">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
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
      validateChangeStatus(){
        document.getElementById('change-status-order-button').disabled = !this.$refs.newStatus.checked;
        document.getElementById('change-status-order-button').classList.toggle('v-btn--disabled')

      },
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      hola(){
        console.log('hola')
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
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      hideModal(){
        this.modal.hide()
      },
      orderChangeStatus(){
        this.hideModal()
        const message = this.$refs.newStatus.value == 2 ? 'Orden colocada en transito' : 'Orden completada exitosamente';
        const type = this.$refs.newStatus.value == 2 ? 'secondary' : 'success';

        this.showSnackbar(type, message)
      },
      cancelOrder(){
        this.hideModal()
        this.showSnackbar('error','Orden cancelada')
      },
      
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