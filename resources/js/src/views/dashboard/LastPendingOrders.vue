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
            <VCol cols="4" md="3" class="text-center hide-text px-2">
              Fecha
            </VCol>
            <VCol cols="4" md="3" class="text-center hide-text">
              Tracker ID
            </VCol>
            <VCol cols="2" md="3" class="text-center hide-text d-none d-sm-block">
              Creado por
            </VCol>
            <VCol cols="4" md="3" class="text-center">
              Estado
            </VCol>
            <!-- <VCol cols="3" md="2" class="text-center hide-text">
              Opciones
            </VCol> -->
          </VRow>
         
        </VListItem>
        <template v-if="orders.length > 0">

          <VListItem
            v-for="order in orders"
            :key="order.id"
            class="py-3 border-bottom-4 px-1 px-md-2"
          >
            <VRow class="pa-0 m-0 align-center">
              <VCol cols="4" md="3" class="text-center px-2">
                
                  {{moment(order.created_at ).format('DD/MM/YYYY') }}
                
              </VCol>
              <VCol cols="4" md="3" class="text-center text-decoration-underline" @click="copyOderNumber($event)">
                
                  #{{ order.trancker }}
                
              
              </VCol>
              <VCol cols="3" class="text-center d-none d-sm-block">
                
                  {{ order.user.name }}
                
              </VCol>
              <VCol cols="4" md="3" class="text-center ">
                <v-chip class="bg-warning">{{ order.status_label.status }}</v-chip>
                <!-- <v-chip class="bg-warning">{{ order.status_info }}</v-chip> -->
              </VCol>
              <!-- <VCol cols="3" md="2" class="text-center">
                <VIcon icon="bx-show" />
              </VCol> -->
            </VRow>
           
          </VListItem>
        </template>
        <template v-else>
          <VListItem 
          >
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
  h5{
    font-weight: 500;
  }
</style>
<script>

import { mapGetters } from 'vuex';
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
        .dispatch(GET_LAST_ORDERS,1)
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
      copyOderNumber(element){
        let texto = element.target.innerHTML.trim().substring(1);
          const textArea = document.createElement('textarea');
          textArea.value = texto;
          textArea.style.opacity = 0;
          document.body.appendChild(textArea);
          textArea.select();
          try {
            const success = document.execCommand('copy');
            this.showSnackbar('success', 'Tracker ID copiado exitosamente')
          } catch (err) {
            console.error(err.name, err.message);
          }
          document.body.removeChild(textArea);
        
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
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>
