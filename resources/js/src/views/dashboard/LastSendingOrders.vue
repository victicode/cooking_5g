<script setup>
import moment from 'moment';
import { GET_LAST_ORDERS } from "@/core/services/store/order.module";

</script>

<template>
  <VCard title="Últimas Ordenes Enviadas" class="h-100">
    <VCardText class="px-md-2 px-4">
      <VList class="card-list">
        <VListItem
          class="py-3 border-bottom-4 px-1 px-md-2"
        >
          <VRow class="pa-0 m-0 align-center">
            <VCol cols="4" md="4" class="text-center hide-text">
              Fecha
            </VCol>
            <VCol cols="5" md="4" class="text-center hide-text">
              Tracker ID
            </VCol>
            <VCol cols="3" md="4" class="text-center hide-text">
              Opciones
            </VCol>
          </VRow>
         
        </VListItem>
        <VListItem
          v-for="order in orders"
          :key="order.id"
          class="py-3 border-bottom-4 px-1 px-md-2"
        >
          <VRow class="pa-0  align-center">
            <VCol cols="4" md="4" class="text-center">
              {{moment(order.created_at ).format('DD/MM/YYYY') }}
            </VCol>
            <VCol cols="5" md="4" class="text-center">
              #{{ order.trancker }}
            </VCol>
            <VCol cols="3" md="4" class="text-center">
              <VIcon icon="bx-show" />
            </VCol>

          </VRow>
         
        </VListItem>
        
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
  .card-list {
    --v-card-list-gap: 1.6rem;
  }
</style>
<script>
import { mapGetters } from "vuex";
  export default {
    data(){
      return{
        orders:[]
      }
    },
    methods:{
      getOrders(){
        this.$store
        .dispatch(GET_LAST_ORDERS,2)
        .then((data) => {
          if (data.code !==200) {
            console.log('ha ocurrido un error al intentar obtener las ordenes')  
            return
          }
          console.log(data)
          this.orders = data.data
        })
        .catch((e) => {

          console.log(e)
        });
      }
    },
    mounted(){
      this.getOrders()
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>
