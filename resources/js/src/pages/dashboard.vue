<script setup>
import GreetingsComponent from '@/views/dashboard/GreetingsComponent.vue'
import AnalyticsFinanceTabs from '@/views/dashboard/AnalyticsFinanceTab.vue'
import LastPendingOrders from '@/views/dashboard/LastPendingOrders.vue'

</script>

<template>
  <VRow>
    <!-- 👉 Congratulations -->
    <VCol
      cols="12"
      md="8"
      class="pe-0"
    >
      <VRow>
        <V-col
        cols="12"
        >
        <GreetingsComponent />

        </V-col>
        <VCol
          cols="12"
          md="12"
          
        >
          <LastPendingOrders />
        </VCol>
      </VRow>
    </VCol>


    <VCol
        cols="12"
        sm="4"
        
        order="1"
        order-sm="2"
        class="text-center"
      >
        <v-card
          elevation="24"
          max-width="444"
          class="mx-auto"
        >
          <v-carousel
            :continuous="false"
            :show-arrows="false"
            hide-delimiter-background
            delimiter-icon="mdi-square"
            height="500"
            cycle
            direction="vertical"
            progress="primary"
            interval="4000"
          >
            <v-carousel-item
              v-for="(slide, i) in slides"
              :key="i"
            >
              <VCol
              class="pa-0"
              cols="12"
            >
              <AnalyticsFinanceTabs />
            </VCol>
            </v-carousel-item>
          </v-carousel>
        </v-card>
    </VCol>
  </VRow>
</template>

<style>
body{
  background:#b7b7b71f!important;
}
</style>
<script>
  import { mapGetters } from "vuex";
  export default {
    data () {
      return {
        colors: [
          'primary',
          'secondary',
          'success',
          'warning',
          'info',
        ],
        slides: [
          'First',
          'Second',
          'Third',
          'Fourth',
          'Fifth',
        ],
      }
    },
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