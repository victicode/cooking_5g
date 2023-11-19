<script setup>
const props = defineProps({
  products: Object,
})
const as = window
</script>

<template>
  <VTable>
    <thead>
      <tr>
        <th class="text-uppercase">
          Nombre del producto
        </th>
        <th style="width: 90px; text-align: center;">
          stock
        </th>
        <th style="width: 150px; text-align: center;">
          opciones
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
          <!-- {{ product.stock}} -->
          <v-chip :class="product.stock < 15 ? 'bg-error' : product.stock >= 30 ? 'bg-success' : 'bg-warning'">
            {{ $helper.numberFormat(product.stock) }} {{ product.type_of_unit }}
          </v-chip>

        </td>
        <td class="text-center">
          <VIcon class="" icon="bx-show" />
          <VIcon class="ms-1" icon="heroicons-outline:pencil-alt" />
          <VIcon class="ms-1" icon="bx-trash" />

        </td>
      </tr>
    </tbody>
  </VTable>
</template>
