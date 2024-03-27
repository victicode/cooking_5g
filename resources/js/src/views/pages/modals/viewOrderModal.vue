<script setup>
import moment from 'moment';
import OrderProductsTables from '@/views/pages/tables/OrderProductsTables.vue';
const props = defineProps({
  order: Object,
})
const token = localStorage.getItem('id_token')


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
                      <div class="my-2  text-start text-md-end">
                        Tracker ID: <a class="text-secondary fw-600">{{ order.trancker }}</a>
                      </div>
                    </div>
                    <div class="d-flex justify-md-end justify-space-between mt-4 mb-4">
                      <v-btn append-icon="icon-park-twotone:history-query"  rounded="xs" variant="outlined" @click="actionModal('show:history')">
                        Historial
                      </v-btn>
                      <div v-if="order.out_order">
                        <a :href="meta.env.VITE_VUE_APP_BACKEND_URL+'api/out_order/print/' + order.out_order.id +'?token='+token"  target="_blank" rel="noopener noreferrer">
                          <v-btn append-icon="carbon:delivery"  class="d-none d-md-flex mx-2" rounded="xs" variant="outlined">
                            Orden de salida
                          </v-btn>
                        </a>
                        <a :href="meta.env.VITE_VUE_APP_BACKEND_URL+'api/out_order/print/' + order.out_order.id +'?token='+token"  target="_blank" rel="noopener noreferrer">
                          <v-btn append-icon="carbon:delivery"  class="d-flex d-md-none" rounded="xs" variant="outlined">
                            Ver salida
                          </v-btn>
                        </a>
                      </div>
                    </div>
                  </VCol>
                </VRow>
                <div class="w-100 mb-5">
                  <OrderProductsTables :products="order.products" />
                </div>
                <VDivider  />
                <!-- <div class="mt-5 w-100 d-flex  justify-center">
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
                </div> -->
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

  methods:{
    
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    actionModal(action){
      this.$emit('actionModal',action)
    }
  }


};

</script>