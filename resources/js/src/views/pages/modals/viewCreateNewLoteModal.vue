<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="addStockInProduct" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                  <VCardItem class="justify-center w-100  py-md-6  py-4 pb-0   ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1 mt-5">Agregar stock de producto</div>
              
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <div>
                    <VCardItem class="w-100  py-md-0  py-4 text-center" >
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title ">
                          <div class="form-title__part1 mx-4 my-2 " style="text-wrap:wrap">
                              {{ validateSelect() ? selectedProduct.title  :'-----'   }}
                          </div>
                          <div class="d-flex mt-4 justify-center w-100 flex-wrap mt-md-0">
                            <div class="form-title__part1 mx-4 d-flex align-center justify-center ">
                              Stock actual:
                              <span class=" ms-2" >
                                <v-chip :class="!validateSelect() ? 'bg-secondary' : totalStockByLotes(selectedProduct)  < 1 ? 'bg-error' : totalStockByLotes(selectedProduct)  >= 30 ? 'bg-success' : 'bg-warning'">
                                  {{ !validateSelect() ? '---- ':totalStockByLotes(selectedProduct)  }}
                                </v-chip>
    
                              </span>
                            </div>
                            <div class="form-title__part1 mx-4 mt-2 mt-md-0 d-flex align-center justify-center" >
                              Stock nuevo:
                              <span class=" ms-2" v-if="!isNaN(selectedProduct.newStock)" >
                                <v-chip :class="!validateSelect() ? 'bg-secondary' : selectedProduct.newStock < 1 ? 'bg-error' : selectedProduct.newStock >= 30 ? 'bg-success' : 'bg-warning'">
                                  {{ !validateSelect() ? '---- ': !isNaN(selectedProduct.newStock) ? selectedProduct.newStock : '----'}}
                                </v-chip>
    
                              </span>
                            </div>
                          </div>
    
                        </div>
                      </VCardTitle>
                    </VCardItem>
                    <VCardText class="w-100 pb-5 px-4 px-md-6"  >
                      <VForm  id="add_stock_form_unique">
                        <VRow class="my-3">
                          <VCol cols="12" md="6"  class="form-group" >
    
                            <v-autocomplete
                              :model-value="selectedProduct"
                              :loading="loading"
                              :items="productOption != undefined ?  productOption : selectedProduct.id !== null ?[ {id: selectedProduct.id, title: selectedProduct.title}] : []"
                              label="Producto"
                              item-title="title"
                              item-value="id"
                              placeholder="Producto"
                              variant="outlined"
                              name="product_stock_unique"
                              clearable
                              no-filter
                              no-data-text="No se encontraron resultados"
                              return-object
                              @keyup="searchProduct($event)"
                              @click:clear="clearSearchProduct()"
                              @update:modelValue="selectProduct($event)"
                            ></v-autocomplete>
                          </VCol>
                          <VCol cols="6" md="6"  class="form-group " >
                            <VTextField
                              placeholder="Número de lote"
                              label="Número de lote"
                              type="text"
                              name="stock_add_lote_unique"
                              class=""
                              autocomplete="off"
                              disabled
                              color="red"
                              v-model="stockOperation.lote"
                            />
                          </VCol>
                          <VCol cols="6" md="6" class="form-group">
                            <VTextField
                              placeholder="Cantidad"
                              label="Cantidad"
                              type="Number"
                              name="stock_form_quatity_unique"
                              v-model="stockOperation.quantity"
                              @keyup="stockCalculate()"
                            />
                          </VCol>
                          <VCol cols="12" md="6" class="form-group" >
                            <VTextField
                              placeholder="Fecha de vencimiento"
                              label="Fecha de vencimiento"
                              type="text"
                              name="stock_due_date_unique"
                              ref="stock_due_date"
                              id="dueDateAddStock_unique"
                            />
                            <input type="hidden" id="date-input-val-dueDateAddStock_unique" ref="due_date_dueDateAddStock_unique" >
                          </VCol>
                        </VRow>
                        <VRow class="ma-0 pa-0  mt-8 align-center">
                          <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                            <v-col cols="auto" class="">
                              <VBtn  color="primary" class="w-100 " type="submit" disabled id="add_stock_form_unique_button"> Guardar </VBtn>
                            </v-col>
                          </VCol>
                        </VRow>
                      </VForm>
                    </VCardText>
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
  </div>
