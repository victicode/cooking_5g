<script setup >

  import DataTablesCore from 'datatables.net';
  import 'datatables.net-responsive-dt';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';

  import * as bootstrap from 'bootstrap'
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import debounce from 'debounce';
  
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  
  import viewProductModal from '@/views/pages/modals/viewProductModal.vue';
  import viewCreateNewLoteModal from '@/views/pages/modals/viewCreateNewLoteModal.vue';
  import viewDeleteProduct from '@/views/pages/modals/viewDeleteProduct.vue';

  import viewUpdateLoteModal from '@/views/pages/modals/viewUpdateLoteModal.vue';
  import viewCreateProduct from '@/views/products/viewCreateProduct.vue';

  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import { GET_LOTE_OF_PRODUCT, GET_PRODUCT_BY_SEARCH, STORE_PRODUCT, UPDATE_PRODUCT, ADD_STOCK, DELETE_LOTE_OF_PRODUCT, GET_LAST_LOTE, } from "@/core/services/store/product.module";

</script>
<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de productos" class="pa-3 px-2 px-md-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0 pb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
            <VBtn @click=" showModal('createProduct'); getProducts('', 0)" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>
          </VCol>
          <VCol
            cols="11"
            md="2"
            class="ma-0 mx-2 px-0 justify-center justify-md-end d-flex"
          >
            <VBtn @click=" showModal('addStockInProduct')" color="secondary" class="w-100 "><VIcon icon="bx-plus"/> Agregar Lote</VBtn>
          </VCol>
          <VCol
            cols="11"
            md="2"
            class="ma-0 me-0 px-0 justify-center justify-md-end d-flex"
          >
            <VBtn @click=" showModal('deleteProductQuick')" color="error" class="w-100 "><VIcon icon="bx-trash" class="me-1"/> Eliminar producto</VBtn>
          </VCol>
        </VRow>
        <VRow class="ma-0  justify-center align-center justify-md-start pa-2 px-0 mb-10 mb-md-2 py-0 my-0">
          <VCol cols="7" md="4" class="form-group mx-0 px-0 px-md-3">
            <VTextField
              placeholder="Buscar producto"
              label="Buscar producto"
              type="text"
              ref="product_title"
              name="product_title"
              @keyup="filterColumn()"
            />
          </VCol>
          <VCol cols="5" md="4" class="my-0 py-0 mx-0 ">
            <VBtn
              color="white"
              class="bg-primary text-white w-30 mx-2 mx-md-5 my-2"
              @click="clearFilters()"
            >
              <span class="">Restaurar</span>
            </VBtn>
          </VCol>
        </VRow>
        
      </VCard>
      <VCard class="mt-2">
        <div class="card-datatable table-responsive">
          <table class="max-width-700 datatables-basic display table" id="data-table">
          </table>
        </div>
      </VCard>
    </VCol>
    <div v-if="Object.keys(selectedProduct).length > 2" >
      <viewProductModal :product="selectedProduct" @hiddenModal="hideModal" />
      <div class="modal animate__animated animate__slideInLeft" id="editProduct" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-0 ma-0" style="width: 100%; height: 100vh;">
        <div class="modal-content h-100">
          <VCol
            cols="12"
            class="pa-0 d-flex justify-center"
            style="position: relative;"
          >
            <VCol
              cols="12"
              class="pa-0"
            >
              <VCard class="modal__content h-100 rounded-0" >
                <div class="modal__close-button__cart" >
                  <v-col  class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                <div class="d-flex justify-space-between  flex-column pa-2 px-4 pa-md-5 ">
                    <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title d-flex ">
                          <div class="form-title__part1">Editar Producto</div>
                          
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
                      <VForm  id="edit_product_form">
                        <VRow>
                          
                          <VCol cols="12"  class=" ">
                            <div class="img-content mx-auto">
                              <label for="editProduct-img">
                                <VImg
                                  width="200"
                                  height="200"
                                  class="rounded "
                                  :src="selectedProduct.product.img "
                                  style="border-radius:10%!important"
                                />
                                <div class="overlay-img">
                                  <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                                </div>
                              </label>
                              <VCol cols="12" md="6" class="form-group">
                                <input type="file" id="editProduct-img" ref="editProductImg" name="edit_product_img"  class="d-none" @change="onFileChange" >
                              </VCol>
                            </div>
                          </VCol>
                          <VCol cols="12" md="6" class="form-group">
                            <VTextField
                              placeholder="Nombre del producto"
                              label="Nombre del producto"
                              type="text"
                              name="edit_product_title"
                              v-model="selectedProduct.product.title"
                              
                            />
                          </VCol>
                          <VCol cols="12" md="6" class="form-group">
                            <VTextField
                              placeholder="Descripción corta"
                              label="Descripción corta"
                              type="text"
                              name="edit_product_description_short"
                              v-model="selectedProduct.product.short_description"
                            />
                          </VCol>
                          <VCol cols="12" class="form-group">
                            <v-textarea
                              label="Descripcion larga"
                              auto-grow
                              variant="outlined"
                              rows="3"
                              row-height="25"
                              shaped
                              name="edit_product_description"
                              v-model="selectedProduct.product.description"
                            ></v-textarea>
                          </VCol>
                          
                          <VCol cols="6" md="6" class="form-group">
                            <v-combobox
                              label="Tipo de unidad"
                              :items="['Selecciona uno','KG', 'UNI', 'PZAS']"
                              variant="outlined"
                              v-model="selectedProduct.product.type_of_unit"
                            ></v-combobox>
                          </VCol>
                          <VCol cols="12" md="6" >
                            <v-switch
                              color="primary"
                              label="Tiene despieces" 
                              v-model="selectedProduct.product.is_dismantling" :value="1" 
                              @change="validateSwitch($event)"
                            />
                          </VCol>
                        </VRow>
                        <VRow class="ma-0 pa-0  mt-4 align-center" v-if="selectedProduct.product.is_dismantling ">
                              <VCol cols="12" class="form-group">
                                <h3>Despieces:</h3>
                              </VCol>
                              <VCol cols="12" md="4" class="mt-0 py-0 px-0">
                                <v-tooltip text="Agregar nuevo despiece">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="">
                                        <VBtn v-bind="props" color="primary" class="w-100 " @click="addDismantlingInput(1)"><VIcon icon="bx-plus"/> Agregar despiece</VBtn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                              </VCol>
                              <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items"  v-if="countDismantlingActive(selectedProduct.product.dismantling) > 0">
                                <VRow  v-for="(item,index) in selectedProduct.product.dismantling" v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'product_desmantling_'+index" >
                                  <VCol cols="12"  md="6" class="form-group" v-if="item.products_pieces" >
                                    <v-autocomplete
                                      :model-value="item.piece_product_id"
                                      :loading="loading"
                                      :items="productOption[index+'_'+selectedProduct.product.id] != undefined ?  productOption[index+'_'+selectedProduct.product.id] : item.piece_product_id !== null ?[ {id: item.piece_product_id, title: item.products_pieces.title}] : []"
                                      label="Nombre del despiece"
                                      item-title="title"
                                      item-value="id"
                                      placeholder="Nombre del despiece"
                                      variant="outlined"
                                      clearable
                                      no-filter
                                      autocomplete="off"
                                      no-data-text="No se encontraron resultados"
                                      :name="'product_desmantling_id_'+index"
                                      class="dismantling_select"
                                      :menu-props="{'content-class':'dismantling_select'}"
                                      @keyup="searchDismantling($event, index+'_'+selectedProduct.product.id)"
                                      @click:clear="clearSearchDismantling(index+'_'+selectedProduct.product.id)"
                                      @update:modelValue="selectDismantling($event, index, 1)"
                                    ></v-autocomplete>
                                  </VCol>
                                  <VCol cols="8"  md="4" class="form-group" v-if="item.products_pieces" >
                                    <VTextField
                                      placeholder="Unidades que trae"
                                      label="Unidades que trae"
                                      type="number"
                                      :name="'product_desmantling_quantity_'+index"
                                      v-model="item.quantity"
                                      
                                    />
                                  </VCol>
                                  <VCol cols="4" md="1" class="form-group pa-0" v-if="item.products_pieces" >
                                    <v-tooltip text="Eliminar despiece">
                                      <template v-slot:activator="{ props }">
                                        <v-col cols="auto" class="">
                                          <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeDismantlingInput(1,index)"></v-btn>
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
                              <VBtn  color="primary" class="w-100 " type="submit" disabled id="edit_product_form_button" > Guardar </VBtn>
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
      <div class="modal animate__animated animate__slideInLeft" id="addStockProduct" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-0 ma-0" style="width: 100%; height: 100vh;">
          <div class="modal-content h-100">
          <VCol
            cols="12"
            class="pa-0 d-flex justify-center"
            style="position: relative;"
          >
            <VCol
              cols="12"
              class="pa-0"
            >
              <VCard class="modal__content h-100 rounded-0">
                <div class="modal__close-button__cart" >
                  <v-col  class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                <div class="d-flex justify-space-between  flex-column pa-2 px-2 pa-md-5 ">
                    <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title d-flex ">
                          <div class="form-title__part1">Editar stock de producto</div>
                
                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <VCardItem class="w-100  py-md-0  py-4 text-center">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title ">
                          <div class="form-title__part1 mx-4 my-2 " style="text-wrap:wrap">
                              {{ selectedProduct.product.title }}
                          </div>
                          <div class="d-flex mt-4 justify-center w-100 flex-wrap mt-md-0">
                            <div class="form-title__part1 mx-4 d-flex align-center justify-center ">
                              Stock actual:
                              <span class=" ms-2" >
                                <v-chip :class="TotalStockByLotes(selectedProduct.product)  < 1 ? 'bg-error' : TotalStockByLotes(selectedProduct.product)  >= 30 ? 'bg-success' : 'bg-warning'">
                                  {{ TotalStockByLotes(selectedProduct.product)  }}
                                </v-chip>
  
                              </span>
                            </div>
                            <div class="form-title__part1 mx-4 mt-2 mt-md-0 d-flex align-center justify-center" >
                              Stock nuevo:
                              <span class=" ms-2" v-if="!isNaN(selectedProduct.product.newStock) ">
                                <v-chip :class="selectedProduct.product.newStock < 1 ? 'bg-error' : selectedProduct.product.newStock >= 30 ? 'bg-success' : 'bg-warning'">
                                  {{ selectedProduct.product.newStock }}
                                </v-chip>
  
                              </span>
                            </div>
                          </div>

                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <VCardText class="mb-5  w-100 pa-0" v-if="alertShow">
                      <v-alert
                        :color="alertType"
                        :text="alertMessage"
                      ></v-alert>
                    </VCardText>
                    <VCardText class="w-100 pb-5 px-4 px-md-6">
                      <VForm  id="add_stock_form">
                        <VRow class="my-3">
                          <VCol cols="5" md="5"  class="form-group" v-show="stockOperation.type==1">
  
                            <VTextField
                              placeholder="Número de lote"
                              label="Número de lote"
                              type="text"
                              name="stock_add_lot"
                              class=""
                              v-model="stockOperation.lot"
                              autocomplete="off"
                              variant="underlined"
                              disabled
                              color="red"
                              
                            />
                          </VCol>
                          <VCol cols="8" md="4"  class="form-group" v-show="stockOperation.type==2">
  
                            <v-combobox  
                              :items="selectedProduct.product.lotes"
                              item-value="id_lote"
                              item-title="lote_code"
                              placeholder="Número de lote"
                              label="Número de lote"
                              type="text"
                              name="stock_dis_lot"
                              class="text-end"
                              v-model="stockOperation.lot"
                              persistent-hint
                              :return-object="false"
                              :hint="'Stock en lote: '+ stockOperation.lot_quantity"
                              @update:modelValue="selectLote($event)"
                              
                            ></v-combobox >
                          </VCol>
                          <VCol cols="7" md="5" offset-md="2" class="form-group px-2" v-show="stockOperation.type==1" >
                            <VTextField
                              placeholder="Fecha de vencimiento"
                              label="Fecha de vencimiento"
                              type="text"
                              name="stock_due_date"
                              ref="stock_due_date"
                              id="dueDateAddStock"
                            />
                            <input type="hidden" id="date-input-val-dueDateAddStock" ref="due_date_dueDateAddStock" >
                          </VCol>

                          
                        </VRow>
                        <VRow>
                          
                          <VCol cols="12" md="6" class="form-group">
                            <v-select
                              item-title="title"
                              item-value="value"
                              label="Tipo de operación"
                              :items=" [{ title:'Agregar stock', value: 1 }, { title:'Disminuir stock', value: 2 }]"
                              variant="outlined"
                              v-model="stockOperation.type"
                              @update:modelValue="stockCalculate(); clearDateAndLot()"
                              
                            ></v-select>
                          </VCol>
                          <VCol cols="12" md="6" class="form-group">
                            <VTextField
                              placeholder="Cantidad"
                              label="Cantidad"
                              type="Number"
                              name="stock_form_quatity"
                              v-model="stockOperation.quantity"
                              @keyup="stockCalculate()"
                            />
                          </VCol>
                        </VRow>
                        <VRow class="ma-0 pa-0  mt-8 align-center">
                          <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                            <v-col cols="auto" class="">
                              <VBtn  color="primary" class="w-100 " type="submit" disabled id="add_stock_form_button"> Guardar </VBtn>
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
      <div class="modal animate__animated animate__fadeInDown" id="deleteProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">   
        <div class="modal-dialog modal-lg mt-10">
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
              <VCol
                cols="12"
                class="px-2"  
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col class="pa-0 pe-4">
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
                          <h2>Eliminar Producto</h2>
                        </div>
                      </VCol>
                      <VCol
                        cols="12"
                        class="px-md-10 px-0 text-center"
                        style=""
                      >
                        <h2>¿Seguro que deseas eliminar este lote de <b class="text-primary">{{selectedProduct.product.title}}</b>?</h2>
                      </VCol>
                    </VRow>                      
                    <VRow>
                      <VCol cols="12"  class="form-group py-0">
                        <div class="d-flex align-center justify-center">
                          <input 
                            type="checkbox"  
                            id="deleteProductToo" 
                            v-model="deleteProductToo" style="height: 15px; width: 15px;">
                          <label for="deleteProductToo" class="mx-2 user-chat__name">Eliminar producto también</label>
                        </div>
                      </VCol>
                    </VRow>
                   
                    <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                      <VCardActions class=" justify-center w-100 d-md-flex  d-flex">
                        <VBtn
                          color="white"
                          class="bg-error text-white w-50 mx-0 mx-md-5 my-2"
                          @click="deleteProduct()"
                        >
                          <span class="">Eliminar</span>
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
      <viewUpdateLoteModal @hiddenModal="updateAndHidden" :lote="selectedProduct" @updateProducts="filterColumn()" />
    </div>
    <viewCreateProduct @hiddenModal="updateAndHidden" />
    <viewCreateNewLoteModal @hiddenModal="updateAndHidden"/>
    <viewDeleteProduct @hiddenModal="updateAndHidden"/>


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

