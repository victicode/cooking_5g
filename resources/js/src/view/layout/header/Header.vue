<template>
  <!-- begin:: Header -->
  <div>
    <nav
      class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme "
      id="kt_header" ref="kt_header" 
      :style="{ backgroundImage: `url('media/backgrounds/bg-blanco.jpg')` }"
      
    >
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="ti ti-menu-2 ti-sm"></i>
        </a>
      </div>

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item navbar-search-wrapper mb-0" @click="headerEmit()">
  
              <span class="d-none d-md-inline-block text-muted">Abiertos 9:00AM a 7:00PM</span>
                    
          </div>
        </div>
          <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
          <button v-if="isAdmin" @click="desactivate()" class="btn rounded-pill btn-label-slack d-flex align-items-center">
            <i class="fa-solid fa-power-off me-1 align-middle"></i> 
            <span>{{ isDesactivate == '1' ? 'Deshabilitar operaciones' : isDesactivate == '0' ? 'Habilitar operaciones' :'' }}</span>
          </button>
            <!-- Quick links  -->
          <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
              <a
                class="nav-link dropdown-toggle hide-arrow"
                href="javascript:void(0);"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
              >
                <i class="ti ti-layout-grid-add ti-md"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-end py-0">
                <div class="dropdown-menu-header border-bottom">
                  <div class="dropdown-header d-flex align-items-center py-3">
                    <h5 class="text-body mb-0 me-auto">Link Directos</h5>
                    <a
                      href="javascript:void(0)"
                      class="dropdown-shortcuts-add text-body"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="Add shortcuts"
                      ><i class="ti ti-sm ti-check"></i
                    ></a>
                  </div>
                </div>
                <div class="dropdown-shortcuts-list scrollable-container">
                  <div class="row row-bordered overflow-visible g-0">
                    <router-link to="/dashboard" v-slot="{ href, navigate, isActive, isExactActive }">
                      <div class="dropdown-shortcuts-item col" :class="[isActive && 'active', isExactActive && 'active']">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                        
                          <i class="fa-solid fa-house"></i>
                        </span>
                        <a :href="href" class="stretched-link" @click="navigate" >inicio</a>
                        <small class="text-muted mb-0">Portada</small>
                      </div>
                    </router-link>
                    <router-link  to="/my-operations" v-slot="{ href, navigate, isActive, isExactActive }">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-file-invoice fs-4"></i>
                        </span>
                        <a :href="href" class="stretched-link" @click="navigate" >Movimiento</a>
                        <small class="text-muted mb-0">Operaciones realizadas</small>
                      </div>
                    </router-link>
                  </div>
                  <div class="row row-bordered overflow-visible g-0">
                    <router-link  to="/operations" v-slot="{ href, navigate, isActive, isExactActive }" >
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="fa-solid fa-money-bill-trend-up"></i>
                        </span>
                        <a :href="href" class="stretched-link" @click="navigate" >Cambiar</a>
                        <small class="text-muted mb-0">Iniciar cambio</small>
                      </div>
                    </router-link>
                    <router-link to="/bank-accounts"  v-slot="{ href, navigate, isActive, isExactActive }">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="fa-solid fa-building-columns"></i>
                        </span>
                        <a :href="href" class="stretched-link" @click="navigate" >Bancos</a>
                        <small class="text-muted mb-0">Cuentas bancarias</small>
                      </div>
                    </router-link>
                  </div>
                </div>
              </div>
          </li>
          <li v-if="isAdmin" class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
            <a
              class="nav-link dropdown-toggle hide-arrow position-relative"
              href="javascript:void(0);"
              data-bs-toggle="dropdown"
              data-bs-auto-close="outside"
              aria-expanded="false"
              @click="viewNotificactions()"
            >
              <i class="fa fa-bell fa-lg" :class="{'text-secondary': this.notiview > 0 }"></i>
              <span v-if="notiview > 0" class="position-absolute notification__span">
                {{ notiview }}
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0 notification__content">
              <div class="dropdown-menu-header border-bottom">
                <div class="dropdown-header  text-center py-3">
                  <h5 class="text-body mb-0">Notificaciones</h5>
                </div>
              </div>
              <ul style="list-style: none;" class="p-0">
                <li class=" p-3 by-primary notification" v-for="notification in notifications" >
                  <router-link 
                    :to="
                      { 
                        path:`/${ notification.type == 1 
                          ? 'all-operations' 
                          : notification.type == 2 
                          ? 'personal/accounts'
                          : 'company/accounts' 
                        }` 
                      }
                    "  
                    v-slot="{ href, navigate, isActive, isExactActive }"
                  >
                    <a :href="href"  @click="navigate">
                      <div class="d-flex align-items-center ">
                        <div class="me-2">
                          <div class="notification__icon">
                          <i 
                            class="fa fa-md " 
                            :class="{'fa-exchange-alt': notification.type == 1,'fa-building-user': notification.type > 1, } "
                          ></i>
                          </div>
                        </div>      
                        <div class="d-flex flex-column align-items-between notification__body">
                          <h6 class="p-0 m-0">
                              {{notification.message}}
                          </h6>
                          <p class="mb-0 py-0">
                            {{ notification.type < 2 ? 'Ve a operaciones para continuar!' : 'Debes validar sus documentos!'}}
                          </p>
                        </div>
                      </div>
                    </a>
                  </router-link>
                </li>
              </ul>
            </div>
          </li>
            <!-- Quick links -->
            <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
              <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div v-if="Object.keys(currentAccount).length > 3" class="avatar avatar-online">
                  {{ currentAccount.label.charAt(0).toUpperCase() }}
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <router-link 
                  :to="{ path: `/profiles/${this.currentAccount.id}`, params: {id: this.currentAccount.id}}"  
                  v-slot="{ href, navigate, isActive, isExactActive }"
                >
                  <li v-if="role != 5 ">
                    <a class="dropdown-item px-2" :href="href"  @click="navigate">
                      <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <div v-if="Object.keys(currentAccount).length > 3" class="avatar avatar-online">
                              {{ currentAccount.label.charAt(0).toUpperCase() }}
                            </div>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">{{ activeAccountType == 1 ? currentAccount.business_name : currentAccount.fullName }}</span>
                          <small class="text-muted">{{ activeAccountType == 1 ? 'Empresa' : 'Persona Natural'}}</small>
                        </div>
                        <div class="flex-grow-1 text-end ms-3">
                          <h5 class="mb-0"><i class="fa-solid fa-gear"></i></h5>
                        </div>
                      </div>
                    </a>
                  </li>
                </router-link>
                <!-- PERSONAL ACCOUNT LIST  -->
                <li v-if="personalAccounts.length > 0">
                  <div class="dropdown-divider"></div>
                </li>
                <li v-if="personalAccounts.length > 0" class="text-center">
                  <span class="fw-bold"> Cuentas personales</span>
                </li>
                <template v-if="personalAccounts.length > 0" v-for="account in personalAccounts">
                  <li>
                    <a class="dropdown-item" href="" @click="changeActiveAccount($event, account.id, 0)">
                      <i class="ti me-2 ti-sm" :class="{ 'ti-user-check': activeAccountType == 0 && account.id == activeAccountId, 'ti-user': !(activeAccountType == 0 && account.id == activeAccountId)}"></i>
                      <span class="align-middle" :class="{ 'active': activeAccountType == 0 && account.id == activeAccountId}">{{ account.name }} {{ account.surname }}</span>
                    </a>
                  </li>
                </template>
                <!-- COMPANYS ACCOUNT LIST  -->
                <li v-if="companyAccounts.length > 0" >
                  <div class="dropdown-divider"></div>
                </li>
                <li  v-if="companyAccounts.length > 0" class="text-center" >
                  <span class="fw-bold">Cuentas de Empresas</span>
                </li>
                <template v-if="companyAccounts.length > 0"  v-for="account in companyAccounts">
                  <li>
                    <a class="dropdown-item" href="" @click="changeActiveAccount($event, account.id, 1)">
                      <i class="ti me-2 ti-sm" :class="{ 'ti-user-check': activeAccountType == 1 && account.id == activeAccountId, 'ti-user': !(activeAccountType == 1 && account.id == activeAccountId)}"></i>
                      <span class="align-middle" :class="{ 'active': activeAccountType == 1 && account.id == activeAccountId}" >{{ account.business_name }}</span>
                    </a>
                  </li>
                </template>
                <li>
                  <div class="dropdown-divider"></div>
                </li>
                <router-link  v-if=" role != 5 || ( personalAccounts.length < 4 && companyAccounts.length < 4 )" :to="{ path:'/create-account'}"  v-slot="{ href, navigate, isActive, isExactActive }">
                  <li >
                    <a class="dropdown-item" :href="href"  @click="navigate" >
                      <i class="ti ti-plus me-2 ti-sm"></i>
                      <span class="align-middle">Crear nueva cuenta</span>
                    </a>
                  </li>
                </router-link>
                <li>
                  <a class="dropdown-item cursor-pointer" @click="onLogout()">
                    <i class="ti ti-logout me-2 ti-sm"></i>
                    <span class="align-middle">Salir</span>
                  </a>
                </li>
              </ul>
          </li>
            <!--/ User -->
        </ul>
      </div>
      <!-- Search Small Screens -->
    </nav>
    <div class="row" v-if="mainAlert">
      <div class="col-lg-12 col-md-12 col-sm-12 px-4 mt-2">
        <b-alert class=" " :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}{{ redirectMessage == '' ? '' : redirectMessage + redirectMessageSeconds + ' segundos.' }}</b-alert>
      </div>
      <hr>
    </div>
    
  </div>


  <!-- end:: Header -->
