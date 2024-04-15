<script setup >
  import * as bootstrap from 'bootstrap'
  import moment from 'moment';
  import { GET_CART, DELETE_PRODUCT_CART, UPDATE_QUANTITY_ITEMS, DESTROY_CART } from "@/core/services/store/cart.module";
  import { CREATE_ORDER, } from "@/core/services/store/order.module";

</script>
<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="printModule" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                        <h2>Imprimir etiquetas</h2>
                      </div>
                    </VCol>
                  </VRow>
                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  pb-1 pb-md-3   d-block">
                      <VBtn
                        color="white"
                        class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
                        id="create_order_of_cart_button"
                        @click="alertaa('nada todavia')"
                      >
                        <span class="">Imprimir</span>
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

</style>
<script>

  export default {
    data: () => ({
      cart:[],
      cartID:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:2000,
    }),
    methods:{
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      hideModal(){
        this.$emit('hide')
      },
    },
    mounted(){
    },
    created(){
    }
    
  };
</script>