<script setup>
import moment from 'moment';
import { GET_LAST_ORDERS } from "@/core/services/store/order.module";
</script>

<template>
  <VCard title="Buscar orden" class="searchOrderTracker">
    <VCardText class="px-md-4 px-2 py-1">
      <VRow class="ma-0  justify-center align-center justify-md-start pa-0 pb-2 px-0 mb-0 mb-md-2">
        <VCol cols="7" md="6" class="form-group pa-1">
          <VTextField
            placeholder="Buscar por Track ID"
            label="Buscar por Track ID"
            type="text"
            ref="tracker"
            name="tracker_id"
            @change="filterColumn()"
          />
        </VCol>
        <VCol cols="5" md="3" class="my-0 py-0">
          <VBtn
            color="white"
            class="bg-primary text-white w-100 mx-0 mx-md-5 my-2"
            @click="searchOrder()"
          >
            <span class="">Buscar</span>
          </VBtn>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>

<style lang="scss" >
  .card-list {
    --v-card-list-gap: 1.6rem;
  }
  .searchOrderTracker > .v-card-item{
    padding: 10px 1rem;
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
        selectedOrder:[]
      }
    },
    methods:{
      searchOrder(){
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
      // this.getOrders()
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>