</template>

<script>
import KTTopbar from "@/view/layout/header/Topbar.vue";
import { ACCOUNTS_GET_ALL, SET_ACTIVE_ACCOUNT} from "@/core/services/store/account.module";
import { LOGOUT } from "@/core/services/store/auth.module";
import { DESACTIVATE_OPERATION, GET_SYSTEM_CONFIGURATION_VALUE } from "@/core/services/store/configuration.module";
import { bootstrap } from "@/concept/bootstrap";
import { NOTIFICATIONS_GET_ALL, VIEW_NOTIFICATIONS } from "@/core/services/store/notification.module";
import notificationTone from "@/assets/audio/completed-status-alert-notification.mp3";
// import notificationTone from "@/assets/audio/notification.mp3";

export default {
  name: "KTHeader",
  components: {
    KTTopbar,
    KTMenu
  },
  data(){
    return{
      personalAccounts: [],
      companyAccounts: [],
      currentAccount: {label:'0'},
      activeAccountType: null,
      activeAccountId: null,
      mainAlert: false,
      mainAlertVariant: "",
      mainAlertMessage: "",
      redirectMessageSeconds: 3,
      redirectMessage: '',
      isAdmin: false,
      isDesactivate:'',
      role: 0,
      notifications:[],
      notiview:0,
      sound: new Audio(notificationTone),
      
    }
  },
  methods:{
    getAllAccounts () {
      this.$store
        .dispatch(ACCOUNTS_GET_ALL)
        .then((data) => {
            if (data.code != 200){
                this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
                return;
            }

            this.personalAccounts = data.data.personalAccounts;
            for (let i = 0; i < this.personalAccounts.length; i++) {
                let label = this.personalAccounts[i].name + ' ' + this.personalAccounts[i].surname;
                let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
                this.personalAccounts[i].label = matches.join('.'); // J.S.O.N
            }

            this.companyAccounts = data.data.companyAccounts;
            for (let i = 0; i < this.companyAccounts.length; i++) {
                let label = this.companyAccounts[i].business_name;
                let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
                  this.companyAccounts[i].label = matches.join('.'); // J.S.O.N
            }
            this.activeAccountType = data.data.activeAccountType;
            this.activeAccountId = data.data.activeAccountId;
            this.getCurrentAccount()
        })
        .catch((err) => {
            this.showMainAlert('danger', err)
        });
    },
    getCurrentAccount(){
      if(this.activeAccountType == 1){
        this.companyAccounts.map((item) => {
          if(item.id == this.activeAccountId){
            this.currentAccount = item;
          }
        })
      }else{
        this.personalAccounts.map((item) => {
          if(item.id == this.activeAccountId){
            this.currentAccount = item;
            this.currentAccount.fullName = `${item.name.split(' ')[0]} ${item.surname.split(' ')[0]}`
          }
        })
      }
      this.currentAccount.type = this.activeAccountType;
      this.role = this.currentAccount.user.roles[0].id
      this.isAdmin = this.currentAccount.user.roles[0].id < 3 ;
      this.$store.currentAccount = this.currentAccount;
    },
    changeActiveAccount (e, id, type) {
      e.preventDefault();

      let data = {
          active_account_type: type,
          active_account: id
      };

      this.$store
        .dispatch(SET_ACTIVE_ACCOUNT, data)
        .then((data) => {
            if (data.code != 200){
                this.showMainAlert('danger', 'Error desconocido al cambiar la cuenta activa.')
                return;
            }

            // close modal
            this.showMainAlert('success', 'Perfil activo cambiado correctamente!')
            this.redirectTimer(' . Recargando página en: ');
        })
        .catch((err) => {
            this.showMainAlert('danger', err)
        });
    },
    SelectActiveAccount (e, id, type) {
      this.openModal.hide()

      let data = {
          active_account_type: type,
          active_account: id
      };

      this.$store
        .dispatch(SET_ACTIVE_ACCOUNT, data)
        .then((data) => {
            if (data.code != 200){
                this.showMainAlert('danger', 'Error desconocido al cambiar la cuenta activa.')
                return;
            }
            // console.log(data)
            // close modal
            // this.showMainAlert('success', 'Perfil activo cambiado correctamente!')
            // this.redirectTimer(' . Recargando página en: ');
        })
        .catch((err) => {
            this.showMainAlert('danger', err)
        });
    },
    desactivate(){
      this.$store
        .dispatch(DESACTIVATE_OPERATION)
        .then((data) => {
          this.isDesactivate = data.data.value
        })
    },
    Isdesactivate(){
      const data = new FormData();
      data.append('id', 1);
      this.$store
        .dispatch(GET_SYSTEM_CONFIGURATION_VALUE, data)
        .then((data) => {
          this.isDesactivate = data.data.value
        })
    },
    showMainAlert(variant, message) {
      this.mainAlertVariant = variant;
      this.mainAlertMessage = message;
      this.mainAlert = true;
      window.scrollTo(0,0);
    },
    redirectTimer (redirectMessage) {
      this.redirectMessage = redirectMessage;
      this.wizardActionsDisabled = true;
      let _this = this;

      let redirectTime = window.setInterval(function () {
            if (_this.redirectMessageSeconds === 0) {
                 _this.redirectMessageSeconds = 0;
                window.clearInterval(redirectTime);
                location.reload();
            } else {
                _this.redirectMessageSeconds -= 1;
            }
        }, 1000);
    },
    onLogout() {
      this.$store.dispatch(LOGOUT).then(() => this.$router.push({ name: "login" }));
    },
    getIsAdmin() {
      this.isAdmin = window.localStorage.getItem("is_admin") == "true" ? true : false;
    },
    showModal(id) {
      this.openModal = new bootstrap.Modal(document.getElementById(id), {
        keyboard: false,
        backdrop:'static'
      })
      this.openModal.show()
    },
    hideModal(){
      this.openModal.hide()     
    },
    getNotifications(){
      this.$store
          .dispatch(NOTIFICATIONS_GET_ALL)
          .then((data) => {
              if (data.code != 200){
                  this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
                  return;
              }

             this.notifications = data.data.notify;
             this.notiview = data.data.count;

            //  this.notifications.count = data.data.count;
            //  console.log(data.data)

          })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
    },
    viewNotificactions(){
      this.$store
          .dispatch(VIEW_NOTIFICATIONS)
          .then((data) => {
              this.notiview = 0;
          })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
    },
    headerEmit(){
      this.$emit("changex",1);
    }
  },
  mounted() {
    // Init Desktop & Mobile Headers
    // this.getIsAdmin();
    this.getAllAccounts();
    this.getNotifications()
    if (this.isAdmin) {
      
      window.Echo.channel('notifications')
      .listen('NotificationsChangedEvent',(e)=>{
        this.getNotifications()
        this.sound.play()
      })
    }
  },
  created(){
    this.Isdesactivate()
  },
};
</script>
