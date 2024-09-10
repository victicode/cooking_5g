<template>
  <div>
    <div class="modal animate__animated animate__slideInLeft" id="deleteProductQuick" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
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
                        <div class="form-title__part1 mt-5">Eliminar producto </div>
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <div>
                    <VCardItem class="w-100  py-md-0  py-4 text-center px-0" >
                      <VCardTitle class="text-2xl font-weight-bold">
                        <div class="card-title ">
                          <div class="form-title__part1 mx-4 my-2 " style="text-wrap:wrap">
                              {{ validateSelect() ? selectedProduct.title  :'-----'   }}
                          </div>
                          <VCardText class="w-100 pb-5 "  >
                            <VForm  id="add_stock_form_unique">
                              <VRow class="my-0 px-md-6">
                                <VCol cols="12" md="12"  class="form-group" >
          
                                  <v-autocomplete
                                    :model-value="selectedProduct"
                                    :loading="loading"
                                    :items="productOption != undefined ?  productOption : selectedProduct.id !== null ?[ {id: selectedProduct.id, title: selectedProduct.title}] : []"
                                    label="Buscar Producto"
                                    item-title="title"
                                    item-value="id"
                                    placeholder="Buscar Producto"
                                    variant="outlined"
                                    name="product_stock_unique"
                                    clearable
                                    no-filter
                                    no-data-text="No se encontraron resultados"
                                    return-object
                                    @keyup="searchProduct($event)"
                                    @click:clear="clearSearchProduct()"
                                    @update:modelValue="selectProduct($event)"
                                  >
                                    <template v-slot:item="{ props, item }">
                                      <v-list-item
                                        v-bind="props"
                                        title=""
                                        class="d-flex flex"
                                      > 
                                        <div class="d-flex my-1">
                                          <span>
                                            {{item.title}}
                                          </span>
                                          <span class="text-error ms-1">
                                          {{  validateIsgoodProduct(item.raw, '', '(Sin stock)*') === '' ? '' : '(Agotado)' }}
                                          </span>
                                        </div>
                                      </v-list-item>
                                    </template>
                                  </v-autocomplete>
                                </VCol>
                              </VRow>
                              <div class="d-flex mt-4 justify-center w-100 flex-wrap mt-1">
                                <VRow class="my-0">

                                  <v-col cols="12" md="4">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Stock total:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' : totalStockByLotes(selectedProduct)  < 1 ? 'bg-error' : 'bg-success' ">
                                          {{ !validateSelect() ? '---- ':totalStockByLotes(selectedProduct)  }}
                                        </v-chip>
            
                                      </span>
                                    </div>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Stock vencido:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' : vencStock(selectedProduct)  < 1 ? 'bg-success' : 'bg-error'">
                                          {{ !validateSelect() ? '---- ':vencStock(selectedProduct)  }}
                                        </v-chip>
            
                                      </span>
                                    </div>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Stock activo:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' : activeStock(selectedProduct)  < 1 ? 'bg-error' : 'bg-success'">
                                          {{ !validateSelect() ? '---- ': activeStock(selectedProduct)  }}
                                        </v-chip>
            
                                      </span>
                                    </div>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Cantidad de lotes:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' :  selectedProduct.lotes.length < 1 ? 'bg-error' : 'bg-success'">
                                          {{ !validateSelect() ? '---- ': selectedProduct.lotes.length  }}
                                        </v-chip>
            
                                      </span>
                                    </div>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Lotes vencidos:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' : selectedProduct.lotes_recipe.length < 1 ? 'bg-error' :  'bg-success'">
                                          {{ !validateSelect() ? '---- ': selectedProduct.lotes_recipe.length  }}
                                        </v-chip>
            
                                      </span>
                                    </div>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Lotes Activos:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' : selectedProduct.lotes_recipe.length < 1 ? 'bg-error' :  'bg-success'">
                                          {{ !validateSelect() ? '---- ': (parseInt(selectedProduct.lotes.length) - parseInt(selectedProduct.lotes_recipe.length))   }}
                                        </v-chip>
            
                                      </span>
                                    </div>
                                  </v-col>
                                  <v-col cols="12" md="6">
                                    <div class="form-title__part1  text-h6 px-2 d-flex align-center justify-start font-weight-bold">
                                      Fecha de venc. proxima:
                                      <span class=" ms-2" >
                                        <v-chip :class="!validateSelect() ? 'bg-secondary' : isDueDate(selectedProduct)  < 1 ? 'bg-error' : 'bg-success' ">
                                          {{ 
                                            !validateSelect() 
                                            ? '---- '
                                            : selectedProduct.lotes_recipe.length > 0 
                                            ? selectedProduct.lotes_recipe[0].due_date
                                            : selectedProduct.lotes.length > 0 
                                            ? selectedProduct.lotes[selectedProduct.lotes.length - 1].due_date
                                            :''
                                           }}
                                        </v-chip>
                                      </span>
                                    </div>
                                  </v-col>
                                </VRow>
                              </div>
                              <VRow class="ma-0 pa-0  mt-8 align-center">
                                <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                                  <v-col cols="auto" class="">
                                    <VBtn  color="error" class="w-100 " @click="deleteProduct()"  id="delete_quick"> Eliminar </VBtn>
                                  </v-col>
                                </VCol>
                              </VRow>
                            </VForm>
                          </VCardText>
                        </div>
                      </VCardTitle>
                    </VCardItem>
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
  import { GET_PRODUCT_BY_SEARCH, DELETE_PRODUCT } from "@/core/services/store/product.module";
  import debounce from 'debounce';
  
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
      validateIsgoodProduct(ingredient, messageGood, messageBad){
        // console.log(ingredient)
        if(!ingredient.lotes_recipe) return messageBad;
        if(ingredient.deleted_at) return messageBad == '(Sin stock)*' ? 'Eliminado' : 'recipe-notproduct text-decoration-line-through'
        if(ingredient.total_stock < 1) return messageBad       
        if(ingredient.lotes_recipe.length == 0) return messageBad == '(Sin stock)*' ? 'Eliminado' : 'recipe-notproduct text-decoration-line-through'
        if(ingredient.lotes_recipe[0].quantity <= 0 || Math.round(moment.duration(moment(ingredient.lotes_recipe[0].due_date).diff(new moment())).as('days') ) < 0 ){
          return messageBad 
        }
       return ingredient.deleted_at == null 
        ? messageGood 
        : messageBad 

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
      activeStock(product){
        let total = 0

        product.lotes_recipe.forEach((lote)=>{
          total += lote.quantity;
        })
        return total
      },
      vencStock(){
        return  parseInt(this.totalStockByLotes(this.selectedProduct)) - parseInt(this.activeStock(this.selectedProduct))
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
        // this.getLastLoteNumber();
      },
      hideModal(){
        this.selectedProduct = [];
        this.getProducts();
        this.$emit('hiddenModal')
      },
      isDueDate(product){
        let today = new moment();
        let product_due_date = moment(product.lotes_recipe.length > 0 ? product.lotes_recipe[0].due_date : product.lotes.length > 0 ? product.lotes[product.lotes.length - 1].due_date : '');

        let diff_due_date = Math.round(moment.duration(product_due_date.diff(today)).as('days'));
        return diff_due_date;
      },
      deleteProduct(){
        this.$store
          .dispatch(DELETE_PRODUCT, this.selectedProduct.id)
          .then((response) => {
            this.showSnackbar('success', 'Producto eliminado')
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
      this.getProducts();
    },
    created(){
      this.emitter.on("updateList", () => {
        this.getProducts();
      })
    }

  };
</script>
