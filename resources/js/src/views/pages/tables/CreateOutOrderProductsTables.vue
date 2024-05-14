<script setup>
  import moment from 'moment';

const props = defineProps({
  products: Object,
  recipes: Array
})
const as = window
</script>

<template>
  <div class="w-100 overflow-x-table">
    <VTable class="border-bottom table-b" style="">
      <thead>
        <tr>
          <th class="text-uppercase px-2">
            Nombre del producto
          </th>
          <th  class="px-2">
            Cantidad solicitadas
          </th>
          <th class="px-2">
            Estado
          </th>
        </tr>
      </thead>

      <tbody>
       <template v-for="(recipe, index) in recipes"> 
         <tr
           v-for="product in recipe.cooking_ingredients"
           :key="product.id"
         >
         <td class="px-2 ">
             <div class="d-block">
 
               {{ product.title }} 
               <v-chip color="primary"  v-if="flagTitle(product)!==''">
   
                 <span class="font-italic">
     
                   {{ recipe.title}}
                 </span>
               </v-chip>
             </div>
           </td>
           <td class="text-center px-2"> 
             {{  (parseFloat(recipe.pivot.quantity) * parseFloat(product.pivot.quantity)).toFixed(2) }}
           </td>
           <td class="text-center px-2"> 
             <v-chip  :append-icon="!product.in_order ? 'fa-solid:truck-loading' : 'fluent:box-checkmark-20-regular'" :color="!product.in_order ? 'warning' : 'success'" @click="selectProduct(product.id, index)">
               {{!product.in_order ? 'Agregar' : 'Agregado'}}
             </v-chip>
           </td>
         </tr>
       </template>
      </tbody>
    </VTable>
  </div>
</template>

<style>
@media only screen and (max-width: 600px){
  
  .table-b{
    width: 600px !important;
    max-width: max-content!important;
  }
  .overflow-x-table{
    overflow-x: scroll;
  }
  .v-menu > .v-overlay__content > .v-list{
    overflow: visible;
    box-shadow: 0 5px 5px -3px rgb(63 63 63 / 24%), 0 8px 10px 1px var(--v-shadow-key-penumbra-opacity), 0 3px 14px 2px var(--v-shadow-key-ambient-opacity);
  }
}
</style>
<script>
export default {
  data: () => ({

  }),
  methods:{
    flagTitle(product){
      return product.pivot.recipe_id 
        ? this.recipes.find((recipe) => recipe.id == product.pivot.recipe_id ).title
        :''
    },
    selectProduct(id, index){
      this.$emit('selectProduct',id,index)
    }
  },
  mounted(){
  }

};

</script>