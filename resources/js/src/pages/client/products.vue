<script setup >
  import * as bootstrap from 'bootstrap'
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import debounce from 'debounce';
  import viewProductModal from '@/views/pages/modals/viewProductModal.vue';
  import moment from 'moment';
  import { GET_PRODUCTS, GET_PRODUCT_BY_ID } from "@/core/services/store/product.module";
  import { ADD_TO_CART } from "@/core/services/store/cart.module";
  import buyCart from "@/layouts/components/BuyCart.vue";

</script>

<template>
  <div class="">

    <VCard title="Productos" class="pa-0 px-1 px-md-3 mb-1">
      <VRow class="ma-0  justify-start align-center justify-md-start pa-2 px-0 mb-0 mb-md-2">
        <VCol cols="12" md="4" class="form-group">
          <VTextField
            placeholder="Buscar nombre de receta"
            label="Buscar por receta"
            type="text"
            ref="recipe_title_search"
            name="recipe_title"
            @keyup="searchProduct()"
          />
        </VCol>
        <VCol cols="12" md="4" class="my-0 py-0">
          <VBtn
            color="white"
            class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
            @click="$refs.recipe_title_search.value='';searchProduct()"
          >
            <span class="">Restaurar</span>
          </VBtn>
        </VCol>
      </VRow>
    </VCard>
    <div v-if="allProducts.length > 0">
        <VRow class="mt-3"  >
          <VCol
            sm="6"
            cols="6"
            
            v-for="(product, index) in allProducts" :key="product.id"
            class="pa-0 mb-md-3 mb-2"
            :class="index%2 == 0 ? 'ps-2 ps-md-3 pe-0 pe-md-3' : 'ps-1 ps-md-3 pe-2 pe-md-3' "
          >
            <VCard class="h-100 relative position-relative">
              <VRow class="h-100 ">

                <div class="d-flex  flex-wrap flex-md-nowrap h-100 pb-3 w-100">
                  <VCol
                  md="6"
                  cols="12"
                  >
                    <div class="ma-auto mx-0 pa-5 pb-0 d-flex justify-md-center">
                      <VImg
                        
                        class="rounded client_product_list"
                        :src="product.img"
                      />
                  </div>
                  </VCol>
                  <VCol
                    md="6"
                    cols="12"
                    class="text-and-button"
                    style=""
                    
                  >
                    <div class="pb-14 relative position-relative h-100">
                      <VCardItem >
                        <h3 class="ma-0 pa-0">{{product.title}}</h3>
                      </VCardItem>
  
                      <VCardText>
                        {{ product.description}}
                      </VCardText>
  
                      <VCardText class="text-subtitle-1 d-flex  align-center">
                        <span>Stock :</span> 
                        <span class="font-weight-medium ms-1">
                          <v-chip :class="product.total_stock  < 1 ? 'bg-error' : product.total_stock  >= 30 ? 'bg-success' : 'bg-warning'">
                            {{ product.total_stock  }} {{ product.type_of_unit}}
                          </v-chip>
                        </span>
                      </VCardText>
  
                       <VCardActions class="justify-end d-flex w-100 add-to-cart__button" >
                        <v-btn @click="addToCart(product.id)" prepend-icon="iconoir:cart-alt" variant="outlined" class="d-md-none d-flex w-100 elevation-24">
                          Agregar
                        </v-btn>
                        <v-btn @click="addToCart(product.id)" prepend-icon="iconoir:cart-alt" variant="outlined" class="d-none d-md-flex w-100 elevation-24">
                          Agregar al carrito
                        </v-btn>
                      </VCardActions>
                    </div>
                  </VCol>
                  
                  
                </div>
              </VRow>
            </VCard>
          </VCol>
        </VRow>
        <VRow>
          <VCol class="d-flex justify-center" col="12" md="4" offset-md="4">

            <div class="text-center mt-8  bg-white w-100 rounded-lg elevation-24">
              <v-pagination
                v-model="pagination.currentPage"
                :length="pagination.totalPage"
                rounded="circle"
                active-color="primary"
                color="rgb(99 92 92)"
                @update:modelValue="getProducts()"
              />
                
            </div>
          </VCol>
        </VRow>
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
    <v-navigation-drawer
          v-model="drawer"
          location="bottom"
          temporary
          :class="`${drawer ? 'd-block' : 'd-none'}` "
          style="height: 180px; background: #f1f1f1; border-top-right-radius: 20px; border-top-left-radius: 20px; overflow-y: visible;"
        >
        <div class="text-center d-flex flex-column  h-100" style="position: relative;">
          <div :class="`drawer__close-button ${drawer ? 'active' : ''}`" >
            <v-col  class="pa-0 pe-4">
              <v-btn icon="mingcute:close-fill" class="bg-secondary shadow-button"   @click="drawer = false" ></v-btn>
            </v-col>
          </div>
          <h3 class="mt-4 mb-0">hola</h3>
          <div class="d-flex  justify-space-between mx-8  align-center h-50 mt-4 " style="box-sizing:content-box;">
            
            <v-btn size="large" class="d-block mx-2 shadow-button" color="primary" @click="showModal('viewRecipe')" icon="carbon:view" />
            <v-btn size="large" class="d-block mx-2 shadow-button" color="primary" @click="showModal('updateRecipe')" icon="line-md:edit-twotone-full" />
            <v-btn size="large" class="d-block mx-2 shadow-button" color="error" @click="showModal('deleteRecipe')" icon="mi:delete" />
          </div>
        </div>
    </v-navigation-drawer>    
    <buyCart />
  </div>
