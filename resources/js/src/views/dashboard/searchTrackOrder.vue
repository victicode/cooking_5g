<script setup>
import moment from 'moment';
import { SEARCH_BY_TRACKER} from "@/core/services/store/order.module";
import * as bootstrap from 'bootstrap';
import { mapGetters } from 'vuex';
import viewTimelineOrderModal from '@/views/pages/modals/viewDetailTimeline.vue';
import OrderProductsTables from '@/views/pages/tables/OrderProductsTables.vue';

</script>

<template>
  <div>

    <VCard title="Buscar orden" class="searchOrderTracker">
      <VCardText class="px-md-4 px-2 py-1">
        <VRow class="ma-0  justify-center align-center justify-md-start pa-0 pb-2 px-0 mb-0 mb-md-2">
          <VCol cols="7" md="6" class="form-group pa-1">
            <VTextField
              placeholder="Buscar por Track ID"
              label="Buscar por Track ID"
              type="text"
              ref="tracker"
              v-model="tracker"
              name="tracker_id"
            />
          </VCol>
          <VCol cols="5" md="3" class="my-0 py-0">
            <VBtn
              color="white"
              class="bg-primary text-white w-100 mx-0 mx-md-5 my-2"
              @click="searchOrder()"
            >
              <span class="">Buscar</span>
            </VBtn>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
    <div v-if="Object.keys(selectedOrder).length > 0" >

      <viewTimelineOrderModal :order="selectedOrder" @actionModal="modalAction"></viewTimelineOrderModal>

      <div class="modal animate__animated animate__fadeInDown" id="productsInOrder" tabindex="-1" aria-labelledby="timeLineOrderLabel" aria-hidden="true">
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
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideInternalModal()" ></v-btn>
                    </v-col>
                  </div>
                  <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                    <VRow  class="mb-2 ma-0">
                      <VCol cols="12">
                        <div class="my-md-4 my-2 text-center">
                          <h2 class="d-none d-md-block">Productos Orden #{{ orderNumberFormat(selectedOrder.id) }}</h2>
                          <h3 class="d-block d-md-none">Productos Orden #{{ orderNumberFormat(selectedOrder.id) }}</h3>
                          <h3 class="mt-2">
                            <v-chip :class="{'bg-error': selectedOrder.status == 0, 'bg-warning': selectedOrder.status == 1, 'bg-secondary': selectedOrder.status == 2, 'bg-success': selectedOrder.status == 3, }">
                              {{ selectedOrder.status_label.status }}
                            </v-chip>

                          </h3>
                        </div>
                      </VCol>
                      <VCol cols="12">
                        <OrderProductsTables :recipes="selectedOrder.recipes"  />
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
    </div>
  </div>
</template>

<style lang="scss" >
  .card-list {
    --v-card-list-gap: 1.6rem;
  }
  .searchOrderTracker > .v-card-item{
    padding: 10px 1rem;
  }
  h5{
    font-weight: 500;
  }
</style>
<script>
  export default {
    data(){
      return{
        selectedOrder:[],
        tracker:'',
        modal:'',
        interModal:''
      }
    },
    methods:{
      searchOrder(){
        this.$store
        .dispatch(SEARCH_BY_TRACKER,this.tracker)
        .then((data) => {
          if (data.code !==200) {
            console.log('ha ocurrido un error al intentar obtener las ordenes')  
            return
          }
          this.selectedOrder = data.data;
          this.tracker = '';
          setTimeout(() => {
            this.showModal('timeLineOrder')
          }, 200);
        })
        .catch((e) => {
          console.log(e)
        });
      },
      showModal(modal) {
        try {
          this.modal.hide()
        } catch (error) {
          
        }
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
      showInterModal(modal) {
        this.modal.hide()
        try {
          this.interModal.hide()
        } catch (error) {
          
        }
        this.interModal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.interModal.show()
      },
      hideModal(){
        this.modal.hide()
        setTimeout(() => {
          let trashElement = document.querySelectorAll('.modal-backdrop');
          trashElement.forEach((item)=>{
            document.querySelector('body').removeChild(item);
          })
        }, 200);
      },
      hideInternalModal() {
        
        this.interModal.hide()
        this.modal.show()
      },
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
      modalAction(action){
        if(action == 'showModal'){
          this.showInterModal('productsInOrder');
          return;
        }
        this.hideModal();
      }
    },
    mounted(){
      // this.getOrders()
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>
