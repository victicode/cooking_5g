<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="createProduct" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column pa-2 px-4 pa-md-5 ">
                  <VCardItem class="justify-center w-100  py-md-6  py-4   ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1">Crear Nuevo Producto</div>
                        
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
                    <VRow class="productStepper">  
                      <v-stepper v-model="stepperNewProduct">
                        <template v-slot:default="">
                          <v-stepper-header class="">
                            <v-stepper-item
                                :complete="stepperNewProduct > 1"
                                step="Detalles del producto"
                                :value="1"
                                icon="fluent-mdl2:product"
                                size="large"
                              >
                                Producto
                              </v-stepper-item>

                              <v-stepper-item
                                :complete="stepperNewProduct > 2"
                                step="Detalles del producto"
                                :value="2"
                                icon="fluent-mdl2:work-item"
                                size="large"
                              >
                                Stock
                              </v-stepper-item>
                          </v-stepper-header>
                          <v-stepper-window class="mx-2" >
                            <v-stepper-window-item
                              :value="1"
                            >
                              <template class="d-block" >
                                <VForm  id="new_product_form">
                                  <VRow>
                                    <VCol cols="12"  class=" ">
                                      <div class="img-content mx-auto">
                                        <label for="newProduct-img">
                                          <VImg
                                            width="200"
                                            height="200"
                                            class="rounded"
                                            :src="newProduct.img"
                                            style="border-radius:10%!important"
                                            id="newProduct-img-content"
                                          />
                                          <div class="overlay-img">
                                            <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                                          </div>
                                        </label>
                                        <VCol cols="12" md="12"  class="form-group text-center ma-0 mt-0 pa-0">
                                          <input type="file"  id="newProduct-img" ref="newProductImg" name="new_product_img" class="d-none" @change="onFileChange" >
                                        </VCol>
                                      </div>
                                    </VCol>
                                    <VCol cols="12" md="6" class="form-group">
                                        <VTextField
                                          placeholder="Nombre del producto"
                                          label="Nombre del producto"
                                          type="text"
                                          name="new_product_title"
                                          autocomplete="off"
                                          v-model="newProduct.title"
                                          @change="formatLoteName($event)"
                                        />
                                    </VCol>
                                    <VCol cols="12" md="6" class="form-group">
                                      <VTextField
                                        placeholder="Descripción corta"
                                        label="Descripción corta"
                                        type="text"
                                        name="new_product_short_description"
                                        autocomplete="off"
                                        v-model="newProduct.short_description"
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
                                        name="new_product_description"
                                        v-model="newProduct.description"
                                      ></v-textarea>
                                    </VCol>
                                    <VCol cols="6" md="4" class="form-group">
                                      <v-select
                                          label="Tipo de unidad"
                                          :items="['KG', 'UNI', 'PZAS']"
                                          variant="outlined"
                                          v-model="newProduct.unit"
                                      ></v-select>
                                    </VCol>
                                    <VCol cols="12" md="4" class="px-5">
                                      <v-switch
                                        color="primary"
                                        label="Tiene despieces" 
                                        :value="1" 
                                        v-model="newProduct.isDismantling"
                                        @change="validateSwitch($event)"
                                      />
                                    </VCol>
                                  </VRow>
                                  <VRow 
                                    class="ma-0 pa-0  mt-4 align-center" 
                                    v-if="newProduct.isDismantling"
                                    >
                                        <VCol cols="12" class="form-group">
                                          <h3>Despieces:</h3>
                                        </VCol>
                                        <VCol cols="12" md="4" class="mt-0 py-0 px-0">
                                          <v-tooltip text="Agregar nuevo despiece">
                                              <template v-slot:activator="{ props }">
                                                <v-col cols="auto" class="">
                                                  <VBtn v-bind="props" color="primary" class="w-100"  @click="addDismantlingInput(2)"><VIcon icon="bx-plus"/> Agregar despiece</VBtn>
                                                </v-col>
                                              </template>
                                            </v-tooltip>
                                        </VCol>
                                        <div id="" class="pa-0 ma-0 align-center w-100 desmantling_items" >
                                          <VRow  v-for="(item,index) in newProduct.dismantling"  v-bind:key="item.id" class="pa-0 ma-0 align-center w-100 mt-5 mt-md-0"  :id="'new_product_desmantling_'+index">
                                            <VCol cols="12"  md="6" class="form-group">
                                              <v-autocomplete
                                                :model-value="item.piece_product_id"
                                                :items="productOption[index] ?  productOption[index] :item.piece_product_id !== null ? [ {id: item.piece_product_id, title: item.products_pieces.title}] : []"
                                                label="Nombre del despiece"
                                                item-props="stock"
                                                item-title="title"
                                                item-value="id"
                                                placeholder="Nombre del despiece"
                                                variant="outlined"
                                                clearable
                                                autocomplete="off"
                                                no-filter
                                                :name="'product_desmantling_id_'+index"
                                                no-data-text="No se encontraron resultados"
                                                :menu-props="{'content-class':'dismantling_select'}"
                                                @keyup="searchDismantling($event,index)"
                                                @click:clear="clearSearchDismantling(index)"
                                                @update:modelValue="selectDismantling($event, index,2)"
                                                @update:menu="true"
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
                                                    <v-btn icon="mdi-cancel-bold" v-bind="props" size="small" @click="removeDismantlingInput(2, index)"></v-btn>
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
                                        <VBtn  color="primary" class="w-100" type="submit"  disabled id="new_product_form_button" > Siguente</VBtn>
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
                                <VForm  id="new_product_form_2">
                                  <VRow>
                                    <VCol cols="6" md="4" class="form-group">
                                      <VTextField
                                        placeholder="Número de lote"
                                        label="Número de lote"
                                        type="text"
                                        name="new_product_init_lot"
                                        autocomplete="off"
                                        variant="underlined"
                                        v-model="newProduct.init_lote"
                                        disabled
                                      />
                                    </VCol>
                                    <VCol cols="6" md="4" class="form-group">
                                      <VTextField
                                        placeholder="Stock"
                                        label="Stock"
                                        type="number"
                                        name="new_product_stock"
                                        autocomplete="off"
                                        v-model="newProduct.stock"
                                      />
                                    </VCol>
                                    
                                    <VCol cols="12" md="4" class="form-group">
                                      <VTextField
                                        id="dueDateNewProduct"
                                        placeholder="Fecha de vencimiento"
                                        label="Fecha de vencimiento"
                                        type="text"
                                        name="new_product_due_date"
                                      />
                                      <input type="hidden" id="date-input-val-dueDateNewProduct" ref="due_date_dueDateNewProduct" >
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
                                        <VBtn  color="primary" class="w-100" block type="submit" disabled id="new_product_form_2_button" :loading="loading" label="Guardar"> 
                                          Guardar
                                          <template v-slot:loader>
                                            <loadingButton />
                                          </template>
                                        </VBtn>
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
    <viewNewDismantlingModal @hiddenModal="hideNewDismantlingForm"  v-if="newDismantling"  />
  </div>
  
