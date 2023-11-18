<script setup>
import chartInfo from '@images/cards/chart-info.png'
import creditCardSuccess from '@images/cards/credit-card-success.png'
import creditCardWarning from '@images/cards/credit-card-warning.png'
import paypalError from '@images/cards/paypal-error.png'
import walletPrimary from '@images/cards/wallet-primary.png'
import MoreBtn from '@core/components/MoreBtn.vue';
import moment from 'moment';
import { GET_LAST_PENDING_ORDERS } from "@/core/services/store/order.module";

const transactions = [
  {
    amount: +82.6,
    paymentMethod: 'Paypal',
    description: 'Send money',
    icon: paypalError,
    color: 'error',
  },
  {
    paymentMethod: 'Wallet',
    amount: +270.69,
    description: 'Mac\'D',
    icon: walletPrimary,
    color: 'primary',
  },
  {
    amount: +637.91,
    paymentMethod: 'Transfer',
    description: 'Refund',
    icon: chartInfo,
    color: 'info',
  },
  {
    paymentMethod: 'Credit Card',
    amount: -838.71,
    description: 'Ordered Food',
    icon: creditCardSuccess,
    color: 'success',
  },
  {
    paymentMethod: 'Wallet',
    amount: +203.33,
    description: 'Starbucks',
    icon: walletPrimary,
    color: 'primary',
  },
  {
    paymentMethod: 'Mastercard',
    amount: -92.45,
    description: 'Ordered Food',
    icon: creditCardWarning,
    color: 'warning',
  },
]

const moreList = [
  {
    title: 'Share',
    value: 'Share',
  },
  {
    title: 'Refresh',
    value: 'Refresh',
  },
  {
    title: 'Update',
    value: 'Update',
  },
]
</script>

<template>
  <VCard title="Ordenes Pendientes">
    <template #append>
      <div class="me-n3 mt-n2">
        <MoreBtn :menu-list="moreList" />
      </div>
    </template>

    <VCardText>
      <VList class="card-list">
        <VListItem
          class="py-3 border-bottom-4"
        >
          <VRow class="pa-0 m-0">
            <VCol cols="2" class="text-center">
              Fecha
            </VCol>
            <VCol cols="3" class="text-center">
              Tracker ID
            </VCol>
            <VCol cols="2" class="text-center">
              N° Productos 
            </VCol>
            <VCol cols="2" class="text-center">
              Creado por
            </VCol>
            <VCol cols="3" class="text-center">
              Estado
            </VCol>
          </VRow>
         
        </VListItem>
        <VListItem
          v-for="order in orders"
          :key="order.id"
          class="py-3 border-bottom-4"
        >
          <VRow class="pa-0 m-0">
            <VCol cols="2" class="text-center">
              {{moment(order.created_at ).format('DD/MM/YYYY') }}
            </VCol>
            <VCol cols="3" class="text-center">
              #{{ order.trancker }}
            
            </VCol>
            <VCol cols="2" class="text-center">
              {{ order.products_count}}

            </VCol>
            <VCol cols="2" class="text-center">
              {{ order.user.name }}

            </VCol>
            <VCol cols="3" class="text-center">
              <v-chip class="bg-warning">{{ order.status_info }}</v-chip>
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
        .dispatch(GET_LAST_PENDING_ORDERS)
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
