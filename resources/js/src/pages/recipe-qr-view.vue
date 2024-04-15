<script setup >
  import * as bootstrap from 'bootstrap'
  import { mapGetters } from "vuex";  
  import { GET_RECIPE_BY_ID,} from "@/core/services/store/recipe.module";
  import recipeVideo from '@/views/pages/player/recipePlayer.vue';
  import { GET_PRODUCT_BY_SEARCH } from "@/core/services/store/product.module";
  import viewProductModal from '@/views/pages/modals/viewProductModal.vue';
  import moment from 'moment';

</script>

<template>
  <VRow class="d-flex flex-column align-center ma-0 " style="height: 100%;">
    <VCol v-if="ready" cols="12" class="d-flex flex-column align-center justify-top mt-0 px-0">


      <v-carousel
        :continuous="false"
        :show-arrows="false"
        :touch="true"
        hide-delimiter-background
        
        delimiter-icon="pepicons-pencil:square-filled"
        :height="'93%'"
        v-model="sliderPosition"
        
      >
        <v-carousel-item
          :value="1"
        >
          <VCard class="px-15 py-2 mb-5 text-center">
            <div>
              <h2>Recetario</h2>
              <h6>by: <b class="text-primary">Cooking 5G</b></h6>
            </div>
          </VCard>
          <VCard class="pa-5 text-center w-100 ">
            <div  class="d-flex flex-column align-center h-100">
              <div>
                <v-img
                  :width="199"
                  aspect-ratio="1/1"
                  cover
                  class="rounded" 
                  :src="'/'+selectedRecipe.image_url"
                ></v-img>
              </div>
              <div class="mt-2">
                <h2>
                  {{ selectedRecipe.title }}
                </h2>
                <h6 class="text-justify mt-1">{{ selectedRecipe.description }} </h6>
              </div>
            </div>
          </VCard>
        </v-carousel-item>
        <v-carousel-item
          :value="2"
        >
          <VCard class="pa-5 text-center w-100 mt-5" >
            <VRow  class="">
              <VCol cols="12" class="mt-">
                <div>
                  <h2>
                    {{ selectedRecipe.title }}
                  </h2>
                </div>
                <div class="border-t-md border-b-md border-dashed mt-5 py-1 border-primary">
                  <div class="d-flex my-1 justify-center">
                    <div class="d-flex align-center">
                      <VIcon icon="fluent-emoji-flat:timer-clock"  size="large" />
                      <h5 class=" my-1 ms-1 text-primary"> 
                        <b>{{selectedRecipe.total_time}} </b> 
                      </h5>
                    </div>
                    <div class="d-flex align-center ms-5">
                      <VIcon icon="fa6-solid:person"  size="large" />
                      <h5 class=" my-1 ms-1 text-primary"> 
                        <b>{{selectedRecipe.person_count}} {{ selectedRecipe.person_count == 1 ? 'Persona' : 'Personas' }} </b> 
                      </h5>
                    </div>
                    <div class="d-flex align-center ms-5">
                      <VIcon icon="twemoji:pot-of-food"  size="large" />
                      <h5 class=" my-1 ms-1 text-primary"> 
                        <b>{{selectedRecipe.type}}</b> 
                      </h5>
                    </div>
                  </div>
                </div>
                <div class="px-2">
                  <div class="border-md rounded mt-5 py-1 border-black">
                    <h4 class="text-center">Nuestros ingredientes:</h4>
                    <div class="my-1 px-5">
                      <div class="text-justify " v-for="(ingredient, index) in selectedRecipe.cooking_ingredients" v-bind:key="index">
                        <a class="text-decoration-underline text-subtitle-1 font-weight-bold text-primary" @click="selectProductView(index)">
                          - {{ ingredient.title }}
                        </a>
                      </div>  
                    </div>
                  </div>
                </div>
                <div class="px-2">
                  <div class="border-md rounded mt-5 py-1 border-black">
                    <h4 class="text-center">Otros ingredientes:</h4>
                    <div class="my-1 px-5">
                      <div class="text-justify " v-for="(ingredient, index) in selectedRecipe.ingredients" v-bind:key="index">
                        <h4 class=" font-weight-bold">
                          - {{ ingredient.name }}
                        </h4>
                      </div>  
                    </div>
                  </div>
                </div>
              </VCol>
            </VRow>
          </VCard>
        </v-carousel-item>
        <v-carousel-item
          v-for="(step, index) in selectedRecipe.preparation"
          v-bind:key="index"
          :value="index+3"
        >
          <VCard class="pa-5 text-center w-100 mt-5" >
            <VRow  class="">
              <VCol cols="12" class="">
                <div>
                  <h2>
                    {{ selectedRecipe.title }}
                  </h2>
                </div>
                <div class="border-t-md border-b-md border-dashed mt-5 py-1 border-primary">
                  <h3 class="text-center  ">Prepación - Paso {{ index+1 }}:</h3>
                  <h3 class="text-center mt-2 text-primary">{{ step.title}}:</h3>
                  
                </div>
                <div class="px-0 mt-5">
                  <div class="">
                    <h4 class="my-2 px-5 text-justify" >
                      {{ step.description }} 
                    </h4>
                  </div>
                </div>
              </VCol>
            </VRow>
          </VCard>
        </v-carousel-item>
        <v-carousel-item
          :value="selectedRecipe.preparation.length+3"
        >
          <VCard class="px-15 py-2 mb-5 text-center">
            <div>
              <h2>{{selectedRecipe.title}}</h2>
              <h6>by: <b class="text-primary">Cooking 5G</b></h6>
            </div>
          </VCard>
          <VCard class="pa-5 text-center w-100 ">
            <div  class="d-flex flex-column align-center h-100">
              <div>
                <v-img
                  :width="199"
                  aspect-ratio="1/1"
                  cover
                  class="rounded" 
                  :src="'/'+selectedRecipe.image_url"
                ></v-img>
              </div>
              <div class="mt-4">
                <h2>
                  ¡BUEN PROVECHO!👨‍🍳
                </h2>
                <h4 class="text-decoration-underline mt-5" @click="sliderPosition = 1">Volver</h4>
              </div>
            </div>
          </VCard>
        </v-carousel-item>
      </v-carousel>
    </VCol>
    <div v-if="Object.keys(selectedProductInRecipe).length > 2" >
      <viewProductModal :product="selectedProductInRecipe" @hiddenModal="hideModal" />
    </div>
  </VRow>
