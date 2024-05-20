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
            show:false
          },
          value: {
            offsetY: 10,
            fontSize: '24px',
            color: primaryTextColor,
            fontFamily: 'Public Sans',
          },
          total: {
            show: true,
            label: 'Total',
            fontSize: '14px',
            color: disabledTextColor,
            fontFamily: 'Public Sans',
          },
        },
      },
    },
  },
}
})


</script>
<style lang="scss" >
.mostProductsInRecipe{
  & > .v-card-item{
    padding-bottom: 0px;
  }
}
</style>
<style lang="scss" scoped>
  
  .square{
    width: 10px;
    height: 10px;
    margin-right: 4px;
  }
  @media screen and (max-width: 780px){
    h5{
      font-size: 9px;
    }
  }
</style>
<template>
  <div v-if="Object.keys(stadistics).length > 0 ">

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
      <div class="d-flex w-100 justify-space-between justify-md-center px-2 py-2">
          <div class="d-flex justify-center align-center mx-md-4">
            <div class="square bg-success" />
            <h5>Completadas: {{ stadistics.allFinish }}</h5> 
          </div>
          <div class="d-flex justify-center align-center mx-md-4">
            <div class="square bg-secondary" />
            <h5>En transito: {{ stadistics.allInTransit }}</h5>
          </div>
          <div class="d-flex justify-center align-center mx-md-4">
            <div class="square bg-warning" />
            <h5>Pendientes: {{ stadistics.allPend }}</h5>
          </div>
          <div class="d-flex justify-center align-center mx-md-4">
            <div class="square bg-error" />
            <h5>Canceladas: {{ stadistics.allCancel }} </h5>
          </div>
        </div>
    </VCard>
    <VCard class="mt-2 mostProductsInRecipe" title="Receta mas pedidas">
      <VCardText class="pa-1" v-if="stadistics.mostOrderProducts.length > 0">
        <VList class="card-list mt-0">
          <VListItem
            v-for="(recipe, index) in stadistics.mostOrderProducts"
            :key="recipe.id"
          >
            <template #prepend>
              <VAvatar
                rounded
                variant="tonal"
                :color="'primary'"
              >
              {{ index+1 }}
              </VAvatar>
            </template>
  
            <VListItemTitle class="mb-1">
              {{ recipe.title }}
            </VListItemTitle>
            <VListItemSubtitle class="">
              {{ recipe.orders_count }} {{ recipe.orders_count > 1 ? 'Ordenes' : 'Orden' }} 
              -
              {{ recipeQuantityCount(recipe.orders) }} unidades solicitadas
            </VListItemSubtitle>
  
            <template #append>
              <!-- <span>{{ recipe.amount }}</span> -->
            </template>
          </VListItem>
        </VList>
      </VCardText>
      <VCardText class="mt-3 w-100 d-flex justify-center align-center py-3">
        <h3>No hay resultados</h3>
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
      recipeQuantityCount(recipe){
        let total = 0 
        recipe.forEach((order) =>{
          total += order.pivot.quantity
        });
        return total
      }
    },
    mounted(){
      this.getAll()
    }
  };
</script>
