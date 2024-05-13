<script setup>
import VueApexCharts from 'vue3-apexcharts'
import { useTheme } from 'vuetify'
import { hexToRgb } from '@layouts/utils'
import { GET_ALL_ORDER_STADISTICS } from '@/core/services/store/order.module';
import { func } from '../../core/services/utils/utils';
const vuetifyTheme = useTheme()

const chartOptions = computed(() => {
  const currentTheme = vuetifyTheme.current.value.colors
  const variableTheme = vuetifyTheme.current.value.variables
  const disabledTextColor = `rgba(${ hexToRgb(String(currentTheme['on-surface'])) },${ variableTheme['disabled-opacity'] })`
  const primaryTextColor = `rgba(${ hexToRgb(String(currentTheme['on-surface'])) },${ variableTheme['high-emphasis-opacity'] })`
  
  return {
    chart: {
      sparkline: { enabled: true },
      animations: { enabled: false },
    },
    stroke: {
      width: 5,
      colors: [currentTheme.surface],
    },
    legend: { show: false },
    tooltip: { enabled: false },
    dataLabels: { enabled: false },
    colors: [
      currentTheme.success,
      currentTheme.secondary,
      currentTheme.warning,
      currentTheme.error,
    ],
    grid: {
      padding: {
        top: -7,
        bottom: 5,
      },
    },
    states: {
      hover: { filter: { type: 'none' } },
      active: { filter: { type: 'none' } },
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
        donut: {
          size: '75%',
          labels: {
            show: true,
            name: {
              offsetY: 17,
              fontSize: '14px',
              color: disabledTextColor,
              fontFamily: 'Public Sans',
            },
            value: {
              offsetY: -17,
              fontSize: '24px',
              color: primaryTextColor,
              fontFamily: 'Public Sans',
            },
            total: {
              show: true,
              label: 'Total',
              fontSize: '14px',
              formatter: () => '6',
              color: disabledTextColor,
              fontFamily: 'Public Sans',
            },
          },
        },
      },
    },
  }
})

const orders = [
  {
    amount: '0.5k',
    title: 'Electronic',
    avatarColor: 'primary',
    subtitle: 'Mobile, Earbuds, TV',
    avatarIcon: 'bx-mobile-alt',
  },
  {
    amount: '23.8k',
    title: 'Fashion',
    avatarColor: 'success',
    subtitle: 'Tshirt, Jeans, Shoes',
    avatarIcon: 'bx-closet',
  },
  {
    amount: 849,
    title: 'Decor',
    avatarColor: 'info',
    subtitle: 'Fine Art, Dining',
    avatarIcon: 'bx-home',
  },
  {
    amount: 99,
    title: 'Sports',
    avatarColor: 'secondary',
    subtitle: 'Football, Cricket Kit',
    avatarIcon: 'bx-football',
  },
]

</script>
<style lang="scss" scoped>
  .square{
    width: 10px;
    height: 10px;
    margin-right: 4px;
  }
  h6{
    font-size: 9px;
  }
</style>
<template>
  <div v-if="Object.keys(stadistics).length > 0">

    <VCard >
      <VCardText class="px-6 px-md-6 py-1">
        <div class="d-flex align-center justify-space-between">
          <div class="">
            <h4 class="text-h4">
              {{ func.numberFormat( stadistics.countAllOrder ) }}
            </h4>
            <h4>Ordenes totales</h4>
          </div>
  
          <div>
            <VueApexCharts
              type="donut"
              :height="125"
              width="105"
              :options="chartOptions"
              :series="series"
            />
          </div>
        </div>
      </VCardText>
      <div class="d-flex w-100 justify-space-between px-2 py-2">
          <div class="d-flex justify-center align-center">
            <div class="square bg-success" />
            <h6>Completadas: {{ stadistics.allFinish }}</h6> 
          </div>
          <div class="d-flex justify-center align-center">
            <div class="square bg-secondary" />
            <h6>En transito: {{ stadistics.allInTransit }}</h6>
          </div>
          <div class="d-flex justify-center align-center">
            <div class="square bg-warning" />
            <h6>Pendientes: {{ stadistics.allPend }}</h6>
          </div>
          <div class="d-flex justify-center align-center">
            <div class="square bg-error" />
            <h6>Canceladas: {{ stadistics.allCancel }} </h6>
          </div>
        </div>
    </VCard>
    <VCard class="mt-2">
      <VCardText class="pa-1">
        <VList class="card-list mt-0">
          <VListItem
            v-for="order in orders"
            :key="order.title"
          >
            <template #prepend>
              <VAvatar
                rounded
                variant="tonal"
                :color="order.avatarColor"
              >
                <VIcon :icon="order.avatarIcon" />
              </VAvatar>
            </template>
  
            <VListItemTitle class="mb-1">
              {{ order.title }}
            </VListItemTitle>
            <VListItemSubtitle class="text-disabled">
              {{ order.subtitle }}
            </VListItemSubtitle>
  
            <template #append>
              <span>{{ order.amount }}</span>
            </template>
          </VListItem>
        </VList>
      </VCardText>
    </VCard>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        stadistics:[],
        series: [],
      }
    },
    methods:{
      getAll(){
      this.$store.dispatch(GET_ALL_ORDER_STADISTICS)
        .then((data) => {
          if(data.code !== 200){
            console.log('alert!!!')
          }
          console.log(data)
          this.stadistics = data.data
          this.series = [this.stadistics.allFinish, this.stadistics.allInTransit, this.stadistics.allPend, this.stadistics.allCancel]

        })
        .catch((e) => {
        });
     },
    },
    mounted(){
      this.getAll()
    }
  };
</script>
