<script setup>
import moment from 'moment';
import CreateOutOrderProductsTables from '@/views/pages/tables/CreateOutOrderProductsTables.vue';
const props = defineProps({
  order: Object,
})
import * as bootstrap from 'bootstrap';
import filter from '../../../assets/plugins/formvalidation/src/js/core/filter';

</script>
<template>
  <div class="modal animate__animated animate__fadeInDown" id="createOutOrder" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
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
                  <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="actionModal('close')" ></v-btn>
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
                      <h2>Crear orden de salida</h2>
                      <h3 class="mt-2">
                        <v-chip :class="{'bg-error': order.status == 0, 'bg-warning': order.status == 1, 'bg-secondary': order.status == 2, 'bg-success': order.status == 3, }">
                          {{ order.status_label.status }}
                        </v-chip>

                      </h3>
                    </div>
                    <div class="my-3 my-md-0  d-block d-md-none text-center text-md-end ">
                      <h4 class="font-400">
                        Orden N°: 
                        <b>
                          {{ orderNumberFormat(order.id) }}
                        </b> 
                      </h4>
                    </div>
                    <div >
                      <div class="my-2  text-start">
                        Solicitante: <a class="text-secondary fw-600">{{ order.client.name.toUpperCase() }}</a>
                      </div>
                      <div class="my-2 text-start">
                        Dirección de destino: <a class="text-secondary fw-600">{{ order.other_address }}</a>
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
                          {{ orderNumberFormat(order.id) }}
                        </b> 
                      </h4>
                    </div>
                    <div >
                      <div class="my-2  text-start text-md-end">
                        Fecha de pedio: <a class="text-secondary fw-600">{{ moment(order.created_at).format('DD/MM/YYYY HH:mm:ss') }}</a>
                      </div>
                      <div class="my-2  text-start text-md-end" @click="showModal('createOutOrderx')">
                        Tracker ID: <a class="text-secondary fw-600">{{ order.trancker }}</a>
                      </div>
                    </div>
                  </VCol>
                </VRow>
                <div class="w-100">
                  <CreateOutOrderProductsTables :products="order.products" @selectProduct="selectProduct" />
                </div>
                <VDivider  />
                <div class="mt-5 w-100 d-flex  justify-center">
                  <VCardActions class=" justify-center w-75">
                    <VBtn
                      color="white"
                      class="bg-secondary text-white w-50"
                      @click="actionModal('close')"
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
  <div v-if="Object.keys(selectedProduct).length > 2">
    <div class="modal animate__animated animate__fadeInDown" id="selectedProductLote" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
      <div class="modal-dialog  mt-10" >
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
                  <div class="internalModalOrder">
                    <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title text-center">
                          <div class="form-title__part1"><h4>Asignar producto a la orden:</h4></div>
                          <h2 class="my-2">{{ selectedProduct.title }}</h2>
                          <h5 class="my-2">Cantidad solicitada: {{ selectedProduct.pivot.quantity }} {{ selectedProduct.type_of_unit}}</h5>
                          
                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <!-- <VCardText class="mb-5  w-100 pa-0" v-if="alertShow">
                      <v-alert
                        :color="alertType"
                        :text="alertMessage"
                      ></v-alert>
                    </VCardText> -->
                    <VCardText class="w-100 pb-5 px-3 px-md-6">
                      <VForm  id="new_order_form">
                        <VRow 
                          class="ma-0 pa-0  mt-1 align-center" 
                          >
                              <VCol cols="12" class="mt-0 py-0 px-0">
                                <v-tooltip text="Agregar nuevo despiece">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="">
                                        <VBtn v-bind="props" color="primary" class="w-100"  @click="addProductInput()"><VIcon icon="bx-plus"/> Agregar lote</VBtn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                              </VCol>
                              <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                                <VRow  v-for="(item,index) in selectedsLotes"  v-bind:key="item.id" class=" position-relative relative pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'new_order_product_'+index">
                                  <VCol cols="12"  md="5" class="form-group pb-md-0  mb-md-1">
                                    <VTextField
                                      placeholder="Producto"
                                      label="Producto"
                                      type="tex"
                                      :name="'product_in_order_quantity_'+index"
                                      v-model="selectedProduct.title"
                                      disabled
                                    />
                                  </VCol>
                                  <VCol cols="12"  md="4" class="form-group pb-md-0  mb-md-1">
                                    <v-combobox  
                                      :items="selectedProduct.lotes"
                                      item-title="lote_code"
                                      item-value="id"
                                      placeholder="Número de lote"
                                      label="Número de lote"
                                      type="text"
                                      name="new_order_client"
                                      v-model="item.lote"  
                                      autocomplete="off"
                                      persistent-hint
                                      :hint="'Fecha venc: ' + (item.lote.due_date ? moment(item.lote.due_date).format('DD-MM-YYYY') : '----')"
                                      :auto-select-first="true"
                                      @update:modelValue="selectedLotes($event,index)"
                                    ></v-combobox >
                                  </VCol>
                                  <VCol cols="6"  md="3" class="form-group pb-md-0 pt-1 mb-md-5">
                                    <VTextField
                                      placeholder="Unidades solicitadas"
                                      label="Unidades solicitadas"
                                      type="number"
                                      :name="'product_in_order_quantity_'+index"
                                      v-model="item.quantity"
                                    />
                                  </VCol>
                                  <!-- <VCol cols="2" md="1" class="form-group pa-0 mb-md-5 d-none d-md-block ">
                                    <v-tooltip text="Quitar producto">
                                      <template v-slot:activator="{ props }">
                                        <v-col cols="auto" class="pa-0">
                                          <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeProductInput(index)"></v-btn>
                                        </v-col>
                                      </template>
                                    </v-tooltip>
                                  </VCol>  -->
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
                          <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
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
  </div>
</template>
<style>
.fw-600{
  font-weight: 600!important;
}
.internalModalOrder{
  box-shadow: 0px 4px 17px 1px #a7a7a7;
    border-radius: 15px;
}
</style>
<script>
export default {
  data: () => ({
    modal:'', 
    selectedProduct:'',
    selectedsLotes:[
      {title:'', lote:'', quantity:'', selected_lote_id:''}
    ]
  }),
  mounted(){
  },
  methods:{
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
    },
    selectProduct(id){
      console.log(id)
      this.selectedProduct = this.order.products.filter(product => product.id == id)[0]

      console.log(this.selectedProduct)
      setTimeout(() => {
        this.showModal('selectedProductLote')
        console.log('per')
      }, 500);
    },
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    actionModal(action){
      this.$emit('actionModal',action)
    }
  }


};

</script>