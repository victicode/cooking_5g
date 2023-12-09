<script setup >
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import Button from 'datatables.net-buttons';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import ButtonPrint from 'datatables.net-buttons/js/buttons.print';
  import ButtonHTML5 from 'datatables.net-buttons/js/buttons.html5';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import * as bootstrap from 'bootstrap'
  import { GET_PRODUCT_BY_ID, GET_PRODUCTS, GET_PRODUCT_BY_SEARCH } from "@/core/services/store/product.module";
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import { func } from '@/core/services/utils/utils.js'
  DataTable.use(DataTablesCore);
  DataTable.use(Button);
  DataTable.use(ButtonHTML5);
  DataTable.use(ButtonPrint); 
  
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
            <v-dialog width="500">
              <template v-slot:activator="{ props }">
                <VBtn v-bind="props" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>
              </template>
            
              <template v-slot:default="{ isActive }">
                <v-card title="Dialog">
                  <v-card-text>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  </v-card-text>
            
                  <v-card-actions>
                    <v-spacer></v-spacer>
            
                    <v-btn
                      text="Close Dialog"
                      @click="isActive.value = false"
                    ></v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
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
          <table class="datatables-basic table" id="data-table">
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
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                  <v-col  class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="modal.hide()" ></v-btn>
                  </v-col>
                </div>
                  <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                    <div class="ma-auto mx-0 pa-5">
                      <VImg
                        width="200"
                        height="200"
                        class="rounded"
                        :src="'images/'+ selectedProduct.img "
                      />
                    </div>

                    <VDivider :vertical="$vuetify.display.mdAndUp" />

                    <div class="w-100">
                      <VCardItem>
                        <VCardTitle>{{ selectedProduct.title }}</VCardTitle>
                      </VCardItem>

                      <VCardText>
                        {{ selectedProduct.description}}
                      </VCardText>
                      <div style="border-top: 1px solid rgba(119, 119, 119, 0.356)">

                        <VCardText class="text-subtitle-1">
                          <span>Stock actual:</span> <span class="font-weight-medium">{{func.numberFormat(selectedProduct.stock)}} {{selectedProduct.type_of_unit }}</span>
                        </VCardText>
                        <VCardText class="text-subtitle-1">
                          <span>Cantidad de despices:</span> <span class="font-weight-medium">{{ selectedProduct.dismantling.length}}</span>
                        </VCardText>
                      </div>
                      <div style="border-top: 1px solid rgba(119, 119, 119, 0.356)" v-if="selectedProduct.is_dismantling">

                        <VCardText class="text-subtitle-1">
                          <p class="mb-0">Despieces:</p> 
                          <b v-for="item in selectedProduct.dismantling" v-bind:key="item.id">
                            <p class="mb-0 ms-2 mt-3" >  
                              * {{ item.products_pieces.title}}: {{item.quantity}}
                            </p> 
                          </b>

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
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="modal.hide()" ></v-btn>
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
                    <VCardText class="w-100">
                      <VForm @submit.prevent="$router.push('/')" id="kt_login_signin_form">
                        <VRow>
                          <!-- email -->
                          <VCol cols="12" md="6" class="form-group">
                            <VTextField
                              placeholder="Nombre del producto"
                              label="Nombre del producto"
                              type="text"
                              name="product_title"
                              v-model="selectedProduct.title"
                              
                            />
                          </VCol>
                          <VCol cols="12" md="6" class="form-group">
                            <VTextField
                              placeholder="Descripción corta"
                              label="Descripción corta"
                              type="text"
                              name="product_description_short"
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
                              v-model="selectedProduct.description"
                            ></v-textarea>
                          </VCol>
                          <VCol cols="6" md="4" class="form-group">
                            <VTextField
                              placeholder="Stock Actual"
                              label="Stock Actual"
                              type="text"
                              name="product_stock"
                              v-model="selectedProduct.stock"
                              readonly
                            />
                          </VCol>
                          <VCol cols="6" md="4" class="form-group">
                            <v-combobox
                              label="Tipo de unidad"
                              :items="['Selecion una opción','KG', 'UNI', 'PZAS']"
                              variant="outlined"
                              v-model="selectedProduct.type_of_unit"
                            ></v-combobox>
                          </VCol>
                          <VCol cols="12" md="4">
                            <v-switch
                              color="primary"
                              label="Tiene despieces" 
                              v-model="selectedProduct.is_dismantling" :value="1" 
                            />
                          </VCol>
                          <VRow class="ma-0 pa-0 align-center" v-if="selectedProduct.is_dismantling">
                            <VCol cols="12" class="form-group">
                              <h3>Despieces:</h3>
                            </VCol>
                            <VCol cols="12" md="4" class="mt-0 pt-0 px-0">
                              <v-tooltip text="Agregar nuevo despiece">
                                  <template v-slot:activator="{ props }">
                                    <v-col cols="auto" class="">
                                      <VBtn v-bind="props" color="primary" class="w-100 " @click="addDismantling()"><VIcon icon="bx-plus"/> Agregar despiece</VBtn>
                                    </v-col>
                                  </template>
                                </v-tooltip>
                            </VCol>
                            <div id="desmantling_items" class="pa-0 ma-0 align-center w-100" >
                              <v-autocomplete
                                :model-value="selectedProduct.dismantling[0].title"
                                :loading="loading"
                                :items="productOption"
                                :search="search"
                                chips
                                active
                                label="Nombre del producto"
                                hide-no-data
                                hide-selected
                                no-filter
                                item-title="title"
                                item-value="id"
                                placeholder="Nombre del producto"
                                variant="outlined"
                                return-object
                              ></v-autocomplete>
                              <VRow  v-for="(item,index) in selectedProduct.dismantling"  v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5"  :id="'product_desmantling_'+index">
                                <VCol cols="12"  md="6" class="form-group">
                                  <VTextField
                                    placeholder="Nombre del producto "
                                    label="Nombre del producto"
                                    type="text"
                                    :name="'product_desmantling_title_'+index"
                                    v-model="item.products_pieces.title"
                                    readonly
                                  />
                                </VCol>
                                <VCol cols="8"  md="4" class="form-group">
                                  <VTextField
                                    placeholder="Unidades que trae"
                                    label="Unidades que trae"
                                    type="text"
                                    :name="'product_desmantling_quantity_'+index"
                                    v-model="item.quantity"
                                    readonly
                                  />
                                </VCol>
                                <VCol cols="4" md="1" class="form-group pa-0">
                                  <v-tooltip text="Eliminar despiece">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="">
                                        <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeDismantling(index)"></v-btn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                                </VCol> 
                              </VRow>
                            </div>
                          </VRow>
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
        <div class="modal-dialog modal-xl mt-10" >
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
              <VCard class="modal__content">
                <div class="modal__close-button" >
                  <v-col class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="modal.hide()" ></v-btn>
                  </v-col>
                </div>
                <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                  <div class="ma-auto mx-0 pa-5">
                    <VImg
                      width="200"
                      height="200"
                      class="rounded"
                      :src="'images/'+ selectedProduct.img "
                    />
                  </div>

                  <VDivider :vertical="$vuetify.display.mdAndUp" />

                  <div class="w-100">
                    <VCardItem>
                      <VCardTitle>{{ selectedProduct.title }}</VCardTitle>
                    </VCardItem>

                    <VCardText>
                      {{ selectedProduct.description}}
                    </VCardText>

                    <VCardText class="text-subtitle-1">
                      <span>Stock:</span> <span class="font-weight-medium">{{func.numberFormat(selectedProduct.stock)}} {{selectedProduct.type_of_unit }}</span>
                    </VCardText>

                    <VCardActions class="justify-space-between">
                      <VBtn
                        color="secondary"
                        icon="bx-share-alt"
                      />
                    </VCardActions>
                  </div>
                </div>
              </VCard>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="deleteProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10">
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
                    <VCardActions class=" justify-center w-100 d-md-flex  d-block">
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
      </div>
      <div class="modal animate__animated animate__fadeInDown"  id="createProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl mt-10" >
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
            
              <VCol
                sm="8"
                cols="12"
              >
                <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="modal.hide()" ></v-btn>
                    </v-col>
                  </div>
                  <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row" style="background: transparent;">
                    <div class="ma-auto mx-0 pa-5">
                      <VImg
                        width="200"
                        height="200"
                        class="rounded"
                        :src="'images/'+ selectedProduct.img "
                      />
                    </div>

                    <VDivider :vertical="$vuetify.display.mdAndUp" />

                    <div class="w-100">
                      <VCardItem>
                        <VCardTitle>{{ selectedProduct.title }}</VCardTitle>
                      </VCardItem>

                      <VCardText>
                        {{ selectedProduct.description}}
                      </VCardText>

                      <VCardText class="text-subtitle-1">
                        <span>Stock:</span> <span class="font-weight-medium">{{func.numberFormat(selectedProduct.stock)}} {{selectedProduct.type_of_unit }}</span>
                      </VCardText>

                      <VCardActions class="justify-space-between">
                        <VBtn
                          color="secondary"
                          icon="bx-share-alt"
                        />
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
  </VRow>
