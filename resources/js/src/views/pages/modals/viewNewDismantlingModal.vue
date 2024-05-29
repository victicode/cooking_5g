<template>
  <div class="modal animate__animated animate__slideInLeft" id="createDismantlingProduct" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                      <div class="form-title__part1">Crear Nuevo Despiece</div>
                      
                    </div>
                  </VCardTitle>
                </VCardItem>
                <VCardText class="w-100 pb-5 px-3 px-md-6">
                  <VRow class="productStepper">  
                    <VForm  id="new_dismantling_form">
                      <VRow>
                        <VCol cols="12"  class=" ">
                          <div class="img-content mx-auto">
                            <label for="newDismantling-img">
                              <VImg
                                width="200"
                                height="200"
                                class="rounded"
                                :src="newDesmantling.img"
                                style="border-radius:10%!important"
                                id="newDismantling-img-content"
                              />
                              <div class="overlay-img">
                                <VIcon color="white" size="x-large" icon="majesticons:image-plus"/>
                              </div>
                            </label>
                            <VCol cols="12" md="12"  class="form-group text-center ma-0 mt-0 pa-0">
                              <input type="file"  id="newDismantling-img" ref="DismantlingImg" name="new_dismantling_img" class="d-none" @change="onFileChange" >
                            </VCol>
                          </div>
                        </VCol>
                        <VCol cols="7" md="6" class="form-group">
                            <VTextField
                              placeholder="Nombre del producto"
                              label="Nombre del producto"
                              type="text"
                              name="new_dismantling_title"
                              autocomplete="off"
                              v-model="newDesmantling.title"
                              @keyup="formatLoteName($event)"
                            />
                        </VCol>
                        <VCol cols="5" md="4" class="form-group">
                          <VTextField
                            placeholder="Número de lote"
                            label="Número de lote"
                            type="text"
                            name="new_dismantling_init_lot"
                            autocomplete="off"
                            variant="underlined"
                            v-model="newDesmantling.init_lote"
                            disabled
                          />
                        </VCol>
                        <VCol cols="12" md="6" class="form-group">
                          <VTextField
                            id="dueDateNewDismantling"
                            placeholder="Fecha de vencimiento"
                            label="Fecha de vencimiento"
                            type="text"
                            name="new_dismantling_due_date"
                          />
                          <input type="hidden" id="date-input-val-dueDateNewDismantling" ref="due_date_dueDateNewDismantling" >
                        </VCol>
                        <VCol cols="6" class="form-group">
                          <VTextField
                            placeholder="Stock"
                            label="Stock"
                            type="number"
                            name="new_dismantling_stock"
                            autocomplete="off"
                            v-model="newDesmantling.stock"
                          />
                        </VCol>
                        <VCol cols="6" class="form-group">
                          <v-select
                              label="Tipo de unidad"
                              :items="['KG', 'UNI', 'PZAS']"
                              variant="outlined"
                              v-model="newDesmantling.unit"
                          ></v-select>
                        </VCol>
                        <VCol cols="12" md="6" class="form-group">
                          <VTextField
                            placeholder="Descripción corta"
                            label="Descripción corta"
                            type="text"
                            name="new_dismantling_short_description"
                            autocomplete="off"
                            v-model="newDesmantling.short_description"
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
                            name="new_dismantling_description"
                            v-model="newDesmantling.description"
                          ></v-textarea>
                        </VCol>
                      </VRow>
                      <VRow class="ma-0 pa-0  mt-8 align-center">
                        <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                          <v-col cols="auto" class="">
                            <VBtn  color="primary" class="w-100" type="submit"  disabled id="new_dismantling_form_button" > Siguente</VBtn>
                          </v-col>
                        </VCol>
                      </VRow>
                    </VForm>
                  </VRow>
                </VCardText>
              </div>
            </VCard>
          </VCol>
        </VCol>
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
  import * as bootstrap from 'bootstrap'
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import moment from 'moment';
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import { STORE_PRODUCT } from "@/core/services/store/product.module";

