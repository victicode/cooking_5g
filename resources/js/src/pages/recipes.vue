<script setup >
  import * as bootstrap from 'bootstrap'
  import { mapGetters } from "vuex";  
  import { GET_RECIPES, GET_RECIPE_BY_ID, STORE_RECIPE, DELETE_RECIPE, UPDATE_RECIPE} from "@/core/services/store/recipe.module";
  import { CREATE_MESSAGE } from "@/core/services/store/message.module";
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
  import { ADD_TO_CART } from "@/core/services/store/cart.module";
  import buyCart from "@/layouts/components/BuyCart.vue";
  import printTags from "@/layouts/components/PrintTags.vue";
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de recetas" class="pa-0 px-1 px-md-3 mb-1">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0 pb-0" v-if="getCurrentAccount.rol_id !== 3">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
          <VBtn @click=" showModal('createRecipe')" color="primary" class="w-100 "  >
            <VIcon icon="bx-plus"/> Agregar nueva receta
          </VBtn>
          
          </VCol>
          <VCol
            cols="11"
            md="3"
            class="ms-0 px-0 px-md-2 ms-md-4 justify-center justify-md-end d-flex"
          >
            <VBtn @click="showModal('printModule')" color="secondary" class="w-100 mx-2 " >
              <VIcon icon="lets-icons:print" class="me-1"/> Imprimir etiquetas
            </VBtn>
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
            <VCol cols="12"  class="d-flex align-center px-1">
              <div  class=" cursor-pointer" @click="getCurrentAccount.rol_id !== 3 ? selectRecipe(recipe.id) : showAction(recipe.id,'viewRecipe')" >
                <img :src="recipe.image_url" width="100" height="100" style="border-radius: 20%;">
              </div>
              <div class="px-2 w-100">
                <div>

                  <div class="d-flex text-md-start " @click="getCurrentAccount.rol_id !== 3 ? selectRecipe(recipe.id) : showAction(recipe.id,'viewRecipe')" >
                    <h3 class="w-100 text-decoration-underline cursor-pointer"> {{ recipe.title }} </h3>
                    
                  </div>
                  <div class="ms-1  d-flex justify-space-between align-end">
                    <div>
                      <div class="d-flex my-1">
                        <div class="d-flex align-center">
                          <VIcon icon="fa6-solid:users"  size="x-small" />
                          <h5 class=" my-1 ms-1 text-primary"> 
                            <b>{{recipe.person_count}} {{ recipe.person_count == 1 ? 'Persona' : 'Personas' }} </b> 
                          </h5>
                        </div>
                        <div class="d-flex align-center ms-2">
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
                      <div v-if="getCurrentAccount.rol_id == 3">
                        <div v-if="updateInCart"  >

                          <v-btn 
                            v-if="maxStockRecipeInput(recipe) > 0 && isAllIngredientsInStock(recipe)"
                            size="small" 
                            class="ms-1" :color="productInCart(recipe) ? 'primary' : 'success'"  
                            @click="productInCart(recipe) ? addToCart(recipe) : readyItemInCart()"
                            :icon="productInCart(recipe) ? 'iconoir:cart-alt' : 'bi:clipboard-check' " 
                          />
                          <div v-else >
                            <v-chip class="bg-error" style="font-size:10px" >
                              Agotado
                            </v-chip>
                          </div>
                        </div>
                        <div v-else>
                          <v-skeleton-loader
                            class="ma-0 cart-skeleton-button "
                            max-width="200"
                            type="button"
                          ></v-skeleton-loader>
                        </div>
                      </div>

                      <v-btn size="x-small" v-if="getCurrentAccount.rol_id !== 3" class="d-block d-md-none" color="secondary" @click="selectRecipe(recipe.id)" icon="majesticons:plus-line" />
                      <v-btn size="small" v-if="getCurrentAccount.rol_id !== 3" class="d-none d-md-block mx-2" color="secondary" @click="showAction(recipe.id,'viewRecipe')" icon="carbon:view" />
                      <v-btn size="small" v-if="getCurrentAccount.rol_id !== 3" class="d-none d-md-block mx-2" color="secondary" @click="showAction(recipe.id, 'updateRecipe' )" icon="line-md:edit-twotone-full" />
                      <v-btn size="small" v-if="getCurrentAccount.rol_id !== 3" class="d-none d-md-block mx-2" color="error" @click="showAction(recipe.id, 'deleteRecipe')" icon="mi:delete" />
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
      <template v-else>
        <VCard class="mt-3 w-100 d-flex justify-center align-center py-5" >
          <h2>No tienes recetas creadas</h2>
        </VCard>
      </template>

    </VCol>
    <div v-if="isRecipe" >
      <div class="modal animate__animated animate__slideInLeft"  id="viewRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                              <div class="d-flex">
                                
                                <v-tooltip text="Imprimir etiquetas" v-if="getCurrentAccount.rol_id == 1">
                                  <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" icon="mingcute:print-line" @click="showInterModal('printTag')"   class="me-5" variant="tonal" />
                                  </template>
                                </v-tooltip>
                                <v-tooltip text="Ver pasos">
                                  <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" icon="material-symbols:menu-book-outline"  @click="showInterModal('stepsRecipe')"   class="me-5" variant="tonal" />
                                  </template>
                                </v-tooltip>
                              </div>
                            </div>
                           </div>
                          <VCardText class="px-1" style="line-height: 1.5;">
                            {{ selectedRecipe.description}}
                          </VCardText>
                          <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
                            <VCardText class="text-subtitle-1 pt-4 pb-2 px-1">
                              <div class="font-weight-medium"><h3>Ingredientes cooking 5G:</h3> </div> 
                              <div class="font-weight-bold mt-2" v-for="(ingredient, index) in selectedRecipe.cooking_ingredients" :key="index">
                                <div class="d-flex align-center">

                                  <a  class="word-break w-80"
                                    :class="validateIsgoodProduct(ingredient, 'blank-modal', '' ) " 
                                    @click="selectProductView(index)" 
                                  > 
                                    - {{ `${ingredient.pivot.quantity} ${ingredient.type_of_unit} ${ingredient.pivot.quantity.length > 1 ?'de':''}`}} {{ ingredient.title }}
                                    
                                  </a>
                                  <div class="w-50 d-flex align-center" v-if="validateIsgoodProduct(ingredient, '', '(Sin stock)*') !== ''">
                                    <span class=" text-error  mx-2 fz-10 " >
                                      {{ 
                                        validateIsgoodProduct(ingredient, '', '(Sin stock)*')
                                      }}
                                    </span>
                                    <v-tooltip text="Notificar al admin" >
                                      <template v-slot:activator="{ props }">
                                        <v-btn 
                                        v-if="getCurrentAccount.rol_id == 3" 
                                        v-bind="props" 
                                        color="error"
                                         icon="gg:danger" 
                                         @click.once="notifyOutStockProduct(ingredient.id)"
                                         variant="tonal"
                                         density="compact"
                                        />
                                      </template>
                                    </v-tooltip>
                                  </div>
                                </div>
                              </div>
                            </VCardText>
                           <VCardText class="text-subtitle-1 pt-4 pb-2 px-1 mt-5" v-if="getCurrentAccount.rol_id == 3">
                              <div class="" v-if="isAllIngredientsInStock(selectedRecipe)">
                                <VRow class="ma-0 pa-0  mt-5 align-center">
                                  <VCol cols="12" md="8" class="mt-0 py-0 px-0">
                                      <VBtn 
                                        v-if="updateInCart"
                                       :color="productInCart(selectedRecipe) ? 'primary' : 'success'"  
                                       class="w-100 " 
                                       @click="productInCart(selectedRecipe) ? addToCart(selectedRecipe) : readyItemInCart()"
                                      > 
                                        {{ productInCart(selectedRecipe) ? 'Pedir receta (todos los productos)' : 'Receta agregada' }}
                                      </VBtn>
                                      <div v-else>
                                      <v-skeleton-loader
                                        class="ma-0 cart-skeleton-buttonx w-100 recipe-cart-button"
                                        max-width="100%"
                                        type="button"
                                      ></v-skeleton-loader>
                                    </div>
                                  </VCol>
                                </VRow>
                              </div>
                            </VCardText> 
                            <div class="mb-5 mt-4">
                              <div class="stock-notify px-3 py-4">
                                <p class="text-secondary pa-0 ma-0">
                                  <b class="text-error">(*) Nota:</b> 
                                  De forma ocasional, la entrega del pedido podría demorar algunos días si alguno de sus ingredientes 
                                  se encuentra sin stock en origen por parte de nuestros distribuidores.
                                </p>
                              </div>
                            </div>
                            <div class="mb-5 mt-2">
                              <div class="no-stock-notify px-3 py-4">
                                <p class="text-black pa-0 ma-0 font-weight-medium ">
                                  <b class="text-error">(*)</b> 
                                  <span>
                                    Si observa algún producto/s de esta receta sin stock, notifique al administrador pulsado el botón:
                                  </span>
                                 
                                   <span class=" d-flex align-center ">
                                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><g fill="#ff3e1d"><path d="M12 6a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1m0 10a1 1 0 1 0 0 2a1 1 0 0 0 0-2"/><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2M4 12a8 8 0 1 0 16 0a8 8 0 0 0-16 0" clip-rule="evenodd"/></g></svg>
                                    al lado de cada producto.
                                  </span>
                                </p>
                              </div>
                            </div>
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
      <div class="modal animate__animated animate__slideInLeft pe-0" id="stepsRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
          
          <div class="modal-dialog modal-lg pt-0 ma-0" style="width: 100%; height: 100vh;">
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
                      <v-col class="pa-0 pe-4">
                        <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideInternalModal()" ></v-btn>
                      </v-col>
                    </div>
                    <div class="d-flex justify-space-between  flex-column pa-2 pb-0 pt-md-5  px-md-5 h-100">
                      <VRow  class="mb-2 ma-0 mt-16">
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
                            :height="sliderPosition == selectedRecipe.preparation .length+2 ? 'max-content': '100%'"
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
                                            <div>
                                              <a 
                                                :class="validateIsgoodProduct(ingredient, 'blank-modal', 'recipe-notproduct' ) " 
                                                @click="selectProductView(index)" 
                                              > 
                                                - {{ `${ingredient.pivot.quantity} ${ingredient.type_of_unit} ${ingredient.pivot.quantity.length > 1 ?'de':''}`}} {{ ingredient.title }}

                                              </a>
                                              <span class=" text-error ms-2" v-if="validateIsgoodProduct(ingredient, '', '(Sin stock)*') !== ''">{{ 
                                                validateIsgoodProduct(ingredient, '', '(Sin stock)*')
                                              
                                              }}</span>
                                            </div>
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
      <div class="modal animate__animated nimate__slideInLeft pe-0"  id="updateRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                  <div>
                    <VCardItem class="justify-center w-100  py-md-6 pb-5 pt-16">
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
                                              type="number"
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
      <div class="modal animate__animated animate__fadeInDown pe-0" id="deleteRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
          
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
      <div class="modal animate__animated animate__fadeInDown pe-0" id="printTag" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
          
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
                    <div class="d-flex justify-space-between  flex-column pa-5 pa-md-5 ">
                      <VRow  class="mb-2 ma-0">
                        <VCol
                          cols="12"
                          class="py-0"
                        >
                          <div class="my-md-4 my-2 text-center">
                            <h2>Imprimir etiquetas</h2>
                          </div>
                        </VCol>
                      </VRow>
                      <VForm  id="tag_recipe_form" class="mt-5">
                        <VRow>
                          <VCol cols="12" md="4" class="form-group">
                            <VTextField
                              placeholder="Cantidad"
                              label="Cantidad"
                              type="number"
                              name="print_tag_quantity"
                              autocomplete="off"
                              v-model="tag.quantity"
                            />
                          </VCol>
                          <VCol cols="12" md="4" class="form-group">
                            <VTextField
                              placeholder="Fecha de elaboración"
                              label="Fecha de elaboración"
                              type="text"
                              name="new_recipe_type"
                              autocomplete="off"
                              v-model="tag.created"
                              id="tag_created"
                              @change="plusMonths()"
                            />
                          </VCol>
                          <VCol cols="12" md="4" class="form-group">
                            <VTextField
                              placeholder="Fecha de consumo preferente"
                              label="Fecha de consumo preferente"
                              type="text"
                              name="new_recipe_person_count"
                              autocomplete="off"
                              v-model="tag.consumo"
                              id="tag_consumo"
                            />
                          </VCol>
                        </VRow>
                        <VRow class="ma-0 pa-0  mt-8 align-center">
                          <VCol cols="12" md="4" class="form-group ms-0 ps-0">
                            <VTextField
                              placeholder="Iniciar en posicion"
                              label="Iniciar en posicion"
                              type="number"
                              name="pintinit"
                              autocomplete="off"
                              v-model="initPrint"
                            />
                          </VCol>
                          <VCol cols="12" md="6" offset-md="3" class="mt-0 py-0 px-0">
                            <v-col cols="auto" class="">
                              <VBtn  color="primary" class="w-100"  id="print" @click="printTag()" >Imprimir</VBtn>
                              <!-- @click="printTag(url+'api/recipes/client/print/' + selectedRecipe.id)" -->
                            </v-col>
                          </VCol>
                        </VRow>
                      </VForm>
                      <VDivider  />
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
                <div>
                  <VCardItem class="justify-center w-100  py-md-6 pb-5 pt-16">
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
                                        <VCol cols="6" class="form-group d-flex">
                                          <VTextField
                                            placeholder="Cantidad"
                                            label="Cantidad"
                                            type="number"
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
          class="drawer-bottom"
          :class="`${drawer ? 'd-block' : 'd-none'}` "
          style=""
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
    <buyCart v-if="getCurrentAccount.rol_id == 3" />  
    <printTags @hide="hideModal()" v-if="getCurrentAccount.rol_id && getCurrentAccount.rol_id !== 3" />
  </VRow>
