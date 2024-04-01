<script setup >
  import * as bootstrap from 'bootstrap'
  import moment from 'moment';
  import { GET_CART, DELETE_PRODUCT_CART, UPDATE_QUANTITY_ITEMS, DESTROY_CART } from "@/core/services/store/cart.module";
  import { CREATE_ORDER, } from "@/core/services/store/order.module";

</script>
<template>
  <div>

    <div 
      class="position-fixed cart-float__button" 
      style=""
      @click="showModal()" 
    >
    <div>
  
      <v-btn icon="iconoir:cart-alt" size="x-large" class="bg-secondary" />
      <div class="cart_item__count animate__animated " v-if="cart.length > 0">
        <div class="w-100">
            {{ cart.length }}
        </div>
      </div>
    </div>
        
      
    </div>
    <div class="modal animate__animated animate__slideInLeft" id="showCart" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                  <VRow  class="mb-2 ma-0 mt-10 mt-md-0">
                    <VCol
                      cols="12"
                      class="py-0"
                    >
                      <div class="my-md-4 my-2 text-center">
                        <h2>Carrito de órdenes</h2>
                      </div>
                    </VCol>
                    <template v-if="cartIsReady">

                      <VCol
                        cols="12"
                        class="px-md-10 px-0 text-center"
                        style=""
                        v-if="cart.length > 0"
                      >
                        <v-list lines="three">
                          <v-list-item
                            v-for="(product, index) in cart"
                            v-bind:key="product.id"
                            style="border-bottom: 1px solid #b1b1b17a;"
                            class="px-0"
                           
                          >
                            <VRow class="align-center position-relative"  v-if="product.cartType == 1">
                              <VCol cols="4" class="pe-2 d-flex justify-center">
                                <VImg
                            
                                  class="rounded client_product_list_cart"
                                  :src="product.img"
                                />
                              </VCol>
                              <VCol cols="8" class="text-start d-flex flex-column align-start ps-0">
                                <div class="text-h6">{{ product.title }}</div>
                                <div class="">
                                  <div class="text-subtitle-1 my-2">
                                    Cantidad solicitada:
                                  </div>
                                  <div class="d-flex align-center">
                                    <div>
                                      <v-btn rounded="sm" size="small" class="text-subtitle-1" block @click="minusItemInCart(index)">-</v-btn>
                                    </div>
                                    <div class="d-flex align-center mx-2">
                                      <VTextField
                                        variant="underlined"
                                        placeholder="Cantidad"
                                        type="number"
                                        :hint="'Max:'+product.total_stock"
                                        persistent-hint
                                        @change="validateQuantityField(index, product.id)"
                                        v-model="product.quantity"
                                        autocomplete="off"
                                        class="cart-quantity-input" 
                                      />
                                      <span class="text-subtitle-1 h-100" style=" margin-top: -20px;  margin-left:-15px; background:white;z-index: 2;">{{  product.type_of_unit}}</span>
                                    </div>
                                    <div>
                                      <v-btn rounded="sm" size="small" class="text-subtitle-1" block @click="plusItemInCart(index)">+</v-btn>
                                    </div>
                                  </div>
                                </div>
                              </VCol>
                              <VCol cols="3" class="d-flex position-absolute form-group pa-0 mb-md-5 delete_item_in_cart ">
                                <v-col cols="auto" class="pa-0">
                                  <v-btn icon="mdi-cancel-bold" size="small" @click="removeItemOfCart(index)"></v-btn>
                                </v-col>
                              </VCol>
                            </VRow>
                            <VRow class="align-center position-relative"  v-else >
                              <VCol cols="4" class="pe-2 d-flex justify-center">
                                <VImg
                            
                                  class="rounded client_product_list_cart"
                                  :src="product.image_url"
                                />
                              </VCol>
                              <VCol cols="8" class="text-start d-flex flex-column align-start ps-0">
                                <div class="text-h6">{{ product.title }}</div>
                                <div class="">
                                  <div class="text-subtitle-1 my-0">
                                    Productos:
                                  </div>
                                  <div class="align-center">
                                    <template v-for="ingredient in product.cooking_ingredients" :key="ingredient.id">
                                      <div class="text-subtitle-2 ms-2" >
                                        - {{ ingredient.title }}: {{ parseFloat(ingredient.pivot.quantity) * parseFloat(product.quantity) }} {{ ingredient.type_of_unit }}
                                      </div>
                                       
                                    </template>
                                  </div>
                                </div>
                                <div class="">
                                  <div class="text-subtitle-1 my-2">
                                    Cantidad solicitada:
                                  </div>
                                  <div class="d-flex align-center">
                                    <div>
                                      <v-btn rounded="sm" size="small" class="text-subtitle-1" block @click="minusItemInCart(index)">-</v-btn>
                                    </div>
                                    <div class="d-flex align-center mx-2">
                                      <VTextField
                                        variant="underlined"
                                        placeholder="Cantidad"
                                        type="number"
                                        @change="validateQuantityField(index, product.id)"
                                        v-model="product.quantity"
                                        autocomplete="off"
                                        class="cart-quantity-input" 
                                      />
                                      
                                    </div>
                                    <div>
                                      <v-btn rounded="sm" size="small" class="text-subtitle-1" block @click="plusItemInCart(index)">+</v-btn>
                                    </div>
                                  </div>
                                </div>
                              </VCol>
                              <VCol cols="3" class="d-flex position-absolute form-group pa-0 mb-md-5 delete_item_in_cart ">
                                <v-col cols="auto" class="pa-0">
                                  <v-btn icon="mdi-cancel-bold" size="small" @click="removeItemOfCart(index)"></v-btn>
                                </v-col>
                              </VCol>
                            </VRow>
                          </v-list-item>
                        </v-list>
                        
                      </VCol>
                      <VCol
                        cols="12"
                        class="px-md-10 px-0 text-center"
                        style=""
                        v-else
                      >
                        <h2>No hay productos agregados en el carrito</h2>
                      </VCol>
                    </template>
                    <template v-else>
                      <!-- <v-skeleton-loader type="card" class="w-100"></v-skeleton-loader> -->
                      <v-skeleton-loader type="avatar, article" class="w-100"></v-skeleton-loader>
                    </template>
                  </VRow>
                    

                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  pb-1 pb-md-3   d-block">
                      <VBtn
                        color="white"
                        class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
                        id="create_order_of_cart_button"
                        @click="createdNewOrder()"
                        v-if="cart.length > 0"
                      >
                        <span class="">Realizar pedido</span>
                      </VBtn>
                    </VCardActions>
                  </div>
                  <div class="mt-0 w-100 d-md-flex  d-block justify-center" v-if="cart.length > 0">
                    <VCardActions class=" justify-center w-100 d-md-flex  d-block">
                      <VBtn
                        color="white"
                        class="bg-secondary text-white w-30 mx-0 mx-md-5 my-0"
                        @click="hideModal()"
                      >
                        <span class="">Agregar mas productos</span>
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
<style lang="scss">
.animate__slideInLeft, .animate__slideOutLeft{
  animation-duration: 0.5s;
}
.cart-float__button{
  transition: all 1s ease;
  top: 90%;
  right: 5%;
}
.modal__close-button__cart{
  position: absolute;
  right: -2%;
  top: 15px;

}
.client_product_list_cart {
  width:200px;
  height:200px;
}
.cart-quantity-input{
  max-width:max-content;
  & > .v-input__control{
    height: 41px;
  }
  & input{
    width: 80px;
    text-align: center;
    padding-top: 0px;
  }
}
.cart_item__count{
  position: absolute;
    height: 32px;
    border-radius: 10px;
    background: #cf6123;
    color: white;
    display: flex;
    align-items: center;
    top: -12px;
    left: 41%;
    text-align: center;
    width: 32px;
    padding: 2px;
    animation-duration: 1s !important;
}

