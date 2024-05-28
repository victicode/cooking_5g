<script setup >
  import moment from 'moment';
  import { UPDATE_LOTE } from "@/core/services/store/product.module";
  import flatpickr from "flatpickr";
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
</script>
<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="updateLote" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                <div class="d-flex justify-space-between  flex-column pa-2 px-3 pa-md-5 ">
                  <VRow  class="mb-2 ma-0 mt-10 mt-md-0">
                    <VCol
                      cols="12"
                      class="py-0"
                    >
                      <div class="my-md-4 my-2 text-center">
                        <h2>Editar lote</h2>
                      </div>
                    </VCol>
                  </VRow>
                  <VDivider  />
                  <div v-if="selectLote">

                    <VCardItem class="w-100  py-md-0  py-2 text-center">
                        <VCardTitle class="text-2xl font-weight-bold">
                          <div class="card-title ">
                            <div class="form-title__part1 mx-4 my-2 " style="text-wrap:wrap">
                                {{ selectLote.product.title }} <br> ({{ selectLote.lote_code }})
                            </div>
                            <div class="d-flex mt-4 justify-center w-100 flex-wrap mt-md-0">
                              <div class="form-title__part1 mx-4 d-flex align-center justify-center ">
                                Stock actual:
                                <span class=" ms-2" >
                                  <v-chip :class="selectLote.quantity  < 1 ? 'bg-error' : selectLote.quantity  >= 30 ? 'bg-success' : 'bg-warning'">
                                    {{ selectLote.quantity }}
                                  </v-chip>
                                </span>
                              </div>
                            </div>
                          </div>
                        </VCardTitle>
                    </VCardItem>
                    <VForm  id="update_lote_form" class="mt-5">
                      <VRow class="my-3">
                        <VCol cols="6" md="6"  class="form-group" >
                          <VTextField
                            placeholder="Número de lote"
                            label="Número de lote"
                            type="text"
                            name="stock_add_lot"
                            class=""
                            v-model="selectLote.lote_code"
                            autocomplete="off"
                            disabled
                            color="red"
                          />
                        </VCol>
   
                        <VCol cols="6" md="6"  class="form-group px-2" >
                          <VTextField
                            placeholder="Fecha de vencimiento"
                            label="Fecha de vencimiento"
                            type="text"
                            name="lote_update_due_date"
                            ref="lote_update_due_date"
                            id="dueDateUpdateLote"
                          />
                          <input type="hidden" id="date-input-val-dueDateUpdateLote" ref="due_date_dueDateUpdateLote" >
                        </VCol>
                      </VRow>
                      <VRow>
                        <VCol cols="12" md="6" class="form-group">
                          <VTextField
                            placeholder="Cantidad"
                            label="Cantidad"
                            type="Number"
                            name="lote_update_quatity"
                            v-model="selectLote.quantity"
                          />
                        </VCol>
                      </VRow>
                      <div class="mt-5 w-100  d-block justify-center">
                        <VCardActions class=" justify-center w-100 d-md-flex  pb-1 pb-md-3   d-block">
                          <VBtn
                            color="white"
                            class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
                            id="update_lote_button"
                            type="submit"
                            disabled=""
                            
                          >
                            <span class="">Guardar</span>
                          </VBtn>
                        </VCardActions>
                      </div>
                    </VForm>
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
<style lang="scss">

</style>
<script>

  export default {
    props:['lote'],
    data: () => ({
      selectLote: false,
      snackShow: false,
      snackMessage: '',
      snackType: '',
      snacktimeOut: 2000,
      form:'',
    }),
    methods:{
      hideModal(){
        this.form.destroy()
        this.$emit('hiddenModal')
      },
      
      initDueDate(){
        this.inputDate = flatpickr(document.getElementById('dueDateUpdateLote'), {
          dateFormat: 'd/m/Y',
          minDate: "today",
          locale: Spanish,
          disableMobile:true,
          onClose: function (selectedDate) {
            document.querySelector('#date-input-val-dueDateUpdateLote').value = moment(selectedDate[0]).format('YYYY-MM-DD')
          }
        });
        this.inputDate.setDate(moment(this.selectLote.due_date).format('DD-MM-YYYY'),true)
        document.querySelector('#date-input-val-dueDateUpdateLote').value = moment(this.selectLote.due_date).format('YYYY-MM-DD')
      },
      assignLote(){
        this.selectLote = this.lote;
        setTimeout(() => {
          this.initDueDate()
          this.validateFormItem()
        }, 500);
      },
      validateFormItem(){
        const fieldToValidate = this.itemsValidateByForm()
        this.form = formValidation(document.getElementById('update_lote_form'), {
          fields: fieldToValidate,
          plugins: {
            trigger: new Trigger(),
            submitButton: new SubmitButton(),
            bootstrap: new Bootstrap(),
          }
        });
        setTimeout( ()=>this.formsActions(), 500)
      },
      itemsValidateByForm(id){
        let fieldByForm = {
          lote_update_quatity: {
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
          lote_update_due_date:{
            validators: {
              notEmpty: {
                message: "La fecha de vencimiento es obligatorio"
              }
            }
          }
        }
        return fieldByForm;
      },
      formsActions(){
        const sendButton = document.getElementById('update_lote_button')
        this.form.on("core.form.valid", () => {
          this.updatedLote()
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
      updatedLote(){
        let formData = new FormData();
        formData.append('quantity', this.selectLote.quantity);
        formData.append('lote', this.selectLote.lote_code);
        formData.append('due_date', this.$refs.due_date_dueDateUpdateLote.value );

        this.$store
          .dispatch(UPDATE_LOTE, {id:this.selectLote.id_lote, data:formData})
          .then((response) => {
            this.showSnackbar('success', 'Lote actualizado con exito')
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
      this.emitter.on("assignLote", () => {
        this.assignLote();
      })
    },
    created(){
      
    }
    
  };
</script>