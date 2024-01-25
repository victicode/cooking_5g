
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
            <VCol cols="4" class="text-center px-2">
              Opciones
            </VCol>
          </VRow>
         
        </VListItem>
        <template v-if="products.length > 0">
          <VListItem 
            v-for="product in products"
            :key="product.id"
            class="py-3 border-bottom-4"
          >
            <VRow class="pa-0 m-0 align-center">
              <VCol cols="5" class="text-center">
                {{ product.title }} {{ product.lote_code }}
              </VCol>
              <VCol cols="3" class="text-center">
                <v-chip :class="product.quantity > 10 ? 'bg-warning' : 'bg-error'">{{ product.quantity }}</v-chip>
              </VCol>
              <VCol cols="4" class="text-center">
                <VIcon icon="bx-link-external" />
              </VCol>
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
        products:[]
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
          this.products = data.data
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
