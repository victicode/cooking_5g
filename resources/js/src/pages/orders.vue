<script setup >
  import DataTablesCore from 'datatables.net';
  import 'datatables.net-responsive-dt';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';
  
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";

  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue';
  import viewOrderModal from '@/views/pages/modals/viewOrderModal.vue';
  import viewCreateOutOrderModal from '@/views/pages/modals/viewCreateOutOrderModal.vue';
  import viewTimelineOrderModal from '@/views/pages/modals/viewTimelineOrderModal.vue';
  import * as bootstrap from 'bootstrap';
  import debounce from 'debounce';

  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css';
  import { Spanish } from "flatpickr/dist/l10n/es.js";

  import { GET_ORDER_BY_ID, CHANGE_STATUS, CREATE_ORDER, CREATE_OUT_ORDER } from "@/core/services/store/order.module";
  import { GET_ALL_USER, GET_USER } from "@/core/services/store/user.module";
  // import { GET_PRODUCT_BY_SEARCH } from "@/core/services/store/product.module";
  import { GET_RECIPE_BY_SEARCH } from "@/core/services/store/recipe.module";

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
          <VBtn v-if="!isUser" @click=" showModal('createOrder')" color="primary" class="w-100 "><VIcon icon="bx-plus"/>
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
          <table class="datatables-basic table display nowrap" id="data-table">
          </table>
        </div>
      </VCard>
    </VCol>
    <div v-if="Object.keys(selectedOrder).length > 2">
      <viewOrderModal :order="selectedOrder"  @actionModal="modalAction" />
      <viewCreateOutOrderModal :order="selectedOrder"  @actionModal="modalAction"  @createOutOrder="createOutOrder"  />
      <viewTimelineOrderModal :order="selectedOrder"  @actionModal="hideInternalModal" />
      <div class="modal animate__animated animate__fadeInDown" id="confirmOrder" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                          <h2>Finalizar Orden #{{ orderNumberFormat(selectedOrder.id) }}</h2>
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
                        <h2>¿Desea confirmar la recepción de la orden #{{orderNumberFormat(selectedOrder.id)}}?</h2>
                      </VCol>
                    </VRow>
                      

                    <VDivider  />
                    <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                      <VCardActions class=" justify-center w-100 d-flex ">
                        <VBtn
                          color="white"
                          class="bg-success text-white w-50 mx-0 mx-md-5 my-2"
                          @click="orderChangeStatus('3')"
                          v-if="selectedOrder.status == 1 || selectedOrder.status == 2"
                        >
                          <span class="">Confirmar</span>
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
                          <v-combobox  
                            v-if="isUser"
                            :items="userForOrder"
                            item-title="name"
                            item-value="id"
                            placeholder="Usuario"
                            label="Usuario"
                            type="text"
                            name="new_order_client"
                            v-model="newOrder.user"  
                            autocomplete="off"        
                            @update:modelValue="forms.validateField('new_order_client')"
                          ></v-combobox >
                          <VTextField
                             v-else 
                            placeholder="Usuario"
                            label="Usuario"
                            type="text"
                            disabled
                            v-model="user.name"
                          />
                        </VCol>
                        <VCol cols="12"  md="5" class="form-group px-3 my-2">
                          <div class="d-flex align-center">
                            <input type="checkbox"  @change="useClientAddress($event)" id="isUserAddress" value="1" style="height: 20px; width: 20px;">
                            <label for="isUserAddress" class="mx-2">Usar dirección del usuario</label>
                          </div>
                        </VCol>
                        <VCol cols="12" md="12" class="form-group mt-md-5">
                          <v-textarea
                            label="Dirección"
                            auto-grow
                            variant="outlined"
                            rows="3"
                            row-height="25"
                            shaped
                            name="new_order_address"
                            v-model="newOrder.userAddress"
                          ></v-textarea>
                        </VCol>
                      </VRow>
                      <VRow 
                        class="ma-0 pa-0  mt-5 align-center" 
                        >
                            <VCol cols="12" class="form-group">
                              <h3>Productos:</h3>
                            </VCol>
                            <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                              <VRow  v-for="(item,index) in newOrder.products"  v-bind:key="item.id" class=" position-relative relative pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'new_order_product_'+index">
                                <VCol cols="12"  :md="isUser ? 4 : 6" class="form-group pb-md-0  mb-md-1">
                                  <v-autocomplete
                                    :model-value="item.id"
                                    :items="productsForOrder[index] ?  productsForOrder[index] : item.id !== null ? [ {id: item.id, title: item.title, stock: item.quantity}] : []"
                                    label="Nombre de la receta"
                                    item-title="title"
                                    item-value="id"
                                    placeholder="Nombre de la receta"
                                    variant="outlined"
                                    :persistent-hint="isUser ? true : false "
                                    clearable
                                    no-filter
                                    :name="'product_in_order_'+index"
                                    no-data-text="No se encontraron resultados"
                                    @click="searchProductsForOrder($event,index )"
                                    @keyup="searchProductsForOrder($event,index )"
                                    @click:clear="clearProductSearch(index)"
                                    @update:modelValue="selectedProduct($event, index)"
                                  ></v-autocomplete>
                                </VCol>
                                <VCol cols="12"  md="5" class="form-group pb-md-0  mb-md-1" v-if="isUser">
                                  <v-combobox  
                                    :items="item.lotes"
                                    item-title="lote_code"
                                    item-value="id"
                                    placeholder="Número de lote"
                                    label="Número de lote"
                                    type="text"
                                    name="new_order_client"
                                    v-model="item.selected_lote"  
                                    autocomplete="off"
                                    persistent-hint
                                    :hint="'Fecha venc: ' + (item.selected_lote.due_date ? moment(item.selected_lote.due_date).format('DD-MM-YYYY') : '----')"
                                    :auto-select-first="true"
                                    @update:modelValue="selectedLotes($event,index)"
                                  ></v-combobox >
                                </VCol>
                                <VCol cols="12"  :md="isUser ? 3 : 6 " :class=" isUser ? 'form-group pb-md-0  mb-md-6' : 'form-group pb-md-0  mb-md-1' " >
                                  <VTextField
                                    placeholder="Unidades solicitadas"
                                    label="Unidades solicitadas"
                                    type="number"
                                    :hint="  item.maxValue ? 'Max: ' + item.maxValue : 0"
                                    persistent-hint
                                    :name="'product_in_order_quantity_'+index"
                                    v-model="item.quantity"
                                  />
                                </VCol>
                                <div class="form-group pa-0 mb-md-5  small-delete-product-button ">
                                  <v-tooltip text="Quitar producto">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="pa-0">
                                        <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeProductInput(index)"></v-btn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                                </div>
                              </VRow>
                            </div>
                      </VRow>
                      <VRow class="ma-0 pa-0  mt-8 align-center">
                        <VCol cols="12" md="6" class="mt-0 py-0 px-0">
                          <v-tooltip text="Agregar producto">
                              <template v-slot:activator="{ props }">
                                <v-col cols="auto" class="">
                                  <VBtn v-bind="props" color="secondary" class="w-100"  @click="addProductInput()"><VIcon icon="bx-plus"/> Agregar producto</VBtn>
                                </v-col>
                              </template>
                            </v-tooltip>
                        </VCol>
                        <VCol cols="12" md="6"  class="mt-0 py-0 px-0">
                          <v-col cols="auto" class="">
                            <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_order_form_button"> Guardar</VBtn>
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
      right: -10px;
    }
    thead > tr > th:last-child{
      width: 10%!important;
    }
  }
