<script setup>
import moment from 'moment';
const props = defineProps({
  product: Object,
})
const as = window

</script>
<template>
  <div class="modal animate__animated animate__fadeInDown" id="selectedProductLote" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
    <div class="modal-dialog modal mt-10" >
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
                        <div class="form-title__part1">Asignar producto a la orden</div>
                        {{ product.title }}
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
                      <VRow 
                        class="ma-0 pa-0  mt-5 align-center" 
                        >
                            <VCol cols="12" md="4" class="mt-0 py-0 px-0">
                              <v-tooltip text="Agregar nuevo despiece">
                                  <template v-slot:activator="{ props }">
                                    <v-col cols="auto" class="">
                                      <VBtn v-bind="props" color="primary" class="w-100"  @click="addProductInput()"><VIcon icon="bx-plus"/> Agregar lote</VBtn>
                                    </v-col>
                                  </template>
                                </v-tooltip>
                            </VCol>
                            <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                              <VRow  v-for="(item,index) in selecteds"  v-bind:key="item.id" class=" position-relative relative pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'new_order_product_'+index">
                                <VCol cols="12"  md="5" class="form-group pb-md-0  mb-md-1">
                                  <VTextField
                                    placeholder="Unidades solicitadas"
                                    label="Unidades solicitadas"
                                    type="number"
                                    :name="'product_in_order_quantity_'+index"
                                    v-model="item.title"
                                  />
                                </VCol>
                                <VCol cols="12"  md="4" class="form-group pb-md-0  mb-md-1">
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
</template>
<style>
.fw-600{
  font-weight: 600!important;
}
</style>
<script>
export default {
  data: () => ({
    selecteds:[
      {
        title:'',
        lote:'',
        quantity:'',
        selected_lote_id:''
      },
    ]
  }),
  methods:{
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    // selectedLotes(e, index){
    //   this.selectedsproducts[index].maxValue = e.quantity
    //   setTimeout(() => {
    //       this.addValidate(this.newOrder.products[index].maxValue)
    //     }, 200);
    // },
    actionModal(action){
      this.$emit('actionModal',action)
    }
  }


};

</script>