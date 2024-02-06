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
          <v-tooltip text="Agregar nuevo ingrediente de cooking 5g">
              <template v-slot:activator="{ props }">
                <v-col cols="auto" class="">
                  <VBtn v-bind="props" :color=" isValidateSteps ? 'success' : 'primary' "  class="w-100" @click="showModal('Pasos')" >
                    <VIcon icon="bx-plus"/>
                    Agregar pasos a seguir
                  </VBtn>
                </v-col>
              </template>
            </v-tooltip>
        </VCol>
        <VCol cols="12" md="4" class="mt-0 py-0 px-0 mb-4">
          <v-tooltip text="Agregar nuevo ingrediente">
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
            <VBtn  color="secondary" class="w-100" @click="backStep()" >
              <span class="d-block d-md-hidden ">
                <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
              </span>
              <span class="d-md-block d-none " >Volver</span>
  
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
    <div class="modal animate__animated animate__fadeInDown me-0" id="Pasos" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        
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
              <VCard class="modal__content" style="box-shadow: 0px 10px 20px 0px #272727;">
                <div class="modal__close-button" >
                  <v-col class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                <div class="d-flex justify-space-between  flex-column pa-5 pa-md-5 ">
                  <VRow  class="mb-2 ma-0">
                    <VCol
                      cols="12"
                      class="py-0 mt-3"
                    >
                      <div class="my-md-4 my-2 text-center">
                        <h2>Añadir nuevo paso seguir</h2>
                      </div>
                    </VCol>
                    <VCol cols="12"  class="mt-0 py-0 px-0 mb-4"> 
                      <VRow v-for="(item, index) in preparation.steps" :key="index" class="position-realative relative mt-7" >
                        <VCol cols="12" class="form-group">
                          <VTextField
                            placeholder="EJ: Preparación del pollo"
                            :label="`Titulo del paso ${(index+1)}`"
                            type="text"
                            :name="`new_recipe_product_${(index+1)}`"
                            v-model="item.title"
                            autocomplete="off"
                          />
                        </VCol>
                        <VCol cols="12" class="form-group">
                          <v-textarea
                            label="Descripción de este paso"
                            auto-grow
                            variant="outlined"
                            rows="10"
                            row-height="25"
                            shaped
                            name="new_product_description"
                            v-model="item.description"
                          ></v-textarea>
                        </VCol>
                        <div class="form-group pa-0 mb-md-5  small-delete-product-button_recipe ">
                          <v-tooltip text="Receta">
                            <template v-slot:activator="{ props }">
                              <v-col cols="auto" class="pa-0">
                                <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeStepRecipe(index)"></v-btn>
                              </v-col>
                            </template>
                          </v-tooltip>
                        </div>
                      </VRow>
                    </VCol>
                    
                  </VRow>
                  <VDivider  />
                  <VRow class="mt-0  d-flex  d-block justify-center">
                    <VCol col="12" md="6" class=" ">
                      <VBtn
                        color="white"
                        class="bg-secondary text-white w-100 mx-0 mx-md-5 my-2"
                        @click="addNewStepRecipe()"
                        
                      >
                      <VIcon icon="bx-plus"/>Agregar nuevo paso
                      </VBtn>
                    </VCol>
                    <VCol col="12" md="6" class=" ">
                      <VBtn
                        color="white"
                        class="bg-primary text-white w-100 mx-0 mx-md-5 my-2"
                        @click="validatorSteps()"
                      >
                        <span class="">Guardar</span>
                      </VBtn>
                    </VCol>
                  </VRow>
                </div>
              </VCard>
            </VCol>
          </VCol>
        </div>
      </div>
    </div>
    <div class="modal animate__animated animate__fadeInDown me-0" id="Video" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        
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
              <VCard class="modal__content" style="box-shadow: 0px 10px 20px 0px #272727;">
                <div class="modal__close-button" >
                  <v-col class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                <div class="d-flex justify-space-between  flex-column pa-5 pa-md-5 ">
                  <VRow  class="mb-2 ma-0">
                    <VCol
                      cols="12"
                      class="py-0 mt-3"
                    >
                      <div class="my-md-4 my-2 text-center">
                        <h2>Añadir video de la receta</h2>
                      </div>
                    </VCol>
                    <VCol
                      cols="12"
                      class="px-md-10 px-0 text-center"
                      style=""
                    >
                      <div class="" id="app">
                        <v-file-input
                          chips
                          label="Sube tu video aquí"
                          variant="outlined"
                          ref="recipe_video"
                          :prepend-icon=" !preparation.video ?  'ic:round-attach-file' : 'icon-park-outline:check-one' "
                        ></v-file-input>
                      </div>
                    </VCol>
                  </VRow>
                    
                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  d-flex">
                      <VBtn
                        color="white"
                        class="bg-primary text-white w-50 mx-0 mx-md-5 my-2"
                        @click="addVideo()"
                      >
                        <span class="">Agregar</span>
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

  data: () => ({
    modal: '',
    isValidateSteps:false,
    preparation: {
      video: null,
      steps: [
        { 
          title:'',
          description:''
        }
      ]
    },
  }),
  methods:{
    addVideo(id){
      this.preparation.video = this.$refs.recipe_video.files[0]
      this.hideModal()
    },
    addNewStepRecipe(){
      let newStep = {
        title:'',
        description:''
      }

      this.preparation.steps.push(newStep)
    },
    validatorSteps(){

      if( this.preparation.steps.length == 1 ) return this.isValidateSteps = false

      this.hideModal()
      return this.isValidateSteps = true
      
      
    },
    removeStepRecipe(index){
        setTimeout(() => {
          try{
            this.preparation.steps.splice(index, 1)
          }catch(e){

          }
        }, 200);
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




















