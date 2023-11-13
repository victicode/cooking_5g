<template>
  <ul class="menu-inner py-4">
    <!-- Dashboards -->
    <router-link to="/dashboard" v-slot="{ href, navigate, isActive, isExactActive }">
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover"  :class="[isActive && 'active', isExactActive && 'active']" >
        <a  :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-house"></i>
          <div>&nbsp; &nbsp;INICIO</div>
        </a>
      </li>
    </router-link>
    <!-- Operations page (client) -->
    <router-link v-if="role == 3" to="/my-operations" v-slot="{ href, navigate, isActive, isExactActive }">
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a  :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-file-lines"></i>
          <div>&nbsp; &nbsp;Mis movimientos</div>
        </a>
      </li>
    </router-link>
    <!-- banks configuration page (admin) -->
    <router-link v-if="isAdmin" to="/banks" v-slot="{ href, navigate, isActive, isExactActive }">
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a  :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-building-columns"></i>
          <div>&nbsp; &nbsp;Bancos</div>
        </a>
      </li>
    </router-link>
    <!-- Operations page (admin) -->
    <router-link v-if="isAdmin" to="/all-operations" v-slot="{ href, navigate, isActive, isExactActive }" >
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a  :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-receipt"></i>
          <div>&nbsp; &nbsp;Todas la operaciones</div>
        </a>
      </li>
    </router-link>
    <!-- Operation page (client) -->
    <router-link v-if="role == 3 && loadSend" to="/operations" v-slot="{ href, navigate, isActive, isExactActive }" >
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-money-bill-transfer"></i>
          <div>&nbsp; &nbsp;Cambiar divisas</div>
        </a> 
      </li>
    </router-link>
    <!-- Bank Account page (client & admin) -->
    <router-link v-if="isAdmin || role == 3 " to="/bank-accounts" v-slot="{ href, navigate, isActive, isExactActive }">
      <li  class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-wallet "></i>
          <div>&nbsp; &nbsp;Mis cuentas</div>
        </a>  
      </li>
    </router-link>
    <!-- Actions page (admin) -->
    <router-link v-if="isAdmin" to="/to-actions" v-slot="{ href, navigate, isActive, isExactActive }" >
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a  :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-toolbox"></i>
          <div>&nbsp; &nbsp;Acciones</div>
        </a>
      </li>
    </router-link>
    <!-- Report page (admin && fiscal) -->
    <li v-if="isAdmin || role == 5"  class="menu-item menu-father" >
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="fa-solid fa-file-lines"></i>
          <div>&nbsp; &nbsp;Reporte</div>
        </a>

        <ul class="menu-sub">
          <router-link to="/report-sbs" v-slot="{ href, navigate, isActive, isExactActive }" >
            <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
              <a  :href="href" class="menu-link" @click="navigate">
                <div>&nbsp; &nbsp;Reporte SBS</div>
              </a>
            </li>
          </router-link>
        </ul>
    </li>
    <!-- Clients Accounts page (admin) -->
    <li v-if="isAdmin"  class="menu-item menu-father" >
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="fa-solid fa-users"></i>
        <div>&nbsp; &nbsp;Todas las cuentas</div>
      </a>

      <ul class="menu-sub">
        <router-link to="/personal/accounts" v-slot="{ href, navigate, isActive, isExactActive }" >
          <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
            <a  :href="href" class="menu-link" @click="navigate">
              <div>&nbsp; &nbsp;Personas naturales</div>
            </a>
          </li>
        </router-link>
        <router-link to="/company/accounts" v-slot="{ href, navigate, isActive, isExactActive }" >
          <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
            <a  :href="href" class="menu-link" @click="navigate">
              <div>&nbsp; &nbsp;Personas jurídicas</div>
            </a>
          </li>
        </router-link>
      </ul>
    </li>
    <!-- Profile page (client) -->
    <router-link v-if="role == 3" to="/profiles" v-slot="{ href, navigate, isActive, isExactActive }" >
      <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover" :class="[isActive && 'active', isExactActive && 'active']" >
        <a :href="href" class="menu-link" @click="navigate">
          <i class="fa-solid fa-users"></i>
          <div>&nbsp; &nbsp;Mis Perfiles</div>
        </a> 
      </li>
    </router-link>
    <!-- Misc -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">&nbsp; &nbsp;Ayuda</span>
    </li>
    <li class="menu-item" aria-haspopup="true" data-menu-toggle="hover"  >
      <a href="#"  class="menu-link">
        <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
        <div>&nbsp; &nbsp;Whatsapp</div>
      </a>
    </li>
  </ul>
</template>

<script>
import { mapGetters } from "vuex";
import { ACCOUNTS_GET_ALL} from "@/core/services/store/account.module";
export default {
  name: "KTMenu",
  data() {
    return {
      isAdmin: false,
      loadSend: false,
      currentAccount:'',
      personalAccounts: [],
      companyAccounts: [],
      activeAccountType: null,
      activeAccountId: null,
      role: 0
    }
  },
  
  methods: {
    activeChildren(){
      setTimeout(()=>{
        const child = document.querySelector('.menu-item.active');
        
        try{
          const father = child.closest('.menu-father');
          father.classList.add("open","active");
        } catch {
          
        }
        
      }, 200)
    },
    hasActiveChildren(match) {
      return this.$route["path"].indexOf(match) !== -1;
    },

    getIsAdmin() {
      this.isAdmin = window.localStorage.getItem("is_admin") == "true" ? true : false;
    },
    getAllAccounts() {
      this.$store
        .dispatch(ACCOUNTS_GET_ALL)
        .then((data) => {
            if (data.code != 200){
                this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
                return;
            }
            this.personalAccounts = data.data.personalAccounts;
            this.companyAccounts = data.data.companyAccounts;
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
      this.userdesactivate();
    },
    userdesactivate(){
      setTimeout(() => {
        if (this.currentAccount.status == "1") {
          this.loadSend = true;
        }
      }, 1000);
    },
  },
  mounted() {
    // this.getIsAdmin();
    this.activeChildren();
    
  },
  created() {
    this.getAllAccounts()
  }
};
</script>