</style>
<script>

  export default {
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
      alertShow:false,
      alertMessage:'',
      alertType:'',
      forms:[],
      newOrder: {
        user:'',
        products:[],
        userAddress:'',
      },
      userForOrder:[],
      productsForOrder:[],
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
            class:'text-md-center text-start',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return `
              
              <span class="justify-center">
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
            class:'text-center px-3',
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
            class:'text-center  px-1 px-md-3',
            render: ( data, type, row, meta ) =>{ 
              let html = `
                <div class="d-md-flex d-none justify-center ">
                  <span data-id="${row.id}" class="view mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                  </span>
              `
              if(row.status == 1 ){
                if(window.localStorage.is_admin !='false'){
                  html += `
                      <span data-bs-toggle="tooltip" data-id="${row.id}" class="process mx-2" data-bs-placement="top" data-bs-title="Procesar salida">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="currentColor" d="M18 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5m1.5-9H17V12h4.46zM6 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5M20 8l3 4v5h-2c0 1.66-1.34 3-3 3s-3-1.34-3-3H9c0 1.66-1.34 3-3 3s-3-1.34-3-3H1V6c0-1.11.89-2 2-2h14v4zM3 6v9h.76c.55-.61 1.35-1 2.24-1c.89 0 1.69.39 2.24 1H15V6zm7 1l3.5 3.5L10 14v-2.5H5v-2h5z"/></svg>
                      </span>`
                }
              }
              if(row.status == 2 ){
                html += `
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="confirm mx-2" data-bs-placement="top" data-bs-title="Confirmar recepción">
                      <svg data-id="${row.id} xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M10.591 2.513a3.75 3.75 0 0 1 2.818 0l7.498 3.04A1.75 1.75 0 0 1 22 7.175v5.635a6.52 6.52 0 0 0-1.5-1.077v-3.96l-7.75 2.992v2.298a6.492 6.492 0 0 0-1.5 2.645v-4.944L3.5 7.75v9.078a.25.25 0 0 0 .156.231l7.499 3.04c.031.013.063.025.095.036l.189.076c.059.024.118.044.179.06c.248.526.565 1.014.94 1.451a3.75 3.75 0 0 1-1.967-.233l-7.498-3.04A1.75 1.75 0 0 1 2 16.827V7.176a1.75 1.75 0 0 1 1.093-1.622zm2.254 1.39a2.25 2.25 0 0 0-1.69 0L9.24 4.68l7.527 2.927l2.67-1.03zM4.59 6.564l7.411 2.883l2.69-1.04L7.216 5.5zM17.5 23.001a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11m-1-4.207l3.646-3.647a.5.5 0 0 1 .708.707l-4 4a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.707z"/></svg>
                    </span>`
              }
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
              `
              if(row.status == 1 ){
                if(window.localStorage.is_admin !='false'){
                  
                  html += `
                  <span data-bs-toggle="tooltip" data-id="${row.id}" class="process mx-3" data-bs-placement="top" data-bs-title="Procesar salida">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-id="${row.id}" fill="currentColor" d="M18 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5m1.5-9H17V12h4.46zM6 18.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5M20 8l3 4v5h-2c0 1.66-1.34 3-3 3s-3-1.34-3-3H9c0 1.66-1.34 3-3 3s-3-1.34-3-3H1V6c0-1.11.89-2 2-2h14v4zM3 6v9h.76c.55-.61 1.35-1 2.24-1c.89 0 1.69.39 2.24 1H15V6zm7 1l3.5 3.5L10 14v-2.5H5v-2h5z"/></svg>
                  </span>`
                }
              }
              if(row.status == 2 ){
                html += `
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="confirm mx-3" data-bs-placement="top" data-bs-title="Confirmar recepción">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg"role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M10.591 2.513a3.75 3.75 0 0 1 2.818 0l7.498 3.04A1.75 1.75 0 0 1 22 7.175v5.635a6.52 6.52 0 0 0-1.5-1.077v-3.96l-7.75 2.992v2.298a6.492 6.492 0 0 0-1.5 2.645v-4.944L3.5 7.75v9.078a.25.25 0 0 0 .156.231l7.499 3.04c.031.013.063.025.095.036l.189.076c.059.024.118.044.179.06c.248.526.565 1.014.94 1.451a3.75 3.75 0 0 1-1.967-.233l-7.498-3.04A1.75 1.75 0 0 1 2 16.827V7.176a1.75 1.75 0 0 1 1.093-1.622zm2.254 1.39a2.25 2.25 0 0 0-1.69 0L9.24 4.68l7.527 2.927l2.67-1.03zM4.59 6.564l7.411 2.883l2.69-1.04L7.216 5.5zM17.5 23.001a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11m-1-4.207l3.646-3.647a.5.5 0 0 1 .708.707l-4 4a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.707z"/></svg>
                    </span>`
              }
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
      user:{},
      isUser:false
    }),
    methods:{
      getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          if(data.code !== 200){
            console.log('alert!!!')
          }
          this.user = data.user;
          if(data.user.rol_id !== 3) this.isUser = true
        })
        .catch((e) => {
          this.logout()
        });
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
              }, 800);
            })
          })	
        })
        document.querySelectorAll('.process').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {

                this.showModal('createOutOrder')
              }, 800);
            })
          })	
        })
        document.querySelectorAll('.confirm').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('confirmOrder')
              }, 800);
            })
          })	
        })
        document.querySelectorAll('.cancel').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('cancelOrder')
              }, 800);
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
            setTimeout(() => {
              console.log(this.selectedOrder)
              
              return new Promise((resolve) => {
                  resolve(response.data);
              });
            }, 700);
          })
          .catch((err) => {
            console.log(err)
            return new Promise((resolve) => {
              resolve(false);
            });
          });
      },
      getUsers(){
        this.$store
          .dispatch(GET_ALL_USER)
          .then((data) => {
            // console.log(data)
            this.userForOrder = data
          })
          .catch((err) => {
            console.log(err)
        
          });
      },
      getProducts(search = "", index){
        this.$store
          .dispatch(GET_RECIPE_BY_SEARCH, search)
          .then((response) => {
            // console.log(response)
            this.productsForOrder[index] = response.data
          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

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
        this.inputDate.clear()
        document.querySelector('[name="tracker_id"]').value = '';
        document.querySelector('[name="start_date"]').value = '';
        document.querySelector('[name="end_date"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      clearNewOrderForm(){
        document.getElementById('isUserAddress').checked = false;
        this.newOrder = {
          user:'',
          products:[],
          userAddress:'',
        }
      },
      useClientAddress(e){
        this.newOrder.userAddress = this.user.user_address

        if(this.isUser){
          this.newOrder.userAddress = e.target.checked 
            ? this.newOrder.user.user_address
            : this.newOrder.userAddress = ''
        }
          
          this.forms.validateField('new_order_address')
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
        this.clearNewOrderForm()
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
      orderChangeStatus(newStatus){
        const message = newStatus == 3 
        ? 'Orden completada exitosamente'
        :'Orden cancelada';
        
        const type =  newStatus == 3 
        ? 'success'
        : 'error';

        this.$store
          .dispatch(CHANGE_STATUS, {id:this.selectedOrder.id, newStatus: newStatus})
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar(type, message)
          })
          .catch((err) => {
            console.log(err)
            this.hideModal()
            this.showSnackbar('error', err )
          })

      },
      createdNewOrder(){
        if(!this.validateHaveProducts()) return ;

        this.sendingButton('new_order_form_button')
        const formData = new FormData();
        const user = this.isUser 
        ? this.newOrder.user.id
        : this.user.id
        formData.append('client', user);
        formData.append('address', this.newOrder.userAddress);
        formData.append('products', JSON.stringify(this.newOrder.products));
        formData.append('isManual', this.isUser ? true : false);
        console.log(this.newOrder)
        this.$store
          .dispatch(CREATE_ORDER, formData)
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Orden creada con exito')
            this.readyButton('new_order_form_button')
            this.clearNewOrderForm()
          })
          .catch((err) => {
            console.log(err)
            // this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('new_order_form_button')
          })
      },
      createOutOrder(products){
        const formData = new FormData();
        formData.append('order', this.selectedOrder.id);
        formData.append('products', JSON.stringify(products));
        formData.append('recipes', JSON.stringify(this.selectedOrder.recipes));
        this.$store
          .dispatch(CREATE_OUT_ORDER, formData)
          .then((response) => {
            console.log(response)
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Orden creada con exito')
            this.readyButton('new_order_form_button')
            // this.clearNewOrderForm()
          })
          .catch((err) => {
            console.log(err)
            // this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('new_order_form_button')
          })
      },
      cancelOrder(){
        // this.$refs.newStatus.value = 0
        this.orderChangeStatus('0')
      },
      sendingButton(id){
        document.getElementById(id).disabled = true
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      removeProductInput(index){
        this.removeValidate(index)

        setTimeout(() => {
          try{
            this.newOrder.products.splice(index, 1)
            this.productsForOrder.splice(index, 1)
          }catch(e){

          }
        }, 200);
        
      },
      addProductInput(){
        let newProducInOrder = {
          id:null,
          title:'',
          quantity:'',
          maxValue:0,
          recipe:'',
          lotes:[],
          selected_lote:''

        }
        this.newOrder.products.push(newProducInOrder)
        console.log(this.newOrder.products)
        setTimeout(() => {
          if(this.isUser){

            this.addValidate()
          }

        }, 200);

      },
      selectedLotes(e, index){
        this.newOrder.products[index].maxValue = e.quantity
        setTimeout(() => {
            this.addValidate(this.newOrder.products[index].maxValue)
          }, 200);
      },
      searchProductsForOrder(e, index){ 
        debounce(this.getProducts, 200)(e.target.value, index)
      },
      clearProductSearch(index){
        this.newOrder.products[index].id = null;
        this.newOrder.products[index].maxValue = 0;
        this.newOrder.products[index].lotes =[]
        this.newOrder.products[index].selected_lote =''
        this.newOrder.products[index].recipe = ''
        this.getProducts('',index)
      },
      selectedProduct(e,index){
        if(!e) return
        if(!this.newOrder.products[index])return
        this.newOrder.products[index] = this.productsForOrder[index].find(product => product.id == e)
        this.newOrder.products[index].maxValue = this.maxStockRecipeInput(this.newOrder.products[index])
        
        if( this.newOrder.products[index].maxValue <= 0){
          this.newOrder.products[index] = {
          id:null,
          title:'',
          quantity:'',
          maxValue:0,
          recipe:'',
          lotes:[],
          selected_lote:''

        }
          alert('Esta producto no tiene Stock')
          return
        }
        setTimeout(() => {
          this.addValidate(this.newOrder.products[index].maxValue)

        }, 200);
        
      },
      maxStockRecipeInput(product){
        let minus = 0;
        product.cooking_ingredients.forEach((ingredient, index)=>{
          if(index==0) minus = ingredient.total_stock /parseFloat(ingredient.pivot.quantity)

          minus = minus > (ingredient.total_stock /parseFloat(ingredient.pivot.quantity) )
          ? ingredient.total_stock /parseFloat(ingredient.pivot.quantity) 
          : minus
        })
        return minus.toFixed(0)
      },
      validateFormItem(){
        this.forms = formValidation(document.getElementById('new_order_form'), {
          fields: {
            new_order_client:{
                validators: {
                  notEmpty: {
                    message: "El cliente es requerido"
                  },
                }
              },
              new_order_address: {
                validators: {
                  notEmpty: {
                    message: "Debes agregar la dirección"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
          },
          plugins: {
            trigger: new Trigger(),
            submitButton: new SubmitButton(),
            bootstrap: new Bootstrap({
                  // Use this for enabling/changing valid/invalid class
                  // eleInvalidClass: '',
                  // eleValidClass: '',
                }),
          }
        });
        setTimeout( ()=> this.formsActions(), 500)
      },
      formsActions(){
        const sendButton = document.getElementById('new_order_form_button')
       
        this.forms.on("core.form.valid", () => {
          this.createdNewOrder()
        }).on("core.field.valid", () => {
          sendButton.disabled = false
          sendButton.classList.remove('v-btn--disabled')

        }).on("core.form.invalid", () => {
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')

        }).on("core.field.invalid", () => {
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')

        });
      },
      destroyFormVal(){
        this.forms['edit_product_form'].destroy()
        this.forms['add_stock_form'].destroy()
        this.forms['new_product_form'].resetForm()
          
      },
      addValidate(max){
        // this.forms[id] 
        
        let form = document.getElementById('new_order_form'),
        quantityInput = form.querySelectorAll('input[name*="product_in_order_quantity_"]')[ form.querySelectorAll('input[name*="product_in_order_quantity_"]').length - 1],
        fieldOptions={
          quantity: {
            validators: {
              notEmpty: {
                message: "Agregar la cantidad unidades"
              },
              numeric: {
                message: "Debe ser númerico"
              },
              lessThan: {
                message: "Cantidad supera el stock",
                max: max,
              },
            }
          }
        } 
        this.forms.addField(quantityInput.name, fieldOptions.quantity)
        return
      },
      removeValidate(){
        let form = document.getElementById('new_order_form'),
        quantityInput = form.querySelectorAll('input[name*="product_in_order_quantity_"]')[ form.querySelectorAll('input[name*="product_in_order_quantity_"]').length - 1]
        try {
          this.forms.removeField(quantityInput.name)
        } catch (error) {
          console.log('no hay validación activa')
        }
        
      },
      validateHaveProducts(){
        if(this.newOrder.products.length == 0 ){
          const sendButton = document.getElementById('new_order_form_button')
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')
          alert('Debes ingresar los productos')
          return false
        } 
        return true
        
      }  
    },
    mounted(){
      this.getUsers();
      this.getUser();
      this.initOptionsTable()
      this.table = new DataTablesCore('#data-table', this.tableData)
      this.initFlatpickr();
      this.bootstrapOptions();
      this.validateFormItem();
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>