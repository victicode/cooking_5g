<template>
  <div>
    <div class="row" v-if="mainAlert">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}{{ redirectMessage == '' ? '' : redirectMessage + redirectMessageSeconds + ' segundos.' }}</b-alert>
      </div>
      <hr>
    </div>
    <div id="operationForm" class="row justify-content-center operation__form-converted ">
      <!-- calculadora-->
      <div class="col-10 mb-4">
        <div id="wizardForm" class="bs-stepper vertical wizard-vertical-icons-example mt-2 h-100">
          <div class="bs-stepper-header px-1 pt-5">
              <div class="step mb-3" data-target="#account-details-vertical">
              <button type="button" class="step-trigger">
                  <span class="bs-stepper-circle">
                    <i class="fa-solid fa-building-columns"></i>
                  </span>
                  <span class="bs-stepper-label">
                    <span class="bs-stepper-title">Tipo de cambio</span>
                    <span class="bs-stepper-subtitle">Selecciona la cuenta bancaria de origen y destino</span>
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
          <div class="bs-stepper-content px-3 h-100">
              <div id="wizard-content" class="h-100">
                <!-- Account Details -->
                <div id="account-details-vertical" class="content h-100">
                    <div class="content-header mb-3">
                      <h4 class="fw-bold pt-3 mb-2 text-center"><span class="text-muted fw-light">Selecciona </span> tu cuenta</h4>
                      <h5 class="card-title text-center">Selecciona el banco de donde nos envías y la cuenta donde deseas recibir.</h5>
                    </div>
                    <div class="d-flex flex-column g-3 h-70 justify-content-between">
                      <accounts-tag v-if="loadSend" :accounts="allBankAccountsByCoin" :idTag="'bankSend'"  @selectSendBank="selects" :labelTag="'¿Desde qué cuenta nos envias tu dinero?'"></accounts-tag>
                      <input type="hidden" name="bankSendInput"   v-model="newOperation.bank_account_send_id">
                      <accounts-tag v-if="loadSend" :accounts="filteredBankAccountsByCoinType" @selectReceiveBank="selects" :idTag="'bankRecived'" :labelTag="'¿En que cuenta recibirás tu dinero?'"></accounts-tag>
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
                          <button class="btn btn-label-secondary btn-backprev align-items-baseline">
                            <i class="ti ti-arrow-left me-sm-1"></i>
                            <span class="align-middle d-sm-inline-block d-none">Anterior</span>
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
                          <button class="btn btn-primary btn-next align-items-baseline" disabled id="step3" @click="timer()" onsubmit="false">
                          <span class="align-middle d-sm-inline-block d-none me-sm-1">Siguiente</span>
                          <i class="ti ti-arrow-right"></i>
                          </button>
                      </div>
                    </div>
                </div>
                <!-- Complete-->
                <div id="complete" class="content h-100">
                  <div class="content-header mb-0 px-3">
                    <h4 class="fw-bold pt-3 mb-2 text-center"><span class="text-muted fw-light">Realiza la transacción </span> a nuestra cuenta</h4>
                    <h6 class="card-title text-center mb-1" v-if="Object.keys(selectedBankAccountSend).length > 0 && Object.keys(selectedBankAccountReceive).length > 0 && Object.keys(selectedBankAccountTransfer).length > 0">Transfiere desde tu banca por internet del {{selectedBankAccountSend.bank.name }} el importe de:</h6>
                  </div>
                  <div class="d-flex justify-content-between px-2 align-items-center amount_section mt-4 mb-2">
                    <div >
                      <h4 class="mb-0 text-primary">
                        {{ newOperation.operationType == 'buy' ? 'S/.' : '$' }} {{ $func.numberFormat(newOperation.amount) }}
                      </h4>
                    </div>
                    <div>
                      <h6 class="mt-2 mb-0 h8 fw-light"><strong class="fw-bold text-primary">Recibes:</strong> {{newOperation.operationType == 'buy' ? '$' : 'S/.'}} {{$func.numberFormat(newOperation.change_amount)}} </h6>
                      <h6 class="mb-2 h8 fw-light"><strong class="fw-bold text-primary">Tasa:</strong> {{newOperation.exchange_rate.toFixed(3) }} </h6>
                    </div>
                  </div>
                  <div class="card-timer d-flex justify-content-between px-2 mb-0">
                    <h6 class="fw-light h852">Tiempo para finalizar:</h6>
                    <div class="d-flex w-25 justify-content-end">
                      <i class="fa-solid mt-0-1 fa-hourglass-half fa-bounce h852"></i>
                      <h6 class="fw-bold ms-1 h852">{{ minutes.toString().length == 1 ? '0' + minutes : minutes }}:{{ seconds.toString().length == 1 ? '0' + seconds : seconds }}</h6>
                    </div>
                    
                  </div>
                  <div class="d-flex flex-column g-3 h-50 justify-content-between">
                    <div class="d-flex flex-column px-2 my-3">
                      <div class="d-flex flex-column justify-content-center align-items-center" v-if="Object.keys(selectedBankAccountSend).length > 0 && Object.keys(selectedBankAccountReceive).length > 0 && Object.keys(selectedBankAccountTransfer).length > 0">
                        <div class="col-12 px-0 py-1 my-1 ">
                          <div class="col-12  d-flex flex-column  align-items-center align-items-center card-transfer" style="">
                            <div class=""><h6 class="mb-0  mt-2">Cuenta a la que vas a transferir</h6></div>
                            <div class="d-flex div-small justify-content-between align-items-center col-12 px-3 ">
                              <div>
                                <img class="img-convert" :src="selectedBankAccountTransfer.bank.logo" />
                                <h6 class="text-img-converte h85 fw-bold">
                                  {{ 
                                  selectedBankAccountTransfer.owner_name != null ? selectedBankAccountTransfer.owner_name : 
                                  selectedBankAccountTransfer.personal_account != null ? selectedBankAccountTransfer.personal_account.name +' '+ selectedBankAccountTransfer.personal_account.surname :
                                  selectedBankAccountReceive.company_account.business_name
                                  }} - {{ 
                                    selectedBankAccountTransfer.owner_document_type_id != null ? selectedBankAccountTransfer.owner_document_type.number : 
                                    selectedBankAccountTransfer.personal_account != null ? selectedBankAccountTransfer.personal_account.document_number :
                                    selectedBankAccountReceive.company_account.ruc
                                   }}
                                </h6>
                              </div>
                              <div class="d-flex mt-2">
                                <div class="text-end">
                                  <h6 class="mb-2 h85 text-primary">{{ newOperation.operationType == 'buy' ? 'Cuenta Soles' : 'Cuenta dolares' }}</h6>
                                  <h6 class="mb-0 h85">{{ selectedBankAccountTransfer.account_number }}</h6>
                                </div>
                                <div class="copy__item--convert ms-3">
                                  <input type="text" name="clipboard" class="d-none" :ref="'clone'+selectedBankAccountTransfer.id" :value="selectedBankAccountTransfer.account_number">
                                  <div class="clipboard-zone copy__item--div">
                                    <button
                                      class="clipboard-btn btn  m-0 p-0"
                                      @click="copyOnClipboard($event)"   data-bs-toggle="tooltip" data-bs-placement="top" title="Copiar"  
                                    >
                                      <i class="fa-regular fa-paste fa-lg text-primary" :data-key="'clone'+selectedBankAccountTransfer.id" ></i>
                                      <div class="tooltip-text" role="tooltip">Copiar</div>
                                    </button>
                                    
                                  </div>
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
                        @change="onFileChange($event)"
                      />
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                      <button class="btn btn-label-secondary btn-prev align-items-baseline " @click="quitTimer()">
                      <i class="ti ti-arrow-left me-sm-1"></i>
                      <span class="align-middle d-sm-inline-block d-none">Anterior</span>
                      </button>
                      <button class="btn btn-primary btn-next align-items-baseline" id="step5" onsubmit="false" disabled>
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

      <!-- <div class="col-3">
        <div class="card w-100">
          <div class="d-flex px-3 mb-4 justify-content-between w-100"> 
            <div>Monto a transferir:</div>
            <div>{{  newOperation.operationType == 'buy' ? newOperation.amount.toFixed(2) : newOperation.change_amount.toFixed(2)   }}</div>
          </div>
          <div class="d-flex px-3 mb-4 justify-content-between w-100"> 
            <div>Recibirás:</div>
            <div>{{ newOperation.operationType == 'buy' ? newOperation.change_amount.toFixed(2)  : newOperation.amount.toFixed(2)   }}</div>
          </div>
          <div class="d-flex px-3 mb-4 justify-content-between w-100"> 
            <div>Tipo de cambio:</div>
            <div> {{ newOperation.operationType == 'buy' ? 'USD > SOL' : 'SOL > USD' }}</div>  
          </div>
          <div class="d-flex px-3 mb-4 justify-content-between w-100"> 
            <div>Tasa:</div>
            <div>{{ newOperation.exchange_rate.toFixed(3)  }}</div>
          </div>
        </div>
      </div> -->
      <!-- / calculadora -->
    </div>
    <div class="modal fade modal-lg" id="alertAccounts" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-5">
          <div class="modal-body">
            <div class="text-center mb-4">
              <h5 class="mb-2">{{haveAccountMsg}}</h5>
            </div>
            <div class="d-flex justify-content-center">
              <router-link to="/bank-accounts" v-slot="{ href, navigate, isActive, isExactActive }">

                <a :href="href" class="justify-content-center" @click=" hideModal()" >
                  <button class="btn btn-primary justify-content-center">
                    <i class="fa-solid fa-wallet "></i>
                    <div>&nbsp; &nbsp;Mis cuentas</div>
                  </button>
                </a>  
              </router-link>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade modal-lg" id="declaracion" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-5">
          <div class="modal-body" id="declaracionj">
            <div class="text-center mb-4">
              <h5 class="mb-2">
                El monto de tu operación es igual o mayor a 10,000 USD o
                su equivalente en soles, por ende debes aceptar la siguiente declaración jurada
              </h5>
              <h6>Declaración Jurada</h6>
            </div>
            <div class="d-flex justify-content-center">
              <div class="form-check form-group valid-item">
                <input class="form-check-input" type="checkbox"  name="agree"  ref="agree"/>
                <label class="form-check-label" for="agree">
                <small> Acepto la declaración jurada
                </small> 
                </label>
              </div>
            </div>
            <div class="col-12 d-flex justify-content-between mt-3 ">
              <button class="btn btn-label-secondary btn-backprev align-items-baseline " @click="openModal.hide()">
              <i class="ti ti-arrow-left me-sm-1"></i>
              <span class="align-middle d-sm-inline-block d-none">Volver</span>
              </button>
              <button class="btn btn-primary btn-next align-items-baseline" id="declaracion-button" onsubmit="false" disabled @click="openModal.hide()">
                <i class="ti ti-check me-sm-1"></i>
                <span class="align-middle d-sm-inline-block d-none">Confirmar!</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade modal" id="confirm" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-3">
          <div class="modal-body">
            <div class="content-header mb-2">
              <h4 class="fw-bold pt-3 mb-2 text-center"><span class="text-muted fw-light">Estas seguro que los</span> datos son correctos</h4>
              <h6 class="card-title text-center mb-1 " v-if="newOperation.operation_number != '' ">
                Número de operacion: #<strong>{{newOperation.operation_number}}</strong>
              </h6>
            
            </div>
            
            <div class="d-flex justify-content-between px-2 align-items-center amount_section mt-3 mb-2">
                <div >
                  <h4 class="mb-0 text-primary">
                    {{ newOperation.operationType == 'buy' ? 'S/.' : '$' }} {{ $func.numberFormat(newOperation.amount) }}
                  </h4>
                </div>
                <div>
                  <h6 class="mt-2 mb-0 h8 fw-light"><strong class="fw-bold text-primary">Recibes:</strong> {{newOperation.operationType == 'buy' ? '$' : 'S/.'}} {{$func.numberFormat(newOperation.change_amount)}} </h6>
                  <h6 class="mb-2 h8 fw-light"><strong class="fw-bold text-primary">Tasa:</strong> {{newOperation.exchange_rate.toFixed(3)}} </h6>
                </div>
            </div>
            <div class="d-flex flex-column px-2 mb-3">
              <div class="d-flex flex-column justify-content-center align-items-center" v-if="Object.keys(selectedBankAccountSend).length > 0 && Object.keys(selectedBankAccountReceive).length > 0 && Object.keys(selectedBankAccountTransfer).length > 0">
                <!-- <div class="col-11 px-0 py-1 my-1 card-bank-content "> -->
                <div class="col-12 px-0 py-1 my-1 ">
                  <div class="col-12  d-flex flex-column  align-items-center card-transfer2" style="">
                    <div class=""><h6 class="mb-0  mt-2">Cuenta desde la que realizaste la transacción</h6></div>
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
                    <div class=""><h6 class="mb-0  mt-2">Cuenta a la que realizaste la transacción</h6></div>
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
                <div class="col-12 d-flex justify-content-between mt-3">
                  <button class="btn btn-label-secondary  align-items-baseline" @click="finish()"> 
                  <i class="ti ti-arrow-left me-sm-1"></i>
                  <span class="align-middle d-sm-inline-block d-none">Volver</span>
                  </button>
                  <button class="align-items-baseline btn btn-primary btn-submit" id="sendForm" onsubmit="false" @click="storeOperation()">
                    <i class="ti ti-check me-sm-1"></i>
                    <span class="align-middle d-sm-inline-block d-none">Aceptar</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    
