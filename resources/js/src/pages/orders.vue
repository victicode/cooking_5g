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
  import { GET_ORDER_BY_ID, CHANGE_STATUS } from "@/core/services/store/order.module";
  import { GET_PRODUCT_BY_SEARCH } from "@/core/services/store/product.module";
</script>

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
          <VBtn @click=" showModal('createOrder')" color="primary" class="w-100 "><VIcon icon="bx-plus"/>Crear orden manual</VBtn>

          </VCol>
        </VRow>
        <VRow class="ma-0  justify-center align-center justify-md-start pa-2 px-0 mb-10 mb-md-2">
          <VCol cols="12" md="4" class="form-group">
            <VTextField
              placeholder="Desde - Hasta"
              label="Buscar por fecha de pedido"
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
                              {{ selectedOrder.status_label.status }}
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
                            Fecha de pedio: {{ moment(selectedOrder.created_at).format('DD/MM/YYYY HH:mm:ss') }}
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
                              {{ selectedOrder.status_label.status }}
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
                              {{ selectedOrder.status_label.status }}
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
    <div class="modal animate__animated animate__fadeInDown"  id="createOrder" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                  <v-col class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                <div>
                  <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1">Crear nueva orden</div>
                        
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <VCardText class="mb-5  w-100 pa-0" v-if="alertShow">
                    <v-alert
                      :color="alertType"
                      :text="alertMessage"
                    ></v-alert>
                  </VCardText>
                  <VCardText class="w-100 pb-5 px-3 px-md-6">
                    <VForm  id="new_order_form">
                      <VRow class="align-center">
                        <VCol cols="12" md="7" class="form-group">
                          <VTextField
                            placeholder="Usuario"
                            label="Usuario"
                            type="text"
                            name="new_product_title"
                            autocomplete="off"
                            v-model="newOrder.user"
                          />
                        </VCol>
                        <VCol cols="12"  md="5" class="form-group px-3">
                          <div class="d-flex align-center">
                            <input type="checkbox" id="isUserAddress" value="1" style="height: 20px; width: 20px;">
                            <label for="isUserAddress" class="mx-2">Usar dirección del usuario</label>
                          </div>
                        </VCol>
                        <VCol cols="12" md="12" class="form-group">
                          <v-textarea
                            label="Dirección"
                            auto-grow
                            variant="outlined"
                            rows="3"
                            row-height="25"
                            shaped
                            name="new_product_description"
                            v-model="newOrder.address"
                          ></v-textarea>
                        </VCol>
                      </VRow>
                      <VRow 
                        class="ma-0 pa-0  mt-4 align-center" 
                        >
                            <VCol cols="12" class="form-group">
                              <h3>Productos:</h3>
                            </VCol>
                            <VCol cols="12" md="4" class="mt-0 py-0 px-0">
                              <v-tooltip text="Agregar nuevo despiece">
                                  <template v-slot:activator="{ props }">
                                    <v-col cols="auto" class="">
                                      <VBtn v-bind="props" color="primary" class="w-100"  @click="addDismantlingInput($event)"><VIcon icon="bx-plus"/> Agregar producto</VBtn>
                                    </v-col>
                                  </template>
                                </v-tooltip>
                            </VCol>
                            <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                              <VRow  v-for="(item,index) in newOrder.products"  v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'new_product_desmantling_'+index">
                                <VCol cols="12"  md="6" class="form-group">
                                  <v-autocomplete
                                    :model-value="item.piece_product_id"
                                    :items="productsForOrder[index] ?  productsForOrder[index] : [ {id: item.id, title: item.title}]"
                                    label="Nombre del producto"
                                    item-props="stock"
                                    item-title="title"
                                    item-value="id"
                                    placeholder="Nombre del producto"
                                    variant="outlined"
                                    clearable
                                    no-filter
                                    :name="'product_desmantling_id_'+index"
                                    no-data-text="No se encontraron resultados"
                                    @keyup="searchDismantling($event,index )"
                                    @click:clear="clearSearchDismantling(index)"
                                    @update:modelValue="selectDismantling($event, index,2)"
                                  ></v-autocomplete>
                                </VCol>
                                <VCol cols="8"  md="4" class="form-group">
                                  <VTextField
                                    placeholder="Unidades solicitadas"
                                    label="Unidades solicitadas"
                                    type="number"
                                    :name="'product_in_order_'+index"
                                    v-model="item.quantity"
                                    
                                  />
                                </VCol>
                                <VCol cols="4" md="1" class="form-group pa-0">
                                  <v-tooltip text="Eliminar despiece">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="">
                                        <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeDismantlingInput(2, index)"></v-btn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                                </VCol> 
                              </VRow>
                            </div>
                      </VRow>
                      <VRow class="ma-0 pa-0  mt-8 align-center">
                        <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                          <v-col cols="auto" class="">
                            <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_product_form_button"> Guardar</VBtn>
                          </v-col>
                        </VCol>
                      </VRow>
                    </VForm>
                  </VCardText>
                </div>
              </VCard>
            </VCol>
          </VCol>
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
  @media screen and (max-width: 780px){

    thead > tr > th:last-child{
      width: 10%!important;
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
      alertShow:false,
      alertMessage:'',
      alertType:'',
      newOrder: {
        direction:'',
        user:'',
        products:[],
        userAddress:false,
      },
      productsForOrder:[],
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-orders",
          "type": "POST",
          data: function ( data ) {
            data.filter_tracker_id = document.querySelector('[name="tracker_id"]').value;
            console.log(data)
            data.filter_start_date = document.querySelector('[name="start_date"]').value;
            data.filter_end_date = document.querySelector('[name="end_date"]').value;

            data.order_sort_date = data.order[0].column == 0 ? data.order[0].dir : ''
            data.order_sort_status = data.order[0].column ==  0 ? '' : data.order[0].dir
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
            class:'text-center px-3',
            render: ( data, type, row, meta ) =>{ 
              console.log(row.status)
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
            class:'text-center  px-1 px-md-3',
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
        this.sendingButton('change-status-order-button')
        const message = this.$refs.newStatus.value == 2 
        ? 'Orden colocada en transito' 
        : this.$refs.newStatus.value == 3 
        ? 'Orden completada exitosamente'
        :'Orden cancelada';
        
        const type = this.$refs.newStatus.value == 2 
        ? 'secondary' 
        : this.$refs.newStatus.value == 3 
        ? 'success'
        : 'error';

        this.$store
          .dispatch(CHANGE_STATUS, {id:this.selectedOrder.id, newStatus: this.$refs.newStatus.value})
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar(type, message)
            this.readyButton('change-status-order-button')
          })
          .catch((err) => {
            console.log(err)
            this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('change-status-order-button')
          })

      },
      cancelOrder(){
        this.$refs.newStatus.value = 0
        this.orderChangeStatus()
      },
      sendingButton(id){
        document.getElementById(id).disabled = true
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      removeDismantlingInput(type, index){
        this.productOption.splice(index, 1)
        const idButton = type == 2 
          ? 'new_product_form_button'
          : 'edit_product_form_button'

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')

        this.removeValidate(type == 2 
            ? 'new_product_form'
            : 'edit_product_form')

        setTimeout(() => {
            return type == 2 
          ? this.newProduct.dismantling.splice(index, 1)
          : this.selectedProduct.dismantling.splice(index, 1);
        }, 300);

        
        
      },
      addDismantlingInput(e){
        let newOrder = {
          id:'',
          title:'',
          quantity:''
        }
        
        
        // const button = document.getElementById('new_order_form')

        // this.disabledButton( button, 'remove')

        // setTimeout(() => {
        //   this.addValidate('new_order_form')
        // }, 500);
        return this.newOrder.products.push(newOrder)

      },
      searchDismantling(e, index){ 
        debounce(this.getProducts, 200)(e.target.value, index)
      },
      clearSearchDismantling(index){
        this.getProducts('',index)
      },
      selectDismantling(e,index,type){
        const idButton = type == 2 
          ? 'new_product_form_button'
          : 'edit_product_form_button'

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')
        console.log(this.selectedProduct.dismantling)
        return type == 2 
        ? this.newProduct.dismantling[index].piece_product_id = e
        : this.selectedProduct.dismantling[index].piece_product_id = e
      },
      getProducts(search = "", index){
        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            this.productsForOrder[index] = response.data
          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

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