<template>
  <div>
    <div id="operationForm" class="row justify-content-center operation__form-converted ">
      <!-- calculadora-->
      <div class="col-12 mb-4">
        <div id="wizardForm" class="bs-stepper shadow-none vertical wizard-vertical-icons-example mt-2 h-100">
        <div class="bs-stepper-header px-1 pt-5">
            <div class="step mb-3" data-target="#account-details-vertical">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-circle">
                  <i class="fa-solid fa-building-columns"></i>
                </span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title">Tipo de cambio</span>
                  <span class="bs-stepper-subtitle" >Selecciona la cuenta bancaria de origen y destino</span>
                </span>
            </button>
            </div>
            <div class="line"></div>
            <div class="step mb-3" data-target="#personal-info-vertical">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-circle">
                  <i class="fa-solid fa-money-bill-transfer"></i>
                </span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title">Transfierenos</span>
                  <span class="bs-stepper-subtitle">Selecciona la cuenta a la que nos tranfieres</span>
                </span>
            </button>
            </div>
            <div class="line"></div>
            <div class="step mb-3" data-target="#complete">
              <button type="button" class="step-trigger">
                <span class="bs-stepper-circle">
                  <i class="fa-solid fa-file-invoice-dollar"></i>
                </span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title">Completado</span>
                  <span class="bs-stepper-subtitle">Visualiza los datos y realiza la operación </span>
                </span>
              </button>
            </div>
        </div>
        <div class="bs-stepper-content px-3 py-0 h-100">
            <div id="wizard-content" class="h-100">
              <!-- Account Details -->
              <div id="account-details-vertical" class="content h-100">
                  <div class="row">
                    <div class="col-8">

                    </div>
                    <div class="col-4 text-end d-flex justify-content-end">
                      <button type="button" class="btn btn-outline-primary" @click="showModal('addNewCCModal',1,1)">Agregar nueva cuenta</button>
                    </div>
                  </div>
                  <div class="content-header mb-3">
                    <h4 class="fw-bold pt-3 mb-2 text-center" ><span class="text-muted fw-light">Selecciona </span> tu cuenta</h4>
                    <h5 class="card-title text-center">Selecciona el banco de donde nos envías y la cuenta donde deseas recibir.</h5>
                  </div>
                  <div class="d-flex flex-column g-3 h-70 justify-content-between">
                    <accounts-tag v-if="loadSend" :accounts="allBankAccountsByCoin" :idTag="'bankSend'"  @selectSendBank="selects" :labelTag="'¿Desde qué cuenta nos envias tu dinero?'"></accounts-tag>
                    <accounts-tag v-if="loadSend" :accounts="filteredBankAccountsByCoinType" @selectReceiveBank="selects" :idTag="'bankRecived'" :labelTag="'¿En que cuenta recibirás tu dinero?'"></accounts-tag>
                    <input type="hidden" name="bankSendInput"   v-model="newOperation.bank_account_send_id">
                    <input type="hidden" name="bankRecivedInput" v-model="newOperation.bank_account_receive_id">

                    <div class="col-lg-12 col-mg-12 col-sm-12 mb-3 valid-item">
                      <label class="" for="dealTitle"><strong> Origen de fondos</strong></label>
                      <select class="selectpicker w-100 select2 form-select"  name="originFound" v-model="newOperation.fund_origin_code" style="width: 100%!important" @change="originValidate()">
                        <option value="-1">Selecciona el origen de fondos</option>
                        <option v-for="fundOrigin in fundOrigins" v-bind:value="fundOrigin.code" :key="`fund_origin_${fundOrigin.id}`">
                            {{ fundOrigin.name }}
                        </option>
                      </select>
                    </div>
                    <div class="col-sm-12 valid-item mb-3" v-if="selectedFundOrigin.code == 'otros'">
            
                      <label class="" for="basic-icon-default-fullname" ><strong>Otro origen de fondos</strong></label>
                      <input
                        type="text"
                        class="form-control"
                        id="basic-default-name"
                        name="business_name" 
                        ref="business_name"
                        v-model="selectedFundOrigin.other_fund_origin"
                        placeholder="Regalias de musica"
                        
                      />
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <button class="btn btn-label-secondary btn-backprev">
                          <i class="ti ti-arrow-left me-sm-1"></i>
                          <span class="align-middle d-sm-inline-block d-none" >Anterior</span>
                        </button>
                        <button class="btn btn-primary btn-next align-items-baseline" id="step2"  disabled onsubmit="false">
                          <span class="align-middle d-sm-inline-block d-none me-sm-1">Siguiente</span>
                          <i class="ti ti-arrow-right"></i>
                        </button>
                    </div>
                  </div>
              </div>
              <!-- Personal Info -->
              <div id="personal-info-vertical" class="content h-100">
                  <div class="content-header mb-3">
                    <h4 class="fw-bold pt-3 mb-2 text-center"><span class="text-muted fw-light">Selecciona </span> a que cuenta nos transfieres</h4>
                    <h5 class="card-title text-center">Selecciona la cuenta de banco a la cual nos vas a transferir.</h5>
                  </div>
                  <div class="d-flex flex-column g-3 h-70 justify-content-between">
                    <accounts-tag v-if="loadSend" :accounts="filteredBankAccountsToTransferByCoinType" @selectTransferBank="selects" :idTag="'bankToTransfer'" :labelTag="'¿A qué cuenta nos transferiras el dinero?'"></accounts-tag>
                    <input type="hidden" name="bankToTransferInput" ref="bankToTransferInput">
                    <div class="col-12 d-flex justify-content-between">
                        <button class="btn btn-label-secondary btn-prev align-items-baseline">
                          <i class="ti ti-arrow-left me-sm-1"></i>
                          <span class="align-middle d-sm-inline-block d-none">Anterior</span>
                        </button>
                        <button class="btn btn-primary btn-next align-items-baseline" disabled id="step3"  onsubmit="false">
                        <span class="align-middle d-sm-inline-block d-none me-sm-1">Siguiente</span>
                        <i class="ti ti-arrow-right"></i>
                        </button>
                    </div>
                  </div>
              </div>
              <!-- Complete-->
              <div id="complete" class="content h-100">
                <div v-if="Object.keys(selectedBankAccountSend).length > 0 && Object.keys(selectedBankAccountReceive).length > 0 && Object.keys(selectedBankAccountTransfer).length > 0">
                
                  <div class="content-header mb-0 px-3">
                    <h4 class="fw-bold pt-0 mb-2 text-center">Verifica los datos de la transacción</h4>
                  </div>
                  <div class="d-flex justify-content-between px-2 align-items-center amount_section mt-4 mb-2">
                    <div >
                      <h4 class="mb-0 text-primary">
                        {{ newOperation.operationType == 'buy' ? 'S/.' : '$' }} {{ $func.numberFormat(newOperation.amount) }}
                      </h4>
                    </div>
                    <div>
                      <h6 class="mt-2 mb-0 h8 fw-light"><strong class="fw-bold text-primary">Recibes:</strong> {{newOperation.operationType == 'buy' ? '$' : 'S/.'}} {{$func.numberFormat(newOperation.change_amount)}} </h6>
                      <h6 class="mb-2 h8 fw-light"><strong class="fw-bold text-primary">Tasa:</strong> {{newOperation.exchange_rate}} </h6>
                    </div>
                  </div>
                  <div class="d-flex flex-column g-3 h-50 justify-content-between">
                    <div class="d-flex flex-column px-2 my-3">
                      <div class="d-flex flex-column justify-content-center align-items-center" >
                        <div class="col-12 px-0 py-1 my-1 ">
                            <div class="col-12  d-flex flex-column  align-items-center card-transfer2" style="">
                              <div class=""><h6 class="mb-0  mt-2">Cuenta desde la que realizaste la trasacción</h6></div>
                              <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                                <div>
                                  <img class="img-convert" :src="selectedBankAccountSend.bank.logo" />
                                  <h6 class="text-img-converte h85 fw-bold">
                                    {{ 
                                    selectedBankAccountSend.owner_name != null ? selectedBankAccountSend.owner_name : 
                                    selectedBankAccountSend.personal_account != null ? selectedBankAccountSend.personal_account.name +' '+selectedBankAccountSend.personal_account.surname :
                                    selectedBankAccountSend.company_account.business_name
                                    }}
                                  </h6>
                                </div>
                                <div class="d-flex">
                                  <div class="text-end">
                                    <h6 class="mb-2 h85 text-primary">{{ newOperation.operationType == 'buy' ? 'Cuenta Soles' : 'Cuenta dolares' }}</h6>
                                    <h6 class="mb-0 h85">{{ selectedBankAccountSend.account_number }}</h6>
                                  </div>
                                </div>
                              </div>
                            </div>  
                          </div>
                          <div class="col-12 px-0 py-1 my-1 ">
                            <div class="col-12  d-flex flex-column  align-items-center align-items-center card-transfer2" style="">
                              <div class=""><h6 class="mb-0  mt-2">Cuenta a la que realizaste la trasacción</h6></div>
                              <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                                <div>
                                  <img class="img-convert" :src="selectedBankAccountTransfer.bank.logo" />
                                  <h6 class="text-img-converte h85 fw-bold">
                                    {{ 
                                    selectedBankAccountTransfer.owner_name != null ? selectedBankAccountTransfer.owner_name : 
                                    selectedBankAccountTransfer.personal_account != null ? selectedBankAccountTransfer.personal_account.name +' '+ selectedBankAccountTransfer.personal_account.surname :
                                    selectedBankAccountReceive.company_account.business_name
                                    }}
                                  </h6>
                                </div>
                                <div class="d-flex">
                                  <div class="text-end">
                                    <h6 class="mb-2 h85 text-primary">{{ newOperation.operationType == 'buy' ? 'Cuenta Soles' : 'Cuenta dolares' }}</h6>
                                    <h6 class="mb-0 h85">{{ selectedBankAccountTransfer.account_number }}</h6>
                                  </div>
                                </div>
                              </div>
                            </div>  
                          </div>
                          <!-- <div class="col-11 px-0 py-1 my-1 card-bank-content2 "> -->
                          <div class="col-12 px-0 py-1 my-1 ">
                            <div class="col-12  d-flex flex-column  align-items-center card-transfer2" style="">
                              <div class=""><h6 class="mb-0  mt-2">Cuenta a la que vas a recibir</h6></div>
                              <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                                <div>
                                  <img class="img-convert" :src="selectedBankAccountReceive.bank.logo" />
                                  <h6 class="text-img-converte h85 fw-bold">
                                    {{ 
                                    selectedBankAccountReceive.owner_name != null ? selectedBankAccountReceive.owner_name : 
                                    selectedBankAccountReceive.personal_account != null ? selectedBankAccountReceive.personal_account.name +' '+selectedBankAccountReceive.personal_account.surname :
                                    selectedBankAccountReceive.company_account.business_name
                                    }}
                                  </h6>
                                </div>
                                <div class="d-flex">
                                  <div class="text-end">
                                    <h6 class="mb-2 h85 text-primary">{{ newOperation.operationType == 'buy' ? 'Cuenta dolares' : 'Cuenta Soles' }}</h6>
                                    <h6 class="mb-0 h85">{{ selectedBankAccountReceive.account_number }}</h6>
                                  </div>
                                </div>
                              </div>
                            </div>  
                          </div>
                      </div>
                      
                    </div>
                    <div class="col-sm-12 valid-item mt-2">
                        <label><strong>Número de operación</strong></label>
                        <input
                          type="text"
                          autocomplete="off"
                          class="form-control"
                          id="basic-default-name"
                          name="operation_number" 
                          ref="operation_number"
                          v-model="newOperation.operation_number"
                          placeholder="Ingrese el número de operación"
                          @change="refAndVaucher()"
                        
                        />
                      </div>
                      <div class="col-sm-12 valid-item mb-5 mt-2">
                        <label class="form-label" for="dealDescription"><strong>Adjunte foto del vaucher de la transacción</strong></label>
                        <input 
                          type="file" 
                          name="vaucher"
                          ref="vaucher"
                          accept=".png, .jpg, .jpeg, .pdf" 
                          class="form-control" 
                          id="basic-default-upload-file"
                          placeholder="Elija un archivo"
                          @change="onFileChange($event); refAndVaucher()"
                        />
                      </div>
                    
                  </div>
                </div>
                <div v-else>
                  <h1>Nos has selecionado la cuenta de destino!</h1>
                </div>
                
                <div class="col-12 d-flex justify-content-between">
                  <button class="btn btn-label-secondary btn-prev align-items-baseline ">
                  <i class="ti ti-arrow-left me-sm-1"></i>
                  <span class="align-middle d-sm-inline-block d-none">Anterior</span>
                  </button>
                  <button class="btn btn-primary  align-items-baseline" id="step5" onsubmit="false"  disabled="true" @click="storeOperation()">
                    <i class="ti ti-check me-sm-1"></i>
                    <span class="align-middle d-sm-inline-block d-none">Confirmar!</span>
                  </button>
                </div>
              </div>
            </div>
        </div>
      </div>
      </div>
    </div>
    <div class="modal fade modal-lg" id="alertAccounts" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-5">
          <div class="modal-body">
            <div class="text-center mb-4">
              <h5 class="mb-2">{{haveAccountMsg}}</h5>
            </div>
            <div class="d-flex justify-content-center">
              <!-- <router-link to="/bank-accounts" v-slot="{ href, navigate, isActive, isExactActive }"> -->

                <a href="#" class="justify-content-center" @click="showModal('addNewCCModal',1)" >
                  <button class="btn btn-primary justify-content-center">
                    <i class="fa-solid fa-wallet "></i>
                    <div>&nbsp; &nbsp;Agregar cuenta</div>
                  </button>
                </a>  
              <!-- </router-link> -->
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <add-bank-modal v-if="loadAddBank" :user="userSel" :typeAction="valueClose" @closeAddModal="hideInternModal"></add-bank-modal>
  </div>