</template>
<style lang="scss" >
  .drawer-bottom{
    height: 180px; 
    background: #f1f1f1; 
    border-top-right-radius: 20px; 
    border-top-left-radius: 20px; 
    overflow-y: visible;
  }
  svg{
    outline:none
  }
  .cart-skeleton-button{
    width: 40px;
    height: 40px;
    border-radius: 10%!important;
    & > .v-skeleton-loader__bone{
      margin: 0px!important;
      border-radius: 50%!important;

    }
  }
  .cart-skeleton-buttonx{
    width: 40px;
    height: 40px;
    border-radius: 10%!important;
    & > .v-skeleton-loader__bone{
      margin: 0px!important;
      border-radius: 10%!important;

    }
  }
  .recipe-cart-button > .v-skeleton-loader__button{
    max-width:100%!important
  }
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
  .v-navigation-drawer{
    height: 190px!important;
    
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
  .no-stock-notify{
    background: #ffe0db;
    border-radius: 15px;
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
  .fz-10{
    font-size: 0.85rem;
  }
  .w-80{
    width: 80%;
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
      tag:{
        quantity:1,
        created:'',
        consumo:'',
      },
      initPrint:'',
      isRecipe:false,
      sliderPosition:1,
      pagination:{
        currentPage:1,
        totalPage:0,
      },
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
      url:import.meta.env.VITE_VUE_APP_BACKEND_URL,
      printDates:{},
    }),
    methods:{
      isAdmin(){
        return window.localStorage.is_admin =='false'
      },
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
          if(id=='cooking_ingredients') newIngredient.pivot = { quantity:''}, newIngredient.lotes = [];
          if(id=='ingredients') newIngredient.quantity = ''
        }


        data.typeaction == 'new' 
        ? this.newRecipe[id].push(newIngredient)
        : this.selectedRecipe[id].push(newIngredient)
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
      maxStockRecipeInput(product){
        let minus = 0;
        product.cooking_ingredients.forEach((ingredient, index)=>{
          if(index==0) minus = ingredient.total_stock /parseFloat(ingredient.pivot.quantity)
  
          minus = minus > (ingredient.total_stock /parseFloat(ingredient.pivot.quantity) )
          ? ingredient.total_stock /parseFloat(ingredient.pivot.quantity) 
          : minus
          
        })
        // console.log(minus.toFixed(0))
        return minus.toFixed(0)
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
      selectRecipe(idAccount){
        this.isRecipe = false
        this.emitter.emit('displayOverlayLoad', true)
        return new Promise((resolve) => {
          this.$store
            .dispatch(GET_RECIPE_BY_ID, idAccount)
            .then((response) => {
              this.selectedRecipe = Object.assign({}, response.data); 
              this.selectedRecipe.ingredients = JSON.parse(response.data.ingredients)
              this.selectedRecipe.preparation = JSON.parse(response.data.preparation)
              this.isRecipe = true
              setTimeout(() => {
                this.emitter.emit('displayOverlayLoad', false)
                if(window.screen.width < 480 && this.getCurrentAccount.rol_id !== 3) this.drawer = true;
                resolve(response.data);
              }, 300);
            })
        })
        .catch((err) => {
          console.log(err)
          resolve(false);
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
      async showAction(id, modal){
       await this.selectRecipe(id).finally((data)=>{
          this.showModal(modal)
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
        if(modal=='printTag')this.initPrintDate()
        this.internalModal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.internalModal.show()
      },
      hideModal(action=""){
        
        this.modal.hide();
        if(action !==""){
          setTimeout(() => {
            let trashElement = document.querySelectorAll('.modal-backdrop');
            trashElement.forEach((item)=>{
              document.querySelector('body').removeChild(item);
            })
          }, 100);
        }
        this.clearNewRecipeForm()
        // this.clearUpdateRecipeForm()

      },
      hideInternalModal(){
        this.internalModal.hide();
        this.sliderPosition = 1
        this.selectedProductInRecipe = {}
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
          setTimeout(() => {
            this.showSnackbar('success','Receta creada con exito')
            this.hideModal();
            this.clearUpdateRecipeForm()
            this.getRecipes()
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
          this.selectedProductInRecipe.product = this.selectedRecipe.cooking_ingredients[index];
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
          // this.selectedRecipe = {}
          this.destroyValidate('update_recipe_form_2')
          this.destroyValidate('update_recipe_form')

      },
      validateIsgoodProduct(ingredient, messageGood, messageBad){
        if(!ingredient.lotes_recipe) return messageBad  ;
        if(ingredient.lotes_recipe.length == 0) return messageBad == '(Sin stock)*' ? 'Eliminado' : 'recipe-notproduct text-decoration-line-through'
        if(ingredient.total_stock < 1) return messageBad
        if(ingredient.lotes_recipe[0].quantity <=0 || Math.round(moment.duration(moment(ingredient.lotes_recipe[0].due_date).diff(new moment())).as('days') ) < 0 ){
          return messageBad 
        }
       return ingredient.deleted_at==null 
        ? messageGood 
        : messageBad 

      },
      setQuantity(ingredient){
        let quantity = 1 
        if(ingredient.total_stock){

          quantity =  ingredient.pivot.quantity.replace(/[^0-9 ./\\]/g, '')
         
          if(quantity.split('/').length > 1){
            quantity = ( parseInt(quantity.split('/')[0])/parseInt(quantity.split('/')[1])).toFixed(3)
          }
        }
        return quantity
      },
      addToCart(recipe){
        this.updateInCart = false;
        setTimeout(() => {
          recipe.quantity = this.setQuantity(recipe)
          recipe.cartType =  2;

          let cache = [];
          const cartFormData = new FormData
          cartFormData.append('products', this.stringify(recipe))
          this.$store
            .dispatch(ADD_TO_CART, cartFormData)
              .then((data) =>{
                this.emitter.emit('getItems')
                setTimeout(() => {
                this.updateInCart = true;
                }, 800); 
              }).catch((err) => {
                console.log(err)
            });
        }, 200);    
      },
      stringify(obj) {
        let cache = [];
        let str = JSON.stringify(obj, function(key, value) {
          if (typeof value === "object" && value !== null) {
            if (cache.indexOf(value) !== -1) {
              // Circular reference found, discard key
              return;
            }
            // Store value in our collection
            cache.push(value);
          }
          return value;
        });
        cache = null; // reset the cache
        return str;
      },
      readyItemInCart(){
        this.showSnackbar('success','Producto ya agregado')
      },
      isAllIngredientsInStock(recipe){
        let isOk= true
        recipe.cooking_ingredients.forEach((item)=>{
          if(item.total_stock < 1 || this.validateIsgoodProduct(item, 'yes','no') == 'no') isOk = false ;
        })
        return isOk
      },
      productInCart(selectedProduct){   
          if(this.$store.cart){
            const data = this.$store.cart.find((product) => product.id == selectedProduct.id && product.cartType == 2);
            return data ? false : true
          }
  
          return true
    
      },
      allIngredients(){
        let all= ''
        this.selectedRecipe.ingredients.forEach((item)=>{
          all+= `${item.name.trim()}, `
        })
        this.selectedRecipe.cooking_ingredients.forEach((item, index)=>{
          all+= `<b class="text-decoration-underline">
            ${item.title.trim()}${(index+1) == this.selectedRecipe.cooking_ingredients.length? '.' : ','}
            </b> `
        })

        return all
      },
      printTag(){
        var ventana = window.open(this.printUrl(), 'PRINT', 'height=400,width=600');
        setTimeout(() => {
        // ventana.document.write('<html><head><title>' + document.title + '</title></head>');
        ventana.document.close();
        ventana.focus();
        ventana.print();
        ventana.close();
        return true;
        }, 3000);
      },
      initPrintDate(){
        this.printDates.tag_created = flatpickr(document.getElementById('tag_created'), {
          dateFormat: 'd/m/Y',
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.getElementById('tag_created').value = moment(selectedDate[0]).format('DD-MM-YYYY')
          }
        });
        this.printDates.tag_consumo = flatpickr(document.getElementById('tag_consumo'), {
          dateFormat: 'd/m/Y',
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.getElementById('tag_consumo').value = moment(selectedDate[0]).format('DD-MM-YYYY')
          }
        });
      },
      printUrl(){
        return `${this.url}api/recipes/client/print/${this.selectedRecipe.id}?quantity=${this.tag.quantity}&created=${this.tag.created}&consumo=${this.tag.consumo}&init=${this.initPrint}&`
      },
      plusMonths(){
        let date = moment(this.printDates.tag_created.selectedDates[0]).add(3, 'months').format('DD-MM-YYYY')
        this.printDates.tag_consumo.setDate(date)
        this.printDates.tag_consumo.set('minDate', date)
        this.tag.consumo = date
      },
      notifyOutStockProduct(id){

        this.$store
        .dispatch(CREATE_MESSAGE, id)
        .then((data)=> {
          this.showSnackbar('success','Notificación enviada con exito')
        })
      }
    },
    mounted(){
      this.getRecipes()
      this.validateFormItem('new_recipe_form')
      setTimeout(() => {
        this.updateInCart = true
      }, 1700);
    },
    created(){
      this.emitter.on("updateRecipesInCart", () => {
        this.updateInCart = false
        setTimeout(() => {
          this.updateInCart = true
        }, 1200);
      
      })
      this.emitter.on("updateRecipes", () => {
        this.getRecipes()
      })
      
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