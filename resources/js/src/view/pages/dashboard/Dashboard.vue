<template>
  <div class="row">
    <!-- Website Analytics -->
    <div class="row" v-if="mainAlert">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert = false">{{
          mainAlertMessage }}</b-alert>
      </div>
      <hr>
    </div>
    <div class="col-lg-6 mb-4">
      <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
        id="swiper-with-pagination-cards">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="row">
              <div class="col-12">
                <h5 class="text-white mb-0 mt-2">Horario de atención</h5><br>
                <small>de Lunes a viernes de 9:00AM a 7:00PM</small><br>
                <small>Sábado de 9:00AM a 2:00PM</small><br><br><br><br>
              </div>
              <div class="row">

                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                  <img src="media/illustrations/reloj.png" alt="Reloj" width="170" class="card-website-analytics-img" />
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="row">
              <div class="col-12">
                <h5 class="text-white mb-0 mt-2">Horario de atención</h5><br>
                <small>de Lunes a viernes de 9:00AM a 7:00PM</small><br>
                <small>Sábado de 9:00AM a 2:00PM</small><br><br><br><br>
              </div>

              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="media/illustrations/cambio.png" alt="Website Analytics" width="170"
                  class="card-website-analytics-img" />
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="row">
              <div class="col-12">
                <h5 class="text-white mb-0 mt-2">Horario de atención</h5><br>
                <small>de Lunes a viernes de 9:00AM a 7:00PM</small><br>
                <small>Sábado de 9:00AM a 2:00PM</small><br><br><br><br>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="media/illustrations/dinero-digital.png" alt="Website Analytics" width="170"
                  class="card-website-analytics-img" />
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
    <!--/ Website Analytics -->

    <!-- Sales Overview -->
    <div class="col-lg-3 mb-4">
      <div class="card">
        <div class="card-header px-3">

          <h4 class="card-title mb-1">{{ pref == false ? 'Tipo de cambio' : 'Tipo de cambio (Prefrencial)' }}</h4>
          <small class="text-muted">{{ DateFormat() }}</small>
        </div>
        <div class="card-body px-3">
          <div class="row">
            <div class="col-4">
              <div class="d-flex gap-2 align-items-center mb-2">
                <span class="badge bg-warning p-1 rounded"><i class="fa-solid fa-arrow-up"></i></span>
                <p class="mb-0">Comprar Dólares</p>
              </div>
              <h5 class="mb-0 pt-1 text-nowrap">{{ purchase.toFixed(3) }}</h5>

            </div>
            <div class="col-4">
              <div class="divider divider-vertical">
                <div class="divider-text">
                  <span class="badge-divider-bg bg-label-secondary"><i class="fa-solid fa-dollar-sign"></i></span>
                </div>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                <p class="mb-0">Vender Dólares</p>
                <span class="badge bg-primary p-1 rounded"><i class="fa-solid fa-arrow-down"></i></span>
              </div>
              <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">{{ sale.toFixed(3) }}</h5>

            </div>
          </div>

        </div>
      </div>
    </div>
    <!--/ Sales Overview -->


    <!-- Bienvenido -->
    <div class="col-lg-3 mb-4">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-12">
            <div class="card-body p-0 px-4 pt-3">
              <h5 v-if="nameShow" class="card-title mb-0">Bienvenido {{ getCurrentAccount.type == 1 ?
                getCurrentAccount.business_name : getCurrentAccount.name }} 🎉</h5>
              <small class="d-block mb-1 text-muted">Hasta ahora tu</small>
            </div>
          </div>
          <div class="col-7">
            <div class="card-body text-nowrap pt-0">

              <p class="mb-2">Compraste</p>
              <h4 v-if="nameShow" class="text-primary mb-1">{{
                FormatBigAmount(getCurrentAccount.operationStats.totalBuy.toFixed(2)) }}</h4>

              <p class="mb-2">Vendiste</p>
              <h4 v-if="nameShow" class="text-primary mb-1">{{
                FormatBigAmount(getCurrentAccount.operationStats.totalSell.toFixed(2)) }}</h4>
            </div>
          </div>
          <div class="col-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-2">
              <img src="media/illustrations/card-advance-sale.png" height="140" alt="view sales" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Bienvenido-->
    <!-- <div v-if="getCurrentAccount.id === 2" class="col-12">
      <button @click="sennn()" class="btn btn-primary">Enviar</button>
    </div> -->
    <!-- Last Transaction -->
    <last-transaction></last-transaction>
    <last-user v-if="isAdmin"></last-user>
  </div>