</template>
<script>
  import { OPERATIONS_STORE } from "@/core/services/store/operation.module";
  import { BANK_ACCOUNTS_GET_ALL, BANK_ACCOUNTS_GET_ALL_TO_TRANSFER } from "@/core/services/store/bank_account.module";
  import { EXCHANGE_RATES_GET_LAST } from "@/core/services/store/exchange_rate.module";
  import { FUND_ORIGINS_GET_ALL } from "@/core/services/store/fund_origin.module";
  import accountsTag from "@/view/pages/operations/client/accountsTag.vue";
  import { bootstrap } from "@/concept/bootstrap";
  export default {
  name:'formOperation',
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
      vaucher: null,
      minutes: 15,
			seconds: '00',
      redirectMessageSeconds: 3,
			redirectMessage: '',
      timerEl:"",
    }
  },
  props:['exchange', 'count'],
  mounted() {
    
    
  },
  components: {
		accountsTag:accountsTag 
	},
  watch: {
    'count': {
        handler: function (after, before) {
          this.Isdeclarable();
        },
        deep: true
    },
    'exchange.operationType': {
          handler: function (after, before) {
            this.loadSend = false
            this.newOperation = this.exchange;
            // console.log(this.newOperation)
            this.getBankAccounts();
			      this.getBankAccountsToTransfer();
            this.getFundOrigins();
          },
          deep: true
      }
	},
  methods: {
    storeOperation () {
      let amount = parseFloat(this.newOperation.amount.replace(/,/g, ''));
      let change_amount = parseFloat(this.newOperation.change_amount.replace(/,/g, ''));
      document.getElementById('sendForm').disabled = true
      document.getElementById('step5').disabled = true
      document.getElementById('sendForm').textContent= 'Cargando ...'


      const data = new FormData();
      data.append('bank_account_send_id', this.newOperation.bank_account_send_id);
      data.append('bank_account_receive_id', this.newOperation.bank_account_receive_id);
      data.append('amount', amount);
      data.append('change_amount', change_amount);
      data.append('exchange_rate', this.newOperation.exchange_rate);
      data.append('bank_account_transfer_id', this.newOperation.bank_account_transfer_id);
      data.append('operation_number', this.newOperation.operation_number);
      data.append('fund_origin_id', this.selectedFundOrigin.id);
      data.append('vaucher', this.vaucher);

      if (this.selectedFundOrigin.code == 'otros') {
          data.append('other_fund_origin', this.selectedFundOrigin.other_fund_origin);
      }
      this.$store
          .dispatch(OPERATIONS_STORE, data)
          .then((data) => {
              if (data.code != 201){
                  this.showMainAlert('danger', 'Error desconocido al guardar la operación.');
                  return;
              }
              this.$emit('hiddenModal')
              this.showMainAlert('success', 'Operación realizada con éxito!');
              this.redirectTimer(' . Redireccionando a Mis Operaciones en: ', '/my-operations');
          })
          .catch((err) => {
              this.showMainAlert('danger', err);
              document.getElementById('sendForm').disabled = false
              document.getElementById('step5').disabled = false
              document.getElementById('sendForm').textContent= 'Aceptar'
          });
		},
    copyOnClipboard(event) {
      let elm = event.srcElement
      let value = this.$refs[event.srcElement.dataset.key]
      let allClipboarReset = document.querySelectorAll('.tooltip-text')
      allClipboarReset.forEach((clip) => {
        return clip.textContent = 'Copiar'  
      }) 
      elm.nextElementSibling.textContent = 'Copiado!';

      navigator.clipboard.writeText(value.value)
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
    showModal(id) {
      this.openModal = new bootstrap.Modal(document.getElementById(id), {
        keyboard: false,
        backdrop:'static'
      })
      this.openModal.show()
    },
    hideModal(){
      this.openModal.hide();
      setTimeout(() => {
        let trashElement = document.querySelectorAll('.modal-backdrop');
        trashElement.forEach((item)=>{
          document.querySelector('body').removeChild(item);
        })
        this.$router.push('/bank-accounts')
      }, 200);
      
     
    },
    Isdeclarable() {
      let amount = parseFloat(this.newOperation.amount.replace(/,/g, ''));
      let change_amount = parseFloat(this.newOperation.change_amount.replace(/,/g, ''));
      if(amount < this.exchange.limit[0] && change_amount < this.exchange.limit[1] ){
      } else {
        this.showModal('declaracion')
      }
       
    },
    validateAccounts(){
      if (this.filteredBankAccountsByCoinType.length > 0 && this.allBankAccountsByCoin.length > 0) {
        this.Isdeclarable();
      }else{
        this.showModal('alertAccounts')
        if (this.filteredBankAccountsByCoinType.length == 0 && this.allBankAccountsByCoin.length == 0) {
          this.haveAccountMsg ='No tienes cuentas bancarias agregadas, dirigete a la sección Mis cuentas y agrega tus cuentas';
        }else if(this.filteredBankAccountsByCoinType.length == 0 && this.allBankAccountsByCoin.length > 0){
          this.haveAccountMsg = 'No tiene cuenta bancaria registrada con la modena de destino, dirigete a la sección Mis cuentas y agrega tus cuentas bancarias';
        }else if(this.filteredBankAccountsByCoinType.length > 0 && this.allBankAccountsByCoin.length == 0){
          this.haveAccountMsg = 'No tiene cuenta bancaria registrada con la modena de origen, dirigete a la sección Mis cuentas y agrega tus cuentas bancarias';
        }
      }
    },
    getBankAccounts () {
        this.$store
            .dispatch(BANK_ACCOUNTS_GET_ALL)
            .then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas bancarias.')
                    return;
                }

              this.allBankAccounts = data.data;

              this.allBankAccounts = this.allBankAccounts.filter(bankAccount => bankAccount.bank.status == 1) 

              this.allBankAccounts.map((item) => {
                return item.value = item.id
              })
              this.allBankAccounts.map((item) => {
                return item.nameBank = item.bank.name
              })
              
              this.filterBankAccountsRecive()
            })
            .catch((err) => {
                this.showMainAlert('danger', err)
            });
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

      if( this.newOperation.bank_account_send_id > -1 && this.newOperation.bank_account_receive_id > -1 ){
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
    getBankAccountsToTransfer() {
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
                setTimeout(()=>{
                  this.loadSend = true
                },100)
            })
            .catch((err) => {
                this.showMainAlert('danger', err)
            });
    },
    getFundOrigins() {
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
        
      

      this.validateAccounts()
    },
    finish(){
      this.$emit('hiddenModal')
    },
    setReceiveAccount () {
        this.selectedBankAccountReceive = this.filteredBankAccountsByCoinType.find(bankAccount => bankAccount.id == this.newOperation.bank_account_receive_id);

        this.coinTypeReceiveData = this.selectedBankAccountReceive.coin_type;
    },

    setTransferAccount () {
        this.selectedBankAccountTransfer = this.allBankAccountsToTransfer.find(bankAccount => bankAccount.id == this.newOperation.bank_account_transfer_id);
    },
    setFundOrigin () {
    },
    timer() {
					let _this = this;

					this.timerEl = setInterval(function () {
						if ((_this.seconds === '00' || _this.seconds === 0) && _this.minutes !== 0) {
							_this.seconds = 59
							_this.minutes -= 1
						} else if (_this.minutes === 0 && _this.seconds === 0) {
							_this.seconds = 0
							clearInterval(this.timerEl)
							_this.showMainAlert('danger', 'Se agotó el tiempo!');
							
							_this.redirectTimer(' . Recargando la página en: ', '');
							
						} else {
							_this.seconds -= 1
						}
					}, 1000);
		},
    redirectTimer (redirectMessage, url) {
						this.redirectMessage = redirectMessage;
						let _this = this;

						let redirectTime = setInterval(function () {
								if (_this.redirectMessageSeconds === 0) {
										_this.redirectMessageSeconds = 0;
										clearInterval(redirectTime);
										if (url != '') {
												_this.$router.push(url);
										}
										else {
												_this.$router.go(); // Reload the page
										}
								} else {
										_this.redirectMessageSeconds =_this.redirectMessageSeconds  - 1;
								}
						}, 1000);
		},
    quitTimer(){
      this.minutes = 15;
      this.seconds = 0;
      clearInterval(this.timerEl)
    },
    resetData () {
        this.newOperation.bank_account_receive_id = -1;
        this.newOperation.exchange_rate = null;
        this.filteredBankAccountsByCoinType = [];
        this.filteredBankAccountsToTransferByCoinType = [];
        this.coinTypeSendData = null;
        this.amountIsDisabled = true;
        this.coinTypeReceiveAbbreviation = '';
        this.coinTypeSendAbbreviation = '';
        this.newOperation.change_amount = null;
        this.newOperation.amount = null;
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
    this.getBankAccounts();
    this.getBankAccountsToTransfer();
    this.getFundOrigins();
    this.newOperation = this.exchange;
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