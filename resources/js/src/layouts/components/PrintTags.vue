<script setup >
  import moment from 'moment';
  import {GET_RECIPE_BY_SEARCH} from "@/core/services/store/recipe.module";
  import debounce from 'debounce';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
</script>
<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="printModule" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                  <VRow  class="mb-2 ma-0 mt-10 mt-md-0">
                    <VCol
                      cols="12"
                      class="py-0"
                    >
                      <div class="my-md-4 my-2 text-center">
                        <h2>Imprimir etiquetas</h2>
                      </div>
                    </VCol>
                  </VRow>
                  <VDivider  />
                  <VForm  id="tag_recipe_form" class="mt-5">
                    <VRow v-for="(tag, index) in tagsToPrint"  v-bind:key="index" class="mb-1 pb-1 border-b-md border-dotted border-opacity-50 border-primary">
                      <VCol cols="6" class="form-group ">
                        <v-autocomplete
                          :model-value="tag.recipe.id"
                          :items="recipeToPrint[index] ?  recipeToPrint[index] : tag.recipe.id !== '' ? [ {id: tag.recipe.id, title: tag.recipe.title,}] : []"
                          :label="`Receta ${(index+1)}`"
                          :name="`recipe_to_print_${(index+1)}`"
                          item-title="title"
                          item-value="id"
                          variant="outlined"
                          persistent-hint
                          no-filter
                          return-object
                          clearable
                          no-data-text="No se encontraron resultados"
                          @click="searchRecipeToPrint($event,index )"
                          @keyup="searchRecipeToPrint($event,index )"
                          @click:clear="clearProductSearch(index)"
                          @update:modelValue="selectedRecipe($event, index)"
                        ></v-autocomplete>
                      </VCol>
                      <VCol cols="6" md="6" class="form-group">
                        <VTextField
                          placeholder="Cantidad"
                          label="Cantidad"
                          type="number"
                          autocomplete="off"
                          v-model="tag.quantity"
                          
                        />
                      </VCol>
                      <VCol cols="6" md="6" class="form-group">
                        <VTextField
                          placeholder="Fecha de elaboración"
                          label="Fecha de elaboración"
                          type="text"
                          autocomplete="off"
                          v-model="tag.created"
                          :id="'tag_created_'+index"
                          @change="plusMonths(index)"
                        />
                      </VCol>
                      <VCol cols="6" md="6" class="form-group">
                        <VTextField
                          placeholder="Consumir antes de"
                          label="Consumir antes de"
                          type="text"                      
                          autocomplete="off"
                          v-model="tag.consumo"
                          :id="'tag_consumo_'+index"
                        />
                      </VCol>
                    </VRow>
                  </VForm>
                  <div class="mt-5 w-100  d-block justify-center">
                    <VRow class="mb-2">
                      <VCol cols="12" md="6"  class="form-group">
                        <VTextField
                          placeholder="Iniciar en posicion"
                          label="Iniciar en posicion"
                          type="number"
                          autocomplete="off"
                          v-model="init"
                          
                        />
                      </VCol>
                      <VCol cols="12"  class="form-group px-5">
                        <div class="mb-2 mt-2">
                          <div class="stock-notify px-5 py-4">
                            <p class="text-secondary pa-0 ma-0">
                              <b class="text-primary text-decoration-underline">Recomendaciones de impresion de etiquetas:</b> 
                            </p>
                            <p class="text-secondary pa-0 ma-0 mt-2">
                              <b class=""> • Margen superior: 8,5mm</b> 
                            </p>
                            <p class="text-secondary pa-0 ma-0 mt-2">
                              <b class=""> • Margen derecho: 0mm</b> 
                            </p>
                            <p class="text-secondary pa-0 ma-0 mt-2">
                              <b class=""> • Margen inferior: 8,5mm</b> 
                            </p>
                            <p class="text-secondary pa-0 ma-0 mt-2">
                              <b class=""> • Margen izquierdo: 0mm</b> 
                            </p>
                            <p class="text-secondary pa-0 ma-0 mt-2">
                              <b class=""> • Tamaño de papel: A4</b> 
                            </p>
                            <p class="text-secondary pa-0 ma-0 mt-2">
                              <b class=""> •  Escala: 63%</b> 
                            </p>
                            <p class="text-primary text-decoration-underline font-weight-bold pa-0 ma-0 mt-2 cursor-pointer" @click="viewGuide()"> 
                              Ver Guia
                            </p>
                          </div>
                        </div>
                      </VCol>
                    </VRow>
                    <VCardActions class=" justify-center w-100 d-md-flex  pb-1 pb-md-3   d-block">
                      <VBtn
                        color="white"
                        class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
                        id="create_order_of_cart_button"
                        @click="printTags()"
                      >
                        <span class="">Imprimir</span>
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
    <form id="TheForm" method="post" action="/api/recipes/client/print/multiple" target="PRINT">
      <input type="hidden" name="recipes" id="validateTags" />
      <input type="hidden" name="init" id="initPosition" />
    </form>
  </div>