</template>
<style lang="scss">
@import "@/assets/sass/pages/cards-advance.scss";
</style>
<script>
import { EXCHANGE_RATES_GET_LAST } from "@/core/services/store/exchange_rate.module";
import { SEND_EMAILS } from "@/core/services/store/account.module";
import Swiper from 'swiper/swiper-bundle';
import { mapGetters } from "vuex";
import ApiService from "@/core/services/api.service";
import lastTransaction from "@/view/pages/dashboard/LastTransaction.vue";
import lastUser from "@/view/pages/dashboard/LastUser.vue";
export default {
  components: {
    lastTransaction: lastTransaction,
    lastUser: lastUser
  },
  data() {
    return {
      mainAlert: false,
      mainAlertVariant: "",
      mainAlertMessage: "",
      sale: 0,
      purchase: 0,
      pref: false,
      lastExchangeRatesNumber: 10,
      accountLabel: [],
      isAdmin: false,
      nameShow: false
    }
  },
  mounted() {
    this.getLastExchangeRate();
    this.slideFront();
    this.DateFormat();
    setTimeout(() => {
      this.nameShow = true;
      this.IsAdmin()
    }, 2000)

  },
  methods: {
    getLastExchangeRate() {
      this.$store
        .dispatch(EXCHANGE_RATES_GET_LAST)
        .then((data) => {
          if (data.code != 200) {
            this.showMainAlert('danger', data.error)
            return;
          }
          if (data.data != null) {
            this.sale = data.data.sale;
            this.purchase = data.data.purchase;
            if (data.data.pref != undefined) {
              this.pref = true;
            }
          }

        })
        .catch((err) => {
          this.showMainAlert('danger', err)
          return;
        });
    },
    sennn() {
      this.$store
        .dispatch(SEND_EMAILS)
        .then((data) => {
          console.log(data)
        })
        .catch((err) => {
          this.showMainAlert('danger', err)
        });
    },
    getAccounts() {
      ApiService.setHeader();
      ApiService.get("api/user/get-label-active-account")
        .then(({ data }) => {
          if (data.code == 200) {
            this.accountLabel = data.data.accountLabel;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    showMainAlert(variant, message) {
      this.mainAlertVariant = variant;
      this.mainAlertMessage = message;
      this.mainAlert = true;
      window.scrollTo(0, 0);
    },
    slideFront() {
      const swiperWithPagination = document.querySelector('#swiper-with-pagination-cards');
      if (swiperWithPagination) {
        new Swiper(swiperWithPagination, {
          loop: true,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false
          },
          pagination: {
            clickable: true,
            el: '.swiper-pagination'
          }
        });
      }
    },
    DateFormat() {
      let date = new Date();
      let daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
      let MonthOfYear = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
      return `${daysOfWeek[date.getDay()]} ${date.getDate()} de ${MonthOfYear[date.getMonth()]} ${date.getFullYear()}`
    },
    IsAdmin() {
      this.isAdmin = this.getCurrentAccount.type == 0 && this.getCurrentAccount.id < 2 
    },
    FormatBigAmount(amount) {

      if (amount >= 1000 && amount < 1000000) {
        amount = (parseFloat(amount) / 1000).toFixed(2) + 'K';
      } else if (amount >= 1000000) {
        amount = (parseFloat(amount) / 1000000).toFixed(2) + 'M';
      }
      return '$' + amount
    }

  },
  computed: {
    ...mapGetters(["currentAccount"]),

    getCurrentAccount() {
      return this.currentAccount.active_account;
    },
  },
};
</script>