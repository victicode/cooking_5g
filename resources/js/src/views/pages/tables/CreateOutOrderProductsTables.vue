<script setup>
  import moment from 'moment';

const props = defineProps({
  products: Object,
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
        <tr
          v-for="product in products"
          :key="product.id"
        >
          <td class="px-2">
            {{ product.title }}
          </td>
          <td class="text-center px-2"> 
            {{  product.pivot.quantity }}
          </td>
          <td class="text-center px-2"> 
            <v-chip  :append-icon="!product.in_order ? 'fa-solid:truck-loading' : 'fluent:box-checkmark-20-regular'" :color="!product.in_order ? 'warning' : 'success'" @click="selectProduct(product.id)">
              {{!product.in_order ? 'Agregar' : 'Agregado'}}
            </v-chip>
          </td>
        </tr>
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
    selectProduct(id){
      this.$emit('selectProduct',id)
    }
  }


};

</script>