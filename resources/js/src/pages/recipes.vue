<script setup >
  import * as bootstrap from 'bootstrap'
  import { GET_RECIPES, GET_RECIPE_BY_ID, STORE_RECIPE, DELETE_RECIPE, UPDATE_RECIPE} from "@/core/services/store/recipe.module";
  import recipeVideo from '@/views/pages/player/recipePlayer.vue';
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import { GET_PRODUCT_BY_SEARCH } from "@/core/services/store/product.module";
  import debounce from 'debounce';
  import viewRecipePreparationModal from '@/views/pages/modals/viewRecipePreparationModal.vue';
  import viewProductModal from '@/views/pages/modals/viewProductModal.vue';
  import moment from 'moment';

</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de recetas" class="pa-0 px-1 px-md-3 mb-1">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0 pb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
          <VBtn @click=" showModal('createRecipe')" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nueva receta</VBtn>

          </VCol>
        </VRow>
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
      <template v-if="recipes.length > 0">
          <VCard class="mt-3" v-for="recipe in recipes" :key="recipe.id" >
            <VRow class="ma-0  justify-center align-center justify-md-start pa-0 px-md-5 px-0 mb-0 mb-md-0">
              <VCol cols="12"  class="d-flex align-center">
                <div>
                  <img :src="recipe.image_url" width="100" height="100" style="border-radius: 20%;">
                </div>
                <div class="px-2 w-100">
                  <div>

                    <div class="d-flex text-md-start ">
                      <h3 class="w-100"> {{ recipe.title }} </h3>
                      
                    </div>
                    <div class="ms-1  d-flex justify-space-between align-end">
                      <div>
                        <div class="d-flex">
                          <div class="d-flex align-center">
                            <VIcon icon="fa6-solid:users"  size="x-small" />
                            <h5 class=" my-1 ms-1 text-primary"> 
                              <b>{{recipe.person_count}} {{ recipe.person_count == 1 ? 'Persona' : 'Personas' }} </b> 
                            </h5>
                          </div>
                          <div class="d-flex align-center ms-5">
                            <VIcon icon="icon-park-outline:big-clock" size="x-small" />
                            <h5 class=" my-1 ms-1 text-primary"> 
                              <b>{{recipe.total_time}}</b> 
                            </h5>
                          </div>
                        </div> 
                        <v-chip color="primary" class="mt-1 pb-0">
                            <b>
                              {{ recipe.type }}
                            </b>
                        </v-chip>
                      </div>
                      <div class="d-flex">
                        <v-btn size="x-small" class="d-block d-md-none" color="secondary" @click="selectRecipe(recipe.id)" icon="majesticons:plus-line" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="secondary" @click="showAction(recipe.id,'viewRecipe')" icon="carbon:view" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="secondary" @click="showAction(recipe.id, 'updateRecipe' )" icon="line-md:edit-twotone-full" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="error" @click="showAction(recipe.id, 'deleteRecipe')" icon="mi:delete" />
                      </div>
                    </div>
                  </div>
                </div>
              </VCol>
            </VRow>
          </VCard>
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
      </template>

    </VCol>
    <div v-if="isRecipe" >
      <div class="modal animate__animated animate__fadeInDown"  id="viewRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
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
                    <v-col  class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
                  <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                    <VDivider :vertical="$vuetify.display.mdAndUp" />

                    <div class="w-100">
                      <VRow class="ma-0 pa-0">
                        <VCol cols="12" md="4" class="justify-center d-grid">
                          <div class="ma-auto mx-0 pa-5 pb-0">
                            <VImg
                              width="200"
                              height="200"
                              class="rounded"
                              :src="selectedRecipe.image_url "
                            />
                          </div>
                        </VCol>
                        <VCol cols="12" md="8" class="mt-0 pt-0 mb-4">
                           <div>
                            <div>
                              <VCardItem class="px-1 pb-0 pt-5">
                                <VCardTitle>{{ selectedRecipe.title }}</VCardTitle>
                              </VCardItem>
                              
                            </div>
                            <div class="d-flex w-100 justify-space-between">
                              <VCardItem class="px-1 pt-4 pb-0">
                                <VChip color="primary">
                                  {{ selectedRecipe.type }}
                                </VChip>
                                
                              </VCardItem>
                              <v-tooltip text="Ver pasos">
                                <template v-slot:activator="{ props }">
                                  <v-btn v-bind="props" icon="material-symbols:menu-book-outline"  @click="showInterModal('stepsRecipe')"   class="me-5" variant="tonal" />
                                </template>
                              </v-tooltip>
                              
                            </div>
                           </div>
                          <VCardText class="px-1" style="line-height: 1.5;">
                            {{ selectedRecipe.description}}
                          </VCardText>
                          <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
                            <VCardText class="text-subtitle-1 py-4 px-1">
                              <div class="font-weight-medium"><h3>Ingredientes cooking 5G:</h3> </div> 
                              <div class="font-weight-bold my-2" v-for="(ingredient, index) in selectedRecipe.cooking_ingredients" :key="index">
                                <a 
                                  :class="this.validateIsgoodProduct(ingredient, 'blank-modal', 'recipe-notproduct' ) " 
                                  @click="selectProductView(index)" 
                                > 
                                  - {{ `${ingredient.pivot.quantity} ${ingredient.pivot.quantity.length > 1 ?'de':''}`}} {{ ingredient.title }}
                                  
                                  {{ 
                                    this.validateIsgoodProduct(ingredient, '', '(Sin stock)')
                                  
                                  }}
                                  
                                </a>
                              </div>
                            </VCardText>
                            <VCardText class="text-subtitle-1 py-4 px-1">
                              <div class="font-weight-medium"><h3>Otros ingredientes:</h3> </div> 
                              <div class="font-weight-medium my-2" v-for="(ingredient, index) in selectedRecipe.ingredients" :key="index">
                                - {{ `${ingredient.quantity} ${ingredient.quantity.length > 1 ?'de':''}`}} {{ ingredient.name }}
                              </div>
                            </VCardText>
                          </div>
                        </VCol>
                      </VRow>
                    </div>
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="stepsRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
          
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
                        <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideInternalModal()" ></v-btn>
                      </v-col>
                    </div>
                    <div class="d-flex justify-space-between  flex-column pa-2 pb-0 pt-md-5  px-md-5 ">
                      <VRow  class="mb-2 ma-0 mt-7">
                        <VCol
                          cols="12"
                          class="py-0 px-0"
                        >
                          <v-carousel
                            :continuous="false"
                            :show-arrows="false"
                            :touch="true"
                            hide-delimiter-background
                            delimiter-icon="ic:outline-circle"
                            :height="sliderPosition == selectedRecipe.preparation .length+2 ? 'max-content': 570"
                            v-model="sliderPosition"
                          >
                            <v-carousel-item
                              :value="1"
                            >
                              <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                                <div class="w-100">
                                  <h3 class="mb-4" >Ingredientes</h3>
                                  <VRow class="ma-0 pa-0">
                                    <VCol cols="12" class="justify-center pa-0">
                                      <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
                                        <VCardText class="text-subtitle-1 py-4 px-1" >
                                          <div class="font-weight-medium"> <h3>Ingredientes cooking 5G:</h3> </div> 
                                          <div class="font-weight-bold my-2" v-for="(ingredient, index) in selectedRecipe.cooking_ingredients" :key="index">
                                            <a 
                                              :class="this.validateIsgoodProduct(ingredient, 'blank-modal', 'recipe-notproduct' ) " 
                                              @click="selectProductView(index) "  
                                            > 
                                              - {{ `${ingredient.pivot.quantity} ${ingredient.pivot.quantity.length > 1 ?'de':''}`}} {{ ingredient.title }}
                                              
                                              {{ 
                                                this.validateIsgoodProduct(ingredient, '', '(Sin stock)')
                                              
                                              }}
                                              
                                            </a>
                                          </div>
                                        </VCardText>
                                        <VCardText class="text-subtitle-1 py-4 px-1">
                                          <div class="font-weight-medium"> <h3>Otros ingredientes:</h3> </div> 
                                          <div class="font-weight-medium my-3" v-for="(ingredient, index) in selectedRecipe.ingredients" :key="index">
                                            - {{ `${ingredient.quantity} ${ingredient.quantity.length > 1 ?'de':''}`}} {{ ingredient.name }}
                                          </div>
                                        </VCardText>
                                      </div>
                                    </VCol>
                                  </VRow>
                                </div>
                              </div>
                            </v-carousel-item>
                            <v-carousel-item
                              v-for="(step, index) in selectedRecipe.preparation"
                                :key="index"
                                :value="index+2"

                            >
                              <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                                <div class="w-100">
                                  <h5>Paso {{ index+1 }} de {{ selectedRecipe.preparation.length }}</h5>  
                                  <h3 class="mb-4" >{{ step.title }}</h3>
                                  <VRow class="ma-0 pa-0">
                                    <VCol cols="12" class="justify-center pa-0">
                                      <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
                                        <VCardText class=" py-4 px-1" style="line-height:1.5">

                                          
                                          <div v-html="step.description.replace(/\n/gi, '<br>')"></div>
                                        </VCardText>
                                      </div>
                                    </VCol>
                                  </VRow>
                                </div>
                              </div>
                            </v-carousel-item>
                            <v-carousel-item
                              v-if="selectedRecipe.video_url"
                              :value="selectedRecipe.preparation.length + 2"
                            >
                              <div class="d-flex  flex-wrap align-center flex-md-nowrap flex-column flex-md-row">
                                <div class="w-100">
                                  <h3 class="mb-4" >Video de Receta</h3>
                                  <VRow class="ma-0 pa-0">
                                    <VCol cols="12" class="justify-center pa-0">
                                      <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
                                        <VCardText class="text-subtitle-1 py-4 px-1">
                                          <div class="font-weight-bold my-3" >
                                            
                                            <recipeVideo :video="selectedRecipe.video_url" />
                                            
                                          </div>
                                        </VCardText>
                                      </div>
                                    </VCol>
                                  </VRow>
                                </div>
                              </div>
                            </v-carousel-item>
                          </v-carousel>
                        </VCol>
                      </VRow>
                    </div>
                  </VCard>
                </VCol>
              </VCol>
            </div>
          </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="deleteRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
          
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
                          <h2>¿Seguro que deseas eliminar <b class="text-primary">{{selectedRecipe.title}}</b>?</h2>
                        </VCol>
                      </VRow>
                        
                      <VDivider  />
                      <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                        <VCardActions class=" justify-center w-100 d-md-flex  d-flex">
                          <VBtn
                            color="white"
                            class="bg-error text-white w-50 mx-0 mx-md-5 my-2"
                            @click="deleteRecipe()"
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

      <div class="modal animate__animated animate__fadeInDown pe-0"  id="updateRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg mt-10" >
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
                    <div>
                      <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                        <VCardTitle class="text-2xl font-weight-bold">
                          <div class="card-title d-flex ">
                            <div class="form-title__part1">Editar Receta</div>
                            
                          </div>
                        </VCardTitle>
                      </VCardItem>
                      <VCardText class="w-100 pb-5 px-3 px-md-6">
                        <VRow >
                          <v-stepper v-model="stepperUpdateProduct" class="losv">
                            <template v-slot:default="">
                              <v-stepper-header class="">
                                <v-stepper-item
                                    :complete="stepperUpdateProduct > 1"
                                    step="Detalles de la receta"
                                    :value="1"
                                    icon="icon-park-outline:chef-hat"
                                    class="pb-5"
                                  >
                                    Datos
                                  </v-stepper-item>

                                  <v-stepper-item
                                    :complete="stepperUpdateProduct > 2"
                                    step="ingredientes"
                                    :value="2"
                                    icon="bi:basket"
                                    class="pb-5"
                                  >
                                    Ingredientes
                                  </v-stepper-item>
                                  <v-stepper-item
                                    :complete="stepperUpdateProduct > 3"
                                    step="perparación"
                                    :value="3"
                                    icon="material-symbols:cooking"
                                    class="pb-5"
                                  >
                                    Preparación
                                  </v-stepper-item>
                              </v-stepper-header>
                              <v-stepper-window class="mx-2" >
                                <v-stepper-window-item
                                  :value="1"
                                >
                                  <template class="d-block" >
                                    <VForm  id="update_recipe_form">
                                      <VRow>
                                        <VCol cols="12"  class=" ">
                                          <div class="img-content mx-auto">
                                            <label for="update-img">
                                              <VImg
                                                width="200"
                                                height="200"
                                                class="rounded"
                                                :src="selectedRecipe.image_url"
                                                style="border-radius:10%!important"
                                                id="update-img-content"
                                              />
                                              <div class="overlay-img">
                                                <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                                              </div>
                                            </label>
                                            <VCol cols="12" md="12"  class="form-group text-center ma-0 mt-0 pa-0">
                
                                              <input type="file"  id="update-img" ref="updateImg" name="update_recipe_img" class="d-none" @change="onFileChange($event, 'update')" >
                                            </VCol>
                                          </div>
                                        </VCol>
                                        <VCol cols="12" class="form-group">
                                            <VTextField
                                              placeholder="Nombre de la receta"
                                              label="Nombre de la receta"
                                              type="text"
                                              name="update_recipe_title"
                                              autocomplete="off"
                                              v-model="selectedRecipe.title"
                                            />
                                        </VCol>
                                        <VCol cols="12" class="form-group">
                                            <VTextField
                                              placeholder="EJ: Postre, Entrada, Plato fuerte"
                                              label="Tipo de plato"
                                              type="text"
                                              name="update_recipe_type"
                                              autocomplete="off"
                                              v-model="selectedRecipe.type"
                                            />
                                        </VCol>
                                        <VCol cols="6" class="form-group">
                                            <VTextField
                                              placeholder="Cantidad de personas"
                                              label="Cant. de personas"
                                              type="number"
                                              name="update_recipe_person_count"
                                              autocomplete="off"
                                              v-model="selectedRecipe.person_count"
                                            />
                                        </VCol>
                                        <VCol cols="6" class="form-group">
                                            <VTextField
                                              placeholder="Tiempo total"
                                              label="Tiempo total"
                                              type="text"
                                              name="update_recipe_time_total"
                                              autocomplete="off"
                                              v-model="selectedRecipe.total_time"
                                            />
                                        </VCol>
                                        <VCol cols="12" class="form-group">
                                          <v-textarea
                                            label="Descripcion larga"
                                            auto-grow
                                            variant="outlined"
                                            rows="3"
                                            row-height="25"
                                            shaped
                                            name="update_recipe_description"
                                            v-model="selectedRecipe.description"
                                          ></v-textarea>
                                        </VCol>
                                      </VRow>
                                      <VRow class="ma-0 pa-0  mt-8 align-center">
                                        <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                                          <v-col cols="auto" class="">
                                            <VBtn  color="primary" class="w-100" type="submit"  id="update_recipe_form_button" >Siguiente</VBtn>
                                          </v-col>
                                        </VCol>
                                      </VRow>
                                    </VForm>
                                    
                                  </template>
                                </v-stepper-window-item>
                                <v-stepper-window-item
                                  :value="2"
                                >
                                  <template class="d-block">
                                    <VForm  id="update_recipe_form_2">
                                      <VRow 
                                      class="ma-0 pa-0  mt-4 align-center pb-4" 
                                      style="border-bottom: 1px solid rgba(90, 90, 90, 0.288);"
                                      >
                                        <VCol cols="12" class="form-group mb-4">
                                          <h3>Ingrendientes cooking 5G</h3>
                                        </VCol>
                                        <VCol cols="12"  class="mt-0 py-0 px-0 mb-0">   
                                          <VRow v-for="(item, index) in selectedRecipe.cooking_ingredients" :key="index" class="position-realative relative mb-2" >
                                            <VCol cols="6" class="form-group ">
                                              <v-autocomplete
                                                :model-value="item.id"
                                                :items="productsForRecipe[index] ?  productsForRecipe[index] : item.id !== null ? [ {id: item.id, title: item.title, stock: item.quantity}] : []"
                                                :label="`Ingrediente ${(index+1)}`"
                                                :name="`update_recipe_cooking_product_${(index+1)}`"
                                                item-title="title"
                                                item-value="id"
                                                placeholder="EJ: Lomo de cerdo, Pollo entero"
                                                variant="outlined"
                                                persistent-hint
                                                clearable
                                                no-filter
                                                no-data-text="No se encontraron resultados"
                                                data-actionTipe="update"
                                                @click="searchProductsForRecipe($event,index )"
                                                @keyup="searchProductsForRecipe($event,index )"
                                                @click:clear="clearProductSearch(index)"
                                                @update:modelValue="selectedProduct($event, index, 'update')"
                                              ></v-autocomplete>
                                            </VCol>
                                            <VCol cols="6" class="form-group">
                                              <VTextField
                                                placeholder="Cantidad"
                                                label="Cantidad"
                                                type="text"
                                                :name="`update_recipe_cooking_product_quantity_${(index+1)}`"
                                                v-model="item.pivot.quantity"
                                                autocomplete="off"
                                              />
                                            </VCol>
                                            <div class="form-group pa-0 mb-md-5  small-delete-product-button_recipe ">
                                              <v-tooltip text="Receta">
                                                <template v-slot:activator="{ props }">
                                                  <v-col cols="auto" class="pa-0">
                                                    <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" data-typeAction="update" :data-indexRecipe="index" @click="removeIngredientInRecipe('cooking_ingredients', $event)"></v-btn>
                                                  </v-col>
                                                </template>
                                              </v-tooltip>
                                            </div>
                                          </VRow>
                                        </VCol>
                                        <VCol cols="12" md="5" class="mt-0 py-0 px-0 mb-4">
                                          <v-tooltip text="Agregar nuevo ingrediente C5G">
                                              <template v-slot:activator="{ props }">
                                                <v-col cols="auto" class=" pa-0">
                                                  <VBtn v-bind="props" color="primary" class="w-100" data-typeAction="update" @click="addIngredientInRecipe($event,'cooking_ingredients')" >
                                                    <VIcon icon="bx-plus"/>Agregar otro ingrediente C5G
                                                  </VBtn>
                                                </v-col>
                                              </template>
                                            </v-tooltip>
                                        </VCol>
                                      </VRow>
                                      <VRow 
                                      class="ma-0 pa-0  mt-4 align-center" 
                                      >
                                        <VCol cols="12" class="form-group mb-4">
                                          <h3>Ingrendientes</h3>
                                        </VCol>
                                        <VCol cols="12"  class="mt-0 py-0 px-0 mb-4"> 
                                          <VRow v-for="(item, index) in selectedRecipe.ingredients" :key="index" class="position-realative relative mb-2" >
                                            <VCol cols="6" class="form-group">
                                              <VTextField
                                                placeholder="EJ: Azucar, Sal"
                                                :label="`Ingrediente ${(index+1)}`"
                                                type="text"
                                                :name="`update_recipe_product_${(index+1)}`"
                                                v-model="item.name"
                                                autocomplete="off"
                                              />
                                            </VCol>
                                            <VCol cols="6" class="form-group">
                                              <VTextField
                                                placeholder="Cantidad"
                                                label="Cantidad"
                                                type="text"
                                                :name="`update_recipe_product_quantity_${(index+1)}`"
                                                v-model="item.quantity"
                                                autocomplete="off"
                                              />
                                            </VCol>
                                            <div class="form-group pa-0 mb-md-5  small-delete-product-button_recipe ">
                                              <v-tooltip text="Receta">
                                                <template v-slot:activator="{ props }">
                                                  <v-col cols="auto" class="pa-0">
                                                    <v-btn icon="mdi-cancel-bold" v-bind="props" size="small"  data-typeAction="update" :data-indexRecipe="index " @click="removeIngredientInRecipe('ingredients', $event)"></v-btn>
                                                  </v-col>
                                                </template>
                                              </v-tooltip>
                                            </div>
                                          </VRow>
                                        </VCol>
                                        <VCol cols="12" md="5" class="mt-0 py-0 px-0 mb-4">
                                          <v-tooltip text="Agregar nuevo ingrediente">
                                              <template v-slot:activator="{ props }">
                                                <v-col cols="auto" class="pa-0">
                                                  <VBtn v-bind="props" color="primary" class="w-100" data-typeAction="update" @click="addIngredientInRecipe($event,'ingredients')" ><VIcon icon="bx-plus"/> Agregar otro ingrediente</VBtn>
                                                </v-col>
                                              </template>
                                            </v-tooltip>
                                        </VCol>
                                      </VRow>
                                      <VRow class="ma-0 pa-0  mt-8 align-center">
                                        <VCol cols="5" md="4"  class="mt-0 py-0 px-0">
                                          <v-col cols="auto" class="">
                                            <VBtn  color="secondary" class="w-100" @click="backStep('update')" >
                                              <span class="d-block d-md-hidden ">
                                                <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
                                              </span>
                                              <span class="d-md-block d-none ">Volver</span>
    
                                              
                                            </VBtn>
                                          </v-col>
                                        </VCol>
                                        <VCol cols="5" md="4" offset="2" offset-md="4" class="mt-0 py-0 px-0">
                                          <v-col cols="auto" class="">
                                            <VBtn  color="primary" class="w-100 "  type="submit"  id="update_recipe_form_2_button"> Siguiente</VBtn>
                                          </v-col>
                                        </VCol>
                                      </VRow>
                                    </VForm>
                                  </template>
                                </v-stepper-window-item>
                                <v-stepper-window-item
                                  :value="3"
                                >
                                  <viewRecipePreparationModal @backStep="backStep('update')" @preparation="getPreparation" :type="'update'" :preparation="selectedRecipe.preparation"/>
                                </v-stepper-window-item>
                              </v-stepper-window>                          
                            </template>
                          </v-stepper>
                        </VRow>
                      </VCardText>
                    </div>
                  </VCard>
                </VCol>
              </VCol>
            </div>
          </div>
      </div>
    </div>
    <div v-if="Object.keys(selectedProductInRecipe).length > 2" >
      <viewProductModal :product="selectedProductInRecipe" @hiddenModal="hideInternalModal" />
    </div>
    <div class="modal animate__animated animate__fadeInDown pe-0"  id="createRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
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
                  <div>
                    <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title d-flex ">
                          <div class="form-title__part1">Crear nueva receta</div>
                          
                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <VCardText class="w-100 pb-5 px-3 px-md-6">
                      <VRow >
                        <v-stepper v-model="stepperNewProduct" class="losv">
                          <template v-slot:default="">
                            <v-stepper-header class="">
                              <v-stepper-item
                                  :complete="stepperNewProduct > 1"
                                  step="Detalles de la receta"
                                  :value="1"
                                  icon="icon-park-outline:chef-hat"
                                  class="pb-5"
                                >
                                  Datos
                                </v-stepper-item>

                                <v-stepper-item
                                  :complete="stepperNewProduct > 2"
                                  step="ingredientes"
                                  :value="2"
                                  icon="bi:basket"
                                  class="pb-5"
                                >
                                  Ingredientes
                                </v-stepper-item>
                                <v-stepper-item
                                  :complete="stepperNewProduct > 3"
                                  step="perparación"
                                  :value="3"
                                  icon="material-symbols:cooking"
                                  class="pb-5"
                                >
                                  Preparación
                                </v-stepper-item>
                            </v-stepper-header>
                            <v-stepper-window class="mx-2" >
                              <v-stepper-window-item
                                :value="1"
                              >
                                <template class="d-block" >
                                  <VForm  id="new_recipe_form">
                                    <VRow>
                                      <VCol cols="12"  class=" ">
                                        <div class="img-content mx-auto">
                                          <label for="newRecipe-img">
                                            <VImg
                                              width="200"
                                              height="200"
                                              class="rounded"
                                              :src="newRecipe.img"
                                              style="border-radius:10%!important"
                                              id="newRecipe-img-content"
                                            />
                                            <div class="overlay-img">
                                              <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                                            </div>
                                          </label>
                                          <VCol cols="12" md="12"  class="form-group text-center ma-0 mt-0 pa-0">
              
                                            <input type="file"  id="newRecipe-img" ref="newRecipeImg" name="new_recipe_img" class="d-none" @change="onFileChange($event, 'new')" >
                                          </VCol>
                                        </div>
                                      </VCol>
                                      <VCol cols="12" class="form-group">
                                          <VTextField
                                            placeholder="Nombre de la receta"
                                            label="Nombre de la receta"
                                            type="text"
                                            name="new_recipe_title"
                                            autocomplete="off"
                                            v-model="newRecipe.title"
                                          />
                                      </VCol>
                                      <VCol cols="12" class="form-group">
                                          <VTextField
                                            placeholder="EJ: Postre, Entrada, Plato fuerte"
                                            label="Tipo de plato"
                                            type="text"
                                            name="new_recipe_type"
                                            autocomplete="off"
                                            v-model="newRecipe.type"
                                          />
                                      </VCol>
                                      <VCol cols="6" class="form-group">
                                          <VTextField
                                            placeholder="Cantidad de personas"
                                            label="Cant. de personas"
                                            type="number"
                                            name="new_recipe_person_count"
                                            autocomplete="off"
                                            v-model="newRecipe.personCount"
                                          />
                                      </VCol>
                                      <VCol cols="6" class="form-group">
                                          <VTextField
                                            placeholder="Tiempo total"
                                            label="Tiempo total"
                                            type="text"
                                            name="new_recipe_time_total"
                                            autocomplete="off"
                                            v-model="newRecipe.timeTotal"
                                          />
                                      </VCol>
                                      <VCol cols="12" class="form-group">
                                        <v-textarea
                                          label="Descripcion larga"
                                          auto-grow
                                          variant="outlined"
                                          rows="3"
                                          row-height="25"
                                          shaped
                                          name="new_recipe_description"
                                          v-model="newRecipe.description"
                                        ></v-textarea>
                                      </VCol>
                                    </VRow>
                                    <VRow class="ma-0 pa-0  mt-8 align-center">
                                      <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                                        <v-col cols="auto" class="">
                                          <VBtn  color="primary" class="w-100" type="submit" disabled id="new_recipe_form_button" >Siguiente</VBtn>
                                        </v-col>
                                      </VCol>
                                    </VRow>
                                  </VForm>
                                  
                                </template>
                              </v-stepper-window-item>
                              <v-stepper-window-item
                                :value="2"
                              >
                                <template class="d-block">
                                  <VForm  id="new_recipe_form_2">
                                    <VRow 
                                    class="ma-0 pa-0  mt-4 align-center pb-4" 
                                    style="border-bottom: 1px solid rgba(90, 90, 90, 0.288);"
                                    >
                                      <VCol cols="12" class="form-group mb-4">
                                        <h3>Ingrendientes cooking 5G</h3>
                                      </VCol>
                                      <VCol cols="12"  class="mt-0 py-0 px-0 mb-0">   
                                        <VRow v-for="(item, index) in newRecipe.cooking_ingredients" :key="index" class="position-realative relative mb-2" >
                                          <VCol cols="6" class="form-group ">
                                            <v-autocomplete
                                              :model-value="item.id"
                                              :items="productsForRecipe[index] ?  productsForRecipe[index] : item.id !== null ? [ {id: item.id, title: item.title, stock: item.quantity}] : []"
                                              :label="`Ingrediente ${(index+1)}`"
                                              :name="`new_recipe_cooking_product_${(index+1)}`"
                                              item-title="title"
                                              item-value="id"
                                              placeholder="EJ: Lomo de cerdo, Pollo entero"
                                              variant="outlined"
                                              persistent-hint
                                              clearable
                                              no-filter
                                              no-data-text="No se encontraron resultados"
                                              @click="searchProductsForRecipe($event,index )"
                                              @keyup="searchProductsForRecipe($event,index )"
                                              @click:clear="clearProductSearch(index)"
                                              @update:modelValue="selectedProduct($event, index)"
                                            ></v-autocomplete>
                                          </VCol>
                                          <VCol cols="6" class="form-group">
                                            <VTextField
                                              placeholder="Cantidad"
                                              label="Cantidad"
                                              type="text"
                                              :name="`new_recipe_cooking_product_quantity_${(index+1)}`"
                                              v-model="item.quantity"
                                              autocomplete="off"
                                            />
                                          </VCol>
                                          <div class="form-group pa-0 mb-md-5  small-delete-product-button_recipe ">
                                            <v-tooltip text="Receta">
                                              <template v-slot:activator="{ props }">
                                                <v-col cols="auto" class="pa-0">
                                                  <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" data-typeAction="new" :data-indexRecipe="index" @click="removeIngredientInRecipe('cooking_ingredients', $event)"></v-btn>
                                                </v-col>
                                              </template>
                                            </v-tooltip>
                                          </div>
                                        </VRow>
                                      </VCol>
                                      <VCol cols="12" md="5" class="mt-0 py-0 px-0 mb-4">
                                        <v-tooltip text="Agregar nuevo ingrediente C5G">
                                            <template v-slot:activator="{ props }">
                                              <v-col cols="auto" class=" pa-0">
                                                <VBtn v-bind="props" color="primary" class="w-100" data-typeAction="new" @click="addIngredientInRecipe($event,'cooking_ingredients')" >
                                                  <VIcon icon="bx-plus"/>Agregar otro ingrediente C5G
                                                </VBtn>
                                              </v-col>
                                            </template>
                                          </v-tooltip>
                                      </VCol>
                                    </VRow>
                                    <VRow 
                                    class="ma-0 pa-0  mt-4 align-center" 
                                    >
                                      <VCol cols="12" class="form-group mb-4">
                                        <h3>Ingrendientes</h3>
                                      </VCol>
                                      <VCol cols="12"  class="mt-0 py-0 px-0 mb-4"> 
                                        <VRow v-for="(item, index) in newRecipe.ingredients" :key="index" class="position-realative relative mb-2" >
                                          <VCol cols="6" class="form-group">
                                            <VTextField
                                              placeholder="EJ: Azucar, Sal"
                                              :label="`Ingrediente ${(index+1)}`"
                                              type="text"
                                              :name="`new_recipe_product_${(index+1)}`"
                                              v-model="item.name"
                                              autocomplete="off"
                                            />
                                          </VCol>
                                          <VCol cols="6" class="form-group">
                                            <VTextField
                                              placeholder="Cantidad"
                                              label="Cantidad"
                                              type="text"
                                              :name="`new_recipe_product_quantity_${(index+1)}`"
                                              v-model="item.quantity"
                                              autocomplete="off"
                                            />
                                          </VCol>
                                          <div class="form-group pa-0 mb-md-5  small-delete-product-button_recipe ">
                                            <v-tooltip text="Receta">
                                              <template v-slot:activator="{ props }">
                                                <v-col cols="auto" class="pa-0">
                                                  <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" data-typeAction="new" :data-indexRecipe="index" @click="removeIngredientInRecipe('ingredients', $event)"></v-btn>
                                                </v-col>
                                              </template>
                                            </v-tooltip>
                                          </div>
                                        </VRow>
                                      </VCol>
                                      <VCol cols="12" md="5" class="mt-0 py-0 px-0 mb-4">
                                        <v-tooltip text="Agregar nuevo ingrediente">
                                            <template v-slot:activator="{ props }">
                                              <v-col cols="auto" class="pa-0">
                                                <VBtn v-bind="props" color="primary" class="w-100" data-typeAction="new" @click="addIngredientInRecipe($event,'ingredients')" ><VIcon icon="bx-plus"/> Agregar otro ingrediente</VBtn>
                                              </v-col>
                                            </template>
                                          </v-tooltip>
                                      </VCol>
                                    </VRow>
                                    <VRow class="ma-0 pa-0  mt-8 align-center">
                                      <VCol cols="5" md="4"  class="mt-0 py-0 px-0">
                                        <v-col cols="auto" class="">
                                          <VBtn  color="secondary" class="w-100" @click="backStep('new')" >
                                            <span class="d-block d-md-hidden ">
                                              <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
                                            </span>
                                            <span class="d-md-block d-none ">Volver</span>
  
                                            
                                          </VBtn>
                                        </v-col>
                                      </VCol>
                                      <VCol cols="5" md="4" offset="2" offset-md="4" class="mt-0 py-0 px-0">
                                        <v-col cols="auto" class="">
                                          <VBtn  color="primary" class="w-100 "  type="submit" disabled id="new_recipe_form_2_button"> Siguiente</VBtn>
                                        </v-col>
                                      </VCol>
                                    </VRow>
                                  </VForm>
                                </template>
                              </v-stepper-window-item>
                              <v-stepper-window-item
                                :value="3"
                              >
                                <viewRecipePreparationModal v-if="stepperNewProduct == 3 " @backStep="backStep('new')" @preparation="getPreparation" :type="'new'" :preparation="newRecipe.preparation" />
                              </v-stepper-window-item>
                            </v-stepper-window>                          
                          </template>
                        </v-stepper>
                      </VRow>
                    </VCardText>
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
          <h3 class="mt-4 mb-0">{{selectedRecipe.title}}</h3>
          <div class="d-flex  justify-space-between mx-8  align-center h-50 mt-4 " style="box-sizing:content-box;">
            
            <v-btn size="large" class="d-block mx-2 shadow-button" color="primary" @click="showModal('viewRecipe')" icon="carbon:view" />
            <v-btn size="large" class="d-block mx-2 shadow-button" color="primary" @click="showModal('updateRecipe')" icon="line-md:edit-twotone-full" />
            <v-btn size="large" class="d-block mx-2 shadow-button" color="error" @click="showModal('deleteRecipe')" icon="mi:delete" />
          </div>
        </div>
    </v-navigation-drawer>    
  </VRow>
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
      isRecipe:false,
      sliderPosition:1,
      pagination:{
        currentPage:1,
        totalPage:0,
      },
      modal: '',
      internalModal:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:1000,
      items:['editar','eliminar', 'ver'],
      drawer:false,
      selectedRecipe:{},
      recipes:[],
      stepperNewProduct: 1,
      stepperUpdateProduct:1,
      steps: 3,
      forms:[],
      productsForRecipe:[],
      newRecipe:{
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
      },
      selectedProductInRecipe:{},
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
      removeIngredientInRecipe(id,event){
        const data = event.target.closest('button').dataset
        setTimeout(() => {
            try{
              data.typeaction == 'new' 
              ? this.newRecipe[id].splice(data.indexrecipe, 1)
              : this.selectedRecipe[id].splice(data.indexrecipe, 1)
              
              if (id == 'cooking_ingredients') this.productsForRecipe.splice(data.indexrecipe, 1)

            }catch(e){

            }
          }, 200)
        
      },
      addIngredientInRecipe(event,id){
        const data = event.target.closest('button').dataset

        
        let newIngredient = {
          id:null,
          name:'',
          ingredient:'',
        }
        if(data.typeaction == 'new' ){
          newIngredient.quantity = ''
        }

        if(data.typeaction == 'update' ){
          if(id=='cooking_ingredients') newIngredient.pivot ={ quantity:''}, newIngredient.lotes =[];
          if(id=='ingredients') newIngredient.quantity = ''
        }


        data.typeaction == 'new' 
        ? this.newRecipe[id].push(newIngredient)
        : this.selectedRecipe[id].push(newIngredient)

          console.log(this.selectedRecipe[id])
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
      getRecipes(){
        const data ={
          page:this.pagination.currentPage,
          search:this.$refs.recipe_title_search.value
        }
        this.$store.dispatch(GET_RECIPES, data ).then((data)=>{
          this.recipes = data.data.data
          this.pagination.totalPage = data.data.last_page

        })
      },
      searchRecipe(){
        debounce(this.getRecipes, 200)()

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
      updateRecipe(){
        const recipeFormData = new FormData
        recipeFormData.append('title', this.selectedRecipe.title )
        recipeFormData.append('description', this.selectedRecipe.description )
        recipeFormData.append('person_count',this.selectedRecipe.person_count)
        recipeFormData.append('type', this.selectedRecipe.type )
        recipeFormData.append('total_time', this.selectedRecipe.total_time)
        recipeFormData.append('ingredients', JSON.stringify(this.selectedRecipe.ingredients))
        recipeFormData.append('cooking_ingredients', JSON.stringify(this.selectedRecipe.cooking_ingredients))
        recipeFormData.append('preparation', JSON.stringify(this.selectedRecipe.preparation))

        recipeFormData.append('image_url', this.$refs.updateImg.files[0])

        recipeFormData.append('video_url', this.selectedRecipe.video ? this.selectedRecipe.video  : false)
 

        this.$store
        .dispatch(UPDATE_RECIPE, {id:this.selectedRecipe.id, data:recipeFormData})
        .then((data) =>{
          console.log(data)

          setTimeout(() => {
            this.getRecipes()
            this.showSnackbar('success','Receta creada con exito')
            this.hideModal();
            this.clearUpdateRecipeForm()
          }, 500);
        }).catch((err) => {
          this.showSnackbar(err)

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
      clearUpdateRecipeForm(){
          this.productsForRecipe = [];
          this.stepperUpdateProduct = 1;
          this.selectedRecipe = {}
          this.destroyValidate('update_recipe_form_2')
          this.destroyValidate('update_recipe_form')

      },
      validateIsgoodProduct(ingredient, messageGood, messageBad){
          if(!ingredient.lotes) return messageBad  ;
          if(ingredient.lotes.length == 0) return messageBad  

          if(ingredient.lotes[0].quantity < 0 || Math.round(moment.duration(moment(ingredient.lotes[0].due_date).diff(new moment())).as('days') ) < 0 ){
            return messageBad 
          }

        
       return ingredient.deleted_at==null 
        ? messageGood 
        : messageBad 

      },
    },
    mounted(){
      this.getRecipes()
      this.validateFormItem('new_recipe_form')
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>