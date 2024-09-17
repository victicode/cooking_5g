<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="showGuide" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column  ">
                  <VCardItem class="justify-center w-100  py-md-6  py-4 pb-0   ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1 mt-5">Guía de impresión</div>
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <div>
                    <VCardItem class="w-100  py-md-0  py-4 text-center px-0" >
                      <VCardTitle class="text-2xl font-weight-bold">
                        <img :src="guideImg" alt="" style="width: 109%; transform: translateX(-7.5%);">
                      </VCardTitle>
                    </VCardItem>
                    <div class="w-100  py-md-0 py-md-4 d-flex items-center mt-md-8  justify-md-center px-2 pa-md-5"  style="flex-wrap: wrap;">
                      <div class="d-flex items-center mx-md-10 mt-md-0 mt-4 mx-2" >
                        <div class="box_legend bg-margen"></div>
                        <div class="ms-1 text-black">Margenes</div>
                      </div>
                      <div class="d-flex items-center mx-md-10 mt-md-0 mt-4 mx-2" >
                        <div class="box_legend bg-paper"></div>
                        <div class="ms-1 text-black">Tamaño de papel</div>
                      </div>
                      <div class="d-flex items-center mx-md-10 mt-md-0 mt-4 mx-2" >
                        <div class="box_legend bg-blue"></div>
                        <div class="ms-1 text-black">Escala</div>
                      </div>

                    </div>
                  </div>
                </div>
              </VCard>
            </VCol>
          </VCol>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import guideImg from '@/assets/images/guia/guia.png'
  import * as bootstrap from 'bootstrap'
  export default {
    data(){
      return{
        modal: false,
        guideImg,
      }
    },
    methods:{
      showModal() {
        try {
          this.modal.hide()
        } catch (error) {
          
        }
        this.modal = new bootstrap.Modal(document.getElementById('showGuide'), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
      hideModal(){
        this.modal.hide()
      },
    },
    created(){
      this.emitter.on("showOnGuide", () => {
        this.showModal()
      })
    }

  };
</script>
<style lang="scss" scoped>
.box_legend{
  height: 20px;
  width: 20px;
  border-radius: 2px;
}
.bg-{
  &margen{
    background: #ed1c24;
  }
  &paper{
    background: #22b14c;
  }
  &blue{
    background: #00a2e8;
  }
}
</style>
