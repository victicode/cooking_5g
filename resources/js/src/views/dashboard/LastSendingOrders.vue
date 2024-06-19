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
            <VCol cols="4" md="4" class="text-center px-0 hide-text">
              Fecha
            </VCol>
            <VCol cols="5" md="4" class="text-center px-0 hide-text">
              Tracker ID
            </VCol>
            <VCol cols="3" md="4" class="text-center px-0 hide-text">
              N° Orden
            </VCol>
          </VRow>
        </VListItem>
        <template v-if="orders.length > 0">
          <VListItem
            v-for="order in orders"
            :key="order.id"
            class="py-3 border-bottom-4 px-1 px-md-2"
          >
            <VRow class="pa-0  align-center">
              <VCol cols="4" md="4" class="text-center px-0">
                {{moment(order.created_at ).format('DD/MM/YYYY') }}
              </VCol>
              <VCol cols="5" md="4" class="text-center px-0" @click="copyOderNumber(order.trancker)" >
                <div class="w-100 d-flex align-center justify-center text-decoration-underline">
                  #{{ order.trancker }} <VIcon icon="fluent:document-copy-20-regular" class="ms-0"></VIcon>
                </div>
              </VCol>
              <VCol cols="3" md="4" class="text-center px-0">
                #{{ orderNumberFormat(order.id) }}
              </VCol>
            </VRow>
          </VListItem>
        </template>
        <template v-else>
          <VListItem>
            <VRow class="pa-0 m-0 align-center">
              <VCol cols="12" class=" mt-5 text-center">
                <h3>No hay resultados</h3>
              </VCol>
            </VRow>
          </VListItem>
        </template>
      </VList>
    </VCardText>
    <v-snackbar
      v-model="snackShow"
      :color="snackType"
      rounded="pill"
      :timeout="snacktimeOut"
      width="max-content"
      class="text-center"
    >
      <h4 class="text-white w-100 text-center">
        {{snackMessage}}
      </h4>
      <template
        v-slot:actions
      >
        <VBtn  color="white" class="text-white" @click="snackShow=false"> Cerrar</VBtn>
      </template>
    </v-snackbar>
  </VCard>
</template>

<style lang="scss" scoped>
  .card-list {
    --v-card-list-gap: 1.6rem;
  }
</style>
<script>
  export default {
    data(){
      return{
        orders:[],
        snackShow:false,
        snackMessage:'',
        snackType:'',
        snacktimeOut:5000,
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
          this.orders = data.data
        })
        .catch((e) => {
          console.log(e)
        });
      },
      orderNumberFormat(id){
        return '0000000'.slice( 0, 6 - id.toString().length ) + id 
      },
      async copyOderNumber(textToCopy){
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(textToCopy);
        } else {
          const textArea = document.createElement("textarea");
          textArea.value = textToCopy;
              
          textArea.style.position = "absolute";
          textArea.style.left = "-999999px";
              
          document.body.prepend(textArea);
          textArea.select();

          try {
            document.execCommand('copy');
          } catch (error) {
            console.error(error);
          } finally {
            textArea.remove();
            this.showSnackbar('success', 'Tracker ID copiado exitosamente')
          }
        }
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
    },
    mounted(){
      this.getOrders()
    },
  };
</script>
