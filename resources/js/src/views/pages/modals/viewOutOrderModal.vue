<script setup>
import moment from 'moment';
import OrderProductsTables from '@/views/pages/tables/OrderProductsTables.vue';
const props = defineProps({
  order: Object,
})
const as = window

</script>
<template>
  <div class="modal animate__animated animate__fadeInDown" id="viewOrder" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
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
                  <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
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
                      <h2>Orden de productos</h2>
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
                        Solicitante: {{ order.client.name.toUpperCase() }}
                      </div>
                      <div class="my-2 text-start">
                        Dirección de destino: {{ order.other_address }}
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
                        Fecha de pedio: {{ moment(order.created_at).format('DD/MM/YYYY HH:mm:ss') }}
                      </div>
                      <div class="my-2  text-start text-md-end">
                        Tracker ID: {{ order.trancker }}
                      </div>
                    </div>
                  
                  </VCol>
                </VRow>
                <div class="w-100">
                  <OrderProductsTables :products="order.products" />
                </div>
                <VDivider  />
                <div class="mt-5 w-100 d-flex  justify-center">
                  <VCardActions class=" justify-center w-75">
                    <VBtn
                      color="white"
                      class="bg-secondary text-white w-50"
                      @click="hideModal()"
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
</template>
<script>

export default {

  methods:{
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    hideModal(){
      this.$emit('hiddenModal')
    }
  }


};

</script>