<script setup>
import moment from 'moment';
import * as bootstrap from 'bootstrap'
import { GET_PRODUCT_BY_SEARCH } from "@/core/services/store/product.module";
import debounce from 'debounce';
</script>
<template class="d-block">
  <VRow>

    <VForm  id="new_recipe_form_3">
      <VRow 
      class="ma-0 pa-0  mt-4 align-center" 
      >
        <VCol cols="12" class="form-group">
          <h3>Añadir Preparación</h3>
        </VCol>
        <VCol cols="12" md="5" class="mt-0 py-0 px-0 mb-4">
          <v-tooltip text="Agregar nuevo despiece">
              <template v-slot:activator="{ props }">
                <v-col cols="auto" class="">
                  <VBtn v-bind="props" color="primary" class="w-100" @click="showModal('Pasos')" >
                    <VIcon icon="bx-plus"/>
                    Agregar pasos a seguir
                  </VBtn>
                </v-col>
              </template>
            </v-tooltip>
        </VCol>
        <VCol cols="12" md="4" class="mt-0 py-0 px-0 mb-4">
          <v-tooltip text="Agregar nuevo despiece">
              <template v-slot:activator="{ props }">
                <v-col cols="auto" class="">
                  <VBtn v-bind="props" color="primary" class="w-100" @click="showModal('Video')" >
                    <VIcon icon="bx-plus"/>
                    Agregar video
                  </VBtn>
                </v-col>
              </template>
            </v-tooltip>
        </VCol>
      </VRow>
      <VRow class="ma-0 pa-0  mt-8 align-center">
        <VCol cols="5" md="4"  class="mt-0 py-0 px-0">
          <v-col cols="auto" class="">
            <VBtn  color="secondary" class="w-100"  >
              <span class="d-block d-md-hidden ">
                <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
              </span>
              <span class="d-md-block d-none " @click="backStep()">Volver</span>
  
            </VBtn>
          </v-col>
        </VCol>
        <VCol cols="5" md="4" offset="2" offset-md="4" class="mt-0 py-0 px-0">
          <v-col cols="auto" class="">
            <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_recipe_form_3_button"> Guardar</VBtn>
          </v-col>
        </VCol>
      </VRow>
  
    </VForm>
    <div class="modal animate__animated animate__fadeInDown" id="Pasos" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        
      <div class="modal-dialog modal-lg mt-10">
        <div class="modal-content">
          <VCol
            cols="12"
            class="pa-0 d-flex justify-center"
            style="position: relative;"
          >
            <VCol
              cols="12"
              class="px-2"  
            >
              <VCard class="modal__content">
                <div class="modal__close-button" >
                  <v-col class="pa-0 pe-4">
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
                        <h2>Eliminar Receta</h2>
                      </div>
                    </VCol>
                    <VCol
                      cols="12"
                      class="px-md-10 px-0 text-center"
                      style=""
                    >
                      <h2>¿Seguro que deseas eliminar <b class="text-primary">dd</b>?</h2>
                    </VCol>
                  </VRow>
                    
                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  d-flex">
                      <VBtn
                        color="white"
                        class="bg-error text-white w-50 mx-0 mx-md-5 my-2"
                        @click="deleteProduct()"
                      >
                        <span class="">Eliminar</span>
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
    <div class="modal animate__animated animate__fadeInDown" id="Video" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        
      <div class="modal-dialog modal-lg mt-10">
        <div class="modal-content">
          <VCol
            cols="12"
            class="pa-0 d-flex justify-center"
            style="position: relative;"
          >
            <VCol
              cols="12"
              class="px-2"  
            >
              <VCard class="modal__content">
                <div class="modal__close-button" >
                  <v-col class="pa-0 pe-4">
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
                        <h2>Eliminar Receta</h2>
                      </div>
                    </VCol>
                    <VCol
                      cols="12"
                      class="px-md-10 px-0 text-center"
                      style=""
                    >
                      <h2>¿Seguro que deseas eliminar <b class="text-primary">dd</b>?</h2>
                    </VCol>
                  </VRow>
                    
                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  d-flex">
                      <VBtn
                        color="white"
                        class="bg-error text-white w-50 mx-0 mx-md-5 my-2"
                        @click="deleteProduct()"
                      >
                        <span class="">Eliminar</span>
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
  </VRow>
</template>
<script>
export default {

  methods:{
    data: () => ({
      modal: '',
      newRecipe:{
        preparation: []
      },
    }),
    addPreparation(id){
      console.log(id)
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
    hideModal(){
      this.modal.hide()
    },
    backStep(){
      this.$emit('backStep')
    },
  }


};

</script>




