</template>
<style lang="scss" >
  .client_product_list {
    width:200px;
    height:200px;
  }
  .text-and-button{
    height: 100%;
  }
  .add-to-cart__button{
    position: absolute!important; 
    bottom: -20px;
  }
  @media screen and (max-width: 780px){
    .client_product_list{
      width:130px;
      height:130px;
    }
    .text-and-button{
      height: 66%;
    }
    .add-to-cart__button{
      bottom: 0;
    }
  }
  
</style>

<script>

  export default {
    data: () => ({
      allProducts:[],
      pagination:{
        currentPage:1,
        totalPage:0,
      },
      isRecipe:false,
      modal: '',
      internalModal:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:1000,
      drawer:false,

    }),
    methods:{
      clearProductSearch(index){
        this.getProducts('',index)
      },


      getProducts(){
        const data ={
          page:this.pagination.currentPage,
          search:this.$refs.recipe_title_search.value
        }
        this.$store.dispatch(GET_PRODUCTS, data ).then((data)=>{
          this.allProducts = data.data.data
          this.pagination.totalPage = data.data.last_page

        })
      },
      searchProduct(){
        debounce(this.getProducts, 200)()

      },
      async selectProduct(idAccount){
        this.$store
          .dispatch(GET_PRODUCT_BY_ID, idAccount)
          .then((response) => {
            this.selectedProduct = Object.assign({}, response.data);
            setTimeout(() => {
              return new Promise((resolve) => {
                  resolve(response.data);
              });
            }, 700);
          })
          .catch((err) => {
            console.log(err)
            return new Promise((resolve) => {
              resolve(false);
            });
          });
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
        
        this.clearNewRecipeForm()
        // this.clearUpdateRecipeForm()
        this.modal.hide();

      },
      hideInternalModal(){
        this.internalModal.hide();
        this.sliderPosition = 1
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
        recipeFormData.append('video_url', this.newRecipe.video)
 

        this.$store
        .dispatch(STORE_RECIPE, recipeFormData)
        .then((data) =>{
          setTimeout(() => {
            this.getProducts()
            this.clearNewRecipeForm()
            this.showSnackbar('success','Receta creada con exito')
            this.hideModal();
          }, 500);
        }).catch((err) => {
          this.hideModal()
          this.showSnackbar('error','Error al crear la receta')

       });
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      addToCart(id){
        this.selectProduct(id).finally((data)=>{
          setTimeout(() => {
            this.selectedProduct.cartQuantity = 500;

            const cartFormData = new FormData
            cartFormData.append('products', JSON.stringify(this.selectedProduct))
            this.$store
              .dispatch(ADD_TO_CART, cartFormData)
                .then((data) =>{
                  setTimeout(() => {
                    console.log(data)
                    // console.log(data.split("-"))
                  }, 500);
                }).catch((err) => {
                  console.log(err)

              });
          }, 800);
        })

        
      
      }
      
    },
    mounted(){
      this.getProducts()
      // this.validateFormItem('new_recipe_form')
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>