</template>
<style lang="scss" >
  .border-black{
    border-color:rgb(77 95 113) !important;
  }
</style>

<script>

  export default {
    data: () => ({
      ready:false,
      sliderPosition:1,
      updateInCart:false,
      isUser:false,
      modal: '',
      internalModal:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:1000,
      items:['editar','eliminar', 'ver'],
      drawer:false,
      selectedRecipe:{},
      selectedProductInRecipe:{},
      url:import.meta.env.VITE_VUE_APP_BACKEND_URL,
      printDates:{},
    }),
    methods:{
      nextStep(action){
        action=='new' 
         ? this.stepperNewProduct = this.stepperNewProduct + 1 
         : this.stepperUpdateProduct = this.stepperUpdateProduct + 1 

         action=='new' 
          ? setTimeout(() => {
            this.stepperNewProduct > 2 ? '' : this.validateFormItem('new_recipe_form_2')
          }, 500)
          : setTimeout(() => {
            this.stepperUpdateProduct > 2 ? '' : this.validateFormItem('update_recipe_form_2')
          }, 500)
    
      },
      backStep(action){
         action=='new' 
         ? this.stepperNewProduct = this.stepperNewProduct - 1 
         : this.stepperUpdateProduct = this.stepperUpdateProduct - 1 

         action=='new' 
          ? setTimeout(() => {
            this.stepperNewProduct >= 2 ? '' : this.destroyValidate('new_recipe_form_2')
          }, 500)
          : setTimeout(() => {
            this.stepperUpdateProduct >= 2 ? '' : this.destroyValidate('update_recipe_form_2')
          }, 500)
      
      },
      getProducts(search = "", index){
        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            this.productsForRecipe[index] = response.data
          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

      },
      maxStockRecipeInput(product){
        let minus = 0;
        product.cooking_ingredients.forEach((ingredient, index)=>{
          if(index==0) minus = ingredient.total_stock /parseFloat(ingredient.pivot.quantity)
  
          minus = minus > (ingredient.total_stock /parseFloat(ingredient.pivot.quantity) )
          ? ingredient.total_stock /parseFloat(ingredient.pivot.quantity) 
          : minus
          
        })
        console.log(minus.toFixed(0))
        return minus.toFixed(0)
      },

      selectRecipe(idAccount){
        this.$store
          .dispatch(GET_RECIPE_BY_ID, idAccount)
          .then((response) => {
            document.title = response.data.title
            this.selectedRecipe = Object.assign({}, response.data); 
            this.selectedRecipe.ingredients = JSON.parse(response.data.ingredients)
            this.selectedRecipe.preparation = JSON.parse(response.data.preparation)
            this.ready = true
          })
          .catch((err) => {
            console.log(err)
            return new Promise((resolve) => {
              resolve(false);
            });
          })
      },
      bootstrapOptions(){
        setTimeout(() => {
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
          const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
          const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
          const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
        }, 2000);
      },
      clearFilters(){
        document.querySelector('[name="chef_name"]').value = '';
        document.querySelector('[name="recipe_title"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showAction(id, modal){
        this.selectRecipe(id).finally((data)=>{
          
          setTimeout(() => {
            this.showModal(modal)
          }, 1000);
        })
      },
      showModal(modal) {
        try {
          this.hideModal()
        } catch (error) {
          
        }
        this.drawer = false
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        
        if(modal=='updateRecipe')this.validateFormItem('update_recipe_form')

        this.modal.show()

      },
      selectProductView(index){


        this.selectedProductInRecipe =  this.selectedRecipe.cooking_ingredients[index].lotes[0]
        this.selectedProductInRecipe.product = this.selectedRecipe.cooking_ingredients[index];
        setTimeout(() => {
          this.showModal('viewProduct')
        }, 800);
        

      },
      hideModal(action=""){
        
        this.modal.hide();
        this.selectedProductInRecipe = {}
        if(action !==""){
          setTimeout(() => {
            let trashElement = document.querySelectorAll('.modal-backdrop');
            trashElement.forEach((item)=>{
              document.querySelector('body').removeChild(item);
            })
          }, 100);
        }
      },
      
    },
    mounted(){
      
      this.selectRecipe(this.$route.params.id)
    },
    created(){
      this.emitter.emit('displayOverlayLoad', false)
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
    
  };
</script>