@media screen and (max-width: 780px){
  .delete_item_in_cart{
    right: -14px;
    display: flex !important;
    justify-content: center;
    top: 13px;

  }
  .client_product_list_cart{
    width:100px;
    height:100px;
  }
}
</style>
<script>

  export default {
    data: () => ({
      cart:[],
      cartID:'',
      modal:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:2000,
      cartIsReady:false
    }),
    methods:{
      nextStep(action){
        action=='new' 
         ? this.stepperNewProduct = this.stepperNewProduct + 1 
         : this.stepperUpdateProduct = this.stepperUpdateProduct + 1 

         action=='new' 
          ? setTimeout(() => {
            this.stepperNewProduct > 2 ? '' : this.validateFormItem('new_recipe_form_2')
          }, 500)
          : setTimeout(() => {
            this.stepperUpdateProduct > 2 ? '' : this.validateFormItem('update_recipe_form_2')
          }, 500)
    
      },
      showModal() {
        try {
          this.hideModal()
        } catch (error) {
          
        }
        this.modal = new bootstrap.Modal(document.getElementById('showCart'), {
          keyboard: false,              
          backdrop:'static'
        })


        this.modal.show()

      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      hideModal(){
        this.modal.hide();
      },
      getCart(){
        this.$store
          .dispatch(GET_CART)
          .then((data) =>{
            this.cartIsReady = true;
            if(data.data.description !== null){

              this.cart = data.data.description.split('?')
              this.cartID = data.data.id
              this.$store.cart = this.formatCart()
              return
            }
            this.cart = []
            this.$store.cart = this.formatCart()
            this.emitter.emit('deleteItemOfCart')
            
          }).catch((err) => {
            console.log(err)

          });
      },
      removeItemOfCart(id){
        this.cartIsReady = false
        this.$store
          .dispatch(DELETE_PRODUCT_CART, id)
          .then((data) =>{
            this.getCart()
            this.emitter.emit('deleteItemOfCart')
          }).catch((err) => {
            console.log(err)

          });
      },
      formatCart(){
        if (this.cart.length > 0) {
          this.cart.pop()
          for (let i = 0; i < (this.cart.length); i++) {
            this.cart[i] = JSON.parse(this.cart[i])
          }
        }
        return this.cart
      },
      animationCart(){
        if (this.cart.length > 0) {
          document.querySelector('.cart_item__count').classList.toggle('animate__bounce')
          setTimeout(() => {
            document.querySelector('.cart_item__count').classList.toggle('animate__bounce')
            
          }, 2100);
        }
      },
      validateQuantityField(index, id){
        const product = this.cart.find((product)=>product.id == id)
        if(product.quantity > product.total_stock){
          this.showSnackbar('error', 'Stock insuficiente')
          return this.cart.find((product)=>product.id == id).quantity = product.total_stock
        }
        if(product.quantity < 1){
          return this.cart.find((product)=>product.id == id).quantity = 1
        }
        this.sendUpdate(index, product)
      },
      plusItemInCart(index){
       
        let product = this.cart[index]
        if(product.cartType == 1){

          product.quantity < product.total_stock
          ? this.cart[index].quantity = parseFloat(this.cart[index].quantity) + 1 
          : this.cart[index].quantity = product.total_stock
  
          this.sendUpdate(index, product)
          return;
        }
        this.cart[index].quantity = parseFloat(this.cart[index].quantity) + 1 
      },
      minusItemInCart(index){
        let product = this.cart[index]
        if(product.cartType == 1){
          const product = this.cart[index]
          product.quantity > 1
          ? this.cart[index].quantity = parseFloat(this.cart[index].quantity) - 1 
          : console.log('')
          this.sendUpdate(index, product)
          return;
        }
        this.cart[index].quantity = parseFloat(this.cart[index].quantity) + 1 
      },
      sendUpdate(index, product){
        const cartFormData = new FormData
        cartFormData.append('products', JSON.stringify(product))

        const data ={
          id:index,
          data:cartFormData
        }
        this.$store
          .dispatch(UPDATE_QUANTITY_ITEMS, data)
          .then((response) => {
            // this.showSnackbar('success', 'Orden creada con exito')
            // this.getCart()
          })
          .catch((err) => {
            console.log(err)
            // this.hideModal()
            this.showSnackbar('error', err )
          })
      },
      createdNewOrder(){
        if(!this.validateHaveProducts()) return ;

        this.sendingButton('create_order_of_cart_button')
        const formData = new FormData();
        formData.append('products', JSON.stringify(this.cart));
        formData.append('isManual', false);

        this.$store
          .dispatch(CREATE_ORDER, formData)
          .then((response) => {
            if (response.code == 200) {
              this.hideModal()
              
              this.readyButton('create_order_of_cart_button')
              this.showSnackbar('success', 'Orden creada con exito')
              this.destroyCart()
            }
          })
          .catch((err) => {
            console.log(err)
            // this.hideModal()
            this.showSnackbar('error', err )
            this.readyButton('create_order_of_cart_button')
          })
      },
      validateHaveProducts(){
        if(this.cart.length == 0 ){
          const sendButton = document.getElementById('create_order_of_cart_button')
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')
          alert('Debes ingresar los productos')
          return false
        } 
        return true
        
      },
      sendingButton(id){
        document.getElementById(id).disabled = true
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      destroyCart(){
        this.$store
          .dispatch(DESTROY_CART, this.cartID)
          .then((response) => {
            if(response.code == 200){
              setTimeout(() => {
                
                this.getCart()
              }, 50);
            }
          })
          .catch((err) => {
            console.log(err)
            // this.hideModal()
            this.showSnackbar('error', err )
          })
      }

    },
    mounted(){
      this.getCart()
    },
    created(){
      this.emitter.on("getItems", () => {
        this.getCart()
        setTimeout(() => {
              this.animationCart()
            }, 200);
      })
      this.emitter.on("showCart", () => {
        this.showModal()
      
      })
    }
    
  };
</script>