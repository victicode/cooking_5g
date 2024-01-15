<script setup>
import moment from 'moment';

const props = defineProps({
  lotes: Object,
})
const as = window

</script>

<template>


  <v-data-table-server
    v-model:items-per-page="itemsPerPage"
    :headers="headers"
    :items-length="lotes.length"
    :items="lotes"
    item-value="lote_code"
  ></v-data-table-server>

</template>

<style>
  .overflow-x-table{
    overflow: hidden;
  }
  
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
      itemsPerPage: 5,
      headers: [
        { title: 'Número de lotes', key: 'lote_code', align: 'end' },
        { title: 'Fecha de entrada', key: 'created_at', align: 'end' },
        { title: 'Fecha de vencimiento', key: 'due_date', align: 'end' },
        { title: 'Cantidad', key: 'quantity', align: 'end' },
      ],
    }),
    methods:{
      loteNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
    },

    
  };
</script>