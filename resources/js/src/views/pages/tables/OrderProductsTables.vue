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
          <th style="width: 200px; " class="px-2">
            Cantidad solicitadas
          </th>
          <th style="width: 90px; " class="px-2">
            Despieces
          </th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="product in products"
          :key="product.id"
        >
          <td class="px-2 ">
            <div class="">

              {{ product.title }} 
              <v-chip color="primary"  v-if="flagTitle(product)!==''">
  
                <span class="font-italic">
    
                  {{ flagTitle(product) }}
                </span>
              </v-chip>
            </div>
          </td>
          <td class="text-center px-2"> 
            {{  product.pivot.quantity }}
          </td>
          <td class="text-center px-2">
            {{ product.is_dismantling ? 'Si' : 'No'}}
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<style>
@media only screen and (max-width: 600px){
  
  .table-b{
    width: 00px !important;
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
    url:import.meta.env.VITE_VUE_APP_BACKEND_URL,
  }),
  methods:{
    
    orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
    flagTitle(product){
      return product.pivot.recipe_id 
        ? this.recipes.find((recipe) => recipe.id == product.pivot.recipe_id ).title
        :''
    },
    actionModal(action){
      this.$emit('actionModal',action)
    }
  },
  mounted(){
  }


};
</script>