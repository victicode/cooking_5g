<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="createOutOrder" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column pa-2 px-3 pa-md-5 pt-md-16 ">
                  <VRow  class="mb-2 ma-0 mt-md-5">
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
                        <div class="my-2  text-start text-md-end" >
                          Tracker ID: <a class="text-secondary fw-600">{{ order.trancker }}</a>
                        </div>
                      </div>
                    </VCol>
                  </VRow>
                  <div class="w-100">
                    <CreateOutOrderProductsTables :products="order.products" :recipes="order.recipes" @selectProduct="selectProduct" />
                  </div>
                  <VDivider  />
                  <div class="mt-5 w-100 d-flex  justify-center">
                    <VCardActions class=" justify-center w-75">
                      <VBtn
                        color="white"
                        class="bg-secondary text-white w-75"
                        @click="createOutOrder()"
                        :id="'create_order_'+order.id"
                        :loading="loading"
                      >
                        <span class="ms-2">Crear salida</span>
                        <template v-slot:loader>
                          <loadingButton />
                        </template>
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
      <div class="modal animate__animated animate__fadeInDown mt-16" id="selectedProductLote" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
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
                        <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideInternalModal()" ></v-btn>
                      </v-col>
                    </div>
                    <div class="internalModalOrder">
                      <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                        <VCardTitle class="text-2xl font-weight-bold">
                          <div class="card-title text-center">
                            <div class="form-title__part1"><h4>Asignar producto a la orden:</h4></div>
                            <div>
                              <h2 class="my-2">{{ selectedProduct.title }}</h2>
                              <v-chip color="primary"  v-if="recipeTitleLabel(selectedProduct)" class="mb-2">
                                <span class="font-italic">
                                  {{ recipeTitleLabel(selectedProduct).title }}
                                </span>
                              </v-chip>
                            </div>
                            <h5 class="my-2">Orden #{{ orderNumberFormat(order.id) }}</h5>
                            <div class="d-flex justify-center align-center">
  
                              <h4 class="my-2">
                                Cantidad solicitada: 
                              </h4>
                              <v-chip class="bg-success ms-2">{{ totalQuantityOfProductInRecipe() }} {{ selectedProduct.type_of_unit}}</v-chip>
                            </div>
                            
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
                            <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                              <VRow  v-for="(item,index) in selectedLotes[loteModalName()]"  v-bind:key="item.id" class=" position-relative relative pa-0 ma-0 align-center w-100 mt-5 mt-md-4"  :id="'new_order_product_'+index">
                                <VCol cols="12"  md="6" class="form-group pb-md-0  mb-md-1">
                                  <v-combobox  
                                    :items="selectedProduct.lotes_recipe"
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
                                    @update:modelValue="selectLotes($event,index)"
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
                                    :data-product-name="selectedProduct.title.replace(/ /g,'_')+'_'+index"
                                    @keyup="calculateUnitOrders()"
                                  />
                                </VCol>
                                <div class="form-group pa-0 mb-md-5  small-delete-product-button " style="right: -2px;">
                                  <v-tooltip text="Quitar producto">
                                    <template v-slot:activator="{ props }">
                                      <v-col cols="auto" class="pa-0">
                                        <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeInputLote(index)"></v-btn>
                                      </v-col>
                                    </template>
                                  </v-tooltip>
                                </div>
                              </VRow>
                            </div>
                          </VRow>
                          <VRow class="ma-0 pa-0  mt-8 align-center">
                            <VCol cols="12" md="6"  class="mt-0 py-0 px-0">
                              <v-tooltip text="Agregar lote">
                                  <template v-slot:activator="{ props }">
                                    <v-col cols="auto" class="">
                                      <VBtn v-bind="props" color="secondary" class="w-100"  @click="addInputLote()"><VIcon icon="bx-plus"/> Agregar lote</VBtn>
                                    </v-col>
                                  </template>
                                </v-tooltip>
                            </VCol>
                            <VCol cols="12" md="6" class="mt-0 py-0 px-0">
                              <v-col cols="auto" class="">
                                <VBtn  color="primary" class="w-100 " type="submit"  :loading="loading" disabled id="select_lote_for_order_button">
                                  Guardar
                                  <template v-slot:loader>
                                    <loadingButton />
                                  </template>
                                 </VBtn>
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
  </div>
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
import moment from 'moment';
import * as bootstrap from 'bootstrap';
import CreateOutOrderProductsTables from '@/views/pages/tables/CreateOutOrderProductsTables.vue';
import loadingButton from "@/layouts/components/loadingButton.vue";
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
import { CREATE_OUT_ORDER } from "@/core/services/store/order.module";

