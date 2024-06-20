<template>
  <div class="modal animate__animated animate__fadeInDown" id="cancelOrder" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                  <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                </v-col>
              </div>
              <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                <VRow  class="mb-2 ma-0">
                  <VCol
                    cols="12"
                    class="py-0"
                  >
                    <div class="my-md-4 my-2 text-center">
                      <h2>Cancelar orden #{{ orderNumberFormat(selectedOrder.id) }}</h2>
                      <h3 class="mt-2">
                        <v-chip :class="{'bg-error': selectedOrder.status == 0, 'bg-warning': selectedOrder.status == 1, 'bg-secondary': selectedOrder.status == 2, 'bg-success': selectedOrder.status == 3, }">
                          {{ selectedOrder.status_label.status }}
                        </v-chip>

                      </h3>
                    </div>
                  </VCol>
                  <VCol
                    cols="12"
                    class="px-md-10 px-0 text-center"
                    style=""
                  >
                    <h2>¿Seguro que deseas cancelar la orden #{{orderNumberFormat(selectedOrder.id)}}?</h2>
                  </VCol>
                </VRow>
                  

                <VDivider  />
                <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                  <VCardActions class=" justify-center w-100 d-md-flex  d-block">
                    <VBtn
                      color="white"
                      class="bg-error text-white w-30 mx-0 mx-md-5 my-2"
                      @click="cancelOrder()"
                      v-if="selectedOrder.status == 1 || selectedOrder.status == 2"
                    >
                      <span class="">Cancelar</span>
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