</template>

<script>
  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import { GET_LOTE_OF_PRODUCT, GET_PRODUCT_BY_SEARCH, STORE_PRODUCT, GET_LAST_LOTE, } from "@/core/services/store/product.module";
  import * as bootstrap from 'bootstrap'
  import viewNewDismantlingModal from '@/views/pages/modals/viewNewDismantlingModal.vue';
  import debounce from 'debounce';
  import loadingButton from "@/layouts/components/loadingButton.vue";

  export default {
    components: {
      viewNewDismantlingModal,
      loadingButton,
    },
    data: () => ({
      alertShow:false,
      alertMessage:'',
      alertType:'',
      forms:[],
      inputDate:[],
      loading: false,
      newDismantling: false,
      newProduct:{
        img:'images/product/default.png',
        title:'',
        description:'',
        short_description:'',
        stock:'',
        unit:'KG',
        isDismantling: 0 ,
        dismantling:[],
        init_due_date:'',
        init_lote:''
      },
      product:'',
      productOption:[],
      search:'',
      selectedIndex:'',
      selectedLote:{},
      selectedProduct:{},
      snackShow:false,
      snackMessage:'',
      snacktimeOut:5000,
      snackType:'',
      stepperNewProduct: 1,
      steps: 2,
      modal:''


    }),
    methods:{
      getProducts(search = "", index){
        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            this.productOption[index] = response.data
            this.productOption[index].push({
              id:-1,
              title: 'Añadir nuevo +'
            })

          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

      },
      onFileChange(e) {
        const file = e.target.files[0];
        return e.target.id == 'newProduct-img' 
          ? this.newProduct.img = URL.createObjectURL(file)
          : this.selectedProduct.product.img = URL.createObjectURL(file)
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
      hideNewDismantlingForm(id){
        this.modal.hide()
        this.newDismantling = false;

        if(id){
          this.newProduct.dismantling[this.selectedIndex.index].piece_product_id = id.id
          this.newProduct.dismantling[this.selectedIndex.index].products_pieces = id
          this.productOption[this.selectedIndex.index] = null;
          this.update()
        }
      },

      clearFilters(){
        document.querySelector('[name="product_title"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showAlert(type, messagge){
        this.alertShow = true;
        this.alertType = type
        this.alertMessage = messagge
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      removeDismantlingInput(type, index){
        this.productOption.splice(index, 1)
        const idButton = type == 2 
          ? 'new_product_form_button'
          : 'edit_product_form_button'

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')

        this.removeValidate(type == 2 
            ? 'new_product_form'
            : 'edit_product_form')

        setTimeout(() => {
            return type == 2 
          ? this.newProduct.dismantling.splice(index, 1)
          : this.selectedProduct.product.dismantling.splice(index, 1);
        }, 300);
      },
      addDismantlingInput(type){
        let newItem = {
          id:'',
          piece_product_id: null,
          product_id: '',
          products_pieces: { title: '', id:'' },
          quantity: '',
        }
        const idButton = type == 2 
          ? 'new_product_form_button'
          : 'edit_product_form_button'

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')

        type == 2 
        ? this.newProduct.dismantling.push(newItem)
        : this.selectedProduct.product.dismantling.push(newItem);

        type == 2 
        ? this.getProducts('',(this.newProduct.dismantling.length -1))
        : this.getProducts('',(this.selectedProduct.product.dismantling.length-1)+'_'+this.selectedProduct.product.id)
        
        setTimeout(() => {
          this.addValidate(
            type == 2 
            ? 'new_product_form'
            : 'edit_product_form', 'new'
          )
        }, 500);
      },
      searchDismantling(e, index){ 
        debounce(this.getProducts, 200)(e.target.value, index)
      },
      clearSearchDismantling(index){
        this.getProducts('',index)
      },
      selectDismantling(e,index,type){
        if(e==-1){
          this.selectedIndex = {
            index:index, 
            type:type
          };
          this.newDismantling = true;
          setTimeout(() => {
            this.showModal('createDismantlingProduct')
          }, 500);
          return
        }
        const idButton = 'new_product_form_button';

        const button = document.getElementById(idButton)

        this.disabledButton( button, 'remove')

        return this.newProduct.dismantling[index].piece_product_id = e
      },
      resetNewProductForm(){     
        this.newProduct = {
          img:'images/product/default.png',
          title:'',
          short_description:'',
          stock:'',
          unit:'KG',
          isDismantling: 0 ,
          dismantling:[],
          init_due_date:'',
          init_lote:''
        }
        
        this.$refs.newProductImg.value =''
        this.stepperNewProduct = 1
        try {
          this.inputDate['dueDateNewProduct'].clear();
        } catch (error) {
          // console.log(error)
        }
      },
      nextStep(){
        this.stepperNewProduct = 2;
        setTimeout(() => {
          this.validateFormItem('new_product_form_2')

          this.initDueDate('dueDateNewProduct')
        }, 500);
      },
      backStep(){
        this.stepperNewProduct = 1;
        setTimeout(() => {
          this.forms['new_product_form_2'].destroy()
        }, 300)
      },
      createdProduct(){
        this.sendingButton('new_product_form_2_button')
        this.loading = true
        let formData = new FormData();
        formData.append('title', this.newProduct.title);
        formData.append('description', this.newProduct.description);
        formData.append('short_description', this.newProduct.short_description);
        formData.append('initial_stock', this.newProduct.stock);
        formData.append('type_unit', this.newProduct.unit);
        formData.append('img', this.$refs.newProductImg.files[0]);
        formData.append('is_dismantling', this.newProduct.isDismantling ? 1 : 0);
        formData.append('due_date', this.$refs.due_date_dueDateNewProduct.value);
        formData.append('initial_lote', this.newProduct.init_lote);
        if (this.newProduct.isDismantling) {
          formData.append('dismantling', JSON.stringify(this.newProduct.dismantling) );
        }

        this.$store
          .dispatch(STORE_PRODUCT, formData)
          .then((response) => {
            if(response.code !==200) throw response

            this.showSnackbar('success', 'Producto creado con exito')
            this.hideModal()
            this.loading = false

          })
          .catch((err) => {
            this.showSnackbar('error', err )
            this.loading = false
          })
        
      },
      hideModal(){
        this.destroyFormVal()
        this.resetNewProductForm()
        this.$emit('hiddenModal')
      },
      update(){
        this.$emit('updateProducts')
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
          case 'new_product_form':
            fieldByForm = {
              new_product_img: {
                validators: {
                  notEmpty: {
                    message: "Debes subir una foto"
                  },
                }
              },
              new_product_title: {
                validators: {
                  notEmpty: {
                    message: "El campo de titulo es obligatorio"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_product_description: {
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
              new_product_short_description: {
                validators: {
                  notEmpty: {
                    message: "La descripción corta es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'new_product_form_2':
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
        }
        return fieldByForm
      },
      formsActions(id){
        const sendButton = document.getElementById(id+'_button')
       
        this.forms[id].on("core.form.valid", () => {
          switch (id) {
            case 'new_product_form':
              this.nextStep()
              
              break;
            case 'new_product_form_2':
              this.createdProduct()
              break;
            case 'edit_product_form':
              this.updatedProduct()
              break;
            case 'add_stock_form':
              this.updatedStockProduct()
              break;
            default:
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
      destroyFormVal(){
        this.forms['new_product_form'].resetForm() 
        try {
          this.forms['new_product_form_2'].resetForm()  

          this.forms['new_product_form_2'].destroy()   
        } catch (error) {
          
        }
        

      },
      sendingButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
        
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      disabledButton(element, action){

        element.disabled = true;
        element.classList.add('v-btn--disabled')

        if(action == 'toggle' ){
          element.disabled = !element.disabled;
          element.classList.toggle('v-btn--disabled')
            return
        }
        element.disabled = false;
        element.classList.remove('v-btn--disabled')
      },
      validateSwitch(e){
        this.disabledButton( document.getElementById(e.target.closest('form').id+'_button'), 'toggle')
      },
      addValidate(id,type){
        let form = document.getElementById(id),
        quantityInput = type == 'new'
        ? form.querySelectorAll('input[name*="product_desmantling_quantity_"]')[ form.querySelectorAll('input[name*="product_desmantling_quantity_"]').length - 1]
        : form.querySelectorAll('input[name*="product_desmantling_quantity_"]'),
        fieldOptions={
            quantity: {
              validators: {
                notEmpty: {
                  message: "Agregar la cantidad de piezas "
                },
                numeric: {
                  message: "Debe ser númerico"
                }
              }
            }
          } 

        if(quantityInput && type == 'new'){
          this.forms[id].addField(quantityInput.name, fieldOptions.quantity)
          return
        }

        quantityInput.forEach((element) => {
          this.forms[id].addField(element.name, fieldOptions.quantity)

        });
      },
      removeValidate(id){
        let form = document.getElementById(id),
        quantityInput = form.querySelectorAll('input[name*="product_desmantling_quantity_"]')[ form.querySelectorAll('input[name*="product_desmantling_quantity_"]').length - 1]
        try {
          this.forms[id].removeField(quantityInput.name)
        } catch (error) {
        }
        
      },
      initDueDate(id){
        this.inputDate[id] = flatpickr(document.getElementById(id), {
          dateFormat: 'd/m/Y',
          minDate: "today",
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.querySelector('#date-input-val-'+id).value = moment(selectedDate[0]).format('YYYY-MM-DD')
          }
        });
      },
      clearDateAndLot(){
        if(this.stockOperation.type == 1) { this.getLastLoteNumber(); this.inputDate['dueDateAddStock'].clear() }
        if(this.stockOperation.type !== 1) this.inputDate['dueDateAddStock'].setDate(moment().format('DD-MM-YYYY'),true);

        this.stockOperation.lot  = ''
        this.stockOperation.due_date = this.stockOperation.type== 1  ? '': moment().format('DD-MM-YYYY')
        this.$refs.stock_due_date.value = moment().format('DD-MM-YYYY')

      },
      selectLote(e){
        const selectedLote = this.selectedProduct.product.lotes.filter((lot) => lot.id_lote === e)[0]
        this.stockOperation.lot_quantity = selectedLote.quantity ? selectedLote.quantity : this.stockOperation.lot_quantity ;
        this.stockOperation.lot = selectedLote.id ? selectedLote.id : this.stockOperation.lot;
        this.addValidateMax(this.stockOperation.lot_quantity)
      },
      formatLoteName(e){
        let isLongWord = !e
        ? this.selectedProduct.product.title.split(" ")
        : this.newProduct.title.split(" ")

        let index = !e
          ? this.stockOperation.lote_index + 1
          : 1;

        let loteTitle = isLongWord.length == 1
          ? isLongWord[0].substring(0,4).toUpperCase()
          : isLongWord.length == 3
          ? isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,1).toUpperCase() + isLongWord[2].substring(0,1).toUpperCase()
          : isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,2).toUpperCase();

        let loteNumber = '000000'.slice( 0, 6 - index.toString().length ) + index;
        if(!e){
          this.stockOperation.lot = loteTitle +'-'+ loteNumber;
          return
        }
        this.newProduct.init_lote =  loteTitle +'-'+ loteNumber;
        return
      },
      getLastLoteNumber(){
        this.$store
        .dispatch(GET_LAST_LOTE, this.selectedProduct.product.id )
        .then((data)=> {
          this.stockOperation.lote_index = data ; 
          this.formatLoteName(this.$event)
        })
          
      },
      TotalStockByLotes(product){
        let total = 0

        product.lotes.forEach((lote)=>{
          total += lote.quantity;
        })
        return total
      },
      addValidateMax(max){
        let form = document.getElementById('add_stock_form'),
        quantityInput = form.querySelector('input[name="stock_form_quatity"]'),
        fieldOptions={
          quantity: {
            validators: {
              notEmpty: {
                message: "Agregar la cantidad unidades"
              },
              numeric: {
                message: "Debe ser númerico"
              },
              lessThan: {
                message: "Cantidad supera el stock",
                max: max,
              },
            }
          }
        } 
        this.forms['add_stock_form'].addField(quantityInput.name, fieldOptions.quantity)
        return
      },
      countDismantlingActive(dismantlings){
        let count = 0;
        dismantlings.forEach((data)=>{
          if(data.products_pieces) count ++
        })
        
        return count
      },
    },
    computed: {
      disabled () {
        return this.stepperNewProduct === 1 ? 'prev' : this.stepperNewProduct === this.steps ? 'next' : undefined
      },
    },
    mounted(){
      this.validateFormItem('new_product_form')
    },
  };
</script>