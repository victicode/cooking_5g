<script setup>
import moment from 'moment';

const props = defineProps({
  lotes: Object,
})
const as = window

</script>

<template>
  <div class="w-100 overflow-x-table my-0 my-md-5">

    <VTable class="blote-bottom table-b">
      <thead>
        <tr>
          <th class="text-uppercase">
            Número de lote
          </th>
          <th style="text-align: center;">
            Fecha de entrada
          </th>
          <th style="text-align: center;">
            Fecha de vencimiento
          </th>
          <th style="text-align: center;">
            Cantidad
          </th>
          
        </tr>
      </thead>
  
      <tbody>
        <tr
          v-for="lote in lotes"
          :key="lote.id"
        >
          <td class="text-start px-8"> 
            <a href="http://" target="_blank" rel="noopener noreferrer">
              {{  lote.lote_code }}
            </a>
          </td>
          <td class="text-center">
            {{ moment(lote.created_at_lote).format('DD/MM/YYYY') }}
          </td>
          <td class="text-center">
            <v-chip :class=" Math.round(moment.duration(moment(lote.due_date).diff(new moment())).as('days') ) > 30 ? 'bg-success' : 'bg-warning'">
              {{
                moment(lote.due_date).format('DD-MM-YYYY') 
              }}
            </v-chip>
          </td>
          <td class="text-center">
            {{ lote.quantity }}
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
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

    }),
    methods:{
      loteNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
    },
    mounted(){
      setTimeout(() => {
        console.log(lotes)
      }, 1000);
    }

    
  };
</script>