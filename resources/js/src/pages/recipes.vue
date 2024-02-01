<script setup >
  import * as bootstrap from 'bootstrap'
  import { GET_RECIPES, GET_RECIPE_BY_ID} from "@/core/services/store/recipe.module";

  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";

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
          <VBtn @click=" showModal('createProduct')" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nueva receta</VBtn>

          </VCol>
        </VRow>
        <VRow class="ma-0  justify-center align-center justify-md-start pa-2 px-0 mb-0 mb-md-2">
          <VCol cols="6" md="4" class="form-group">
            <VTextField
              placeholder="Buscar nombre de receta"
              label="Buscar por receta"
              type="text"
              ref="recipe"
              name="recipe_title"
              @change="filterColumn()"
            />
          </VCol>
          <VCol cols="6" md="4" class="form-group">
            <VTextField
              placeholder="Buscar por chef"
              label="Buscar por chef"
              type="text"
              ref="chef"
              name="chef_name"
              @change="filterColumn()"
            />
          </VCol>
          <VCol cols="12" md="4" class="my-0 py-0">
            <VBtn
              color="white"
              class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
              @click="clearFilters()"
            >
              <span class="">Restaurar</span>
            </VBtn>
          </VCol>
        </VRow>
        <!-- <div class="card-datatable table-responsive">
          <table class="datatables-basic table recipes-table" id="data-table">
          </table>
        </div> -->
      </VCard>
      <template v-if="recipes.length > 0">
          <VCard class="mt-3" v-for="recipe in recipes" :key="recipe.id" >
            <VRow class="ma-0  justify-center align-center justify-md-start pa-0 px-md-5 px-0 mb-0 mb-md-0">
              <VCol cols="12"  class="d-flex align-center">
                <div>
                  <img src="https://casadepaellas.com/wp-content/uploads/2015/11/Paella-de-Marisco-300x300.jpg" width="100" height="100" style="border-radius: 20%;">
                </div>
                <div class="px-2 w-100">
                  <div>

                    <div class="d-flex text-md-start ">
                      <h3 class="w-100"> {{ recipe.title }} </h3>
                      
                    </div>
                    <div class="ms-1 d-flex justify-space-between align-end">
                      <div>
                        <h5 class="text-primary my-1"> Chef: <b class="text-decoration-underline cursor-pointer">{{ recipe.chef.name }}</b> </h5>
                        <v-chip color="primary" class="mt-1 pb-0">
                            <b>
                              {{ recipe.type }}
                            </b>
                        </v-chip>
                      </div>
                      <div class="d-flex">
                        <v-btn size="x-small" class="d-block d-md-none" color="secondary" @click="selectRecipe(recipe.id)" icon="majesticons:plus-line" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="secondary" @click="showAction(recipe.id,'viewRecipe')" icon="carbon:view" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="secondary" @click="showAction(recipe.id, 'deleteRecipe' )" icon="line-md:edit-twotone-full" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="error" @click="showAction(recipe.id, 'deleteRecipe')" icon="mi:delete" />
                      </div>
                    </div>
                  </div>
                </div>
              </VCol>
            </VRow>
          </VCard>
      </template>
    </VCol>
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
            <v-btn size="large" class="d-block mx-2 shadow-button" color="primary" @click="showModal('deleteRecipe')" icon="line-md:edit-twotone-full" />
            <v-btn size="large" class="d-block mx-2 shadow-button" color="error" @click="showModal('deleteRecipe')" icon="mi:delete" />
          </div>
        </div>
    </v-navigation-drawer>    
    <div v-if="Object.keys(selectedRecipe).length > 2" >
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
                              <!-- <VImg
                                width="200"
                                height="200"
                                class="rounded"
                                :src="selectedRecipe.product.img "
                              /> -->
                            </div>
                          </VCol>
                          <VCol cols="12" md="8" class="mt-0 pt-0 mb-4">
  
                            <VCardItem class="px-1 ">
                              <VCardTitle>{{ selectedRecipe.title }}</VCardTitle>
                              
                            </VCardItem>
                            <VCardItem class="px-1 ">
                              <VChip>
                                {{ selectedRecipe.type }}
                              </VChip>
                              
                            </VCardItem>
                            <VCardText class="px-1">
                              {{ selectedRecipe.description}}
                            </VCardText>
                            <div class="mt-0" style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
  
                              <VCardText class="text-subtitle-1 py-4 px-1">
                                <span class="font-weight-medium">Ingredientes:</span> <span class="font-weight-bold">
                                  {{ selectedRecipe.ingredients}}
                                </span>
                              </VCardText>
                        
                            </div>
                          </VCol>
                        </VRow>
                        
                        <div style="border-top: 1px solid rgba(119, 119, 119, 0.356)">
                          <VCardText class="text-subtitle-1 py-4 px-1">
                            <span class="font-weight-medium">Preparación: </span> 
                            <span class="font-weight-bold">
                              {{ selectedRecipe.preparation }}
                            </span>
                          </VCardText>
                        </div>
                      </div>
                    </div>
                  </VCard>
                </VCol>
              </VCol>
            </div>
          </div>
        </div>
        <!-- <div class="modal animate__animated animate__fadeInDown"  id="editRecipe" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                            <div class="form-title__part1">Editar Producto</div>
                            
                          </div>
                        </VCardTitle>
                      </VCardItem>
                      <VCardText class="mb-5  w-100 pa-0" v-if="alertShow">
                        <v-alert
                          :color="alertType"
                          :text="alertMessage"
                        ></v-alert>
                      </VCardText>
                      <VCardText class="w-100 pb-5 px-3 px-md-6">
                        <VForm  id="edit_product_form">
                          <VRow>
                            
                            <VCol cols="12"  class=" ">
                              <div class="img-content mx-auto">
                                <label for="editProduct-img">
                                  <VImg
                                    width="200"
                                    height="200"
                                    class="rounded "
                                    :src="selectedRecipe.product.img "
                                    style="border-radius:10%!important"
                                  />
                                  <div class="overlay-img">
                                    <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                                  </div>
                                </label>
                                <VCol cols="12" md="6" class="form-group">
                                  <input type="file" id="editProduct-img" ref="editProductImg" name="edit_product_img"  class="d-none" @change="onFileChange" >
                                </VCol>
                              </div>
                            </VCol>
                            <VCol cols="12" md="6" class="form-group">
                              <VTextField
                                placeholder="Nombre del producto"
                                label="Nombre del producto"
                                type="text"
                                name="edit_product_title"
                                v-model="selectedRecipe.product.title"
                                
                              />
                            </VCol>
                            <VCol cols="12" md="6" class="form-group">
                              <VTextField
                                placeholder="Descripción corta"
                                label="Descripción corta"
                                type="text"
                                name="edit_product_description_short"
                                v-model="selectedRecipe.product.type"
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
                                name="edit_product_description"
                                v-model="selectedRecipe.product.description"
                              ></v-textarea>
                            </VCol>
                            
                            <VCol cols="6" md="6" class="form-group">
                              <v-combobox
                                label="Tipo de unidad"
                                :items="['Selecciona uno','KG', 'UNI', 'PZAS']"
                                variant="outlined"
                                v-model="selectedRecipe.product.type_of_unit"
                              ></v-combobox>
                            </VCol>
                            <VCol cols="12" md="6" >
                              <v-switch
                                color="primary"
                                label="Tiene despieces" 
                                v-model="selectedRecipe.product.is_dismantling" :value="1" 
                                @change="validateSwitch($event)"
                              />
                            </VCol>
                          </VRow>
                          <VRow class="ma-0 pa-0  mt-4 align-center" v-if="selectedRecipe.product.is_dismantling">
                                <VCol cols="12" class="form-group">
                                  <h3>Despieces:</h3>
                                </VCol>
                                <VCol cols="12" md="4" class="mt-0 py-0 px-0">
                                  <v-tooltip text="Agregar nuevo despiece">
                                      <template v-slot:activator="{ props }">
                                        <v-col cols="auto" class="">
                                          <VBtn v-bind="props" color="primary" class="w-100 " @click="addDismantlingInput(1)"><VIcon icon="bx-plus"/> Agregar despiece</VBtn>
                                        </v-col>
                                      </template>
                                    </v-tooltip>
                                </VCol>
                                <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                                  <VRow  v-for="(item,index) in selectedRecipe.product.dismantling"  v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'product_desmantling_'+index">
                                    <VCol cols="12"  md="6" class="form-group">
                                      <v-autocomplete
                                        :model-value="item.piece_product_id"
                                        :loading="loading"
                                        :items="productOption[index+'_'+selectedRecipe.product.id] != undefined ?  productOption[index+'_'+selectedRecipe.product.id] : item.piece_product_id !== null ?[ {id: item.piece_product_id, title: item.products_pieces.title}] : []"
                                        label="Nombre del producto"
                                        item-title="title"
                                        item-value="id"
                                        placeholder="Nombre del producto"
                                        variant="outlined"
                                        clearable
                                        no-filter
                                        no-data-text="No se encontraron resultados"
                                        :name="'product_desmantling_id_'+index"
                                        @keyup="searchDismantling($event, index+'_'+selectedRecipe.product.id)"
                                        @click:clear="clearSearchDismantling(index+'_'+selectedRecipe.product.id)"
                                        @update:modelValue="selectDismantling($event, index, 1)"
                                      ></v-autocomplete>
                                    </VCol>
                                    <VCol cols="8"  md="4" class="form-group">
                                      <VTextField
                                        placeholder="Unidades que trae"
                                        label="Unidades que trae"
                                        type="number"
                                        :name="'product_desmantling_quantity_'+index"
                                        v-model="item.quantity"
                                        
                                      />
                                    </VCol>
                                    <VCol cols="4" md="1" class="form-group pa-0">
                                      <v-tooltip text="Eliminar despiece">
                                        <template v-slot:activator="{ props }">
                                          <v-col cols="auto" class="">
                                            <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeDismantlingInput(1,index)"></v-btn>
                                          </v-col>
                                        </template>
                                      </v-tooltip>
                                    </VCol> 
                                  </VRow>
                                </div>
                          </VRow>
                          <VRow class="ma-0 pa-0  mt-8 align-center">
                            <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                              <v-col cols="auto" class="">
                                <VBtn  color="primary" class="w-100 " type="submit" disabled id="edit_product_form_button" > Guardar </VBtn>
                              </v-col>
                            </VCol>
                          </VRow>
                        </VForm>
                      </VCardText>
                    </div>
                  </VCard>
                </VCol>
              </VCol>
            </div>
          </div>
        </div> -->
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
                            <h2>Eliminar Producto</h2>
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
    </div>
    <div class="modal animate__animated animate__fadeInDown"  id="createProduct" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
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
                    <VCardText class="mb-5  w-100 pa-0" v-if="alertShow">
                      <v-alert
                        :color="alertType"
                        :text="alertMessage"
                      ></v-alert>
                    </VCardText>
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
                
                                              <input type="file"  id="newRecipe-img" ref="newRecipeImg" name="new_recipe_img" class="d-none" @change="onFileChange" >
                                            </VCol>
                                          </div>
                                        </VCol>
                                        <VCol cols="12" md="6" class="form-group">
                                            <VTextField
                                              placeholder="Nombre de la receta"
                                              label="Nombre de la receta"
                                              type="text"
                                              name="new_recipe_title"
                                              autocomplete="off"
                                              v-model="newRecipe.title"
                                            />
                                        </VCol>
                                        <VCol cols="12" md="6" class="form-group">
                                            <VTextField
                                              placeholder="EJ: Postre, Entrada, Plato fuerte"
                                              label="Tipo de plato"
                                              type="text"
                                              name="new_recipe_type"
                                              autocomplete="off"
                                              v-model="newRecipe.type"
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
                                      <VRow>

                                        <VCol cols="6" md="4" class="form-group" v-for="(item, index) in newRecipe.ingredients" :key="index">
                                          <VTextField
                                            placeholder="EJ: Azucar, Sal"
                                            :label="`Ingrediente ${(index+1)}`"
                                            type="text"
                                            :name="`new_recipe_stock_${(index+1)}`"
                                            autocomplete="off"
                                            v-model="item.name"
                                          />
                                        </VCol>
                                      </VRow>
                                      <VRow class="ma-0 pa-0  mt-8 align-center">
                                        <VCol cols="5" md="4"  class="mt-0 py-0 px-0">
                                          <v-col cols="auto" class="">
                                            <VBtn  color="secondary" class="w-100" @click="backStep()" >
                                              <span class="d-block d-md-hidden ">
                                                <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
                                              </span>
                                              <span class="d-md-block d-none ">Volver</span>
    
                                              
                                            </VBtn>
                                          </v-col>
                                        </VCol>
                                        <VCol cols="5" md="4" offset="2" offset-md="4" class="mt-0 py-0 px-0">
                                          <v-col cols="auto" class="">
                                            <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_recipe_form_2_button"> Siguiente</VBtn>
                                          </v-col>
                                        </VCol>
                                      </VRow>
                                    </VForm>
                                  </template>
                                </v-stepper-window-item>
                                <v-stepper-window-item
                                  :value="3"
                                >
                                  <template class="d-block">
                                    <VForm  id="new_recipe_form_3">
                                      <VRow>
                                        <VCol cols="6" md="6" class="form-group">
                                          <VTextField
                                            placeholder="Número de lote"
                                            label="Número de lote"
                                            type="text"
                                            name="new_recipe_init_lot"
                                            autocomplete="off"
                                            variant="underlined"
                                            v-model="newRecipe.init_lote"
                                            disabled
                                          />
                                        </VCol>
                                        <VCol cols="6" md="6" class="form-group">
                                          <VTextField
                                            placeholder="Stock"
                                            label="Stock"
                                            type="number"
                                            name="new_recipe_stockss"
                                            autocomplete="off"
                                            v-model="newRecipe.stock"
                                          />
                                        </VCol>
                                      
                                      </VRow>
                                      <VRow class="ma-0 pa-0  mt-8 align-center">
                                        <VCol cols="5" md="4"  class="mt-0 py-0 px-0">
                                          <v-col cols="auto" class="">
                                            <VBtn  color="secondary" class="w-100" @click="stepperNewProduct = 2" >
                                              <span class="d-block d-md-hidden ">
                                                <VIcon icon="ion:arrow-back-outline" color="white"></VIcon>  
                                              </span>
                                              <span class="d-md-block d-none ">Volver</span>
    
                                              
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
                                  </template>
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
</style>

