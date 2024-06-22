<template>
  <div class="w-100">
    <div class="modal animate__animated animate__slideInLeft"  id="createOrder" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                <div>
                  <VCardItem class="justify-center w-100  pb-md-10  py-4 pt-16 mb-5  ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1 ">Crear nueva orden</div>
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <VCardText class="w-100 pb-5 px-3 px-md-6 pt-md-5">
                    <VForm  id="new_order_form">
                      <VRow class="align-center">
                        <VCol cols="12" md="7" class="form-group">
                          <v-combobox  
                            v-if="!isUser"
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
                          />
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
                            <input type="checkbox"  @change="useClientAddress($event)" id="isUserAddress"  :checked="isUser ? true : false" value="1" style="height: 20px; width: 20px;">
                            <label for="isUserAddress" class="mx-2"> {{ !isUser ? 'Usar dirección del usuario' : 'Usar tu dirección registrada' }}</label>
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
                            <VCol cols="12"  :md="!isUser ? 4 : 6" class="form-group pb-md-0  mb-md-1">
                              <v-autocomplete
                                :model-value="item.id"
                                :items="productsForOrder[index] ?  productsForOrder[index] : item.id !== null ? [ {id: item.id, title: item.title, stock: item.quantity}] : []"
                                label="Nombre de la receta"
                                item-title="title"
                                item-value="id"
                                placeholder="Nombre de la receta"
                                variant="outlined"
                                :persistent-hint="!isUser ? true : false "
                                clearable
                                no-filter
                                :name="'product_in_order_'+index"
                                no-data-text="No se encontraron resultados"
                                @click="searchProductsForOrder($event,index )"
                                @keyup="searchProductsForOrder($event,index )"
                                @click:clear="clearProductSearch(index)"
                                @update:modelValue="selectedProduct($event, index)"
                              />
                            </VCol>
                            <VCol cols="12"  md="5" class="form-group pb-md-0  mb-md-1" v-if="!isUser">
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
                              />
                            </VCol>
                            <VCol cols="12"  :md="!isUser ? 3 : 6 " :class=" !isUser ? 'form-group pb-md-0  mb-md-6' : 'form-group pb-md-0  mb-md-1' " >
                              <VTextField
                                placeholder="Unidades solicitadas"
                                label="Unidades solicitadas"
                                type="number"
                                :hint="  item.maxValue ? 'Max: ' + item.maxValue : 'Max: 0'"
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
                            <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_order_form_button" :loading="loading">
                              Crear Orden
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
<script>
  import { GET_USER } from "@/core/services/store/user.module";
  import { GET_RECIPE_BY_SEARCH } from "@/core/services/store/recipe.module";
  import { CREATE_ORDER } from "@/core/services/store/order.module";
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import debounce from 'debounce';
  import loadingButton from "@/layouts/components/loadingButton.vue";
  import moment from 'moment';

