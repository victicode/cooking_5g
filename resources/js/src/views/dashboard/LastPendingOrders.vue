<script setup>
import moment from 'moment';
import { GET_LAST_ORDERS } from "@/core/services/store/order.module";
</script>

<template>
  <VCard title="Ordenes Pendientes">
    <VCardText class="px-md-6 px-4">
      <VList class="card-list">
        <VListItem
          class="py-3 border-bottom-4 px-1 px-md-2"
        >
          <VRow class="pa-0 m-0 align-center">
            <VCol cols="4" md="2" class="text-center hide-text">
              Fecha
            </VCol>
            <VCol cols="5" md="3" class="text-center hide-text">
              Tracker ID
            </VCol>
            <VCol cols="2" class="text-center hide-text d-none d-sm-block">
              Creado por
            </VCol>
            <VCol cols="3" class="text-center hide-text d-none d-sm-block">
              Estado
            </VCol>
            <VCol cols="3" md="2" class="text-center hide-text">
              Opciones
            </VCol>
          </VRow>
         
        </VListItem>
        <VListItem
          v-for="order in orders"
          :key="order.id"
          class="py-3 border-bottom-4 px-1 px-md-2"
        >
          <VRow class="pa-0 m-0 align-center">
            <VCol cols="4" md="2" class="text-center">
              
                {{moment(order.created_at ).format('DD/MM/YYYY') }}
              
            </VCol>
            <VCol cols="5" md="3" class="text-center">
              
                #{{ order.trancker }}
              
            
            </VCol>
            <VCol cols="2" class="text-center d-none d-sm-block">
              
                {{ order.user.name }}
              
            </VCol>
            <VCol cols="3"  class="text-center d-none d-sm-block">
              <v-chip class="bg-warning">{{ order.status_info.status }}</v-chip>
              <!-- <v-chip class="bg-warning">{{ order.status_info }}</v-chip> -->
            </VCol>
            <VCol cols="3" md="2" class="text-center">
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
  h5{
    font-weight: 500;
  }
</style>
<script>

import { mapGetters } from 'vuex';
  export default {
    data(){
      return{
        orders:[]
      }
    },
    methods:{
      getOrders(){
        this.$store
        .dispatch(GET_LAST_ORDERS,1)
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
