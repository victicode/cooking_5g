<script setup >
  import * as bootstrap from 'bootstrap'
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import debounce from 'debounce';
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
            placeholder="Buscar producto"
            label="Buscar producto"
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
                      <VCardItem class="px-4">
                        <h3 class="ma-0 pa-0">{{product.title}}</h3>
                      </VCardItem>
  
                      <VCardText class="px-4">
                        {{ product.description}}
                      </VCardText>
  
                      <VCardText class="px-4 text-subtitle-1 d-flex  align-center">
                        <span>Stock :</span> 
                        <span class="font-weight-medium ms-1">
                          <v-chip :class="product.total_stock  < 1 ? 'bg-error' : product.total_stock  >= 30 ? 'bg-success' : 'bg-warning'">
                            {{ product.total_stock  }} {{ product.type_of_unit}}
                          </v-chip>
                        </span>
                      </VCardText>
  
                       <VCardActions class="justify-end d-flex w-100 add-to-cart__button ps-md-0"  v-if="product.total_stock  > 1" >
                        <v-btn 
                          @click="productInCart(product.id) ? addToCart(product) : showSnackbar('success','Producto ya agregado')" 
                          :prepend-icon="productInCart(product.id) ? 'iconoir:cart-alt' : 'ic:baseline-check'" variant="outlined" class="d-md-none d-flex w-100 elevation-24">
                         {{ productInCart(product.id) ?'Agregar':'Agregado'}} 
                        </v-btn>
                        <v-btn  
                          @click="productInCart(product.id) ? addToCart(product) : showSnackbar('success','Producto ya agregado')" :prepend-icon="productInCart(product.id) ? 'iconoir:cart-alt' : 'ic:baseline-check'" variant="outlined" class="d-none d-md-flex w-100 elevation-24 ">
                          {{ productInCart(product.id) ?'Agregar al carrito':'Agregado al carrito'}} 
                        </v-btn>
                      </VCardActions>
                      <VCardActions class="justify-center d-flex w-100 add-to-cart__button ps-md-0"  v-else>
                        <v-chip :class="product.total_stock  < 1 ? 'bg-error' : product.total_stock  >= 30 ? 'bg-success' : 'bg-warning'">
                          No stock
                        </v-chip>
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
      productsAreReady:true,
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
      snacktimeOut:2000,
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
          // this.productsAreReady=true

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
      addToCart(product){
          setTimeout(() => {
            product.quantity = 1;
            const cartFormData = new FormData
            cartFormData.append('products', JSON.stringify(product))
            this.$store
              .dispatch(ADD_TO_CART, cartFormData)
                .then((data) =>{
                  this.emitter.emit('getItems')
                  this.getProducts()
                }).catch((err) => {
                  console.log(err)
              });
          }, 800);     
      },
      productInCart(id){
        if(this.$store.cart){
          const data = this.$store.cart.find((product) => product.id == id);
           return data ? false : true
        }
        return true
      }
      
    },
    mounted(){
      this.getProducts()
      // this.validateFormItem('new_recipe_form')
    },
    created(){
      this.emitter.on("deleteItemOfCart", () => {
        this.getProducts()
      })
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>