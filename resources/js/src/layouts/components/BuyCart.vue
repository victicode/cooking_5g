<script setup >
  import * as bootstrap from 'bootstrap'
  import moment from 'moment';
  import { GET_CART } from "@/core/services/store/cart.module";



</script>
<template>
  <div 
    class="position-fixed cart-float__button" 
    style=""
  >
  <div>

    <v-btn icon="iconoir:cart-alt" size="x-large" class="bg-secondary" style="transition: all 1s ease;" />
    <div class="notification__count animate__animated " v-if="cart.length > 0">
      <div class="w-100">
          {{ cart.length }}
      </div>
    </div>
  </div>
      
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
                        <h2>Cancelar orden</h2>
                      </div>
                    </VCol>
                    <VCol
                      cols="12"
                      class="px-md-10 px-0 text-center"
                      style=""
                    >
                      <h2>¿Seguro que deseas cancelar la orden?</h2>
                    </VCol>
                  </VRow>
                    

                  <VDivider  />
                  <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                    <VCardActions class=" justify-center w-100 d-md-flex  d-block">
                      <VBtn
                        color="white"
                        class="bg-error text-white w-30 mx-0 mx-md-5 my-2"
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
  </div>
</template>
<style>
.cart-float__button{
  transition: all 1s ease;
  top: 90%;
  right: 5%;
}
</style>
<script>

  export default {
    data: () => ({
      cart:[],
      modal:'',

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
      showModal(modal) {
        try {
          this.hideModal()
        } catch (error) {
          
        }
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })


        this.modal.show()

      },
      showInterModal(modal){
        this.hideModal()

        try {
          this.internalModal.hide()
        } catch (error) {
          
        }
        this.internalModal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.internalModal.show()
      },
      hideModal(){
        this.modal.hide();
      },
      hideInternalModal(){
        this.modal.show()
      },
      createRecipe(){
        const recipeFormData = new FormData
        recipeFormData.append('title', this.newRecipe.title )
        recipeFormData.append('description', this.newRecipe.description )
        recipeFormData.append('person_count',this.newRecipe.personCount)
        recipeFormData.append('type', this.newRecipe.type )
        recipeFormData.append('total_time', this.newRecipe.timeTotal)
        recipeFormData.append('ingredients', JSON.stringify(this.newRecipe.ingredients))
        recipeFormData.append('cooking_ingredients', JSON.stringify(this.newRecipe.cooking_ingredients))
        recipeFormData.append('preparation', JSON.stringify(this.newRecipe.preparation))
        recipeFormData.append('image_url', this.$refs.newRecipeImg.files[0])



         console.log([this.newRecipe.video, this.$refs.newRecipeImg.files[0]])
        recipeFormData.append('video_url', this.newRecipe.video)
 

        this.$store
        .dispatch(STORE_RECIPE, recipeFormData)
        .then((data) =>{
          setTimeout(() => {
            this.getRecipes()
            this.clearNewRecipeForm()
            this.showSnackbar('success','Receta creada con exito')
            this.hideModal();
          }, 500);
        }).catch((err) => {
          this.hideModal()
          this.showSnackbar('error','Error al crear la receta')

       });
      },
      getCart(){
        this.$store
        .dispatch(GET_CART)
        .then((data) =>{
          this.cart = data.data.description.split('?')
          this.formatCart()
        }).catch((err) => {
          console.log(err)

       });
      },
      formatCart(){
        this.cart.pop()
        for (let i = 0; i < (this.cart.length); i++) {
          this.cart[i] = JSON.parse(this.cart[i])
        }
        console.log(this.cart)
      }

    },
    mounted(){
      this.getCart()
    },
    created(){
    }
    
  };
</script>