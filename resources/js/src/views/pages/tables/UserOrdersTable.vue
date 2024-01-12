<script setup>
import moment from 'moment';
import color from '../../../assets/plugins/formvalidation/src/js/validators/color';

const props = defineProps({
  orders: Object,
})
const as = window

</script>

<template>
  <div class="w-100 overflow-x-table my-5">

    <VTable class="border-bottom table-b">
      <thead>
        <tr>
          <th class="text-uppercase">
            Numero de orden
          </th>
          <th style="text-align: center;">
            Fecha
          </th>
          <th style="text-align: center;">
            Track ID
          </th>
          <th style="text-align: center;">
           Estado
          </th>
          <th style="width:115px; text-align: center;">
            Cantidad de productos
          </th>
          
        </tr>
      </thead>
  
      <tbody>
        <tr
          v-for="order in orders"
          :key="order.id"
        >
          <td class="text-start px-0 px-md-4"> 
            <a class="blank-modal" @click="showOrderModal(order.id)" rel="noopener noreferrer">

              #{{  orderNumberFormat(order.id) }}
            </a>
          </td>
          <td class="text-center">
            {{ moment(order.created_at).format('DD/MM/YYYY') }}
          </td>
          <td class="text-center">
            {{  order.trancker }}
          </td>
          <td class="text-start  d-flex justify-center"> 
            <v-chip :class="{'bg-error': order.status == 0, 'bg-warning': order.status == 1, 'bg-secondary': order.status == 2, 'bg-success': order.status == 3, }">
              {{ order.status_label.status }}
            </v-chip>
          </td>
          <td class="text-center">
            {{ order.products.length }}
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<style lang="scss">
  .overflow-x-table{
    overflow: hidden;
  }
  .blank-modal{
    text-decoration: underline;
    font-weight: bolder;
    cursor: pointer;
    &:hover{
      color:peru
    }
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
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
      showOrderModal(id){
        console.log(this.orders)
        console.log(id)
        const order = this.orders.filter((order)=> order.id == id )
        this.$emit('selectedOrder', order[0])
      }
    },

    
  };
</script>