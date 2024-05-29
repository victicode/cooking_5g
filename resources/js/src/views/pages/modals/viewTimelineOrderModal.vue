<script setup>
import moment from 'moment';
const props = defineProps({
  order: Object,
})
</script>

<template>
  <div class="modal animate__animated animate__slideInLeft" id="timeLineOrder" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column pa-2 px-3 pa-md-5 ">
                <VRow  class="mb-2 ma-0">
                  <VCol
                    cols="12"
                    class="py-0"
                  >
                    <div class="my-md-4 my-2 text-center">
                      <h2>Linea de tiempo Orden #{{ orderNumberFormat(order.id) }}</h2>
                      <h3 class="mt-2">
                        <v-chip :class="{'bg-error': order.status == 0, 'bg-warning': order.status == 1, 'bg-secondary': order.status == 2, 'bg-success': order.status == 3, }">
                          {{ order.status_label.status }}
                        </v-chip>

                      </h3>
                    </div>
                  </VCol>
                  <VCol
                    cols="12"
                    class="px-md-10 px-0 overflow-scroll relative position-relative py-10 my-10"
                    style=""
                  >
                  <div class="py-10 ">

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