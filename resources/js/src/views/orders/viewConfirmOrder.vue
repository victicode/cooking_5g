<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="confirmOrder" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <VRow  class="mb-2 ma-0 mt-15">
                  <VCol
                    cols="12"
                    class="py-0"
                  >
                    <div class="my-md-4 my-2 text-center">
                      <h2>Finalizar Orden #{{ orderNumberFormat(order.id) }}</h2>
                      <h3 class="mt-2">
                        <v-chip :class="{'bg-error': order.status == 0, 'bg-warning': order.status == 1, 'bg-secondary': order.status == 2, 'bg-success': order.status == 3, }">
                          {{ order.status_label.status }}
                        </v-chip>

                      </h3>
                    </div>
                  </VCol>
                  <VCol
                    cols="12"
                    class="px-md-10 px-0 text-center"
                    style=""
                  >
                    <h2>¿Desea confirmar la recepción de la orden #{{orderNumberFormat(order.id)}}?</h2>
                  </VCol>
                </VRow>
                  

                <VDivider  />
                <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                  <VCardActions class=" justify-center w-100 d-flex ">
                    <VBtn
                      color="white"
                      class="bg-success text-white w-50 mx-0 mx-md-5 my-2"
                      @click="orderChangeStatus()"
                      :loading="loading"
                      v-if="order.status == 1 || order.status == 2"
                    >
                      <span class="">Confirmar</span>
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
import { CHANGE_STATUS } from "@/core/services/store/order.module";
import loadingButton from "@/layouts/components/loadingButton.vue";

export default {
  props:['order'],
  components: {
    loadingButton,
  },
  data: () => ({
    loading: false,
    snackShow: false,
    snackMessage:'',
    snackType:'',
    snacktimeOut:5000,
  }),
  methods: {
    orderNumberFormat(id){
      return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    hideModal(){
      this.$emit('hiddenModal')
    },
    orderChangeStatus(){
      this.loadingState(true)
      const dataForm = { id:this.order.id, newStatus: '3' }

      this.$store
        .dispatch(CHANGE_STATUS, dataForm)
        .then((response) => {
          this.hideModal()
          this.showSnackbar('success', 'Orden completada exitosamente') 
          this.loadingState(false)
        })
        .catch((err) => {
          console.log(err)
          this.hideModal()
          this.showSnackbar('error', err )
          this.loadingState(false)
        })

    },
    loadingState(state){
      this.loading = state
    },
    showSnackbar(type, messagge){
      this.snackShow = true;
      this.snackType = type
      this.snackMessage = messagge
    },
  }

}
</script>