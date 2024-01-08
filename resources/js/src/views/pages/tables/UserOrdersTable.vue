<script setup>
const props = defineProps({
  orders: Object,
})
const as = window
</script>

<template>
  <VTable class="border-bottom">
    <thead>
      <tr>
        <th style="width: 90px; text-align: center;">
          Fecha
        </th>
        <th class="text-uppercase">
          Numero de orden
        </th>
        <th style="width: 90px; text-align: center;">
          Track ID
        </th>
        <th style="width: 90px; text-align: center;">
          Cantidad de productos
        </th>
        
      </tr>
    </thead>

    <tbody>
      <tr
        v-for="order in orders"
        :key="order.id"
      >
        <td>
          {{ order.created_at }}
        </td>
        <td class="text-center"> 
          {{  orderNumberFormat(order.id) }}
        </td>
        <td class="text-center">
          {{  order.tranker_id }}
        </td>
        <td>
          {{ order.products.length }}
        </td>
      </tr>
    </tbody>
  </VTable>
</template>

<style>
@media only screen and (max-width: 600px){
  
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
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
    },

    
  };
</script>