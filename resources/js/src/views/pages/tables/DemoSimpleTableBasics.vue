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
          <v-chip :class="product.stock < 1 ? 'bg-error' : product.stock >= 30 ? 'bg-success' : 'bg-warning'">
            {{ $helper.numberFormat(product.stock) }} {{ product.type_of_unit }}
          </v-chip>

        </td>
        <td class="text-center">
          <v-tooltip text="Ver ficha de producto">
            <template v-slot:activator="{ props }">
              <VIcon  v-bind="props" class="" icon="mdi-show-outline" />
            </template>
          </v-tooltip>
          <v-tooltip text="Editar producto">
            <template v-slot:activator="{ props }">
              <VIcon class="ms-1" v-bind="props" icon="heroicons-outline:pencil-alt" />
            </template>
          </v-tooltip>
          <v-tooltip text="Agregar stock">
            <template v-slot:activator="{ props }">
              <VIcon class="ms-1" v-bind="props" icon="mdi-box-variant-closed-plus" />
            </template>
          </v-tooltip>
          <v-tooltip text="Eliminar producto">
            <template v-slot:activator="{ props }">
              <VIcon class="ms-1" v-bind="props" icon="bx-trash" />
            </template>
          </v-tooltip>
        </td>
      </tr>
    </tbody>
  </VTable>
</template>