<script>

  export default {
    data: () => ({
      modal: '',
      items:['editar','eliminar', 'ver'],
      drawer:false,
      selectedRecipe:{},
      recipes:[],
      stepperNewProduct: 1,
      steps: 3,
      forms:[],
      newRecipe:{
        img:'images/product/default.png',
        title:'',
        type:'',
        description:'',
        ingredients:[
          {
            name:'',
            cantidad:'',
          }
        ],
        unit:'KG',
        isDismantling: 0 ,
        dismantling:[],
        init_due_date:'',
        init_lote:'',
        
      },
    }),
    methods:{
      nextStep(){
        console.log(this.stepperNewProduct )
        this.stepperNewProduct =  this.stepperNewProduct + 1 ;

        console.log(this.stepperNewProduct)

        
        setTimeout(() => {
          this.stepperNewProduct > 2 ? console.log('hh') : this.validateFormItem('new_recipe_form_2')
        }, 500);
      },
      backStep(){
        this.stepperNewProduct = this.stepperNewProduct - 1 ;
        setTimeout(() => {
          this.stepperNewProduct > 2 ? this.destroyValidate('new_recipe_form_3') : this.destroyValidate('new_recipe_form_2')
        }, 500);
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
                    regexp: /^[A-Za-z0-9À-ÿ .*-+,/&@$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'new_recipe_form_2':
            fieldByForm = {
              new_recipe_stock_1:{
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
            }
            break;
          case 'new_recipe_form_3':
            fieldByForm = {
              new_product_init_lot:{
                validators: {
                  notEmpty: {
                    message: "El lote inicial es requerido"
                  }
                }
              },
              new_product_stock: {
                validators: {
                  notEmpty: {
                    message: "Debes agregar un stock Inicial"
                  },
                  regexp: {
                    regexp: /^[0-9]+$/i,
                    message: "Debe ser númerico",
                  },
                }
              },
              new_product_due_date:{
                validators: {
                  notEmpty: {
                    message: "La fecha de vencimiento es requerida"
                  }
                }
              },
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
              this.nextStep()
              
              break;
            case 'new_recipe_form_2':
              this.nextStep()
              break;
            case 'new_recipe_form_3':
              this.createdProduct()
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
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      getRecipes(){
        this.$store.dispatch(GET_RECIPES).then((data)=>{
          this.recipes = data.data.data
        })
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('viewOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.change').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('changeStatusOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.cancel').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('cancelOrder')
              }, 500);
            })
          })	
        })
      },
      async selectRecipe(idAccount){
        this.$store
          .dispatch(GET_RECIPE_BY_ID, idAccount)
          .then((response) => {
            this.selectedRecipe = Object.assign({}, response.data);
            setTimeout(() => {
              
              if(window.screen.width < 480) this.drawer = true;

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
          }, 800);
        })
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
      onFileChange(e) {
        const file = e.target.files[0];
       return e.target.id == 'newRecipe-img' 
       ? this.newRecipe.img = URL.createObjectURL(file)
       : this.selectedProduct.product.img = URL.createObjectURL(file)
      },
    },
    mounted(){
      // this.initOptionsTable()
      // this.table = new DataTablesCore('#data-table', this.tableData)
      // this.bootstrapOptions();
      this.getRecipes()
      this.validateFormItem('new_recipe_form')
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>