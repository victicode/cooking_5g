<script setup>
import moment from 'moment';
import * as bootstrap from 'bootstrap';
import CreateOutOrderProductsTables from '@/views/pages/tables/CreateOutOrderProductsTables.vue';
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
const props = defineProps({
  order: Object,
})


</script>
<template>
  <div class="modal animate__animated animate__fadeInDown" id="createOutOrder" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
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
                      class="bg-secondary text-white w-75"
                      @click="createOutOrder()"
                      :id="'create_order_'+this.order.id"
                    >
                      <span class="ms-2">Crear salida</span>
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
                          <h6 class="my-2">orden #{{ orderNumberFormat(order.id) }}</h6>

                          <h4 class="my-2">Cantidad solicitada: {{ selectedProduct.pivot.quantity }} {{ selectedProduct.type_of_unit}}</h4>
                          
                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <VCardText class="mb-0 pb-1  w-100 px-md-8 px-2" v-if="alertShow">
                      <v-alert
                        :color="alertType"
                        :text="alertMessage"
                      ></v-alert>
                    </VCardText>
                    <VCardText class="w-100 pb-5 px-3 px-md-6">
                      <VForm  id="select_lote_for_order">
                        <VRow 
                          class="ma-0 pa-0  mt-1 align-center" 
                          >
                              <VCol cols="12" class="mt-0 py-0 px-0">
                                <v-tooltip text="Agregar nuevo despiece">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="">
                                        <VBtn v-bind="props" color="primary" class="w-100"  @click="addLoteInput()"><VIcon icon="bx-plus"/> Agregar lote</VBtn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                              </VCol>
                              <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                                <VRow  v-for="(item,index) in selectedsLotes[selectedProduct.title.replace(/ /g,'_')]"  v-bind:key="item.id" class=" position-relative relative pa-0 ma-0 align-center w-100 mt-5 mt-md-4"  :id="'new_order_product_'+index">
                                  <VCol cols="12"  md="6" class="form-group pb-md-0  mb-md-1">
                                    <v-combobox  
                                      :items="selectedProduct.lotes"
                                      item-title="lote_code"
                                      item-value="id"
                                      placeholder="Número de lote"
                                      label="Número de lote"
                                      type="text"
                                      :name="'product_in_order_lote_'+selectedProduct.title.replace(/ /g,'_')+'_'+index"
                                      v-model="item.selected_lote"  
                                      autocomplete="off"
                                      persistent-hint
                                      :hint="'Fecha venc: ' + (item.selected_lote ? moment(item.selected_lote.due_date).format('DD-MM-YYYY') : '----')"
                                      :auto-select-first="true"
                                      @update:modelValue="selectedLotes($event,index)"
                                    ></v-combobox >
                                  </VCol>
                                  <VCol cols="12"  md="6" class="form-group pb-md-0  mb-md-1">
                                    <VTextField
                                      placeholder="Unidades solicitadas"
                                      label="Unidades solicitadas"
                                      type="number"
                                      persistent-hint
                                      :hint="'Stock de lote: ' + (item.selected_lote ? item.selected_lote.quantity  : '----')"
                                      :name="'product_in_order_quantity_'+selectedProduct.title.replace(/ /g,'_')+'_'+index"
                                      v-model="item.quantity"
                                      @keyup="calculateUnitOrders()"
                                    />
                                  </VCol>
                                  <div class="form-group pa-0 mb-md-5  small-delete-product-button " style="right: -2px;">
                                    <v-tooltip text="Quitar producto">
                                      <template v-slot:activator="{ props }">
                                        <v-col cols="auto" class="pa-0">
                                          <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeLote(index)"></v-btn>
                                        </v-col>
                                      </template>
                                    </v-tooltip>
                                  </div>
                                </VRow>
                              </div>
                        </VRow>
                        <VRow class="ma-0 pa-0  mt-8 align-center">
                          <VCol cols="12" md="6" offset-md="3" class="mt-0 py-0 px-0">
                            <v-col cols="auto" class="">
                              <VBtn  color="primary" class="w-100 " type="submit" disabled id="select_lote_for_order_button"> Guardar</VBtn>
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
</template>
<style>
.fw-600{
  font-weight: 600!important;
}
.internalModalOrder{
  box-shadow: 0px 5px 32px 0px #5f5f5fe6;
    border-radius: 15px;
}
</style>
<script>
export default {
  data: () => ({
    forms:'',
    modal:'', 
    alertShow:false,
    alertType:'',
    alertMessage:'',
    selectedProduct:'',
    selectedsLotes:[
    ],
    snackShow:false,
    snackMessage:'',
    snackType:'',
    snacktimeOut:5000,
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
      this.destroyFormVal()
      this.verifyCheckLotes()
      this.modal.hide()
    },
    showAlert(message,type){
      this.alertShow = true,
      this.alertType = type,
      this.alertMessage = message
    },
    closeAlert(message,type){
      this.alertShow = false
      this.alertType = ''
      this.alertMessage = 'message'
    },
    showSnackbar(type, messagge){
      this.snackShow = true;
      this.snackType = type
      this.snackMessage = messagge
    },
    selectProduct(id){
      this.selectedProduct = this.order.products.filter(product => product.id == id)[0]
      setTimeout(() => {
        this.showModal('selectedProductLote')
        this.validateFormItem()
      }, 300);
    },
    removeLote(index){  
      setTimeout(() => {
        try{
          this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')].splice(index, 1)
        }catch(e){

        }
      }, 200);
      
    },
    addLoteInput(){
      let newLote = {selected_lote:'', quantity:'', selected_lote_id:''}

      try {
        this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')].push(newLote)
      } catch (error) {
        this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')] = [newLote]
      }
      this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')]['idProduct']=this.selectedProduct.id
      setTimeout(() => {
        this.createValidate()

      }, 200);

    }, 
    selectedLotes(e, index){
      this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')][index].indexLote = index
      this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')][index].product_id = this.selectedProduct.id

      setTimeout(() => {
        this.forms.validateField('product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'_'+index)
          this.addValidate(this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')][index])
        }, 200);
    },
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    validateFormItem(){
      this.forms = formValidation(document.getElementById('select_lote_for_order'), {
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
      let fields = {
        validators: {
          notEmpty: {
            message: "El cliente es requerido"
          },
        }
      };
      this.forms.addField(
        'product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'_0', fields
      )
      setTimeout( ()=> this.formsActions(), 500)
    },
    formsActions(){
      
      this.forms.on("core.form.valid", () => {
        this.addLote()
      }).on("core.field.valid", () => {
        this.enableButton('select_lote_for_order_button')

      }).on("core.form.invalid", () => {
        this.disabledButton('select_lote_for_order_button')

      }).on("core.field.invalid", () => {
        this.disabledButton('select_lote_for_order_button')

      });
    },
    actionModal(action){
      this.$emit('actionModal',action)
    },
    destroyFormVal(){
      this.forms.destroy()
    },
    addValidate(lote){
      let form = document.getElementById('select_lote_for_order'),
      quantityInput = form.querySelector('input[name="product_in_order_quantity_'+this.selectedProduct.title.replace(/ /g,'_')+'_'+lote.indexLote+'"]'),
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
              max: lote.selected_lote.quantity,
            },
          }
        }
      } 
      this.forms.addField(quantityInput.name, fieldOptions.quantity)
      return
    },
    addLote(){
    
      if(this.calculateUnitOrders()){
        this.hideModal()
        this.disabledButton('select_lote_for_order_button')
        // this.$emit('checkProductOrder',this.selectedProduct.title.replace(/ /g,'_'))
        this.order.products.filter(product => product.id == this.selectedProduct.id)[0].in_order= true
        return
      }
      
      this.disabledButton('select_lote_for_order_button')
      this.showAlert('Cantidad ingresada es menor a lo solicitado', 'error')
  
    },
    
    createValidate(){

      let form = document.getElementById('select_lote_for_order'),
      index = form.querySelectorAll('input[name*="product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'"]').length - 1,
      input = form.querySelector('input[name="product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'_'+index+'"]'),
      fieldOptions={
        selectLote: {
          validators: {
            notEmpty: {
              message: "Debes selecionar un lote"
            },
          }
        }
      } 
      this.forms.addField(input.name, fieldOptions.selectLote)
      return
    },
    calculateUnitOrders(){
      let total = 0
      this.selectedsLotes[this.selectedProduct.title.replace(/ /g,'_')].forEach( el =>  total = parseInt(total) + parseInt(el.quantity))
      if(total <= this.selectedProduct.pivot.quantity){
        
        this.enableButton('select_lote_for_order_button')
        this.closeAlert()
        return total == this.selectedProduct.pivot.quantity
      }
      if ( isNaN(total) ) {
        this.enableButton('select_lote_for_order_button')
        this.closeAlert()
        return 
      }
      
      this.disabledButton('select_lote_for_order_button')
      this.showAlert('Cantidad ingresada supera lo solicitado', 'error')
      return
    },
    enableButton(id){
      const sendButton = document.getElementById(id)
      sendButton.disabled = false
      sendButton.classList.remove('v-btn--disabled') 

    },
    disabledButton(id){
      const sendButton = document.getElementById(id)
      sendButton.disabled = true
      sendButton.classList.add('v-btn--disabled')
    },
    removeValidate(){
      let form = document.getElementById('new_order_form'),
      quantityInput = form.querySelectorAll('input[name*="product_in_order_quantity_"]')[ form.querySelectorAll('input[name*="product_in_order_quantity_"]').length - 1]
      try {
        this.forms.removeField(quantityInput.name)
      } catch (error) {
      }
      
    },
    verifyCheckLotes(){

      Object.values(this.selectedsLotes).forEach( (lote) =>{
        if (lote.length == 0){
          this.order.products.filter(product => product.id == lote.idProduct )[0].in_order= false
          return false;
        } 
      })

      if(Object.values(this.selectedsLotes).length != this.order.products.length) return false
      this.enableButton('create_order_'+this.order.id)
      return true;
      
    },
    cleanForm(){
      this.selectedProduct ='';
      this.selectedsLotes =[
      ];
    
    },
    createOutOrder(){
      if(this.verifyCheckLotes()){
        this.disabledButton('create_order_'+this.order.id)
        this.$emit('createOutOrder', Object.values(this.selectedsLotes))

        this.cleanForm()
        return
      }
      this.showSnackbar('error', 'Faltan productos')
    },
  }


};

</script>