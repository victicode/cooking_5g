<script setup>
import moment from 'moment';
const props = defineProps({
  order: Object,
})
</script>

<template>
  <div class="modal animate__animated animate__fadeInDown" id="timeLineOrder" tabindex="-1" aria-labelledby="timeLineOrderLabel" aria-hidden="true">
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
              <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                <VRow  class="mb-2 ma-0">
                  <VCol cols="12">
                    <div class="my-md-4 my-2 text-center">
                      <h2 class="d-none d-md-block">Linea de tiempo Orden #{{ orderNumberFormat(order.id) }}</h2>
                      <h3 class="d-block d-md-none">Linea de tiempo Orden #{{ orderNumberFormat(order.id) }}</h3>
                      <h3 class="mt-2">
                        <v-chip :class="{'bg-error': order.status == 0, 'bg-warning': order.status == 1, 'bg-secondary': order.status == 2, 'bg-success': order.status == 3, }">
                          {{ order.status_label.status }}
                        </v-chip>

                      </h3>
                    </div>
                  </VCol>
                  <VCol
                    cols="12"
                    md="7"
                    class="py-0"
                  >
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
                    md="5"
                    class="py-0"
                  >
                    <div >
                      <div class="my-2  text-start text-md-end">
                        Fecha de pedio: <a class="text-secondary fw-600">{{ moment(order.created_at).format('DD/MM/YYYY HH:mm:ss') }}</a>
                      </div>
                      <div class="my-2  text-start text-md-end">
                        Tracker ID: <a class="text-secondary fw-600">{{ order.trancker }}</a>
                      </div>
                      <div class="my-2  text-start text-md-end text-decoration-underline text-primary" @click="showModal()">
                        Cantidad de productos: <a class="text-primary fw-600 ">{{ order.recipes_count }}</a>
                      </div>
                      
                    </div>
                    <div class="d-flex justify-md-end justify-space-between mt-4 mb-4">
                      <div v-if="order.out_order">
                        <a :href="url+'api/out_order/print/' + order.out_order.id +'?token='+token"  target="_blank" rel="noopener noreferrer">
                          <v-btn append-icon="carbon:delivery"  class="d-none d-md-flex mx-2" rounded="xs" variant="outlined">
                            Orden de salida
                          </v-btn>
                        </a>
                        <a :href="url+'api/out_order/print/' + order.out_order.id +'?token='+token"  target="_blank" rel="noopener noreferrer">
                          <v-btn append-icon="carbon:delivery"  class="d-flex d-md-none" rounded="xs" variant="outlined">
                            Ver salida
                          </v-btn>
                        </a>
                      </div>
                    </div>
                  </VCol>
                </VRow>
                <VRow  class="mb-2 ma-0">
                  <VCol
                    cols="12"
                    class="py-0"
                  >
                  </VCol>
                  <VCol
                    cols="12"
                    class="px-md-10 px-0 overflow-scroll relative position-relative py-10 "
                    style=""
                  >
                    <div class="my-8">
                      <div class="">
                        <div class="timeline__content">
                            <div class="d-flex justify-center align-center timeline__content--items">
                              <div class="timeline__divider"></div>
                              <div class="timeline__content--item-center bg-primary">
                                <div class="timeline__content--item-text up">
                                  <h3>
                                    Orden creada
                                  </h3>
                                </div>

                                <v-icon size="large" color="white" icon="ic:outline-pending-actions"></v-icon>  
                                
                                <div class="text-h6 timeline__content--item-text down">
                                  <h4>
                                    {{  moment(order.created_at).format('DD/MM/YYYY') }}
                                  </h4>
                                </div>
                              </div>
                              <div class="timeline__divider"></div>
                            </div>
                            <div class="d-flex justify-center align-center timeline__content--items" v-if="order.status >= 1 ">
                              <div class="timeline__divider"></div>
                              <div class="timeline__middle">
                                <input type="checkbox" value="2" id="in-transit" name="newStatus" ref="newStatus" class="d-none" v-if="order.status == 1"  @input="validateChangeStatus()">
                                <label for="in-transits">
                                  <div class="timeline__content--item-center " :class="order.status > 1 ? 'bg-primary' :'bg-unavailable'">
                                    <div class="timeline__content--item-text up" :class="{'opacity-0' : order.status == 1 } " >
                                      <h3>
                                        En transito
                                      </h3>
                                    </div>
      
                                    <v-icon size="large" color="white" icon="carbon:delivery-parcel"></v-icon>
                                    
                                    <div class="text-h6 timeline__content--item-text down" v-if="order.status >= 2">
                                      <h4>
                                        {{  moment(order.out_order.created_at).format('DD/MM/YYYY') }}
                                      </h4>
                                    </div>
                                  </div>
                                </label>
                              </div>
                              <div class="timeline__divider"></div>
                            </div>
                            <div class="d-flex justify-center align-center timeline__content--items" v-if="order.status >= 1" >
                              <div class="timeline__divider"></div>
                              <div class="timeline__middle">
                                <input type="checkbox" value="3" id="complete" name="newStatus" ref="newStatus" class="d-none" v-if="order.status == 2"  @input="validateChangeStatus()">
                                <label for="completes">
                                  <div class="timeline__content--item-center " :class="order.status > 2 ? 'bg-primary' :'bg-unavailable'">
                                    <div class="timeline__content--item-text up" :class="{'opacity-0' : order.status <= 2 } " >
                                      <h3>
                                        Orden entregada
                                      </h3>
                                    </div>
                                    <v-icon size="large" color="white" icon="line-md:confirm-circle"></v-icon>
                                    <div class="text-h6 timeline__content--item-text down" v-if="order.status == 3">
                                      <h4>
                                        {{  moment(order.updated_at).format('DD/MM/YYYY') }}
                                      </h4>
                                    </div>
                                  </div>
                                </label>
                              </div>
                              <div class="timeline__divider"></div>
                            </div>
                            <div class="d-flex justify-center align-center timeline__content--items" v-if="order.status == 0" >
                              <div class="timeline__divider"></div>
                              <div class="timeline__content--item-center bg-error">
                                
                                <div class="timeline__content--item-text up"  >
                                      <h3>
                                        Orden Cancelada
                                      </h3>
                                    </div>
                                    <v-icon size="large" icon="ic:outline-cancel"></v-icon>
                                    <div class="text-h6 timeline__content--item-text down">
                                      <h4>
                                        {{  moment(order.updated_at).format('DD/MM/YYYY') }}
                                      </h4>
                                    </div>
                              </div>
                              <div class="timeline__divider"></div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </VCol>
                </VRow>
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
  data: () => ({
    token: localStorage.getItem('id_token'),
    url:import.meta.env.VITE_VUE_APP_BACKEND_URL,
  }),
  methods:{
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    hideModal(){
      this.$emit('actionModal','hiddenModal')
    },
    showModal(){
      this.$emit('actionModal','showModal')
    }
  }


};

</script>