</template>
<script>
  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import { GET_PRODUCT_BY_SEARCH, ADD_STOCK, GET_LAST_LOTE, } from "@/core/services/store/product.module";
  import debounce from 'debounce';
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  
  export default {
    data(){
      return{
        selectedProduct:[],
        stockOperation:{
          quantity:0,
          product:{},
          lote:'',
        },
        snackShow:false,
        snackMessage:'',
        snackType:'',
        snacktimeOut:5000,
        modal: '',
        product:'',
        productOption:[],
        search:'',
        loading: false,
        inputDate:'',
        form:''
      }
    },
    methods:{
      getProducts(search = ""){
        this.$store
          .dispatch(GET_PRODUCT_BY_SEARCH, search)
          .then((response) => {
            this.productOption = response.data

          })
          .catch((err) => {
            return new Promise((resolve) => {
              resolve(false);
            });
          })

      },
      validateSelect(){
        return  Object.keys(this.selectedProduct).length > 2
      },
      totalStockByLotes(product){
        let total = 0

        product.lotes.forEach((lote)=>{
          total += lote.quantity;
        })
        return total
      },
      stockCalculate(){
        this.selectedProduct.newStock = parseInt(this.totalStockByLotes(this.selectedProduct))  + parseInt(this.stockOperation.quantity)
      },
      initDueDate(id){
        this.inputDate = flatpickr(document.getElementById(id), {
          dateFormat: 'd/m/Y',
          minDate: "today",
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.querySelector('#date-input-val-dueDateAddStock_unique').value = moment(selectedDate[0]).format('YYYY-MM-DD')
          }
        });
      },
      searchProduct(e){ 
        debounce(this.getProducts, 200)(e.target.value)
      },
      clearSearchProduct(){
        this.getProducts('')
        this.selectedProduct = ''
        this.stockOperation.lote =''
      },
      selectProduct(e){
        if(!e){ 
          return
        }        
        this.selectedProduct = e
        this.stockOperation.product = e.id
        this.form.enableValidator('stock_due_date_unique')
        this.getLastLoteNumber();
      },
      getLastLoteNumber(){
        this.stockOperation.lote = 'Cargando...'
        this.$store
        .dispatch(GET_LAST_LOTE, this.selectedProduct.id )
        .then((data)=> {
          this.stockOperation.lote_index = data ; 
          this.formatLoteName()
        })
          
      },
      formatLoteName(){
        let isLongWord =this.selectedProduct.title.split(" ")
        let index =  this.stockOperation.lote_index + 1;

        let loteTitle = isLongWord.length == 1
          ? isLongWord[0].substring(0,4).toUpperCase()
          : isLongWord.length == 3
          ? isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,1).toUpperCase() + isLongWord[2].substring(0,1).toUpperCase()
          : isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,2).toUpperCase();

        let loteNumber = '000000'.slice( 0, 6 - index.toString().length ) + index;
        this.stockOperation.lote = loteTitle +'-'+ loteNumber;
        this.form.validateField('stock_add_lote_unique')
        return
      },
      hideModal(){
        this.stockOperation = {
          quantity:0,
          product:{},
          lote:'',
        },
        this.selectedProduct = [];
        this.getProducts();
        this.form.disableValidator('stock_due_date_unique')
        this.inputDate.clear();
        this.$emit('hiddenModal')
      },
      validateFormItem(){
        const fieldToValidate = this.itemsValidateByForm()
        this.form = formValidation(document.getElementById('add_stock_form_unique'), {
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
        setTimeout( ()=>this.formsActions(), 500)
      },
      itemsValidateByForm(id){
        let fieldByForm = {
          stock_form_quatity_unique: {
            validators: {
              notEmpty: {
                message: "La cantidad es obligatoria"
              },
              regexp: {
                regexp: /^[0-9]+$/i,
                message: 'Debe ser numerico',
              },
              greaterThan: {
                  message: 'El valor debe ser mayor a 0',
                  min: 1,
              },
            }
          },
          stock_add_lote_unique:{
            validators: {
              notEmpty: {
                message: "El número de lote es obligatorio"
              },
            }
          },
          stock_due_date_unique:{
            validators: {
              notEmpty: {
                message: "La fecha de vencimiento es obligatorio"
              }
            }
          }
        }

        return fieldByForm
      },
      formsActions(){
        const sendButton = document.getElementById('add_stock_form_unique_button')
        this.form.on("core.form.valid", () => {
          this.updatedStockProduct()

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
      updatedStockProduct(){
        let formData = new FormData();
        formData.append('type', 1);
        formData.append('quantity', this.stockOperation.quantity);
        formData.append('lote', this.stockOperation.lote);
        formData.append('due_date', this.$refs.due_date_dueDateAddStock_unique.value );

        this.$store
          .dispatch(ADD_STOCK, {id:this.selectedProduct.id, data:formData})
          .then((response) => {
            this.showSnackbar('success', 'Stock actualizado con exito')
            this.hideModal()
          })
          .catch((err) => {
            console.log(err)
            this.showSnackbar('error', err )
          })
        
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      
    },
    mounted(){
      this.initDueDate('dueDateAddStock_unique');
      this.getProducts();
      this.validateFormItem();
    },
    created(){
      this.emitter.on("updateList", () => {
        this.getProducts();
      })
    }

  };
</script>
