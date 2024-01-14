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
  
  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  
  import { GET_PRODUCT_BY_ID, GET_PRODUCT_BY_SEARCH, STORE_PRODUCT, UPDATE_PRODUCT, ADD_STOCK, DELETE_PRODUCT, GET_LAST_LOTE} from "@/core/services/store/product.module";
  import { func } from '@/core/services/utils/utils.js'

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
          <VBtn @click=" showModal('createProduct')" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>

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
        <div class="card-datatable table-responsive">
          <table class="max-width-700 datatables-basic display table" id="data-table">
          </table>
        </div>
      </VCard>
    </VCol>
    <div v-if="Object.keys(selectedProduct).length > 2" >
      <div class="modal animate__animated animate__fadeInDown"  id="viewProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
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
                  <v-col  class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                  <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                    <VDivider :vertical="$vuetify.display.mdAndUp" />

                    <div class="w-100">
                      <VRow class="ma-0 pa-0">
                        <VCol cols="12" md="4" class="justify-center d-grid">
                          <div class="ma-auto mx-0 pa-5 pb-0">
                            <VImg
                              width="200"
                              height="200"
                              class="rounded"
                              :src="selectedProduct.img "
                            />
                          </div>
                        </VCol>
                        <VCol cols="12" md="8" class="mt-0 pt-0 mb-4">

                          <VCardItem class="px-1">
                            <VCardTitle>{{ selectedProduct.title }}</VCardTitle>
                          </VCardItem>
    
                          <VCardText class="px-1">
                            {{ selectedProduct.description}}
                          </VCardText>
                          <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">

                            <VCardText class="text-subtitle-1 py-4 px-1">
                              <span class="font-weight-medium">Número Lote:</span> <span class="font-weight-bold">
                                {{ 
                                    selectedProduct.lotes[0]
                                      ? selectedProduct.lotes[0].lote_code
                                      : '-----'
                                }}</span>
                            </VCardText>
                            <VCardText class="text-subtitle-1 pt-0 px-1 d-block">
                              <div class="font-weight-medium mb-0">Fecha de vencimiento proxima:</div>
                              <div class="font-weight-bold mt-2">
                                <v-chip :class=" Math.round(moment.duration(moment(selectedProduct.due_date).diff(new moment())).as('days') ) > 30 ? 'bg-success' : 'bg-warning'">
                                 {{
                                    selectedProduct.lotes[0]
                                      ? moment(selectedProduct.due_date).format('DD-MM-YYYY') 
                                      : '-----'
                                  }}
                                </v-chip>
                              </div>
                            </VCardText>

                            <VCardText class="text-subtitle-1 py-0 px-1">
                              <span class="font-weight-medium">Stock actual:</span> 
                              <span class="font-weight-bold" v-if="selectedProduct.stock > 0">
                                {{func.numberFormat(selectedProduct.stock)}} {{selectedProduct.type_of_unit }}
                              </span>
                              <span class="font-weight-bold text-error" v-else>
                                SIN STOCK
                              </span>
                            </VCardText>
                            
                          </div>
                        </VCol>
                      </VRow>
                      
                      <div style="border-top: 1px solid rgba(119, 119, 119, 0.356)" v-if="selectedProduct.is_dismantling && selectedProduct.dismantling.length > 0">
                        <VCardText class="text-subtitle-1 pb-4">
                          <span class="font-weight-medium">Cantidad de despices:</span> <span class="font-weight-bold">{{ selectedProduct.dismantling.length}}</span>
                        </VCardText>
                        <VCardText class="text-subtitle-1">
                          <p class="mb-0">Despieces:</p> 
                        <div class="d-block d-md-flex">
                          <b v-for="item in selectedProduct.dismantling" v-bind:key="item.id">
                            <p class="mb-0 ms-2 mt-3" >  
                              <b class="d-inline-flex d-md-none">*</b> {{ item.products_pieces.title}}: {{item.quantity}} {{ item.quantity > 1 ? 'Piezas' : 'Pieza' }} <b class="d-none d-md-inline-flex">||</b>
                            </p> 
                          </b>
                        </div>
                        </VCardText>
                      </div>

                    </div>
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown"  id="editProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
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
                  <div>
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
                                  :src="selectedProduct.img "
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
                              v-model="selectedProduct.title"
                              
                            />
                          </VCol>
                          <VCol cols="12" md="6" class="form-group">
                            <VTextField
                              placeholder="Descripción corta"
                              label="Descripción corta"
                              type="text"
                              name="edit_product_description_short"
                              v-model="selectedProduct.short_description"
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
                              v-model="selectedProduct.description"
                            ></v-textarea>
                          </VCol>
                          <VCol cols="6" md="4" class="form-group">
                            <VTextField
                              placeholder="Stock Actual"
                              label="Stock Actual"
                              type="text"
                              name="edit_product_stock"
                              v-model="selectedProduct.stock"
                              readonly
                            />
                          </VCol>
                          <VCol cols="6" md="4" class="form-group">
                            <v-combobox
                              label="Tipo de unidad"
                              :items="['Selecciona uno','KG', 'UNI', 'PZAS']"
                              variant="outlined"
                              v-model="selectedProduct.type_of_unit"
                            ></v-combobox>
                          </VCol>
                          <VCol cols="12" md="4" >
                            <v-switch
                              color="primary"
                              label="Tiene despieces" 
                              v-model="selectedProduct.is_dismantling" :value="1" 
                              @change="validateSwitch($event)"
                            />
                          </VCol>
                        </VRow>
                        <VRow class="ma-0 pa-0  mt-4 align-center" v-if="selectedProduct.is_dismantling">
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
                              <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                                <VRow  v-for="(item,index) in selectedProduct.dismantling"  v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'product_desmantling_'+index">
                                  <VCol cols="12"  md="6" class="form-group">
                                    <v-autocomplete
                                      :model-value="item.piece_product_id"
                                      :loading="loading"
                                      :items="productOption[index+'_'+selectedProduct.id] != undefined ?  productOption[index+'_'+selectedProduct.id] : item.piece_product_id !== null ?[ {id: item.piece_product_id, title: item.products_pieces.title}] : []"
                                      label="Nombre del producto"
                                      item-title="title"
                                      item-value="id"
                                      placeholder="Nombre del producto"
                                      variant="outlined"
                                      clearable
                                      no-filter
                                      no-data-text="No se encontraron resultados"
                                      :name="'product_desmantling_id_'+index"
                                      @keyup="searchDismantling($event, index+'_'+selectedProduct.id)"
                                      @click:clear="clearSearchDismantling(index+'_'+selectedProduct.id)"
                                      @update:modelValue="selectDismantling($event, index, 1)"
                                    ></v-autocomplete>
                                  </VCol>
                                  <VCol cols="8"  md="4" class="form-group">
                                    <VTextField
                                      placeholder="Unidades que trae"
                                      label="Unidades que trae"
                                      type="number"
                                      :name="'product_desmantling_quantity_'+index"
                                      v-model="item.quantity"
                                      
                                    />
                                  </VCol>
                                  <VCol cols="4" md="1" class="form-group pa-0">
                                    <v-tooltip text="Eliminar despiece">
                                      <template v-slot:activator="{ props }">
                                        <v-col cols="auto" class="">
                                          <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeDismantlingInput(1,productOption[index+'_'+selectedProduct.id])"></v-btn>
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
      <div class="modal animate__animated animate__fadeInDown"  id="addStockProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
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
                  <div>
                    <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title d-flex ">
                          <div class="form-title__part1">Agregar stock Producto</div>
                          
                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <VCardItem class="w-100  py-md-6  py-4 text-center">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title ">
                          <div class="form-title__part1 mx-4" style="text-wrap:wrap">
                              {{ selectedProduct.title }}
                          </div>
                          <div class="d-flex mt-4 justify-center w-100 flex-wrap">
                            <div class="form-title__part1 mx-4 d-flex align-center justify-center">
                              Stock actual:
                              <span class=" ms-2" >
                                <v-chip :class="selectedProduct.stock < 1 ? 'bg-error' : selectedProduct.stock >= 30 ? 'bg-success' : 'bg-warning'">
                                  {{ selectedProduct.stock }}
                                </v-chip>
  
                              </span>
                            </div>
                            <div class="form-title__part1 mx-4 mt-2 mt-md-0 d-flex align-center justify-center" >
                              Stock nuevo:
                              <span class=" ms-2" v-if="!isNaN(selectedProduct.newStock) ">
                                <v-chip :class="selectedProduct.newStock < 1 ? 'bg-error' : selectedProduct.newStock >= 30 ? 'bg-success' : 'bg-warning'">
                                  {{ selectedProduct.newStock }}
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
                    <VCardText class="w-100 pb-5 px-3 px-md-6">
                      <VForm  id="add_stock_form">
                        <VRow class="my-2">
                          <VCol cols="6" md="4" class="form-group px-2" v-show="stockOperation.type==1" >
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

                          <VCol cols="6" md="4" offset-md="4" class="form-group" v-show="stockOperation.type==1">

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
                          <VCol cols="6" md="4" offset-md="8" class="form-group" v-show="stockOperation.type==2">

                            <v-combobox  
                              :items="selectedProduct.lotes"
                              item-title="lote_code"
                              item-value="id"
                              placeholder="Número de lote"
                              label="Número de lote"
                              type="text"
                              name="stock_dis_lot"
                              class="text-end"
                              v-model="stockOperation.lot"
                              persistent-hint
                              :return-object="false"
                              :hint="'Stock en lote: '+stockOperation.lot_quantity"
                              @update:modelValue="selectLote($event)"
                              
                            ></v-combobox >
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
                        <h2>¿Seguro que deseas eliminar <b class="text-primary">{{selectedProduct.title}}</b>?</h2>
                      </VCol>
                    </VRow>
                      
                    <VDivider  />
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
    </div>
    <div class="modal animate__animated animate__fadeInDown"  id="createProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg mt-10" >
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
                <div>
                  <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1">Crear Nuevo Producto</div>
                        
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
                    <VRow >
                        
                        <v-stepper v-model="stepperNewProduct">
                          <template v-slot:default="">
                            <v-stepper-header class="">
                              <v-stepper-item
                                  :complete="stepperNewProduct > 1"
                                  step="Detalles del producto"
                                  :value="1"
                                  icon="fluent-mdl2:product"
                                  size="large"
                                >
                                  Producto
                                </v-stepper-item>

                                <v-stepper-item
                                  :complete="stepperNewProduct > 2"
                                  step="Detalles del producto"
                                  :value="2"
                                  icon="fluent-mdl2:work-item"
                                  size="large"
                                >
                                  Stock
                                </v-stepper-item>
                            </v-stepper-header>

                            <v-stepper-window class="mx-2" >
                              <v-stepper-window-item
                                :value="1"
                                
                              >
                                <template class="d-block" >
                                  
                                  <VForm  id="new_product_form">
                                  
                                    <VRow>
                                      <VCol cols="12"  class=" ">
                                        <div class="img-content mx-auto">
                                          <label for="newProduct-img">
                                            <VImg
                                              width="200"
                                              height="200"
                                              class="rounded"
                                              :src="newProduct.img"
                                              style="border-radius:10%!important"
                                              id="newProduct-img-content"
                                            />
                                            <div class="overlay-img">
                                              <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                                            </div>
                                          </label>
                                          <VCol cols="12" md="12"  class="form-group text-center ma-0 mt-0 pa-0">
              
                                            <input type="file"  id="newProduct-img" ref="newProductImg" name="new_product_img" class="d-none" @change="onFileChange" >
                                          </VCol>
                                        </div>
                                      </VCol>
                                      <VCol cols="12" md="6" class="form-group">
                                          <VTextField
                                            placeholder="Nombre del producto"
                                            label="Nombre del producto"
                                            type="text"
                                            name="new_product_title"
                                            autocomplete="off"
                                            v-model="newProduct.title"
                                            @change="formatLoteName($event)"
                                          />
                                      </VCol>
                                      <VCol cols="12" md="6" class="form-group">
                                        <VTextField
                                          placeholder="Descripción corta"
                                          label="Descripción corta"
                                          type="text"
                                          name="new_product_short_description"
                                          autocomplete="off"
                                          v-model="newProduct.short_description"
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
                                          name="new_product_description"
                                          v-model="newProduct.description"
                                        ></v-textarea>
                                      </VCol>
                                      <VCol cols="6" md="4" class="form-group">
                                        <v-select
                                            label="Tipo de unidad"
                                            :items="['KG', 'UNI', 'PZAS']"
                                            variant="outlined"
                                            v-model="newProduct.unit"
                                        ></v-select>
                                      </VCol>
                                      <VCol cols="12" md="4" class="px-5">
                                        <v-switch
                                          color="primary"
                                          label="Tiene despieces" 
                                          :value="1" 
                                          v-model="newProduct.isDismantling"
                                          @change="validateSwitch($event)"
                                        />
                                      </VCol>
                                    </VRow>
                                    <VRow 
                                      class="ma-0 pa-0  mt-4 align-center" 
                                      v-if="newProduct.isDismantling"
                                      >
                                          <VCol cols="12" class="form-group">
                                            <h3>Despieces:</h3>
                                          </VCol>
                                          <VCol cols="12" md="4" class="mt-0 py-0 px-0">
                                            <v-tooltip text="Agregar nuevo despiece">
                                                <template v-slot:activator="{ props }">
                                                  <v-col cols="auto" class="">
                                                    <VBtn v-bind="props" color="primary" class="w-100"  @click="addDismantlingInput(2)"><VIcon icon="bx-plus"/> Agregar despiece</VBtn>
                                                  </v-col>
                                                </template>
                                              </v-tooltip>
                                          </VCol>
                                          <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                                            <VRow  v-for="(item,index) in newProduct.dismantling"  v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'new_product_desmantling_'+index">
                                              <VCol cols="12"  md="6" class="form-group">
                                                <v-autocomplete
                                                  :model-value="item.piece_product_id"
                                                  :items="productOption[index] ?  productOption[index] :item.piece_product_id !== null ? [ {id: item.piece_product_id, title: item.products_pieces.title}] : []"
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
                                                  placeholder="Unidades que trae"
                                                  label="Unidades que trae"
                                                  type="number"
                                                  :name="'product_desmantling_quantity_'+index"
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
                                          <VBtn  color="primary" class="w-100" type="submit"  disabled id="new_product_form_button" > Siguente</VBtn>
                                        </v-col>
                                      </VCol>
                                    </VRow>
                                  </VForm>
                                  
                                </template>
                              </v-stepper-window-item>
                              <v-stepper-window-item
                                :value="2"
                              >
                                <template class="d-block">
                                  <VForm  id="new_product_form_2">
                                    <VRow>
                                      <VCol cols="6" md="4" class="form-group">
                                        <VTextField
                                          placeholder="Numero de lote"
                                          label="Numero de lote"
                                          type="text"
                                          name="new_product_init_lot"
                                          autocomplete="off"
                                          variant="underlined"
                                          v-model="newProduct.init_lote"
                                          disabled
                                        />
                                      </VCol>
                                      <VCol cols="6" md="4" class="form-group">
                                        <VTextField
                                          placeholder="Stock"
                                          label="Stock"
                                          type="number"
                                          name="new_product_stock"
                                          autocomplete="off"
                                          v-model="newProduct.stock"
                                        />
                                      </VCol>
                                      
                                      <VCol cols="12" md="4" class="form-group">
                                        <VTextField
                                          id="dueDateNewProduct"
                                          placeholder="Fecha de vencimiento"
                                          label="Fecha de vencimiento"
                                          type="text"
                                          name="new_product_due_date"
                                        />
                                        <input type="hidden" id="date-input-val-dueDateNewProduct" ref="due_date_dueDateNewProduct" >
                                      </VCol>
                                    </VRow>
                                    <VRow class="ma-0 pa-0  mt-8 align-center">
                                      <VCol cols="5" md="4"  class="mt-0 py-0 px-0">
                                        <v-col cols="auto" class="">
                                          <VBtn  color="secondary" class="w-100" @click="stepperNewProduct = 1" >
                                            <span class="d-block d-md-hidden ">
                                              <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
                                            </span>
                                            <span class="d-md-block d-none ">Volver</span>
  
                                            
                                          </VBtn>
                                        </v-col>
                                      </VCol>
                                      <VCol cols="5" md="4" offset="2" offset-md="4" class="mt-0 py-0 px-0">
                                        <v-col cols="auto" class="">
                                          <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_product_form_2_button"> Guardar</VBtn>
                                        </v-col>
                                      </VCol>
                                    </VRow>
                                  </VForm>
                                </template>
                              </v-stepper-window-item>
                            </v-stepper-window>

                            
                          </template>
                        </v-stepper>
                        
                    </VRow>
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
<style lang="scss">
.card-datatable > thead > tr > th.title-th{
  width: 35%!important;
}
.card-datatable > thead > tr > th:nth-child(n+2){
  width: 15%!important;
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
    width: 500px!important;
  }
  .max-width-700{
    width: 500px!important;
    margin-top: 30px!important;
  }
  .card-datatable > thead > tr > th.title-th{
    width: 25%!important;
  }
  .card-datatable > thead > tr > th:nth-child(n+2) {
      width: 25%!important;
  }
  .dataTables_scrollBody{
    overflow: auto!important;
  }
}
</style>