</template>
<style lang="scss">
  input,textarea, select{
    font-weight: 600;
  }
  .v-switch__track{
    box-shadow: 0px 0px 17px 2px #727272;
  }
  thead > tr > th.title-th{
    width: 70%!important;
  }
  thead > tr > th:nth-child(n+2){
    width: 15%!important;
  }
  .modal__close-button{
    position: absolute; right: -2%; top:0%
  }
  .modal__content{
    position: initial!important; overflow: visible!important;
  }
  @media screen and (max-width: 780px){
    .modal__close-button{
      position: absolute; right: -5%; top: 0%
    }
    div.dropdown-menu.large{
      width:max-content;
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
      inputDate: '',
      selectedProduct:{},
      table:'',
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-products",
          "type": "POST",
          data: function ( data ) {
            data.filter_product_title = document.querySelector('[name="product_title"]').value;
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
          { title: 'Nombre del producto', class:'text-start title-th',
          render: ( data, type, row, meta ) =>{ 
              return row.title
            }   
          },
          { 
            title: 'Stock',
            class:'text-center',
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
            title: 'Acciones',
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
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="stock me-5" data-bs-placement="top" data-bs-title="Agregar stock">
                      <svg  xmlns="http://www.w3.org/2000/svg" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24">
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
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('viewProduct')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.edit').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('editProduct')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.stock').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('addStockProduct')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.delete').forEach(item => {
          item.addEventListener('click', event => {
            this.selectProduct(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('deleteProduct')
              }, 500);
            })
          })	
        })
      },
      async selectProduct(idAccount){
        this.$store
          .dispatch(GET_PRODUCT_BY_ID, idAccount)
          .then((response) => {
            this.selectedProduct = Object.assign({}, response.data);
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
      getProducts(search = "", self){
        if (!search) {
          self.productOption = [];
          self.product = '';
        }
        // Items have already been requested
        if (self.loading) {
          return
        }
        self.loading = true
        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            console.log(response)
            self.productOption = response.data
          })
          .catch((err) => {
            console.log(err)
            return new Promise((resolve) => {
              resolve(false);
            });
          })
          .finally(() => (self.loading = false))
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
        this.table.clear();
        this.table.draw('full-hold');
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
      removeDismantling(item){
        document.getElementById('product_desmantling_'+item).blur()
        setTimeout(() => {
          
          document.getElementById('product_desmantling_'+item).remove()
        }, 200);
      },
      addDismantling(){
        let newItem = {
          id:'',
          piece_product_id: '',
          product_id: '',
          products_pieces: { title: '' },
          quantity: '',
        }
        this.selectedProduct.dismantling.push(newItem)
      }

    },
    watch: {
      search(newQuestion){
      cosnole.log(newQuestion)
    }
    },
    mounted(){
      this.initOptionsTable()
      this.table = new DataTablesCore('#data-table', this.tableData)
      this.bootstrapOptions();
      this.getProducts(' ', this);
      this.$store
          .dispatch(GET_PRODUCTS)
          .then((response) => {
            console.log(response.data )
          })
          .catch((err) => {
            console.log(err)
          });
    },
    created(){
      this.emitter.emit('displayOverlayLoad', false)
    }
  };
</script>