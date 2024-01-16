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
            <v-chip v-if="!product.in_order" append-icon="fa-solid:truck-loading" color="warning" @click="selectProduct(product.id)">
              Agregar
            </v-chip>
            <v-chip v-else append-icon="fluent:box-checkmark-20-regular" color="success" >
              Agregado
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