export default {
  components: {
    loadingButton,
  },
  data: () => ({
    loading: false,
    snackShow: false,
    snackMessage:'',
    snackType:'',
    snacktimeOut:5000,
    forms:'',
    newOrder: {
      user:'',
      products:[],
      userAddress:'',
    },
    userForOrder:[],
    productsForOrder:[],
    user:{},
    isUser:false
  }),
  methods: {
    orderNumberFormat(id){
      return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    hideModal(){
      this.clearNewOrderForm()
      this.$emit('hiddenModal')
    },
    clearNewOrderForm(){
      if(!this.isUser){
        document.getElementById('isUserAddress').checked = false;
        this.newOrder = {
          user:'',
          products:[],
          userAddress:'',
        }
        return
      }
      this.newOrder.products = [] ;
      this.newOrder.userAddress = this.user.user_address;
      document.getElementById('isUserAddress').checked = true;
    },
    useClientAddress(e){
      this.newOrder.userAddress = e.target.checked 
        ? this.user.user_address
        : this.newOrder.userAddress = ''

      this.forms.validateField('new_order_address')
    },
    getUser(){
      this.$store.dispatch(GET_USER)
        .then((data) => {
          if(data.code !== 200){
            console.log('alert!!!')
          }
          this.user = data.user;
          if(data.user.rol_id == 3) {
            this.isUser = true
            this.newOrder.userAddress = this.user.user_address
          }
        })
        .catch((e) => {
          // this.logout()
        });
    },
    createdNewOrder(){
      if(!this.validateHaveProducts()) return ;
      this.loadingState(true)
      this.sendingButton('new_order_form_button')

      const formData = new FormData();

      formData.append('address', this.newOrder.userAddress);
      formData.append('products', JSON.stringify(this.newOrder.products));
      formData.append('client', !this.isUser ? this.newOrder.user.id : this.user.id);
      formData.append('isManual', !this.isUser ? true : false);

      this.$store
        .dispatch(CREATE_ORDER, formData)
        .then((response) => {
          this.showSnackbar('success', 'Orden creada con exito')
          this.readyButton('new_order_form_button')
          this.hideModal()
          this.loadingState(false)
        })
        .catch((err) => {
          console.log(err)
          this.showSnackbar('error', err )
          this.readyButton('new_order_form_button')
          this.loadingState(false)
        })
    },
    getProducts(search = "", index){
      this.$store
        .dispatch(GET_RECIPE_BY_SEARCH, search)
        .then((response) => {
          this.productsForOrder[index] = this.unSelectedProducts(response.data)
        })
        .catch((err) => {
          return new Promise((resolve) => {
            resolve(false);
          });
        })

    },
    showSnackbar(type, messagge){
      this.snackShow = true;
      this.snackType = type
      this.snackMessage = messagge
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


      if( this.newOrder.products[index].maxValue <= 0 || !this.isAllIngredientsInStock(this.newOrder.products[index]) ){
        this.newOrder.products[index] = {
          id:null,
          title:'',
          quantity:'',
          maxValue:0,
          recipe:'',
          lotes:[],
          selected_lote:''

        }
        alert('Este producto no tiene Stock')
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
    addValidate(max){
      // this.forms[id] 
      
      const form = document.getElementById('new_order_form');
      const quantityInput = form.querySelectorAll('input[name*="product_in_order_quantity_"]')[ form.querySelectorAll('input[name*="product_in_order_quantity_"]').length - 1];
      const fieldOptions = {
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
      
    }, 
    unSelectedProducts(data){
      const ids = this.getUnSelectedId()
      const unSelectedRecipes = data.filter((data) => !ids.includes(data.id) )

      return unSelectedRecipes
    }, 
    getUnSelectedId(){
      const ids = []
      this.newOrder.products.forEach((data) =>{
        if(data.id && data.id !== '' ) ids.push(data.id)
      })

      return ids
    },
    sendingButton(id){
      document.getElementById(id).disabled = true
    },
    readyButton(id){
      document.getElementById(id).disabled = true
      
      document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
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
    },
    loadingState(state){
      this.loading = state
    },
    isAllIngredientsInStock(recipe){
      let isOk= true
      recipe.cooking_ingredients.forEach((item)=>{
        if(item.total_stock < 1 || this.validateIsgoodProduct(item, 'yes','no') == 'no') isOk = false ;
      })
      return isOk
    },
    validateIsgoodProduct(ingredient, messageGood, messageBad){
      if(!ingredient.lotes) return messageBad  ;
      if(ingredient.lotes.length == 0) return messageBad == '(Sin stock)*' ? 'Eliminado' : 'recipe-notproduct text-decoration-line-through'
      if(ingredient.total_stock < 1) return messageBad
      if(ingredient.lotes[0].quantity <=0 || Math.round(moment.duration(moment(ingredient.lotes[0].due_date).diff(new moment())).as('days') ) < 0 ){
        return messageBad 
      }
      return ingredient.deleted_at==null 
      ? messageGood 
      : messageBad 

    },
  },
  mounted() {
    this.getUser();
    this.validateFormItem();
  }
}
</script>