<script setup >
  import * as bootstrap from 'bootstrap'
  import { GET_RECIPES, GET_RECIPE_BY_ID, STORE_RECIPE, DELETE_RECIPE, UPDATE_RECIPE} from "@/core/services/store/recipe.module";
  import recipeVideo from '@/views/pages/player/recipePlayer.vue';
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import debounce from 'debounce';
  import viewRecipePreparationModal from '@/views/pages/modals/viewRecipePreparationModal.vue';
  import viewProductModal from '@/views/pages/modals/viewProductModal.vue';
  import moment from 'moment';
  import { GET_PRODUCTS, GET_LAST_LOTE, } from "@/core/services/store/product.module";


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
                @keyup="searchRecipe()"
              />
            </VCol>
            <VCol cols="12" md="4" class="my-0 py-0">
              <VBtn
                color="white"
                class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
                @click="$refs.recipe_title_search.value='';searchRecipe()"
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
                  <div class="d-flex  flex-wrap flex-md-nowrap flex-column flex-md-row h-100">
                    <div class="ma-auto mx-0 pa-5 pb-0" style="width: 35%;">
                        <VImg
                          width="200"
                          height="200"
                          class="rounded"
                          :src="product.img"
                        />
                    </div>
                    <div class="pb-14 relative position-relative h-100" style="width: 64%;">
                      <VCardItem >
                        <VCardTitle>{{product.title}}</VCardTitle>
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
  
                      <VCardActions class="justify-end d-flex w-100" style="position: absolute; bottom: 0;">
                        <v-btn prepend-icon="iconoir:cart-alt" variant="outlined">
                          Agregar al carrito
                        </v-btn>
  
                      </VCardActions>
                    </div>
                  </div>
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
                    @update:modelValue="getRecipes()"
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
  </div>
