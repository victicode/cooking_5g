
<template>
  <VCard title="Productos con poco stock" class="h-100">
    <VCardText class="px-md-6 px-4">
      <VList class="card-list">
        <VListItem
          class="py-3 border-bottom-4"
        >
          <VRow class="pa-0 m-0 align-center">
            <VCol cols="5" class="text-center">
              Producto
            </VCol>
            <VCol cols="3" class="text-center">
              Stock
            </VCol>
            <VCol cols="4" class="text-center">
              Opciones
            </VCol>
          </VRow>
         
        </VListItem>
        <VListItem
          v-for="order in product"
          :key="order.id"
          class="py-3 border-bottom-4"
        >
          <VRow class="pa-0 m-0 align-center">
            <VCol cols="5" class="text-center">
              {{ order.title }}
            </VCol>
            <VCol cols="3" class="text-center">
              <v-chip :class="order.stock > 10 ? 'bg-warning' : 'bg-error'">{{ order.stock }}</v-chip>
            </VCol>
            <VCol cols="4" class="text-center">
              <VIcon icon="bx-link-external" />
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
  import { GET_CRITICAL_STOCK_PRODUCTS } from "@/core/services/store/product.module";
  import { mapGetters } from "vuex";
  export default {
    data(){
      return{
        product:[]
      }
    },
    methods:{
      getProduct(){
        this.$store
        .dispatch(GET_CRITICAL_STOCK_PRODUCTS)
        .then((data) => {
          if (data.code !==200) {
            console.log('ha ocurrido un error al intentar obtener los productos')  
            return
          }
          console.log(data)
          this.product = data.data
        })
        .catch((e) => {
          console.log(e)
        });
      }
    },
    mounted(){
      this.getProduct()
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>
