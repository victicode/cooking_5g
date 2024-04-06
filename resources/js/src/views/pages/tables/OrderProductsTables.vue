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
    <VTable class="border-bottom table-a" style="">
      <thead>
        <tr>
          <th class="text-uppercase px-2">
            Nombre del producto
          </th>
          <th style="width: 300px; " class="px-2">
            Cantidad solicitadas
          </th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="recipe in recipes"
          :key="recipe.id"
        >
          <td class="px-2 ">
            <div class="">

              {{ recipe.title }} 
              <!-- <v-chip color="primary"  v-if="flagTitle(recipe)!==''">
  
                <span class="font-italic">
    
                  {{ flagTitle(recipe) }}
                </span>
              </v-chip> -->
            </div>
          </td>
          <td class="text-center px-2"> 
            {{  recipe.pivot.quantity }}
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<style>
@media only screen and (max-width: 600px){
  
  .table-a{
    width: 100%!important;
    max-width: 100%!important;
    /* width: 600px!important;
    max-width: max-content!important; */
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