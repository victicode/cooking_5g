<script setup>
import { useTheme } from 'vuetify'
import illustrationJohnDark from '@images/cards/illustration-john-dark.png'
import illustrationJohnLight from '@images/cards/illustration-john-light.png'

const { global } = useTheme()
const illustrationJohn = computed(() => global.name.value === 'dark' ? illustrationJohnDark : illustrationJohnLight)
</script>

<template>
  <VCard class="text-center text-sm-start">
    <VRow no-gutters>
      <VCol
        cols="12"
        sm="8"
        order="2"
        order-sm="1"
      >
        <VCardItem>
          <VCardTitle class="text-md-h5 text-primary">
            Bienvenido {{ getCurrentAccount.name }} 🎉
          </VCardTitle>
        </VCardItem>

        <VCardText>
          <span>
            {{getCurrentAccount.rol_id == 1 ? 'Te encuentras en el area del dashboard.' :''}}
            <br>
            {{getCurrentAccount.rol_id == 1 ? 'Aquí te mostraremos las últimas novedades dentro del sistema.' :''}}
          </span>
          <br>
          <!-- <VBtn
            variant="tonal"
            class="mt-4"
            size="small"
          >
            Vamos!
          </VBtn> -->
        </VCardText>
      </VCol>

      <VCol
        cols="12"
        sm="4"
        order="1"
        order-sm="2"
        class="text-center bg-gradient-primary"
      >
        
      </VCol>
    </VRow>
  </VCard>
</template>

<style lang="scss" scoped>
.john-illustration {
  inset-block-end: -0.0625rem;
  inset-inline-end: 3rem;
}
</style>
<script>
import { mapGetters } from "vuex";
  export default {
    methods:{
    },
    created(){
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>
