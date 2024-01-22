<script setup>
import moment from 'moment';
import OutOrderProductsTables from '@/views/pages/tables/OutOrderProductsTables.vue';
const props = defineProps({
  order: Object,
})
const as = window

</script>
<template>
  <div class="modal animate__animated animate__fadeInDown" id="viewOutOrder" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
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
                      <h2>Orden de salida</h2>
                      <h3 class="mt-2">
                        <v-chip :class="{'bg-error': order.status == 0, 'bg-warning': order.status == 1, 'bg-secondary': order.status == 2, 'bg-success': order.status == 3, }">
                          {{ order.status_label.status }}
                        </v-chip>

                      </h3>
                    </div>
                    <div class="my-3 my-md-0  d-block d-md-none text-center text-md-end ">
                      <h4 class="font-400">
                        Orden asociada N°: 
                        <b>
                          {{ orderNumberFormat(order.id) }}
                        </b> 
                      </h4>
                      <h4 class="font-400" style="border-bottom: 1px solid #474747">
                        Salida N°: 
                        <b>
                          {{ orderNumberFormat(order.out_order.id) }}
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
                    <div class="mt-md-4 my-0  d-none d-md-flex flex-column align-end text-md-end ">
                      <h4 class="font-400 w-50 pb-1" style="border-bottom: 1px solid #474747">
                        Salida N°: 
                        <b>
                          {{ orderNumberFormat(order.out_order.id) }}
                        </b> 
                      </h4>
                      <h4 class="font-400 w-50 mt-2">
                        Orden asociada N°: 
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
                      <div class="d-flex justify-md-end justify-start mt-4 mb-4">
                      <v-btn append-icon="icon-park-twotone:history-query"  rounded="xs" color="secondary" variant="outlined" @click="actionModal('show:history')">
                        Imprimir
                      </v-btn>
                    </div>
                    </div>
                  </VCol>
                </VRow>
                <div class="w-100">
                  <OutOrderProductsTables :products="order.out_order.products" />
                </div>
                <VDivider  />
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
      this.$emit('actionModal','hiddenModal')
    }
  }


};

</script>