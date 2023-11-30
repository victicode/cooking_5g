<template>
  <div>
    <template class="d-block d-sm-none">
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
                  <VIcon  class="me-2" v-bind="props"  icon="mdi-show-outline" />
                </template>
              </v-tooltip>
            </v-list-item>
            <v-list-item>
              <v-tooltip text="Editar producto">
                <template v-slot:activator="{ props }">
                  <VIcon class="me-2" v-bind="props" icon="heroicons-outline:pencil-alt" />
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
                  <VIcon class="me-2" v-bind="props" icon="bx-trash" />
                </template>
              </v-tooltip>
            </v-list-item>
          </v-list>
      </v-menu>
    </template>
  
    <template class="d-none d-sm-flex">
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
          <VIcon class="me-2" v-bind="props" icon="mdi-box-variant-closed-plus" />
        </template>
      </v-tooltip>
      <v-tooltip text="Eliminar producto">
        <template v-slot:activator="{ props }">
          <VIcon class="me-2" v-bind="props" icon="bx-trash" />
        </template>
      </v-tooltip>
    </template>
  </div>
</template>