<script>
  export default {
    data: () => ({
      alertShow:false,
      alertMessage:'',
      alertType:'',
      deleteProductToo:false,
      forms:[],
      inputDate:[],
      internalModal:'',
      loading: false,
      modal: '',
      newDismantling: false,
      newProduct:{
        img:'images/product/default.png',
        title:'',
        description:'',
        short_description:'',
        stock:'',
        unit:'KG',
        isDismantling: 0 ,
        dismantling:[],
        init_due_date:'',
        init_lote:''
      },
      product:'',
      productOption:[],
      search:'',
      selectedIndex:'',
      selectedLote:{},
      selectedProduct:{},
      snackShow:false,
      snackMessage:'',
      snacktimeOut:5000,
      snackType:'',
      stepperNewProduct: 1,
      steps: 2,
      stockOperation:{
        type:1,
        quantity:'',
        lot:'',
        lot_quantity: '',
        due_date:''
      },
      table:'',
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-lotes",
          "type": "POST",
          data: function ( data ) {
            data.filter_product_title = document.querySelector('[name="product_title"]').value;
            data.order_title = data.order[0].column == 0 ?data.order[0].dir : ''  
            data.order_stock = data.order[0].column == 2 ? data.order[0].dir : ''
            data.order_due_date = data.order[0].column == 1 ? data.order[0].dir : ''
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
        scrollX: true,
        dataType:'json',
        processing: true,
        serverSide: true,
        resposive:true,
        columnDefs: [
          {
            defaultContent: "",
            targets: "_all"
          },
        ],
        columns: [
          { 
            title: '<div class="d-none d-md-block ms-md-2">Nombre del producto</div><div class="d-md-none d-block">Producto</div>', class:'text-start title-th',
            render: ( data, type, row, meta ) =>{ 
                return `<div  data-lote="${row.id_lote}"  class="view text-decoration-underline ms-md-2" >${row.product.title} (${row.lote_code})</div>`
              }   
          },
          {
            title:' <div class="d-none d-md-block">Fecha venc.</div><div class="d-md-none d-block">Fecha venc</div>',
            class:'text-center ',
            render: (data, type, row, meta) =>{
              let today = new moment();
              let product_due_date = moment(row.due_date);
              let diff_due_date = Math.round(moment.duration(product_due_date.diff(today)).as('days'));
              return `
                <span 
                  class="view_lotes v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${ diff_due_date < 1 ? 'bg-error' : diff_due_date >= 30 ? 'bg-success' : 'bg-warning'}" 
                  draggable="false"
                  >
                    <span class="v-chip__underlay"></span>
                  <div class="v-chip__content">${moment(row.due_date).format('DD-MM-YYYY')} </div>
                </span> 
              `
            }
          },
          { 
            title: 'Stock lote',
            class:'text-center ',
            render: ( data, type, row, meta ) =>{ 
              return `
              <span 
                class="v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${row.quantity < 1 ? 'bg-error' : row.quantity >= 30 ? 'bg-success' : 'bg-warning'}" 
                draggable="false"
                >
                  <span class="v-chip__underlay"></span>
                <div class="v-chip__content">${$number_format(row.quantity)} ${row.product.type_of_unit}</div>
              </span> `
            } 

          },
          { 
            title: '<span class="d-none d-md-block">Acciones</span> <span class="d-flex d-md-none justify-center "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#8c8c8c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4H7.2c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C4 5.52 4 6.08 4 7.2v9.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h9.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.987.218-2.105V14m-4-9l-6 6v3h3l6-6m-3-3l3-3l3 3l-3 3m-3-3l3 3"/></svg></span>',
            orderable: false, 
            searchable: false, 
            class:'text-center',
            render: ( data, type, row, meta ) =>{ 
              return `
                <div class="d-md-flex d-none justify-center">
                  <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="edit" data-bs-placement="top" data-bs-title="Editar producto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" data-lote="${row.id_lote}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24">
                      <g data-lote="${row.id_lote}"  fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path data-lote="${row.id_lote}"  d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path data-lote="${row.id_lote}"  d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1l1-4Z"/>
                      </g>
                    </svg>
                  </span>
                  <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="stock" data-bs-placement="top" data-bs-title="Agregar stock">
                    <svg xmlns="http://www.w3.org/2000/svg" data-lote="${row.id_lote}"  width="24" height="24" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
                      <g fill="none" stroke="#808080" data-lote="${row.id_lote}"  stroke-linecap="round" stroke-width="1.5">
                        <path stroke-linejoin="round" data-lote="${row.id_lote}"  d="M20.935 11.009V8.793a2.978 2.978 0 0 0-1.529-2.61l-5.957-3.307a2.978 2.978 0 0 0-2.898 0L4.594 6.182a2.978 2.978 0 0 0-1.529 2.611v6.414a2.978 2.978 0 0 0 1.529 2.61l5.957 3.307a2.978 2.978 0 0 0 2.898 0l2.522-1.4"/>
                        <path stroke-linejoin="round" data-lote="${row.id_lote}"  d="M20.33 6.996L12 12L3.67 6.996M12 21.49V12"/>
                        <path stroke-miterlimit="10" data-lote="${row.id_lote}"  d="M19.903 13.965v5m-2.494-2.495h5"/>
                      </g>
                    </svg>
                  </span>
                  <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="update_stock" data-bs-placement="top" data-bs-title="Editar lote">
                    <svg data-lote="${row.id_lote}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
                      <path data-lote="${row.id_lote}" fill="#808080" d="M10.591 2.513a3.75 3.75 0 0 1 2.818 0l7.498 3.04A1.75 1.75 0 0 1 22 7.175v5.635a6.5 6.5 0 0 0-1.5-1.077v-3.96l-7.75 2.992v2.298a6.5 6.5 0 0 0-1.5 2.645v-4.944L3.5 7.75v9.078a.25.25 0 0 0 .156.231l7.499 3.04q.047.02.095.036l.189.076q.088.036.179.06c.248.526.565 1.014.94 1.451a3.75 3.75 0 0 1-1.967-.233l-7.498-3.04A1.75 1.75 0 0 1 2 16.827V7.176a1.75 1.75 0 0 1 1.093-1.622zm2.254 1.39a2.25 2.25 0 0 0-1.69 0L9.24 4.68l7.527 2.927l2.67-1.03zM4.59 6.564l7.411 2.883l2.69-1.04L7.216 5.5zM23 17.501a5.5 5.5 0 1 0-11 0a5.5 5.5 0 0 0 11 0m-5.458-3.498l.086.015l.063.02l.068.035l.047.032l2.548 2.542l.057.07a.5.5 0 0 1-.695.695l-.07-.057L18 15.707v5.294l-.008.09a.5.5 0 0 1-.402.402l-.09.008l-.09-.008a.5.5 0 0 1-.402-.402L17 21l-.001-5.292l-1.645 1.646l-.07.057a.5.5 0 0 1-.568 0l-.07-.057l-.057-.07a.5.5 0 0 1 0-.568l.057-.07l2.513-2.512l.056-.045l.074-.042l.083-.03l.06-.012l.056-.005z"/>
                    </svg>
                  </span>
                  <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="delete" data-bs-placement="top" data-bs-title="Eliminar producto">
                    <svg data-lote="${row.id_lote}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" " viewBox="0 0 24 24">
                      <g data-lote="${row.id_lote}" fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                        <path data-lote="${row.id_lote}" d="M5.47 6.015v12.514a2.72 2.72 0 0 0 2.721 2.721h7.618a2.72 2.72 0 0 0 2.72-2.72V6.014m-15.235.001h17.412"/>
                        <path data-lote="${row.id_lote}" d="M8.735 6.015V4.382a1.632 1.632 0 0 1 1.633-1.632h3.264a1.632 1.632 0 0 1 1.633 1.632v1.633M9.824 16.992v-5.439m4.353 5.439v-5.439"/>
                      </g>
                    </svg>
                  </span>
                </div>
                <div class="d-md-none d-flex justify-center position-relative relative ">
                  <div class="dropdown dropup ">
                    <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                    </button>
                    <div class="dropdown-menu animate__animated animate__rubberBand large">
                      <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="edit " data-bs-placement="top" data-bs-title="Editar producto">
                        <svg  data-lote="${row.id_lote}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24">
                          <g  data-lote="${row.id_lote}" fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                            <path  data-lote="${row.id_lote}" d="M19.09 14.441v4.44a2.37 2.37 0 0 1-2.369 2.369H5.12a2.37 2.37 0 0 1-2.369-2.383V7.279a2.356 2.356 0 0 1 2.37-2.37H9.56"/>
                            <path  data-lote="${row.id_lote}" d="M6.835 15.803v-2.165c.002-.357.144-.7.395-.953l9.532-9.532a1.362 1.362 0 0 1 1.934 0l2.151 2.151a1.36 1.36 0 0 1 0 1.934l-9.532 9.532a1.361 1.361 0 0 1-.953.395H8.197a1.362 1.362 0 0 1-1.362-1.362M19.09 8.995l-4.085-4.086"/>
                          </g>
                        </svg>
                      </span>
                      <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="stock" data-bs-placement="top" data-bs-title="Agregar stock">
                        <svg xmlns="http://www.w3.org/2000/svg" data-lote="${row.id_lote}"  width="24" height="24" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
                          <g fill="none" stroke="#808080" data-lote="${row.id_lote}"  stroke-linecap="round" stroke-width="1.5">
                            <path stroke-linejoin="round" data-lote="${row.id_lote}"  d="M20.935 11.009V8.793a2.978 2.978 0 0 0-1.529-2.61l-5.957-3.307a2.978 2.978 0 0 0-2.898 0L4.594 6.182a2.978 2.978 0 0 0-1.529 2.611v6.414a2.978 2.978 0 0 0 1.529 2.61l5.957 3.307a2.978 2.978 0 0 0 2.898 0l2.522-1.4"/>
                            <path stroke-linejoin="round" data-lote="${row.id_lote}"  d="M20.33 6.996L12 12L3.67 6.996M12 21.49V12"/>
                            <path stroke-miterlimit="10" data-lote="${row.id_lote}"  d="M19.903 13.965v5m-2.494-2.495h5"/>
                          </g>
                        </svg>
                      </span>
                      <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="update_stock" data-bs-placement="top" data-bs-title="Editar lote">
                        <svg data-lote="${row.id_lote}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
                          <path data-lote="${row.id_lote}" fill="#808080" d="M10.591 2.513a3.75 3.75 0 0 1 2.818 0l7.498 3.04A1.75 1.75 0 0 1 22 7.175v5.635a6.5 6.5 0 0 0-1.5-1.077v-3.96l-7.75 2.992v2.298a6.5 6.5 0 0 0-1.5 2.645v-4.944L3.5 7.75v9.078a.25.25 0 0 0 .156.231l7.499 3.04q.047.02.095.036l.189.076q.088.036.179.06c.248.526.565 1.014.94 1.451a3.75 3.75 0 0 1-1.967-.233l-7.498-3.04A1.75 1.75 0 0 1 2 16.827V7.176a1.75 1.75 0 0 1 1.093-1.622zm2.254 1.39a2.25 2.25 0 0 0-1.69 0L9.24 4.68l7.527 2.927l2.67-1.03zM4.59 6.564l7.411 2.883l2.69-1.04L7.216 5.5zM23 17.501a5.5 5.5 0 1 0-11 0a5.5 5.5 0 0 0 11 0m-5.458-3.498l.086.015l.063.02l.068.035l.047.032l2.548 2.542l.057.07a.5.5 0 0 1-.695.695l-.07-.057L18 15.707v5.294l-.008.09a.5.5 0 0 1-.402.402l-.09.008l-.09-.008a.5.5 0 0 1-.402-.402L17 21l-.001-5.292l-1.645 1.646l-.07.057a.5.5 0 0 1-.568 0l-.07-.057l-.057-.07a.5.5 0 0 1 0-.568l.057-.07l2.513-2.512l.056-.045l.074-.042l.083-.03l.06-.012l.056-.005z"/>
                        </svg>
                      </span>
                      <span data-bs-toggle="tooltip" data-lote="${row.id_lote}" class="delete" data-bs-placement="top" data-bs-title="Eliminar producto">
                        <svg data-lote="${row.id_lote}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" " viewBox="0 0 24 24">
                          <g data-lote="${row.id_lote}" fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                            <path data-lote="${row.id_lote}" d="M5.47 6.015v12.514a2.72 2.72 0 0 0 2.721 2.721h7.618a2.72 2.72 0 0 0 2.72-2.72V6.014m-15.235.001h17.412"/>
                            <path data-lote="${row.id_lote}" d="M8.735 6.015V4.382a1.632 1.632 0 0 1 1.633-1.632h3.264a1.632 1.632 0 0 1 1.633 1.632v1.633M9.824 16.992v-5.439m4.353 5.439v-5.439"/>
                          </g>
                        </svg>
                      </span>
                    </div>
                  </div>
                </div> 
              `
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
          '<"v-col v-col-md-5 v-col-12 mt-0 text-primary"i>' +
          '<"v-col v-col-md-7 v-col-12 mt-0 text-primary"p>' +
          '>',	   
        language: {
          sLengthMenu: '_MENU_',
          search: '',
          searchPlaceholder: 'Ingresa el nombre del producto',
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
        order:[[1, 'asc']],
        drawCallback: function( settings ) {
          const TableElement = document.getElementById('data-table');
          const event = new Event("OptionsActionTable")
          TableElement.dispatchEvent(event);
        },
      },
    }),
    methods:{
      stockCalculate(){
        this.selectedProduct.product.newStock =  this.stockOperation.type == 1
          ? parseInt(this.TotalStockByLotes(this.selectedProduct.product))  + parseInt(this.stockOperation.quantity)
          : parseInt(this.TotalStockByLotes(this.selectedProduct.product))  - parseInt(this.stockOperation.quantity)
      
      },
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectProduct(event.target.dataset.lote).finally((data)=>{
              this.showModal('viewProduct')
            })
          })	
        })
        document.querySelectorAll('.edit').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectProduct(event.target.dataset.lote).finally( (data) => {
              this.showModal('editProduct')
            })
          })	
        })
        document.querySelectorAll('.stock').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectProduct(event.target.dataset.lote).finally( (data) => {
              this.showModal('addStockProduct')
              this.getLastLoteNumber();
              this.initDueDate('dueDateAddStock')
            })
          })	
        })
        document.querySelectorAll('.update_stock').forEach( item => {
          item.addEventListener('click', async event => {
            await this.selectProduct(event.target.dataset.lote).finally( (data) => {
              this.showModal('updateLote')
              this.emitter.emit('assignLote', false)
            })
          })
        })
        document.querySelectorAll('.delete').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectProduct(event.target.dataset.lote).finally( (data) => {
              this.showModal('deleteProduct')
            })
          })	
        })
      },
      selectProduct(idAccount){
        this.emitter.emit('displayOverlayLoad', true)
        return new Promise((resolve) => {
          this.$store
            .dispatch(GET_LOTE_OF_PRODUCT, idAccount)
            .then((response) => {
              this.selectedProduct = Object.assign({}, response.data);
              setTimeout(() => {
                this.emitter.emit('displayOverlayLoad', false)
                this.validateFormItem('add_stock_form')
                this.validateFormItem('edit_product_form')
                this.addValidate('edit_product_form', 'update')
                resolve(response.data);
              }, 300);
            });
          })
          .catch((err) => {
            console.log(err)
              resolve(false);
          });
      }, 
      getProducts(search = "", index){
        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            this.productOption[index] = response.data
            this.productOption[index].push({
              id:-1,
              title: 'Añadir nuevo +'
            })

          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

      },
      onFileChange(e) {
        const file = e.target.files[0];
       return e.target.id == 'newProduct-img' 
       ? this.newProduct.img = URL.createObjectURL(file)
       : this.selectedProduct.product.img = URL.createObjectURL(file)
      },
      bootstrapOptions(){
        setTimeout(() => {
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
          const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
          const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
          const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
        }, 2000);
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
        this.deleteProductToo = false;
        this.destroyFormVal();
        // this.resetNewProductForm()
        this.resetStockForm();
      },
      showInterModal(modal){
        this.modal.hide()

        try {
          this.internalModal.hide()
        } catch (error) {
          
        }
        this.internalModal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.internalModal.show()
      },
      hideNewDismantlingForm(id){
        this.internalModal.hide();
        this.newDismantling = false;
        this.modal.show()
        if(id){
          if(this.selectedIndex.type == 2){

            this.newProduct.dismantling[this.selectedIndex.index].piece_product_id = id.id
            this.newProduct.dismantling[this.selectedIndex.index].products_pieces = id
            this.productOption[this.selectedIndex.index] = null;
          }
          if(this.selectedIndex.type == 1){
            this.selectedProduct.product.dismantling[this.selectedIndex.index].piece_product_id = id.id
            this.selectedProduct.product.dismantling[this.selectedIndex.index].products_pieces = id
            this.productOption[this.selectedIndex.index+'_'+this.selectedProduct.product.id] = null;
          }
          this.filterColumn()
        }
      },
      filterColumn(){
        debounce(()=>{
          this.table.clear();
          this.table.draw('full-hold');
        }, 300)()
      },
      clearFilters(){
        document.querySelector('[name="product_title"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showAlert(type, messagge){
        this.alertShow = true;
        this.alertType = type
        this.alertMessage = messagge
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
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
          : this.selectedProduct.product.dismantling.splice(index, 1);
        }, 300);
      },
      addDismantlingInput(type){
        let newItem = {
          id:'',
          piece_product_id: null,
          product_id: '',
          products_pieces: { title: '', id:'' },
          quantity: '',
        }
        const idButton = type == 2 
          ? 'new_product_form_button'
          : 'edit_product_form_button'

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')

        type == 2 
        ? this.newProduct.dismantling.push(newItem)
        : this.selectedProduct.product.dismantling.push(newItem);

        type == 2 
        ? this.getProducts('',(this.newProduct.dismantling.length -1))
        : this.getProducts('',(this.selectedProduct.product.dismantling.length-1)+'_'+this.selectedProduct.product.id)
        
        setTimeout(() => {
          this.addValidate(
            type == 2 
            ? 'new_product_form'
            : 'edit_product_form', 'new'
          )
        }, 500);
      },
      searchDismantling(e, index){ 
        debounce(this.getProducts, 200)(e.target.value, index)
      },
      clearSearchDismantling(index){
        this.getProducts('',index)
      },
      selectDismantling(e,index,type){
        if(e==-1){
          this.selectedIndex = {
            index:index, 
            type:type
          };
          this.newDismantling = true;
          setTimeout(() => {
            this.showInterModal('createDismantlingProduct')
          }, 500);
          return
        }
        const idButton = type == 2 
          ? 'new_product_form_button'
          : 'edit_product_form_button'

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')

        return type == 2 
        ? this.newProduct.dismantling[index].piece_product_id = e
        : this.selectedProduct.product.dismantling[index].piece_product_id = e
      },
      resetStockForm(){    
        try {
          this.inputDate['dueDateAddStock'].clear();
        } catch (error) {
          // console.log(error)
        }
        this.stockOperation = {
          type:1,
          quantity:'',
          reason:1,
          lot:'',
          due_date:''
          
        }
      },
      updatedProduct(){
        this.sendingButton('edit_product_form_button')
        let formData = new FormData();
        formData.append('title', this.selectedProduct.product.title);
        formData.append('description', this.selectedProduct.product.description);
        formData.append('short_description', this.selectedProduct.product.short_description);
        formData.append('type_unit', this.selectedProduct.product.type_of_unit);
        formData.append('img', this.$refs.editProductImg.files[0]);
        formData.append('is_dismantling', this.selectedProduct.product.is_dismantling ? 1 : 0);

        if (this.selectedProduct.product.is_dismantling) {
          formData.append('dismantling', JSON.stringify(this.selectedProduct.product.dismantling) );
        }

        this.$store
          .dispatch(UPDATE_PRODUCT, {id:this.selectedProduct.product.id, data:formData})
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Producto actualizado con exito')
            this.readyButton('edit_product_form_button')
          })
          .catch((err) => {
            console.log(err)
            this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('edit_product_form_button')
          })

      },
      updatedStockProduct(){
        this.sendingButton('add_stock_form_button')
        let formData = new FormData();
        formData.append('type', this.stockOperation.type);
        formData.append('quantity', this.stockOperation.quantity);
        formData.append('lote', this.stockOperation.lot);
        formData.append('due_date', this.$refs.due_date_dueDateAddStock.value );
        this.$store
          .dispatch(ADD_STOCK, {id:this.selectedProduct.product.id, data:formData})
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Stock actualizado con exito')
            this.resetStockForm()
            this.readyButton('add_stock_form_button')
          })
          .catch((err) => {
            console.log(err)
            this.showSnackbar('error', err )
            this.readyButton('add_stock_form_button')
          })
        
      },
      deleteProduct(){
        this.$store
          .dispatch(DELETE_LOTE_OF_PRODUCT, {id: this.selectedProduct.id_lote, deleteProduct: this.deleteProductToo} )
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('error', 'Producto Eliminado con exito')
            this.deleteProductToo = false;
            this.emitter.emit('updateList', false)
          })
          .catch((err) => {
            console.log(err)
            this.hideModal()
            this.showSnackbar('warning', err )
          })

        
      },
      validateFormItem(id){
        const fieldToValidate = this.itemsValidateByForm(id)
        this.forms[id] = formValidation(document.getElementById(id), {
          fields: fieldToValidate,
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
        setTimeout( ()=>this.formsActions(id), 500)
      },
      itemsValidateByForm(id){
        let fieldByForm = {}
        switch (id) {
          case 'edit_product_form':
            fieldByForm = {
              
              edit_product_title: {
                validators: {
                  notEmpty: {
                    message: "Debes subir una imagen"
                  },
                }
              },
              edit_product_title: {
                validators: {
                  notEmpty: {
                    message: "El campo de titulo es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&,@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              edit_product_description: {
                validators: {
                  notEmpty: {
                    message: "La descripción es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,º°ª\n:/z\-&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              edit_product_description_short: {
                validators: {
                  notEmpty: {
                    message: "La descripción corta es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'add_stock_form':
            fieldByForm = {
              stock_form_quatity: {
                validators: {
                  notEmpty: {
                    message: "La cantidad es obligatoria"
                  },
                  regexp: {
                    regexp: /^[0-9]+$/i,
                    message: 'Debe ser numerico',
                  },
                }
              },
              stock_add_lot:{
                validators: {
                  notEmpty: {
                    message: "El número de lote es obligatorio"
                  },
                }
              },
              stock_dis_lot:{
                validators: {
                  notEmpty: {
                    message: "El número de lote es obligatorio"
                  },
                }
              },
              stock_due_date:{
                validators: {
                  notEmpty: {
                    message: "La fecha de vencimiento es obligatorio"
                  }
                }
              }
            }
            break;
          default:
            break;
        }
        return fieldByForm
      },
      formsActions(id){
        const sendButton = document.getElementById(id+'_button')
       
        this.forms[id].on("core.form.valid", () => {
          switch (id) {
            case 'new_product_form':
              this.nextStep()
              
              break;
            case 'new_product_form_2':
              this.createdProduct()
              break;
            case 'edit_product_form':
              this.updatedProduct()
              break;
            case 'add_stock_form':
              this.updatedStockProduct()
              break;
            default:
              break;
          }

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
        try {
          this.forms['edit_product_form'].destroy()
        } catch (error) {
          
        }
        try {
          this.forms['add_stock_form'].destroy()
        } catch (error) {
          
        }
      },
      sendingButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
        
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      disabledButton(element, action){
        element.disabled = true;
        element.classList.add('v-btn--disabled')

        if(action == 'toggle' ){
          element.disabled = !element.disabled;
          element.classList.toggle('v-btn--disabled')
            return
        }
        element.disabled = false;
        element.classList.remove('v-btn--disabled')
      },
      validateSwitch(e){
        this.disabledButton( document.getElementById(e.target.closest('form').id+'_button'), 'toggle')
      },
      addValidate(id,type){
        let form = document.getElementById(id),
        quantityInput = type == 'new'
        ? form.querySelectorAll('input[name*="product_desmantling_quantity_"]')[ form.querySelectorAll('input[name*="product_desmantling_quantity_"]').length - 1]
        : form.querySelectorAll('input[name*="product_desmantling_quantity_"]'),
        fieldOptions={
            quantity: {
              validators: {
                notEmpty: {
                  message: "Agregar la cantidad de piezas "
                },
                numeric: {
                  message: "Debe ser númerico"
                }
              }
            }
          } 

        if(quantityInput && type == 'new'){
          this.forms[id].addField(quantityInput.name, fieldOptions.quantity)
          return
        }

        quantityInput.forEach((element) => {
          this.forms[id].addField(element.name, fieldOptions.quantity)

        });
      },
      removeValidate(id){
        let form = document.getElementById(id),
        quantityInput = form.querySelectorAll('input[name*="product_desmantling_quantity_"]')[ form.querySelectorAll('input[name*="product_desmantling_quantity_"]').length - 1]
        try {
          this.forms[id].removeField(quantityInput.name)
        } catch (error) {
        }
        
      },
      initDueDate(id){
        this.inputDate[id] = flatpickr(document.getElementById(id), {
          dateFormat: 'd/m/Y',
          minDate: "today",
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.querySelector('#date-input-val-'+id).value = moment(selectedDate[0]).format('YYYY-MM-DD')
          }
        });
      },
      clearDateAndLot(){
        if(this.stockOperation.type == 1) { this.getLastLoteNumber(); this.inputDate['dueDateAddStock'].clear() }
        if(this.stockOperation.type !== 1) this.inputDate['dueDateAddStock'].setDate(moment().format('DD-MM-YYYY'),true);

        this.stockOperation.lot  = ''
        this.stockOperation.due_date = this.stockOperation.type== 1  ? '': moment().format('DD-MM-YYYY')
        this.$refs.stock_due_date.value = moment().format('DD-MM-YYYY')

      },
      selectLote(e){
        const selectedLote = this.selectedProduct.product.lotes.filter((lot) => lot.id_lote === e)[0]
        this.stockOperation.lot_quantity = selectedLote.quantity ? selectedLote.quantity : this.stockOperation.lot_quantity ;
        this.stockOperation.lot = selectedLote.id ? selectedLote.id : this.stockOperation.lot;
        this.addValidateMax(this.stockOperation.lot_quantity)
      },
      formatLoteName(e){
        let isLongWord = !e
        ? this.selectedProduct.product.title.split(" ")
        : this.newProduct.title.split(" ")

        let index = !e
          ? this.stockOperation.lote_index + 1
          : 1;

        let loteTitle = isLongWord.length == 1
          ? isLongWord[0].substring(0,4).toUpperCase()
          : isLongWord.length == 3
          ? isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,1).toUpperCase() + isLongWord[2].substring(0,1).toUpperCase()
          : isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,2).toUpperCase();

        let loteNumber = '000000'.slice( 0, 6 - index.toString().length ) + index;
        if(!e){
          this.stockOperation.lot = loteTitle +'-'+ loteNumber;
          return
        }
        this.newProduct.init_lote =  loteTitle +'-'+ loteNumber;
        return
      },
      getLastLoteNumber(){
        this.$store
        .dispatch(GET_LAST_LOTE, this.selectedProduct.product.id )
        .then((data)=> {
          this.stockOperation.lote_index = data ; 
          this.formatLoteName(this.$event)
        })
          
      },
      TotalStockByLotes(product){
        let total = 0

        product.lotes.forEach((lote)=>{
          total += lote.quantity;
        })
        return total
      },
      addValidateMax(max){
        let form = document.getElementById('add_stock_form'),
        quantityInput = form.querySelector('input[name="stock_form_quatity"]'),
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
        this.forms['add_stock_form'].addField(quantityInput.name, fieldOptions.quantity)
        return
      },
      countDismantlingActive(dismantlings){
        let count = 0;
        dismantlings.forEach((data)=>{
          if(data.products_pieces) count ++
        })
        
        return count
      },
      updateAndHidden(){
        this.filterColumn()
        this.modal.hide()
        this.destroyFormVal();
      }
    },
    computed: {
      disabled () {
        return this.stepperNewProduct === 1 ? 'prev' : this.stepperNewProduct === this.steps ? 'next' : undefined
      },
    },
    mounted(){
      try {
        this.initOptionsTable()
        this.table = new DataTablesCore('#data-table', this.tableData)
        setTimeout(()=> this.bootstrapOptions(),2000)
      } catch (error) {
        
      }
      
    },
    created(){
      this.emitter.emit('displayOverlayLoad', false)
    }
  };
</script>
<style lang="scss">
  .dismantling_select .v-list-item {
      color:#cf6123   
  }
 .dismantling_select .v-list-item:nth-last-child(n+3) {
      color:#616161
  }
  .productStepper > .v-stepper.v-sheet {
    box-shadow: none!important;
  }
  table.dataTable > thead > tr > th.title-th{
    width: 40%!important;
  }
  table.dataTable > thead > tr > th:nth-child(n+2){
    width: 20%!important;
  }
  .v-messages__message{
    color: #cf6123;
    font-weight: 500;
    text-align: start;
    font-size: 13px!important;

  }
  .v-stepper.v-sheet{
    width:100%
  }
  .v-stepper-window{
    margin-right: 8px !important;
    margin-left: 8px !important;
  }
  .v-stepper-window-item{
    padding: 5px;
  }
  .dataTables_scrollBody{
    overflow: hidden!important;
  }
  .max-width-700{
    width: 100%!important;
  }
  @media screen and (max-width: 780px){
    .dataTables_scrollHeadInner{
      width: 650px!important;
    }
    .max-width-700{
      width:600px!important;
      margin-top: 30px!important;
    }
    table.dataTable > thead > tr > th.title-th, table.dataTable > tbody > tr > td.title-th{
      width: 36%!important;
    }
    table.dataTable > thead > tr > th:nth-child(n+2), table.dataTable > tbody > tr > td:nth-child(n+2){
        width: 27%!important;
    }
    table.dataTable > thead > tr > th.laste_item, table.dataTable > tbody > tr > td.laste_item{
      width: 10%!important;
    }

    .dataTables_scrollBody{
      overflow: auto!important;
    }
  }
</style>