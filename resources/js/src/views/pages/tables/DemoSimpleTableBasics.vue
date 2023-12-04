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
          opcionesa
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
          <div class="d-block d-sm-none">

            <v-menu
                transition="scale-transition"
                :location="'top'"
                class="mb-10 "
              >
                <template v-slot:activator="{ props }">
                  <div
                    v-bind="props"
                  >
                  <VIcon  class="me-2" v-bind="props"  icon="mdi-plus" />
                </div>
                </template>
  
                <v-list class="d-flex">
                  <v-list-item
                  >
                    <v-tooltip text="Ver ficha de producto">
                      <template v-slot:activator="{ props }">
                        <VIcon  class="me-2" v-bind="props"  icon="line-md:circle-to-confirm-circle-transition" />
                      </template>
                    </v-tooltip>
                  </v-list-item>
                  <v-list-item>
                    <v-tooltip text="Editar producto">
                      <template v-slot:activator="{ props }">
                        <VIcon class="me-2" v-bind="props" icon="ic:outline-cancel" />
                      </template>
                    </v-tooltip>
                  </v-list-item>
                  <v-list-item>
                    <v-tooltip text="Agregar stock">
                      <template v-slot:activator="{ props }">
                        <VIcon class="me-2" v-bind="props" icon="mdi-box-variant-closed-plus" />
                      </template>
                    </v-tooltip>
                  </v-list-item>
                  <v-list-item>
                    <v-tooltip text="Eliminar producto">
                      <template v-slot:activator="{ props }">
                        <VIcon class="me-2" v-bind="props" icon="ic:outline-cancel" />
                      </template>
                    </v-tooltip>
                  </v-list-item>
                </v-list>
            </v-menu>
          </div>

          <div class="d-none d-sm-flex">
            <v-tooltip text="Ver ficha de producto">
              <template v-slot:activator="{ props }">
                <VIcon  class="me-2" v-bind="props"  icon="mdi-show-outline" />
              </template>
            </v-tooltip>
            <v-tooltip text="Editar producto">
              <template v-slot:activator="{ props }">
                <VIcon class="me-2" v-bind="props" icon="heroicons-outline:pencil-alt" />
              </template>
            </v-tooltip>
            <v-tooltip text="Agregar stock">
              <template v-slot:activator="{ props }">
                <VIcon class="me-2" v-bind="props" icon="icon-park-outline:update-rotation" />
              </template>
            </v-tooltip>
            <v-tooltip text="Eliminar producto">
              <template v-slot:activator="{ props }">
                <VIcon class="me-2" v-bind="props" icon="ic:outline-cancel" />
              </template>
            </v-tooltip>
          </div>
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