<script>
  export default {
    data: () => ({
      modal: '',
      product:'',
      productOption:[],
      search:'',
      loading: false,
      alertShow:false,
      alertMessage:'',
      alertType:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:5000,
      selectedProduct:{},
      forms:[],
      table:'',
      stepperNewProduct: 1,
      steps: 2,
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
      inputDate:[],
      stockOperation:{
        type:1,
        quantity:'',
        lot:'',
        lot_quantity: '',
        due_date:''
      },
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-products",
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
            title: '<div class="d-none d-md-block">Nombre del producto</div><div class="d-md-none d-block">Producto</div>', class:'text-start title-th',
            render: ( data, type, row, meta ) =>{ 
                return `${row.title}`
              }   
          },
          {
            title:' <div class="d-none d-md-block">Proxima fecha venc.</div><div class="d-md-none d-block">Fecha venc</div>',
            class:'text-center ',
            render: (data, type, row, meta) =>{
              let today = new moment();
              let product_due_date = moment(row.due_date_most_evenly);
              let diff_due_date = Math.round(moment.duration(product_due_date.diff(today)).as('days'));
              return `
                <span 
                  class="view_lotes v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${ diff_due_date < 1 ? 'bg-error' : diff_due_date >= 30 ? 'bg-success' : 'bg-warning'}" 
                  draggable="false"
                  >
                    <span class="v-chip__underlay"></span>
                  <div class="v-chip__content">${moment(row.due_date_most_evenly).format('DD-MM-YYYY')} </div>
                </span> 
              `
            }
          },
          { 
            title: 'Stock total',
            class:'text-center ',
            render: ( data, type, row, meta ) =>{ 
              return `
              <span 
                class="v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${row.stock < 1 ? 'bg-error' : row.stock >= 30 ? 'bg-success' : 'bg-warning'}" 
                draggable="false"
                >
                  <span class="v-chip__underlay"></span>
                <div class="v-chip__content">${$number_format(row.stock)} ${row.type_of_unit}</div>
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
                
                <span data-bs-toggle="tooltip" data-id="${row.id}" class="view" data-bs-placement="top" data-bs-title="Ver ficha de producto">
                  <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-id="${row.id}" class="edit" data-bs-placement="top" data-bs-title="Editar producto">
                  <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}"  fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-id="${row.id}" class="stock" data-bs-placement="top" data-bs-title="Agregar stock">
                  <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}"  fill="currentColor" d="M13 19.3v-6.7l6-3.4V13c.7 0 1.4.1 2 .4V7.5c0-.4-.2-.7-.5-.9l-7.9-4.4c-.2-.1-.4-.2-.6-.2s-.4.1-.6.2L3.5 6.6c-.3.2-.5.5-.5.9v9c0 .4.2.7.5.9l7.9 4.4c.2.1.4.2.6.2s.4-.1.6-.2l.9-.5c-.3-.6-.4-1.3-.5-2M12 4.2l6 3.3l-2 1.1l-5.9-3.4l1.9-1m-1 15.1l-6-3.4V9.2l6 3.4v6.7m1-8.5L6 7.5l2-1.2l6 3.5l-2 1m8 4.2v3h3v2h-3v3h-2v-3h-3v-2h3v-3h2Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-id="${row.id}" class="delete" data-bs-placement="top" data-bs-title="Eliminar producto">
                  <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}"  fill="currentColor" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path data-id="${row.id}" fill="currentColor" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
                </span>
              </div>
              <div class="d-md-none d-flex justify-center position-relative relative ">
                <div class="dropdown dropup ">
                  <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                  </button>
                  <div class="dropdown-menu animate__animated animate__rubberBand large">
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="view me-5" data-bs-placement="top" data-bs-title="Ver ficha de producto">
                      <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="edit me-5" data-bs-placement="top" data-bs-title="Editar producto">
                      <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}"  fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="stock" data-bs-placement="top" data-bs-title="Agregar stock">
                      <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}"  fill="currentColor" d="M13 19.3v-6.7l6-3.4V13c.7 0 1.4.1 2 .4V7.5c0-.4-.2-.7-.5-.9l-7.9-4.4c-.2-.1-.4-.2-.6-.2s-.4.1-.6.2L3.5 6.6c-.3.2-.5.5-.5.9v9c0 .4.2.7.5.9l7.9 4.4c.2.1.4.2.6.2s.4-.1.6-.2l.9-.5c-.3-.6-.4-1.3-.5-2M12 4.2l6 3.3l-2 1.1l-5.9-3.4l1.9-1m-1 15.1l-6-3.4V9.2l6 3.4v6.7m1-8.5L6 7.5l2-1.2l6 3.5l-2 1m8 4.2v3h3v2h-3v3h-2v-3h-3v-2h3v-3h2Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="delete" data-bs-placement="top" data-bs-title="Eliminar producto">
                      <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}"  fill="currentColor" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path data-id="${row.id}" fill="currentColor" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
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
      items:[],
    }),
    methods:{
      stockCalculate(){
        this.selectedProduct.newStock =  this.stockOperation.type == 1
          ? parseInt(this.selectedProduct.stock)  + parseInt(this.stockOperation.quantity)
          : parseInt(this.selectedProduct.stock)  - parseInt(this.stockOperation.quantity)
      
      },
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('viewProduct')
              }, 800);
            })
          })	
        })
        document.querySelectorAll('.edit').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('editProduct')
              }, 800);
            })
          })	
        })
        document.querySelectorAll('.stock').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('addStockProduct')
                this.getLastLoteNumber();
                this.initDueDate('dueDateAddStock')
              }, 800);
            })
          })	
        })
        document.querySelectorAll('.delete').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('deleteProduct')
              }, 800);
            })
          })	
        })
      },
      async selectProduct(idAccount){
        this.$store
          .dispatch(GET_PRODUCT_BY_ID, idAccount)
          .then((response) => {
            this.selectedProduct = Object.assign({}, response.data);
            setTimeout(() => {
              this.validateFormItem('add_stock_form')
              this.validateFormItem('edit_product_form')
              this.addValidate('edit_product_form', 'update')
              return new Promise((resolve) => {
                  resolve(response.data);
              });
            }, 200);
          })
          .catch((err) => {
            console.log(err)
            return new Promise((resolve) => {
              resolve(false);
            });
          });
      },
      
      getProducts(search = "", index){

        const loge = {
          items:this.productOption[index],
          index: index,
          search: search
        }



        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            this.productOption[index] = response.data
            const loge = {
              items:this.productOption[index],
              items_count:this.productOption[index].length,
              index: index,
              search: search
            }
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
       : this.selectedProduct.img = URL.createObjectURL(file)
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
          : this.selectedProduct.dismantling.splice(index, 1);
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

        setTimeout(() => {
          
          this.addValidate(type == 2 
            ? 'new_product_form'
            : 'edit_product_form', 'new')

        }, 500);
        return type == 2 
        ? this.newProduct.dismantling.push(newItem)
        : this.selectedProduct.dismantling.push(newItem);
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
        // console.log(this.selectedProduct.dismantling)
        return type == 2 
        ? this.newProduct.dismantling[index].piece_product_id = e
        : this.selectedProduct.dismantling[index].piece_product_id = e
      },
      hideModal(){
        this.modal.hide()
        this.destroyFormVal();
        this.resetNewProductForm()
        this.resetStockForm();
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
      resetNewProductForm(){
        
        this.newProduct = {
          img:'images/product/default.png',
          title:'',
          short_description:'',
          stock:'',
          unit:'KG',
          isDismantling: 0 ,
          dismantling:[],
          init_due_date:'',
          init_lote:''
        }
        
        this.$refs.newProductImg.value =''
        this.stepperNewProduct = 1
        try {
          this.inputDate['dueDateNewProduct'].clear();
        } catch (error) {
          // console.log(error)
        }
      },
      nextStep(){
        this.stepperNewProduct = 2;
        setTimeout(() => {
          this.validateFormItem('new_product_form_2')

          this.initDueDate('dueDateNewProduct')
        }, 500);
      },
      createdProduct(){
        this.sendingButton('new_product_form_2_button')
        let formData = new FormData();
        formData.append('title', this.newProduct.title);
        formData.append('description', this.newProduct.description);
        formData.append('short_description', this.newProduct.short_description);
        formData.append('initial_stock', this.newProduct.stock);
        formData.append('type_unit', this.newProduct.unit);
        formData.append('img', this.$refs.newProductImg.files[0]);
        formData.append('is_dismantling', this.newProduct.isDismantling ? 1 : 0);
        formData.append('due_date', this.$refs.due_date_dueDateNewProduct.value);
        formData.append('initial_lote', this.newProduct.init_lote);

        

        if (this.newProduct.isDismantling) {
          formData.append('dismantling', JSON.stringify(this.newProduct.dismantling) );
        }

        this.$store
          .dispatch(STORE_PRODUCT, formData)
          .then((response) => {
            this.filterColumn()
            this.showSnackbar('success', 'Producto creado con exito')
            this.readyButton('new_product_form_2_button')
            this.hideModal()
          })
          .catch((err) => {
            console.log(err)
            this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('new_product_form_2_button')
          })
        
      },
      updatedProduct(){

        this.sendingButton('edit_product_form_button')
        let formData = new FormData();
        formData.append('title', this.selectedProduct.title);
        formData.append('description', this.selectedProduct.description);
        formData.append('short_description', this.selectedProduct.short_description);
        formData.append('type_unit', this.selectedProduct.type_of_unit);
        formData.append('img', this.$refs.editProductImg.files[0]);
        formData.append('is_dismantling', this.selectedProduct.is_dismantling ? 1 : 0);

        

        if (this.selectedProduct.is_dismantling) {
          formData.append('dismantling', JSON.stringify(this.selectedProduct.dismantling) );
        }

        this.$store
          .dispatch(UPDATE_PRODUCT, {id:this.selectedProduct.id, data:formData})
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
          .dispatch(ADD_STOCK, {id:this.selectedProduct.id, data:formData})
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Stock actualizado con exito')
            this.resetStockForm()
            this.readyButton('add_stock_form_button')
          })
          .catch((err) => {
            console.log(err)
            // this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('add_stock_form_button')
          })
        
      },
      deleteProduct(){
        this.$store
          .dispatch(DELETE_PRODUCT, this.selectedProduct.id )
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('error', 'Producto Eliminado con exito')
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
          case 'new_product_form':
            fieldByForm = {
              new_product_img: {
                validators: {
                  notEmpty: {
                    message: "Debes subir una foto"
                  },
                }
              },
              new_product_title: {
                validators: {
                  notEmpty: {
                    message: "El campo de titulo es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_product_description: {
                validators: {
                  notEmpty: {
                    message: "La descripción es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_product_short_description: {
                validators: {
                  notEmpty: {
                    message: "La descripción corta es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'new_product_form_2':
            fieldByForm = {
              new_product_init_lot:{
                validators: {
                  notEmpty: {
                    message: "El lote inicial es requerido"
                  }
                }
              },
              new_product_stock: {
                validators: {
                  notEmpty: {
                    message: "Debes agregar un stock Inicial"
                  },
                  regexp: {
                    regexp: /^[0-9]+$/i,
                    message: "Debe ser númerico",
                  },
                }
              },
              new_product_due_date:{
                validators: {
                  notEmpty: {
                    message: "La fecha de vencimiento es requerida"
                  }
                }
              },
            }
            break;
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
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@$,_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              edit_product_short_description: {
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
        try{
          this.forms['new_product_form_2'].destroy()

        }catch(error){

        }
        
        this.forms['new_product_form'].resetForm()
          
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
        // this.forms[id] 
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
        
        // console.log(newfield)
      },
      removeValidate(id){
        let form = document.getElementById(id),
        quantityInput = form.querySelectorAll('input[name*="product_desmantling_quantity_"]')[ form.querySelectorAll('input[name*="product_desmantling_quantity_"]').length - 1]
        try {
          this.forms[id].removeField(quantityInput.name)
        } catch (error) {
          // console.log('no hay validación activa')
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
        const selectedLote = this.selectedProduct.lotes.filter((lot) => lot.id === e)[0]
        this.stockOperation.lot_quantity = selectedLote.quantity ? selectedLote.quantity : this.stockOperation.lot_quantity ;
        this.stockOperation.lot = selectedLote.id ? selectedLote.id : this.stockOperation.lot;

      },
      formatLoteName(e){
        let isLongWord = !e
        ? this.selectedProduct.title.split(" ")
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
          // console.log(this.stockOperation.lot)
          return

        }
        this.newProduct.init_lote =  loteTitle +'-'+ loteNumber;
        // console.log(this.newProduct.init_lote)
        return
        
      },
      getLastLoteNumber(){
        this.$store
        .dispatch(GET_LAST_LOTE, this.selectedProduct.id )
        .then((data)=> {
          this.stockOperation.lote_index = data ; 
          this.formatLoteName(this.$event)
        })
          
      },
    },
    computed: {
      disabled () {
        return this.stepperNewProduct === 1 ? 'prev' : this.stepperNewProduct === this.steps ? 'next' : undefined
      },
    },
    mounted(){
      this.initOptionsTable()
      this.table = new DataTablesCore('#data-table', this.tableData)
      this.bootstrapOptions();
      this.validateFormItem('new_product_form')

    },
    created(){
      this.emitter.emit('displayOverlayLoad', false)
    }
  };
</script>