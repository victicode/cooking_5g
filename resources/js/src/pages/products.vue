<script setup>
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import { GET_PRODUCTS } from "@/core/services/store/product.module";

</script>

<template>
  <VRow>
  <VCol cols="12">
    <VCard title="Listado de productos" class="pa-3">

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
};</script>