</template>
<style lang="scss">

</style>
<script>

  export default {
    data: () => ({
      recipeToPrint:[],
      printDates: [],
      tagsToPrint:[
        {
          recipe:{
            id:'',
            title:'',
          },
          quantity:'',
          created:'',
          consumo:'',
        }
      ], 
      init: 0,
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:2000,
    }),
    methods:{
      getRecipeToPrint(search = "", index){
        this.$store
          .dispatch(GET_RECIPE_BY_SEARCH, search)
          .then((response) => {
            this.recipeToPrint[index] = response.data
          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

      },
      viewGuide(){
        this.emitter.emit('showOnGuide')
      },
      searchRecipeToPrint(e, index){ 
        debounce(this.getRecipeToPrint, 200)(e.target.value, index)
      },
      clearProductSearch(index){
        this.tagsToPrint[index].recipe = {
            id:'',
            title:'',
        }
        this.getRecipeToPrint('',index)
      },
      addOtherRecipeToPrint(){
        let otherRecipe = {
          recipe:{
            id:'',
            title:'',
          },
          quantity:'',
          created:'',
          consumo:'',
        }
        if (this.tagsToPrint.filter((tag)=> tag.recipe.id !=='' ).length == this.tagsToPrint.length) {
          this.tagsToPrint.push(otherRecipe)
        }
      },
      selectedRecipe(e,index){

        if(!e) return
        this.tagsToPrint[index].recipe = e
        this.addOtherRecipeToPrint()
        setTimeout(() => {
          this.initPrintDate(index+1)
        }, 1000);
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      hideModal(){
        this.$emit('hide')
      },
      initPrintDate(id){
        let dates = {
          tag_created: flatpickr(document.getElementById('tag_created_'+id), {
            dateFormat: 'd/m/Y',
            locale: Spanish,
            disableMobile:true,
            onClose: function (selectedDate) {
              document.getElementById('tag_created_'+id).value = moment(selectedDate[0]).format('DD-MM-YYYY')
            }
          }),
          tag_consumo: flatpickr(document.getElementById('tag_consumo_'+id), {
            dateFormat: 'd/m/Y',
            locale: Spanish,
            disableMobile:true,
            onClose: function (selectedDate) {
              document.getElementById('tag_consumo_'+id).value = moment(selectedDate[0]).format('DD-MM-YYYY')
            }
          }),
        }

        this.printDates.push(dates)
      },
      printTags(){
        let validTags = this.tagsToPrint.filter((tag)=> tag.recipe.id !=='' )
        if(validTags.length > 0){
          var ventana = window.open(this.printUrl(), 'PRINT', 'height=400,width=600');
          this.sendUrlData(validTags)

          setTimeout(() => {
            ventana.document.close();
            ventana.focus();
            ventana.print();
            // ventana.close();
            return true;
          }, 2500);
          return
        }
       this.showSnackbar('error','Listado vacio')
      },
      plusMonths(index){
        let date = moment(this.printDates[index].tag_created.selectedDates[0]).add(3, 'months').format('DD-MM-YYYY')
        this.printDates[index].tag_consumo.setDate(date)
        this.printDates[index].tag_consumo.set('minDate', date)

        this.tagsToPrint[index].consumo = date
      },
      printUrl(){
        return `/api/recipes/client/print/multiple`
      },
      sendUrlData(validTags){
        document.getElementById('validateTags').value = JSON.stringify(validTags);
        document.getElementById('initPosition').value = this.init;
        document.getElementById('TheForm').submit()
      }
      
    },
    mounted(){
      setTimeout(() => {
        
        this.initPrintDate(0)
      }, 2000);
    },
    created(){
    }
    
  };
</script>