export default {
  data(){
    return {
      modal: '',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:5000,
      forms:'',
      stepperNewDismantling: 1,
      steps: 2,
      inputDate:'',
      newDesmantling:{
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
    }
  },
  methods:{
      onFileChange(e) {
        const file = e.target.files[0];
        return this.newDesmantling.img = URL.createObjectURL(file)
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
      hideModal(id){
        this.$emit('hiddenModal', id)
        this.destroyFormVal();
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      createdProduct(){
        this.readyButton('new_dismantling_form_button')
        let formData = new FormData();
        formData.append('title', this.newDesmantling.title);
        formData.append('description', this.newDesmantling.description);
        formData.append('short_description', this.newDesmantling.short_description);
        formData.append('initial_stock', this.newDesmantling.stock);
        formData.append('type_unit', this.newDesmantling.unit);
        formData.append('img', this.$refs.DismantlingImg.files[0]);
        formData.append('is_dismantling',  0);
        formData.append('due_date', this.$refs.due_date_dueDateNewDismantling.value);
        formData.append('initial_lote', this.newDesmantling.init_lote);


        this.$store
          .dispatch(STORE_PRODUCT, formData)
          .then((response) => {
            // this.filterColumn()
            this.showSnackbar('success', 'Despiece creado con exito')
            this.hideModal(response.data)
          })
          .catch((err) => {
            this.showSnackbar('error', err )
          })
        
      },
      validateFormItem(){
        const fieldToValidate = this.itemsValidateByForm()
        this.forms = formValidation(document.getElementById('new_dismantling_form'), {
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
      itemsValidateByForm(){
        let fieldByForm = {
          new_dismantling_img: {
            validators: {
              notEmpty: {
                message: "Debes subir una foto"
              },
            }
          },
          new_dismantling_title: {
            validators: {
              notEmpty: {
                message: "El nombre es obligatorio"
              },
              regexp: {
                regexp: /^[A-Za-z0-9À-ÿ .*-+/&@,$_ñ_ ]+$/i,
                message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
              },
            }
          },
          new_dismantling_description: {
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
          new_dismantling_short_description: {
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
          new_dismantling_init_lot:{
            validators: {
              notEmpty: {
                message: "El lote inicial es requerido"
              }
            }
          },
          new_dismantling_stock: {
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
          new_dismantling_due_date:{
            validators: {
              notEmpty: {
                message: "La fecha de vencimiento es requerida"
              }
            }
          },
        }
        return fieldByForm
      },
      formsActions(id){
        const sendButton = document.getElementById('new_dismantling_form_button')
       
        this.forms.on("core.form.valid", () => {
          this.createdProduct()
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
        this.forms.resetForm() 
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
      addValidate(id,type){
        // this.forms[id] 
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
        
        // console.log(newfield)
      },
      removeValidate(id){
        let form = document.getElementById(id),
        quantityInput = form.querySelectorAll('input[name*="product_desmantling_quantity_"]')[ form.querySelectorAll('input[name*="product_desmantling_quantity_"]').length - 1]
        try {
          this.forms[id].removeField(quantityInput.name)
        } catch (error) {
          // console.log('no hay validación activa')
        }
        
      },
      initDueDate(){
        this.inputDate = flatpickr(document.getElementById('dueDateNewDismantling'), {
          dateFormat: 'd/m/Y',
          minDate: "today",
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.querySelector('#date-input-val-dueDateNewDismantling').value = moment(selectedDate[0]).format('YYYY-MM-DD')
          }
        });
      },
      formatLoteName(e){
        let isLongWord = this.newDesmantling.title.split(" ")
        let loteTitle = isLongWord.length == 1
          ? isLongWord[0].substring(0,4).toUpperCase()
          : isLongWord.length >= 3
          ? isLongWord[0].substring(0,1).toUpperCase() + isLongWord[1].substring(0,1).toUpperCase() + isLongWord[2].substring(0,1).toUpperCase()
          : isLongWord[0].substring(0,2).toUpperCase() + isLongWord[1].substring(0,2).toUpperCase();

        let loteNumber = '000000'.slice( 0, 6 - 1 ) + 1;
        if(!e){
          this.stockOperation.lot = loteTitle +'-'+ loteNumber;
          return
        }
        this.newDesmantling.init_lote =  loteTitle +'-'+ loteNumber;

        this.forms.validateField('new_dismantling_init_lot')
        return
      },

  },
  mounted(){
    this.validateFormItem()
    this.initDueDate()
  }
}
</script>