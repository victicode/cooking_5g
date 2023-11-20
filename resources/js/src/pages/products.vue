<script setup>
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import { GET_PRODUCTS } from "@/core/services/store/product.module";

</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Listado de productos" class="pa-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 mb-5">
          <VBtn color="primary"><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>
        </VRow>
        <DemoSimpleTableBasics v-if="ready" :products="products" />
      </VCard>
    </VCol>
  </VRow>
</template>
<script>
  export default {
    methods:{
      getProducts(){
        this.$store.dispatch(GET_PRODUCTS)
          .then((data) => {
            this.emitter.emit('displayOverlay', false)
            if (data.code !==200) {
              console.log('ha ocurrido un error al intentar obtener las ordenes')  
              return
            }
            this.products = data.data;
            // console.log(this.products)
            this.ready = true
          }).catch((e)=>{
            this.emitter.emit('displayOverlay', false)

          })
      },
    },
    data: () => ({
    products : {},
    ready:false,
    }),
    mounted(){
      this.getProducts()
    }
  };
</script>