</template>
<style lang="scss" >
  .losv .v-stepper-item__avatar.v-avatar{
    width: 40px!important;
    height: 40px!important;
    & > svg{
      width: 20px!important;
      height: 20px!important;
    }
  }
  .shadow-button{
    box-shadow: 0px 2px 10px 2px #57575775;
  }
  table.recipes-table > thead > tr > th:nth-child(n+1){
    width: max-content!important
  }

  .item-list-drawer{
    border-bottom: 1px solid rgba(165, 165, 165, 0.822); border-radius: 0px;
  }
  .drawer__close-button{
    position: absolute;
    top: 0px;
    right: -20px;
    transition: all 0.5s ease;
    &.active{
    top:-20px!important
  }

  }
  .v-navigation-drawer__content{
    overflow: visible!important;
    overflow-x: hidden;
  }
  .small-delete-product-button_recipe{
      position: absolute;
      top: -10px;
      right: 6px;
    }
  .stock-notify{
    border-radius: 15px;
    background: #dce7ef;
    & > p{
      font-size: smaller;
    }
  }
  @media screen and (max-width: 780px){
    .small-delete-product-button_recipe{
      position: absolute;
      top: -10px;
      right: 5px;
    }
    thead > tr > th:last-child{
      width: 10%!important;
    }
  }

  .recipe-notproduct{
    font-weight: bolder;
    color:#4d5f71;
    &:hover{
      color:#4d5f71
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
      searchProductsForRecipe(e, index){ 
        debounce(this.getProducts, 200)(e.target.value, index)
      },
      clearProductSearch(index){
        this.getProducts('',index)
      },
      selectedProduct(e,index, type="new"){
        if(!e) return
        try {
         
         type=='new'
          ? this.newRecipe.cooking_ingredients[index].id = e
          : this.selectedRecipe.cooking_ingredients[index].id = e
          
        } catch (error) {
          
        }
        
      },
      validateFormItem(id){
        const fieldToValidate = this.itemsValidateByForm(id)
        this.forms[id] = formValidation(document.getElementById(id), {
          fields: fieldToValidate,
          plugins: {
            trigger: new Trigger(),
            submitButton: new SubmitButton(),
            bootstrap: new Bootstrap({
                  // Use this for enabling/changing valid/invalid class
                  // eleInvalidClass: '',
                  // eleValidClass: '',
                }),
          }
        });
        setTimeout( ()=>this.formsActions(id), 500)
      },
      itemsValidateByForm(id){
        let fieldByForm = {}
        switch (id) {
          case 'new_recipe_form':
            fieldByForm = {
              new_recipe_img: {
                validators: {
                  notEmpty: {
                    message: "Debes subir una foto"
                  },
                }
              },
              new_recipe_title: {
                validators: {
                  notEmpty: {
                    message: "El titulo es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_recipe_type: {
                validators: {
                  notEmpty: {
                    message: "El tipo de plato es necesario"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_recipe_description: {
                validators: {
                  notEmpty: {
                    message: "La descripción es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_\s+ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_recipe_person_count: {
                validators: {
                  notEmpty: {
                    message: "La cantidad de personas"
                  },
                  regexp: {
                    regexp: /^[0-9]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_recipe_time_total: {
                validators: {
                  notEmpty: {
                    message: "Agrega el tiempo total"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'new_recipe_form_2':
            fieldByForm = {
              new_recipe_product_1:{
                validators: {
                  notEmpty: {
                    message: "El ingrediente es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_recipe_product_quantity_1:{
                validators: {
                  notEmpty: {
                    message: "El ingrediente es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              }
            }
            break;
          case 'update_recipe_form':
            fieldByForm = {
              update_recipe_title: {
                validators: {
                  notEmpty: {
                    message: "El titulo es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              update_recipe_type: {
                validators: {
                  notEmpty: {
                    message: "El tipo de plato es necesario"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              update_recipe_description: {
                validators: {
                  notEmpty: {
                    message: "La descripción es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_\s+ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              update_recipe_person_count: {
                validators: {
                  notEmpty: {
                    message: "La cantidad de personas"
                  },
                  regexp: {
                    regexp: /^[0-9]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              update_recipe_time_total: {
                validators: {
                  notEmpty: {
                    message: "Agrega el tiempo total"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'update_recipe_form_2':
            fieldByForm = {
              update_recipe_product_1:{
                validators: {
                  notEmpty: {
                    message: "El ingrediente es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              update_recipe_product_quantity_1:{
                validators: {
                  notEmpty: {
                    message: "El ingrediente es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              }
            }
            break;
          
          default:
            break;
        }
        return fieldByForm
      }, 
      destroyValidate(id){
        this.forms[id].destroy()
      },
      formsActions(id){
        const sendButton = document.getElementById(id+'_button')
       
        this.forms[id].on("core.form.valid", () => {
          switch (id) {
            case 'new_recipe_form':
              this.nextStep('new')
              
              break;
            case 'new_recipe_form_2':
              this.nextStep('new')
              break;
            case 'update_recipe_form':
              this.nextStep('update')
              
              break;
            case 'update_recipe_form_2':
              this.nextStep('update')
              break;
          }

        }).on("core.field.valid", () => {
          sendButton.disabled = false
          sendButton.classList.remove('v-btn--disabled')

        }).on("core.form.invalid", () => {
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')

        }).on("core.field.invalid", () => {
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')

        });
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
      searchRecipe(){
        debounce(this.getProducts, 200)()

      },
      async selectRecipe(idAccount){
        this.isRecipe = false
        this.$store
          .dispatch(GET_RECIPE_BY_ID, idAccount)
          .then((response) => {

           
            this.selectedRecipe = Object.assign({}, response.data); 
            this.selectedRecipe.ingredients = JSON.parse(response.data.ingredients)
            this.selectedRecipe.preparation = JSON.parse(response.data.preparation)
            
            this.isRecipe = true
            console.log(this.selectedRecipe)
            setTimeout(() => {
              if(window.screen.width < 480) this.drawer = true;
              return new Promise((resolve) => {
                  resolve(response.data);
              });
            }, 1000);
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
      onFileChange(e, type) {
        const file = e.target.files[0];


       return  type=='new'
        ? this.newRecipe.img = URL.createObjectURL(file)
        : this.selectedRecipe.image_url = URL.createObjectURL(file)
      },
      getPreparation(preparation, type){


        if(type=="new"){
          this.newRecipe.preparation = preparation.steps; 
          this.newRecipe.video = preparation.video; 
          
          setTimeout(() => {
            this.createRecipe()
            
          }, 1000);
          return
        }
        this.selectedRecipe.preparation = preparation.steps; 
        this.selectedRecipe.video = preparation.video 
        this.updateRecipe()
        
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
      deleteRecipe(){
        this.$store.dispatch(DELETE_RECIPE, this.selectedRecipe.id).then((data)=>{
          setTimeout(() => {
            this.hideModal()
            this.showSnackbar('success','Receta eliminada con exito')
            this.getRecipes()
          }, 500);

        }).catch((err)=>{
          this.hideModal()
          this.showSnackbar('error','Error al eliminar la receta')
        })
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      selectProductView(index){

        const val = this.validateIsgoodProduct(this.selectedRecipe.cooking_ingredients[index], true, false)
        if(val){
          this.selectedProductInRecipe =  this.selectedRecipe.cooking_ingredients[index].lotes[0]
          this.selectedProductInRecipe.product = Object.assign({}, this.selectedRecipe.cooking_ingredients[index]);
          setTimeout(() => {
            this.showInterModal('viewProduct')
          }, 800);
        }
        
      },
      clearNewRecipeForm(){
          this.productsForRecipe = [];
          this.stepperNewProduct = 1;
          this.newRecipe = {
            img:'images/product/default.png',
            title:'',
            type:'',
            description:'',
            personCount:'',
            timeTotal:'',
            ingredients:[
              {
                name:'',
                quantity:'',
              }
            ],
            cooking_ingredients:[
              {
                id:null,
                name:'',
                quantity:'',
              }
            ],
            preparation: [
                { 
                  title:'',
                  description:''
                }
            ],
            video:'',
          };
          try {
            this.destroyValidate('new_recipe_form_2')
            
          } catch (error) {
            
          }
      },
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