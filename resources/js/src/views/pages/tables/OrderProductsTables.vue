<script setup>
const props = defineProps({
  products: Object,
})
const as = window
</script>

<template>
  <VTable class="border-bottom">
    <thead>
      <tr>
        <th class="text-uppercase">
          Nombre del producto
        </th>
        <th style="width: 90px; text-align: center;">
          Cantidad
        </th>
        <th style="width: 90px; text-align: center;">
          Despieces
        </th>
      </tr>
    </thead>

    <tbody>
      <tr
        v-for="product in products"
        :key="product.id"
      >
        <td>
          {{ product.title }}
        </td>
        <td class="text-center"> 
          {{  product.pivot.quantity }}

        </td>
        <td class="text-center">
          Si
        </td>
      </tr>
    </tbody>
  </VTable>
</template>

<style>
@media only screen and (max-width: 600px){
  
  .v-overlay__content{
    /* top: 275px!important; */
    left: 28%!important;
  }
  .v-menu > .v-overlay__content > .v-list{
    overflow: visible;
    box-shadow: 0 5px 5px -3px rgb(63 63 63 / 24%), 0 8px 10px 1px var(--v-shadow-key-penumbra-opacity), 0 3px 14px 2px var(--v-shadow-key-ambient-opacity);
  }
}


</style>