export default {
  props:['order'],
  components: {
    CreateOutOrderProductsTables,
    loadingButton,
  },
  data: () => ({
    loading:false,
    forms:'',
    modal:'', 
    alertShow:false,
    alertType:'',
    alertMessage:'',
    selectedProduct:'',
    selectedLotes:[],
    products: [],
    snackShow:false,
    snackMessage:'',
    snackType:'',
    snacktimeOut:5000,
    moment,
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
      this.$emit('hiddenModal');
      this.selectedLotes = [];
    },
    hideInternalModal(){
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
    cleanForm(){
      this.selectedProduct = '';
      this.selectedLotes = [];
    },
    loadingState(state){
      this.loading = state
    },
    selectProduct(id, index){
      console.log(this.order.recipes[index])
      this.selectedProduct = this.order.recipes[index].cooking_ingredients.find(product => product.id == id)
      this.selectedProduct.position = index
      setTimeout(() => {
        // console.log(this.selectedProduct)
        this.showModal('selectedProductLote')
        this.initLotesFormsValidate()
      }, 300);
    },
    selectLotes(e, index){
      this.selectedLotes[this.loteModalName()][index].indexLote = index
      this.selectedLotes[this.loteModalName()][index].product_id = this.selectedProduct.id
      this.selectedLotes[this.loteModalName()][index]['inOrder'] = this.recipeTitleLabel(this.selectedProduct).id
      setTimeout(() => {
        this.addLoteQuantityInputValidate(this.selectedLotes[this.loteModalName()][index])
        this.forms.validateField('product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'_'+index)
      }, 500);
    },
    initLotesFormsValidate(){
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
      setTimeout( ()=> this.formsActionsValidate(), 500)
    },
    addLoteQuantityInputValidate(lote){
      const form = document.getElementById('select_lote_for_order');
      const quantityInput = form.querySelector('input[name="product_in_order_quantity_'+this.selectedProduct.title.replace(/ /g,'_')+'_'+lote.indexLote+'"]');
      const fieldOptions  = {
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
      };
      this.forms.addField(quantityInput.name, fieldOptions.quantity)
      return
    },
    addLoteSelectInputValidate(){
      const form = document.getElementById('select_lote_for_order');
      const index = form.querySelectorAll('input[name*="product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'"]').length - 1;
      const input = form.querySelector('input[name="product_in_order_lote_'+this.selectedProduct.title.replace(/ /g,'_')+'_'+index+'"]');
      const fieldOptions={
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
    removeValidate(){
      const form = document.getElementById('select_lote_for_order');
      const loteInput = "product_in_order_lote_"+this.selectedProduct.title.replace(/ /g,'_') +"_"+ (form.querySelectorAll('input[name*="product_in_order_lote_"]').length - 1);
      const quantityInput = "product_in_order_quantity_"+this.selectedProduct.title.replace(/ /g,'_') +"_"+ (form.querySelectorAll('input[name*="product_in_order_lote_"]').length - 1);
      try {
        this.forms.removeField(loteInput)
        this.forms.removeField(quantityInput)
      } catch (error) {
        // console.log('no hay validación activa')
      }
    },
    destroyFormVal(){
      this.forms.destroy()
    },
    formsActionsValidate(){
      this.forms.on("core.form.valid", () => {
        this.addLoteInOrder()
      }).on("core.field.valid", () => {
        this.enableButton('select_lote_for_order_button')

      }).on("core.form.invalid", () => {
        this.disabledButton('select_lote_for_order_button')

      }).on("core.field.invalid", () => {
        this.disabledButton('select_lote_for_order_button')

      });
    },
    verifyCheckLotes(){
      let result = true
      Object.values(this.selectedLotes).forEach( (lote) =>{
        if (lote.length == 0 || !lote[0].product_id){
          this.order.recipes[lote.recipePosition].cooking_ingredients.find( product => product.id == lote.idProduct).in_order= false
          result =  false;
        } 
      })
      if(Object.values(this.selectedLotes).length != this.totalProductsInOrder()) return false
      this.enableButton('create_order_'+this.order.id)
      return result;
    },
    
    addInputLote(){
      const newLote = {selected_lote:'', quantity:'', selected_lote_id:''}
      try {
        this.selectedLotes[this.loteModalName()].push(newLote)
      } catch (error) {
        this.selectedLotes[this.loteModalName()] = [newLote]
      }
      this.selectedLotes[this.loteModalName()]['idProduct'] = this.selectedProduct.id
      this.selectedLotes[this.loteModalName()]['recipePosition'] = this.selectedProduct.position

      this.products.push(newLote)

      setTimeout(() => {
        this.addLoteSelectInputValidate()
      }, 200);

    }, 
    removeInputLote(index){  
      this.removeValidate()
      setTimeout(() => {
        try{
          this.selectedLotes[this.loteModalName()].splice(index, 1)
        }catch(e){
          console.log('noni')
        }
      }, 200);
      
    },
    orderNumberFormat(id){
      return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    loteModalName(){
      return this.selectedProduct.title.replace(/ /g,'_')+'_'+this.selectedProduct.position;
    },
    recipeTitleLabel(product){
      return product.pivot.recipe_id 
        ? this.order.recipes.find((recipe) => recipe.id == product.pivot.recipe_id )
        :''
    },
    calculateUnitOrders(){
      let total = 0
      this.selectedLotes[this.loteModalName()].forEach( lote =>  total = (parseFloat(total) + parseFloat(lote.quantity)))

      if(total <= parseFloat(this.totalQuantityOfProductInRecipe())){
        this.enableButton('select_lote_for_order_button')
        this.closeAlert()
        return total == this.totalQuantityOfProductInRecipe()
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
    totalProductsInOrder(){
      let total = 0;
      this.order.recipes.forEach((recipe)=>{
        total = recipe.cooking_ingredients.length + total
      })

      return total;
    },
    totalQuantityOfProductInRecipe(){

      return Number.isInteger((parseFloat(this.selectedProduct.pivot.quantity) * parseFloat(this.order.recipes[this.selectedProduct.position].pivot.quantity)))  
        ? (parseFloat(this.selectedProduct.pivot.quantity) * parseFloat(this.order.recipes[this.selectedProduct.position].pivot.quantity)).toFixed(0) 
        : (parseFloat(this.selectedProduct.pivot.quantity) * parseFloat(this.order.recipes[this.selectedProduct.position].pivot.quantity)).toFixed(2) 
      
    },
    addLoteInOrder(){
      this.loadingState(true)
      if(this.calculateUnitOrders()){
        setTimeout(() => {
          
          this.hideInternalModal()
          this.disabledButton('select_lote_for_order_button')
          this.loadingState(false)
          this.order.recipes[this.selectedProduct.position].cooking_ingredients.find(product => product.id == this.selectedProduct.id).in_order = true
        }, 200);
        return
      }
      this.loadingState(false)
      this.disabledButton('select_lote_for_order_button')
      this.showAlert('Cantidad ingresada es menor a lo solicitado', 'error')
  
    },
    createOutOrder(){
      this.loadingState(true)

      if(!this.verifyCheckLotes()) {
        this.showSnackbar('error', 'Faltan productos')
        this.loadingState(false)
        return
      }
      const formData = new FormData();
      formData.append('order', this.order.id);
      formData.append('products', JSON.stringify(this.products));
      formData.append('recipes', JSON.stringify(this.order.recipes));
      this.$store
        .dispatch(CREATE_OUT_ORDER, formData)
        .then((response) => {
          this.hideModal()
          this.showSnackbar('success', 'Orden creada con exito')
          this.cleanForm()
          this.loadingState(false)
        })
        .catch((err) => {
          this.showSnackbar('error', err )
          this.loadingState(false)
        })
    },

  }
};

</script>