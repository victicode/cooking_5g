<template>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header pt-4 pb-2 min-vh-0">
                    <div class="row w-100">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <h3 class="pt-4"><strong>Nueva Operación</strong></h3>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="mainAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}{{ redirectMessage == '' ? '' : redirectMessage + redirectMessageSeconds + ' segundos.' }}</b-alert>
                    </div>
                    <hr>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10 col-md-12 col-sm-12">
                            <div class="wizard wizard-4" id="wizard_new_operation" data-wizard-state="step-first" data-wizard-clickable="true">
                                <div class="wizard-nav">
                                    <div class="wizard-steps">
                                        <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                            <div class="wizard-wrapper">
                                                <div class="wizard-number">
                                                    1
                                                </div>
                                                <div class="wizard-label">
                                                    <div class="wizard-title">
                                                        Tipo de Cambio
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wizard-step" data-wizard-type="step">
                                            <div class="wizard-wrapper">
                                                <div class="wizard-number">
                                                    2
                                                </div>
                                                <div class="wizard-label">
                                                    <div class="wizard-title">
                                                        Transfierenos
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wizard-step" data-wizard-type="step">
                                            <div class="wizard-wrapper">
                                                <div class="wizard-number">
                                                    3
                                                </div>
                                                <div class="wizard-label">
                                                    <div class="wizard-title">
                                                        Datos adicionales
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wizard-step" data-wizard-type="step">
                                            <div class="wizard-wrapper">
                                                <div class="wizard-number">
                                                    4
                                                </div>
                                                <div class="wizard-label">
                                                    <div class="wizard-title">
                                                        Completado
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-custom card-shadowless rounded-top-0">
                                    <div class="card-body p-0">
                                        <div class="row justify-content-center pb-4 px-8">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <form class="form mt-0 mt-lg-10" id="kt_form">
                                                    <!--begin: Wizard Step 1-->
                                                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>¿Desde qué cuenta nos envias tu dinero?</strong></label>
                                                                <select class="form-control" v-model="newOperation.bank_account_send_id" style="width: 100%!important" @change="filterBankAccounts()">
                                                                    <option value="-1">Seleccione la cuenta de envío</option>
                                                                    <option v-for="(bankAccount, index) in allBankAccounts" v-bind:value="bankAccount.id" :key="`bank_account_send_${index}`">
                                                                        <!-- <img :src="`../${bankAccount.bank.logo}`" width="50" height="50" /> -->
                                                                        {{ bankAccount.bank.name }} | {{ bankAccount.account_number }} | {{ bankAccount.account_number_cci }} | {{ bankAccount.alias }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>¿A qué cuenta depositaremos el dinero?</strong></label>
                                                                <select class="form-control" v-model="newOperation.bank_account_receive_id" style="width: 100%!important" @change="setReceiveAccount()">
                                                                    <option value="-1">Seleccione la cuenta de depósito</option>
                                                                    <option v-for="(bankAccount, index) in filteredBankAccountsByCoinType" v-bind:value="bankAccount.id" :key="`bank_account_receive_${index}`">
                                                                        <!-- <img :src="`../${bankAccount.bank.logo}`" width="50" height="50" /> -->
                                                                        {{ bankAccount.bank.name }} | {{ bankAccount.account_number }} | {{ bankAccount.account_number_cci }} | {{ bankAccount.alias }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>Monto a transferir</strong></label>
                                                                <input type="text" class="form-control" v-model="newOperation.amount" :disabled="amountIsDisabled" placeholder="Ingrese el monto a transferir" @keypress="checkNumber($event)" @keyup="amountKeyUp()">
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mt-2">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 px-2 py-10 rounded-xl text-center bg-light">
                                                                <span class="d-block my-2">
                                                                    <i class="menu-icon fas fa-calculator"></i>
                                                                </span>
                                                                <span class="text-dark font-weight-bold font-size-h6">Recibirás:</span>
                                                                <span class="text-dark font-weight-bold font-size-h6"><strong>{{ coinTypeReceiveAbbreviation }} {{ newOperation.change_amount }}</strong></span>
                                                                <br>
                                                                <span class="text-dark font-weight-bold font-size-h6">Tipo de cambio usado:</span>
                                                                <span class="text-dark font-weight-bold font-size-h6"><strong>{{ newOperation.exchange_rate }}</strong></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end: Wizard Step 1-->

                                                    <!--begin: Wizard Step 2-->
                                                    <div class="pb-5" data-wizard-type="step-content">
                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>A qué cuenta nos transferiras el dinero?</strong></label>
                                                                <select class="form-control" v-model="newOperation.bank_account_transfer_id" style="width: 100%!important" @change="setTransferAccount()">
                                                                    <option value="-1">Seleccione la cuenta de envío</option>
                                                                    <option v-for="(bankAccount, index) in filteredBankAccountsToTransferByCoinType" v-bind:value="bankAccount.id" :key="`bank_account_transfer_${index}`">
                                                                        <!-- <img :src="`../${bankAccount.bank.logo}`" width="50" height="50" /> -->
                                                                        {{ bankAccount.bank.name }} | {{ bankAccount.account_number }} | {{ bankAccount.account_number_cci }} | {{ bankAccount.alias }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mt-2">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 px-2 py-10 rounded-xl text-center bg-light">
                                                                <span class="text-dark font-weight-bold font-size-h6">Transfierenos a nuestra cuenta:</span>
                                                                <span class="text-dark font-weight-bold font-size-h6"><strong>{{ coinTypeSendAbbreviation }} {{ newOperation.amount }}</strong></span>
                                                                <br>
                                                                <span class="text-dark font-weight-bold font-size-h6">Recibirás en tu cuenta:</span>
                                                                <span class="text-dark font-weight-bold font-size-h6"><strong>{{ coinTypeReceiveAbbreviation }} {{ newOperation.exchange_rate }}</strong></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end: Wizard Step 2-->

                                                    <!--begin: Wizard Step 3-->
                                                    <div class="pb-5" data-wizard-type="step-content">
                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>Origen de fondos</strong></label>
                                                                <select class="form-control form-control-lg" v-model="newOperation.fund_origin_code" style="width: 100%!important" @change="setFundOrigin()">
                                                                    <option value="-1">Selecciona el origen de fondos</option>
                                                                    <option v-for="fundOrigin in fundOrigins" v-bind:value="fundOrigin.code" :key="`fund_origin_${fundOrigin.id}`">
                                                                        {{ fundOrigin.name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row justify-content-center" v-if="selectedFundOrigin.code == 'otros'">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>Otro origen de fondos</strong></label>
                                                                <input type="text" class="form-control" v-model="selectedFundOrigin.other_fund_origin" placeholder="Ingrese otro origen de fondos">
                                                            </div>
                                                        </div>

                                                        <div class="row justify-content-center">
                                                            <div class="col-lg-8 col-mg-8 col-sm-12 mb-2">
                                                                <label><strong>Número de operación</strong></label>
                                                                <input type="text" class="form-control" v-model="newOperation.operation_number" placeholder="Ingrese el número de operación">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end: Wizard Step 3-->

                                                    <!--begin: Wizard Step 4-->
                                                    <div class="pb-5" data-wizard-type="step-content">
                                                        <div v-if="Object.keys(selectedBankAccountSend).length > 0 && Object.keys(selectedBankAccountReceive).length && Object.keys(selectedBankAccountTransfer).length" class="row justify-content-center py-4 px-4">
                                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                                <div class="d-flex justify-content-between pb-5 flex-column text-center">
                                                                    <h1 class="display-4 font-weight-boldest">Detalles de la Operación</h1>
                                                                </div>
                                                                <div class="border-bottom w-100"></div>
                                                                <div class="d-flex justify-content-between pt-6">
                                                                    <div class="d-flex flex-column flex-root text-center">
                                                                        <span class="font-weight-bolder mb-2 h5">Cuenta de envío</span>
                                                                        <span class="opacity-70">
                                                                            <img :src="`../${selectedBankAccountSend.bank.logo}`" width="75" height="75" />
                                                                            <br>
                                                                            {{ selectedBankAccountSend.account_number }} | {{ selectedBankAccountSend.account_number_cci }} | {{ selectedBankAccountSend.alias }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex flex-column flex-root text-center">
                                                                        <span class="font-weight-bolder mb-2 h5">Cuenta de depósito</span>
                                                                        <span class="opacity-70">
                                                                            <img :src="`../${selectedBankAccountReceive.bank.logo}`" width="75" height="75" />
                                                                            <br>
                                                                            {{ selectedBankAccountReceive.account_number }} | {{ selectedBankAccountReceive.account_number_cci }} | {{ selectedBankAccountReceive.alias }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex flex-column flex-root text-center">
                                                                        <span class="font-weight-bolder mb-2 h5">Cuenta donde transfiere</span>
                                                                        <span class="opacity-70">
                                                                            <img :src="`../${selectedBankAccountTransfer.bank.logo}`" width="75" height="75" />
                                                                            <br>
                                                                            {{ selectedBankAccountTransfer.account_number }} | {{ selectedBankAccountTransfer.account_number_cci }} | {{ selectedBankAccountTransfer.alias }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between pt-6">
                                                                    <div class="d-flex flex-column flex-root text-center">
                                                                        <span class="font-weight-bolder mb-2 h5">Monto de envío</span>
                                                                        <span class="opacity-70">
                                                                            {{ newOperation.amount }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex flex-column flex-root text-center">
                                                                        <span class="font-weight-bolder mb-2 h5">Monto a recibir</span>
                                                                        <span class="opacity-70">
                                                                            {{ newOperation.change_amount }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex flex-column flex-root text-center">
                                                                        <span class="font-weight-bolder mb-2 h5">Tipo de cambio</span>
                                                                        <span class="opacity-70">
                                                                            {{ newOperation.exchange_rate }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end: Wizard Step 4-->

                                                    <div class="d-flex justify-content-between border-top pt-10">
                                                        <div class="mr-2">
                                                            <button class="btn btn-light-primary font-weight-bold px-9 py-4" data-wizard-type="action-prev" :disabled="wizardActionsDisabled">
                                                                Regresar
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <button class="btn btn-success font-weight-bold px-9 py-4" data-wizard-type="action-submit" :disabled="wizardActionsDisabled">
                                                                Confirmar
                                                            </button>
                                                            <button class="btn btn-primary font-weight-bold px-9 py-4" data-wizard-type="action-next" :disabled="wizardActionsDisabled">
                                                                Siguiente
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 my-auto">
                            <div class="row m-2">
                                <div class="col px-2 py-10 rounded-xl text-center bg-light">
                                    <span class="d-block my-2">
                                        <i class="far fa-clock text-dark"></i>
                                    </span>
                                    <span class="text-dark font-weight-bold font-size-h6">Se actualizara el tipo de cambio en:</span>
                                    <span class="text-dark font-weight-bold font-size-h6">{{ minutes.toString().length == 1 ? '0' + minutes : minutes }}:{{ seconds.toString().length == 1 ? '0' + seconds : seconds }}</span>
                                </div>
                            </div>
                            <div class="row m-2">
                                <div class="col px-2 py-10 rounded-xl text-center" :style="{ 'background-color': $mainColor }">
                                    <span class="d-block my-2">
                                        <i class="fas fa-arrow-circle-up text-white"></i>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h6">Compramos a</span>
                                    <span class="text-white font-weight-bold font-size-h6">{{ $func.numberFormat(purchase.toFixed(3)) }}</span>
                                </div>
                            </div>
                            <div class="row m-2">
                                <div class="col px-2 py-10 rounded-xl text-center" :style="{ 'background-color': $secondaryColor }">
                                    <span class="d-block my-2">
                                        <i class="fas fa-arrow-circle-down text-white"></i>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h6">Vendemos a</span>
                                    <span class="text-white font-weight-bold font-size-h6">{{ $func.numberFormat(sale.toFixed(3)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    @import "@/assets/sass/pages/wizard/wizard-4.scss";
</style>

<script>
    import { OPERATIONS_STORE } from "@/core/services/store/operation.module";
    import { BANK_ACCOUNTS_GET_ALL, BANK_ACCOUNTS_GET_ALL_TO_TRANSFER } from "@/core/services/store/bank_account.module";
    import { EXCHANGE_RATES_GET_LAST } from "@/core/services/store/exchange_rate.module";
    import { FUND_ORIGINS_GET_ALL } from "@/core/services/store/fund_origin.module";

    import KTWizard from "@/assets/js/components/wizard";

    export default {
        data() {
            return {
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: "",

                newOperation: {
                    bank_account_send_id: -1,
                    bank_account_receive_id: -1,
                    amount: null,
                    change_amount: null,
                    exchange_rate: null,
                    bank_account_transfer_id: -1,
                    fund_origin_code: -1,
                    operation_number: null,
                },

                allBankAccounts: [],
                filteredBankAccountsByCoinType: [],
                allBankAccountsToTransfer: [],
                filteredBankAccountsToTransferByCoinType: [],
                fundOrigins: [],

                selectedFundOrigin: {},
                selectedBankAccountSend: {},
                selectedBankAccountReceive: {},
                selectedBankAccountTransfer: {},

                // Exchange rate & timer
                sale: 0,
                purchase: 0,
                minutes: 5,
                seconds: 0,
                redirectMessageSeconds: 5,
                redirectMessage: '',

                coinTypeSendAbbreviation: '',
                coinTypeReceiveAbbreviation: '',

                coinTypeSendData: null,
                coinTypeReceiveData: null,
                amountIsDisabled: true,
                wizardActionsDisabled: false,
            }
        },
        watch: {
            'newOperation': {
                handler: function (after, before) {
                    if (this.mainAlert) {
                        this.hideMainAlert()
                    }
                },
                deep: true
            }
        },
        mounted() {
            // Initialize form wizard
            const wizard = new KTWizard("wizard_new_operation", {
                startStep: 1, // initial active step number
                clickableSteps: false // allow step clicking
            });

            this.$nextTick().then((thisAux) => {
                // Change event
                wizard.on("change", function(wizardObj) {
                    if (wizardObj.getStep() > wizard.getNewStep()) { // If press prev button, dont validate form data
                        return;
                    }

                    switch (wizardObj.getStep()) {
                        case 1:
                            if (thisAux.newOperation.bank_account_send_id == -1 || thisAux.newOperation.bank_account_receive_id == -1 
                            || (thisAux.newOperation.amount == null || thisAux.newOperation.amount == '')
                            || (thisAux.newOperation.change_amount == null || thisAux.newOperation.change_amount == '')
                            || (thisAux.newOperation.exchange_rate == null || thisAux.newOperation.exchange_rate == '')
                            ) {
                                thisAux.showMainAlert('danger', 'Debe completar todos los datos solicitados antes de continuar con la siguiente etapa.')
                                wizardObj.stop();
                                return;
                            }
                            break;
                        case 2:
                            if (thisAux.newOperation.bank_account_transfer_id == -1) {
                                thisAux.showMainAlert('danger', 'Debe completar todos los datos solicitados antes de continuar con la siguiente etapa.')
                                wizardObj.stop();
                                return;
                            }
                            break;
                        case 3:
                            if (thisAux.newOperation.fund_origin_code == -1) {
                                thisAux.showMainAlert('danger', 'Debe completar el origen de los fondos antes de continuar con la siguiente etapa.')
                                wizardObj.stop();
                                return;
                            }

                            if (thisAux.newOperation.fund_origin_code == "otros") {
                                if (thisAux.selectedFundOrigin.other_fund_origin == null || thisAux.selectedFundOrigin.other_fund_origin == "") {
                                    thisAux.showMainAlert('danger', 'Debe completar el origen de los fondos antes de continuar con la siguiente etapa.')
                                    wizardObj.stop();
                                    return;
                                }
                            }
                            break;
                        default:
                            break;
                    }
                });

                wizard.on("submit", function(wizardObj) {
                    if (thisAux.newOperation.bank_account_send_id == -1 || thisAux.newOperation.bank_account_receive_id == -1 
                    || (thisAux.newOperation.amount == null || thisAux.newOperation.amount == '')
                    || (thisAux.newOperation.change_amount == null || thisAux.newOperation.change_amount == '')
                    || (thisAux.newOperation.exchange_rate == null || thisAux.newOperation.exchange_rate == '')
                    || thisAux.newOperation.bank_account_transfer_id == -1  || thisAux.newOperation.fund_origin_code == -1
                    ) {
                        thisAux.showMainAlert('danger', 'Debe completar todos los datos solicitados antes de confirmar la operación.')
                        wizardObj.stop();
                        return;
                    }

                    thisAux.storeOperation()

                    wizardObj.stop();
                    return;
                });
            });

            this.timer()
        },
        methods: {
            storeOperation () {
                this.newOperation.amount = parseFloat(this.newOperation.amount.replace(/,/g, ''));
                this.newOperation.change_amount = parseFloat(this.newOperation.change_amount.replace(/,/g, ''));
                this.newOperation.exchange_rate = parseFloat(this.newOperation.exchange_rate.replace(/,/g, ''));

                const data = new FormData();
                data.append('bank_account_send_id', this.newOperation.bank_account_send_id);
                data.append('bank_account_receive_id', this.newOperation.bank_account_receive_id);
                data.append('amount', this.newOperation.amount);
                data.append('change_amount', this.newOperation.change_amount);
                data.append('exchange_rate', this.newOperation.exchange_rate);
                data.append('bank_account_transfer_id', this.newOperation.bank_account_transfer_id);
                data.append('operation_number', this.newOperation.operation_number);
                data.append('fund_origin_id', this.selectedFundOrigin.id);
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

                        this.showMainAlert('success', 'Operación realizada con éxito!');
                        this.redirectTimer(' . Redireccionando a Mis Operaciones en: ', '/my-operations');
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err);
                    });
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
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
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
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getLastExchangeRate () {
                this.$store
                    .dispatch(EXCHANGE_RATES_GET_LAST)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        if (data.data != null) {
                            this.sale = data.data.sale;
                            this.purchase = data.data.purchase;
                        }

                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
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

            filterBankAccounts () {
                this.resetData();
                if (this.newOperation.bank_account_send_id == -1) {
                    return;
                }

                this.selectedBankAccountSend = this.allBankAccounts.find(bankAccount => bankAccount.id == this.newOperation.bank_account_send_id);

                // Filter bank accounts to select by coin type
                this.filteredBankAccountsByCoinType = this.allBankAccounts.filter(bankAccount => bankAccount.coin_type_id != this.selectedBankAccountSend.coin_type_id);
                this.filteredBankAccountsToTransferByCoinType = this.allBankAccountsToTransfer.filter(bankAccount => bankAccount.coin_type_id == this.selectedBankAccountSend.coin_type_id);

                this.coinTypeSendData = this.selectedBankAccountSend.coin_type;
                this.coinTypeSendAbbreviation = this.coinTypeSendData.abbreviation;
                this.newOperation.exchange_rate = this.coinTypeSendData.code == 'sol' ? this.sale.toFixed(3) : this.purchase.toFixed(3);

                this.amountIsDisabled = false;
            },

            setReceiveAccount () {
                this.selectedBankAccountReceive = this.filteredBankAccountsByCoinType.find(bankAccount => bankAccount.id == this.newOperation.bank_account_receive_id);

                this.coinTypeReceiveData = this.selectedBankAccountReceive.coin_type;
            },

            setTransferAccount () {
                this.selectedBankAccountTransfer = this.allBankAccountsToTransfer.find(bankAccount => bankAccount.id == this.newOperation.bank_account_transfer_id);
            },

            setFundOrigin () {
                this.selectedFundOrigin = this.fundOrigins.find(fundOrigin => fundOrigin.code == this.newOperation.fund_origin_code);
            },

            checkNumber ($event) {
                // , = 44
                // . = 46

                let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

                // only allow number and one dot
                if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.newOperation.amount.indexOf('.') != -1)) { // 46 is dot
                    $event.preventDefault();
                }

                // restrict to 2 decimal places
                if(this.newOperation.amount != null && this.newOperation.amount.indexOf(".")>-1 && (this.newOperation.amount.split('.')[1].length > 1)){
                    $event.preventDefault();
                }
            },

            amountKeyUp () {
                let newAmount = parseFloat(this.newOperation.amount.replace(/,/g, ''));
                this.newOperation.amount = this.$func.numberFormat(this.newOperation.amount);

                this.coinTypeReceiveAbbreviation = this.coinTypeReceiveData.abbreviation;
                if (this.coinTypeSendData.code == 'sol') { // Send 'sol' receive 'dolar'
                    this.newOperation.change_amount = this.$func.numberFormat((newAmount / this.sale).toFixed(2));
                }
                else { // Send 'dolar' receive 'sol'
                    this.newOperation.change_amount = this.$func.numberFormat((newAmount * this.sale).toFixed(2));
                }
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

            timer () {
                let _this = this;

                let time = setInterval(function () {
                    if (_this.seconds === 0 && _this.minutes !== 0) {
                        _this.seconds = 59
                        _this.minutes -= 1
                    } else if (_this.minutes === 0 && _this.seconds === 0) {
                        _this.seconds = 0
                        clearInterval(time)

                        _this.showMainAlert('danger', 'Se agotó el tiempo!');
                        _this.redirectTimer(' . Recargando la página en: ', '');
                    } else {
                        _this.seconds -= 1
                    }
                }, 1000);
            },
            resetTimer() {
                this.seconds = 0
                this.minutes = 5
            },
            redirectTimer (redirectMessage, url) {
                this.redirectMessage = redirectMessage;
                this.wizardActionsDisabled = true;
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
                        _this.redirectMessageSeconds -= 1;
                    }
                }, 1000);
            },
        },
        computed: {

        },
        created() {
            this.getBankAccounts();
            this.getBankAccountsToTransfer();
            this.getLastExchangeRate();
            this.getFundOrigins();
        },
    };
</script>