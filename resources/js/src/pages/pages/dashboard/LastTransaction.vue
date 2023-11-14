<template>
  <div class="col-lg-12 mb-12 mb-lg-0">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <h5 class="card-title m-0 me-2">{{ isAdmin == true ? 'Toda las operaciones pendientes':'Operaciones realizadas' }}</h5>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="teamMemberList"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="ti ti-dots-vertical ti-sm text-muted"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="teamMemberList">
              <router-link to="/my-operations" v-slot="{ href, navigate }">
                <a  :href="href"   @click="navigate" class="dropdown-item" >
                  <i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="ms-2">Ver todas</span>
                </a>
              </router-link>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-borderless border-top" v-if="showOperation =='ready' " >
            <thead class="border-bottom">
              <tr>
                <th>BANCO</th>
                <th>FECHA</th>
                <th>ESTADO</th>
                <th>TIPO DE CAMBIO</th>
                <th>MONTO</th>
                <th class="text-center">TASA DE CAMBIO</th>
                <th>MONTO CAMBIADO</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="operation in operations" >
                <tr>
                  <td>
                      <div class="d-flex justify-content-start align-items-center">
                        <div class="me-3">
                          <img :src="operation.bank_account_send.bank.logo" @error="getDefaultIconBank($event)" alt="Visa" height="50" />
                        </div>
                        <div class="d-flex flex-column">
                          <p class="mb-0 fw-semibold">{{ operation.bank_account_send.account_number.slice(0,4) }}****{{ operation.bank_account_send.account_number.slice(-4) }}</p>
                          <small class="text-muted">{{ operation.bank_account_send.bank_account_type.name }}</small>
                        </div>
                      </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <p class="mb-0 fw-semibold">Enviado</p>
                      <small class="text-muted text-nowrap">{{ tableFormatDate(operation.created_at) }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="badge" 
                      :class="{ 
                        'bg-label-danger': operation.status == -1,
                        'bg-label-secondary': operation.status == 0,
                        'bg-label-warning': operation.status == 1,
                        'bg-label-primary': operation.status == 2,
                        'bg-label-success': operation.status == 3,
                        

                      }"
                    >
                      {{operation.status_label}}
                    </span>
                  </td>
                  <td>
                    {{ operation.bank_account_receive.coin_type.code == 'dolar' ? "Compra de dolares" : "Venta de dolares"}}
                  </td>
                  <td>
                    <p class="mb-0 fw-semibold">{{ operation.bank_account_send.coin_type.code == 'dolar' ? '$':'S/' }}{{ $func.numberFormat(parseFloat(operation.amount))}}</p>
                  </td>
                  <td class="text-center">
                    <p class="mb-0 fw-semibold">{{ operation.exchange_rate.toFixed(3) }}</p>
                  </td>
                  <td>
                    <p class="mb-0 fw-semibold">{{ operation.bank_account_receive.coin_type.code == 'dolar' ? '$':'S/' }}{{ $func.numberFormat(parseFloat(operation.change_amount))}}</p>
                  </td>
                </tr>
              </template>  
            </tbody>
          </table>
          <template v-else-if="showOperation =='load' ">
            <div class="w-100 border border-base border-end-0 border-start-0 d-flex justify-content-center p-4 align-items-center"  >
              <div class="spinner-border me-2 text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <h3 class=" text-center m-0 text-primary">Cargando</h3>
            </div>
          </template>
          <template v-else>
            <div class="w-100 border border-base border-end-0 border-start-0 d-flex justify-content-center p-4 align-items-center"  >
              <h3 class=" text-center ">Todavía no has realizado tu primera operación</h3>
            </div>
          </template>
        </div>
      </div>
  </div>
</template>
<script>
  import { mapGetters } from "vuex";
  import ApiService from "@/core/services/api.service";
  import { OPERATIONS_GET_LAST_FIVE } from "@/core/services/store/operation.module";
  export default {
    name: "lastTransaction",
    data() {
      return {
        mainAlert: false,
        mainAlertVariant: "",
        mainAlertMessage: "",
        operationDataAlert: false,
        operationDataAlertVariant: '',
        operationDataAlertMessage: '',
        allOperations: [],
        operations: 'i',
        currentAccountid: '',
        showOperation:'load',
        isAdmin:false
      }
    },
    mounted() {
     
    },
    methods: {
      getOperations () {
        let query = '?page=' + this.currentPage;
        setTimeout(()=>{
            this.$store
            .dispatch(OPERATIONS_GET_LAST_FIVE, query)
            .then((data) => {
                if (data.code != 200){
                    // this.showMainAlert('danger', 'Error desconocido al obtener las operaciones.')
                    return;
                }
                this.operations = data.data;
                setTimeout(()=>{
                  
                  this.showOperation = this.operations.length > 0 ? 'ready' : 'not-yet'
                },500)
            })
            .catch((err) => {
                // this.showMainAlert('danger', err)
                console.log(err)
            });
        },200)
                
      },
      getDefaultIconBank(event){
        event.srcElement.src = 'media/images/banks/default-logo.png'
        
      },
      getIsAdmin() {
        this.isAdmin = window.localStorage.getItem("is_admin") == "true" ? true : false;
      },
      tableFormatDate(date){
        let initDate = new Date(date);
        let MonthOfYear = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago','Sep', 'Oct', 'Nov', 'Dic']

        return `${initDate.getDate()} ${MonthOfYear[initDate.getMonth()]} ${initDate.getFullYear()}`

      }
    },
    computed: {
      ...mapGetters(["currentAccount"]),

      getCurrentAccount() {
        this.currentAccountid = this.currentAccount.id
        return this.currentAccount;
      }
    },
    created(){
      this.getIsAdmin();
      this.getOperations();
    }
  };
</script>