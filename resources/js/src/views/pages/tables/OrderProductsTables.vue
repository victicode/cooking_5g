<script setup>
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
          <th style="text-align: start!important;" class="px-2">
            Lote
          </th>
          <th class="px-2">
            Fecha de vencimiento
          </th>
          <th style="width: 90px; " class="px-2">
            Cantidad
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
          <td class="px-2">
            {{ product.title }}
          </td>
          <td class="px-2">
            {{ product.lote.lote_code }}
          </td>
          <td class="text-center px-2">
            {{ product.lote.due_date}}
          </td>
          <td class="text-center px-2"> 
            {{  product.pivot.quantity }}
          </td>
          <td class="text-center px-2">
            Si
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<style>
@media only screen and (max-width: 600px){
  

  .v-menu > .v-overlay__content > .v-list{
    overflow: visible;
    box-shadow: 0 5px 5px -3px rgb(63 63 63 / 24%), 0 8px 10px 1px var(--v-shadow-key-penumbra-opacity), 0 3px 14px 2px var(--v-shadow-key-ambient-opacity);
  }
}


</style>