</template>
<script>
  import { OPERATIONS_STORE } from "@/core/services/store/operation.module";
  import { BANK_ACCOUNTS_GET_ALL_TO_TRANSFER, BANK_ACCOUNTS_GET_ALL_BY_USER } from "@/core/services/store/bank_account.module";
  import { FUND_ORIGINS_GET_ALL } from "@/core/services/store/fund_origin.module";
  import accountsTag from "@/view/pages/operations/client/accountsTag.vue";
  import { bootstrap } from "@/concept/bootstrap";
  import addBankModal from "@/view/pages/actions/manual-operation/modal/addNewBankAccount.vue";
  export default {
  name:'formManual',
  data() {
    return {
      mainAlert: false,
      mainAlertVariant: "",
      mainAlertMessage: "",
      allBankAccounts: [],
      filteredBankAccountsByCoinType: [],
      allBankAccountsByCoin: [],
      allBankAccountsToTransfer: [],
      filteredBankAccountsToTransferByCoinType: [],
      fundOrigins: [],
      newOperation:{},
      selectedFundOrigin: {},
      selectedBankAccountSend: {},
      selectedBankAccountReceive: {},
      selectedBankAccountTransfer: {},
      openModal:'',
      haveAccountMsg:'',
      loadSend: false,
      userSel:'',
      vaucher:'',
      loadAddBank:'',
      valueClose:0,

    }
  },
  props:['exchange', 'count', 'users'],
  mounted() {
    
    this.newOperation = this.exchange;
    this.userSel= this.users;
    this.getBankAccounts();
    this.getBankAccountsToTransfer();
    this.getFundOrigins();
  },
  components: {
        accountsTag:accountsTag,
        addBankModal:addBankModal
  },
  watch: {
    'exchange.operationType': {
        handler: function (after, before) {
          this.loadSend = false
          this.newOperation = this.exchange;
          this.userSel= this.users;
          this.getBankAccounts();
          this.getBankAccountsToTransfer();
          this.getFundOrigins();
        },
        deep: true
    },
    'users': {
        handler: function (after, before) {
          this.loadSend = false
          this.newOperation = this.exchange;
          this.userSel= this.users;
          this.getBankAccounts();
          this.getBankAccountsToTransfer();
          this.getFundOrigins();
        },
        deep: true
    }
  },
  methods: {
    storeOperation() {
      document.getElementById('step5').setAttribute('disabled', '');
      document.getElementById('step5').textContent = 'Cargando...'
      const data = new FormData();
      data.append('bank_account_send_id', this.newOperation.bank_account_send_id);
      data.append('bank_account_receive_id', this.newOperation.bank_account_receive_id);
      data.append('amount', parseFloat(this.newOperation.amount.replace(/,/g, '')));
      data.append('change_amount', parseFloat(this.newOperation.change_amount.replace(/,/g, '')));
      data.append('exchange_rate', this.newOperation.exchange_rate);
      data.append('bank_account_transfer_id', this.newOperation.bank_account_transfer_id);
      data.append('operation_number', this.newOperation.operation_number);
      data.append('fund_origin_id', this.selectedFundOrigin.id);
      data.append('vaucher', this.vaucher);
      data.append('status', 3);
      if (this.selectedFundOrigin.code == 'otros') {
          data.append('other_fund_origin', this.selectedFundOrigin.other_fund_origin);
      }
      if (this.refAndVaucher()) {
        document.getElementById('step5').setAttribute('disabled', '');
        this.$store
            .dispatch(OPERATIONS_STORE, data)
            .then((data) => {
                if (data.code != 201){
                    this.showMainAlert('danger', 'Error desconocido al guardar la operación.');
                    return;
                }
                document.getElementById('step5').textContent = 'EXITOSO!'
                
                this.finishOperation()
                this.showMainAlert('success', 'Transacción realizada con exito! seras redirigido a las transacciones.');
                setTimeout(() => {
                  this.$router.push('/all-operations')
                }, 1000);
                
            })
            .catch((err) => {
                this.showMainAlert('danger', err);
            });
      }
    },
    finishOperation() {
      this.$emit("hidde");
    },

    validateAccounts(){

        
      if (this.filteredBankAccountsByCoinType.length == 0 && this.allBankAccountsByCoin.length == 0) {
        this.haveAccountMsg ='No tienes cuentas bancarias agregadas, por favor debes agregar por lo menos 2 cuentas bancarias';
        this.showModal('alertAccounts')
      }else if(this.filteredBankAccountsByCoinType.length == 0 && this.allBankAccountsByCoin.length > 0){
        this.haveAccountMsg = 'No tiene cuenta bancaria registrada con la modena de destino, agrega tu cuentas bancaria de destino';
        this.showModal('alertAccounts')
      }else if(this.filteredBankAccountsByCoinType.length > 0 && this.allBankAccountsByCoin.length == 0){
        this.haveAccountMsg = 'No tiene cuenta bancaria registrada con la modena de origen, agrega tu cuentas bancaria de origen';
        this.showModal('alertAccounts')
      }
      
    },
    showModal(id, time=0, closeType=0) {
      this.loadAddBank = time;
      this.valueClose = closeType
      try {
        this.openModal.hide()
      } catch (error) {
        
      }
      setTimeout(() => {
        this.openModal = new bootstrap.Modal(document.getElementById(id), {
          keyboard: false,
          backdrop:'static'
        })
        
        this.openModal.show()
      }, 200);
    },
    hideInternModal(cancel = 0,type=0){
      this.openModal.hide()

      if(type == 0){
        if(cancel == 1){
          this.finishOperation()
          return
        }
      }
      if(cancel == 2){
        this.loadSend = false;
        this.getBankAccounts();
        this.getBankAccountsToTransfer();
        this.getFundOrigins();
      }
      this.loadAddBank = false;
    },
    onFileChange(e) {
      this.vaucher = e.target.files[0];

      if (typeof FileReader === "function") {
          const reader = new FileReader();
          reader.readAsDataURL(this.vaucher);
      } else {
          alert("Sorry, FileReader API not supported");
      }
    },
    getBankAccounts() {
      setTimeout(() => {
        const datax = new FormData();
        datax.append('id', this.userSel.id);
        datax.append('type_acc', this.userSel.type);
        this.$store
            .dispatch(BANK_ACCOUNTS_GET_ALL_BY_USER,datax)
            .then((data) => {
                if (data.code != 200) {
                    return;
                }
                this.allBankAccounts = data.data;
                this.allBankAccounts.map((item) => {
                  return item.value = item.id
                })
                this.allBankAccounts.map((item) => {
                  return item.nameBank = item.bank.name
                })

                setTimeout(() => {
                  this.filterBankAccountsRecive()
                },200)
                
              })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
      },500)
      
    },
    selects(bank) { 
      if(bank.typeOperationBank == 'bankRecived'){

        this.newOperation.bank_account_receive_id = Object.keys(bank).length > 1 ? bank.data[0].id : '';
        this.selectedBankAccountReceive = Object.keys(bank).length > 1 ? bank.data[0] : '';

      }else if(bank.typeOperationBank == 'bankSend'){

        this.selectedBankAccountSend = Object.keys(bank).length > 1 ? bank.data[0] : '';
        this.newOperation.bank_account_send_id = Object.keys(bank).length > 1 ? bank.data[0].id : '';

      }else if(bank.typeOperationBank =='bankToTransfer'){

        this.newOperation.bank_account_transfer_id = Object.keys(bank).length > 1 ? bank.data[0].id : '';
        this.selectedBankAccountTransfer = Object.keys(bank).length > 1 ? bank.data[0] : '';
        this.$refs.bankToTransferInput.value = Object.keys(bank).length > 1 ? bank.data[0].id : '';
        
      }

      if( this.newOperation.bank_account_send_id > '' && this.newOperation.bank_account_receive_id > '' ){
        document.getElementById('step2').removeAttribute('disabled');
        this.originValidate()
      }else{
        document.getElementById('step2').setAttribute('disabled', '');
        this.originValidate()
      }
      if( this.newOperation.bank_account_transfer_id > -1 ){
        document.getElementById('step3').removeAttribute('disabled');
      }else{
        document.getElementById('step3').setAttribute('disabled', '');
      }
    },
    originValidate(){
      this.selectedFundOrigin = this.fundOrigins.find(fundOrigin => fundOrigin.code == this.newOperation.fund_origin_code);
      if( this.newOperation.fund_origin_code != -1 ){
        document.getElementById('step2').removeAttribute('disabled');
      }else{
        document.getElementById('step2').setAttribute('disabled', '');
      }
    },
    originValidate(){
      this.selectedFundOrigin = this.fundOrigins.find(fundOrigin => fundOrigin.code == this.newOperation.fund_origin_code);
      if( this.newOperation.fund_origin_code != -1 ){
        document.getElementById('step2').removeAttribute('disabled');
      }else{
        document.getElementById('step2').setAttribute('disabled', '');
      }
    },
    refAndVaucher(){
      let send = false;
      if( this.$refs.operation_number.value != '' && this.$refs.vaucher.files.length > 0){
        document.getElementById('step5').removeAttribute('disabled');
        send = true
      }else{
        document.getElementById('step5').setAttribute('disabled', '');
      }
      return send
    },
    getBankAccountsToTransfer () {
        this.$store
            .dispatch(BANK_ACCOUNTS_GET_ALL_TO_TRANSFER)
            .then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas bancarias para transferir.')
                    return;
                }
                this.allBankAccountsToTransfer = data.data;
                this.allBankAccountsToTransfer.map((item) => {
                  return item.value = item.id
                })

                this.filteredBankAccountsToTransferByCoinType = this.newOperation.operationType == 'buy' ?
                this.allBankAccountsToTransfer.filter(bankAccount => bankAccount.coin_type_id != 2) :
                this.allBankAccountsToTransfer.filter(bankAccount => bankAccount.coin_type_id != 1) 
            })
            .catch((err) => {
                this.showMainAlert('danger', err)
            });
    },
    getFundOrigins () {
        if (this.fundOrigins.length > 0) {
            return;
        }
        this.$store
            .dispatch(FUND_ORIGINS_GET_ALL)
            .then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger', 'Error desconocido al obtener todos origenes de fondos.')
                    return;
                }

                this.fundOrigins = data.data;
            })
            .catch((err) => {
                this.showMainAlert('danger', err)
            });
    },
    filterBankAccountsRecive() {
      this.allBankAccountsByCoin = this.newOperation.operationType == 'buy' ?
        this.allBankAccounts.filter(bankAccount => bankAccount.coin_type_id != 2) :
        this.allBankAccounts.filter(bankAccount => bankAccount.coin_type_id != 1) 

      this.filteredBankAccountsByCoinType = this.newOperation.operationType == 'buy' ?
        this.allBankAccounts.filter(bankAccount => bankAccount.coin_type_id != 1) :
        this.allBankAccounts.filter(bankAccount => bankAccount.coin_type_id != 2) 
        
      this.loadSend = true

      this.validateAccounts()
    },
    setReceiveAccount () {
        this.selectedBankAccountReceive = this.filteredBankAccountsByCoinType.find(bankAccount => bankAccount.id == this.newOperation.bank_account_receive_id);

        this.coinTypeReceiveData = this.selectedBankAccountReceive.coin_type;
    },

    setTransferAccount () {
        this.selectedBankAccountTransfer = this.allBankAccountsToTransfer.find(bankAccount => bankAccount.id == this.newOperation.bank_account_transfer_id);
    },

    showMainAlert(variant, message) {
        this.mainAlertVariant = variant;
        this.mainAlertMessage = message;
        this.mainAlert = true;
        window.scrollTo(0,0);
    },

    hideMainAlert () {
        this.mainAlertVariant = '';
        this.mainAlertMessage = '';
        this.mainAlert = false;
    },
  },
  created() {
  },
  computed: {
    logo() {
      return process.env.BASE_URL + "media/branding/logo-dls.png";
    },
    img() {
      return ({
        block1: process.env.BASE_URL + 'media/illustrations/wizard-create-deal-girl-with-laptop-light.png',
        confirm: process.env.BASE_URL + 'media/illustrations/wizard-create-deal-confirm.png',
        background: process.env.BASE_URL + 'media/backgrounds/registro.jpg',
        background2: process.env.BASE_URL + "media/backgrounds/bg-blanco.jpg",
      });
    }
  }
  };
</script>