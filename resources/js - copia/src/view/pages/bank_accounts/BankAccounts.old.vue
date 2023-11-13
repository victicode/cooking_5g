<template>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header pt-4 pb-2 min-vh-0">
                    <div class="row w-100">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <h3 class="pt-4"><strong>Cuentas Bancarias</strong></h3>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <div class="row justify-content-end">
                                <button class="btn btn-primary font-weight-bold ml-2" v-b-modal.modal-new-bank-account>Nueva cuenta bancaria</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="mainAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
                    </div>
                    <hr>
                </div>

                <!-- If the user logged is an admin -->
                <div v-if="isAdmin" class="card-body">
                    <div class="row m-2">
                        <div class="col-lg-2 col-md-2 col-sm-12 mr-2">
                            <div class="form-group">
                                <label>Estado:</label>
                                <select class="form-control" v-model="bankAccountStatus">
                                    <option value="all">Todas</option>
                                    <option value="not_deleted">Activas</option>
                                    <option value="deleted">Eliminadas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                            <div class="form-group">
                                <label>Banco:</label>
                                <select class="form-control" v-model="bankIdSearch">
                                    <option value="all">Todos</option>
                                    <option v-for="(bank, index) in banks" v-bind:value="bank.id" :key="`bank_filter_${index}`">{{ bank.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                            <div class="form-group">
                                <label>Tipo de cuenta:</label>
                                <select class="form-control" v-model="bankAccountTypeId">
                                    <option value="all">Todos</option>
                                    <option v-for="(bankAccountType, index) in bankAccountTypes" v-bind:value="bankAccountType.id" :key="`bank_account_type_filter_${index}`">{{ bankAccountType.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Tipo de moneda:</label>
                                <select class="form-control" v-model="coinTypeId">
                                    <option value="all">Todos</option>
                                    <option v-for="(coinType, index) in coinTypes" v-bind:value="coinType.id" :key="`coin_type_filter_${index}`">{{ coinType.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mt-2 mr-2">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." v-model="bankAccountSearch">
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 mt-2">
                            <a class="btn btn-light-primary px-3 font-weight-bold cursor-pointer mr-2" @click="filterBankAccounts()">Buscar</a>
                            <a class="btn btn-light-primary px-3 font-weight-bold cursor-pointer" @click="resetFilters()">Resetear</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <b-table
                                id="my-table"
                                :fields="fields"
                                :items="bankAccounts"
                                :per-page="perPage"
                                :current-page="currentPage"
                                head-variant="light"
                                table-variant="light"
                                hover
                            >
                                <template #cell(bank)="data">
                                    {{ data.item.name_lr }} {{ data.item.bank.name }}
                                </template>
                                <template #cell(bank_account_type)="data">
                                    {{ data.item.bank_account_type.name }}
                                </template>
                                <template #cell(coin_type)="data">
                                    {{ data.item.coin_type.name }}
                                </template>
                                <template #cell(owner_document_type)="data">
                                    {{ data.item.owner_document_type.name }}
                                </template>
                                <template #cell(own_account)="data">
                                    <span class="label label-lg label-light-success label-inline" v-if="data.item.own_account">SI</span>
                                    <span class="label label-lg label-light-danger label-inline" v-else>NO</span>
                                </template>
                                <template #cell(authorized_deposit)="data">
                                    <div v-if="data.item.own_account == 0">
                                        <span class="label label-lg label-light-success label-inline" v-if="data.item.authorized_deposit">SI</span>
                                        <span class="label label-lg label-light-danger label-inline" v-else>NO</span>
                                    </div>
                                </template>
                                <template #cell(deleted_at)="data">
                                    <span class="label label-lg label-light-danger label-inline" v-if="data.item.deleted_at">Eliminada</span>
                                    <span class="label label-lg label-light-success label-inline" v-else>Activa</span>
                                </template>
                                <template #cell(actions)="data">
                                    <a v-if="data.item.deleted_at == null" class="m-2" v-b-modal.modal-edit-bank-account @click="setSelectedBankAccount(data.item.id)" title="Editar cuenta bancaria"><i class="far fa-edit"></i></a>
                                    <a v-if="data.item.deleted_at == null" class="m-2 cursor-pointer" @click="deleteBankAccount(data.item.id)" title="Eliminar cuenta bancaria"><i class="fas fa-trash"></i></a>
                                    <a v-if="data.item.deleted_at != null" class="m-2 cursor-pointer" @click="restoreBankAccount(data.item.id)" title="Restaurar cuenta bancaria"><i class="fas fa-trash-restore-alt"></i></a>
                                </template>
                            </b-table>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <b-pagination
                                v-model="currentPage"
                                :total-rows="rows"
                                :per-page="perPage"
                                aria-controls="my-table"
                            ></b-pagination>
                        </div>
                    </div>
                </div>

                <!-- If the user logged is an admin -->
                <div v-if="!isAdmin" class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12" v-for="(bankAccount, index) in bankAccounts" :key="`bank_account__${index}`">
                            <b-card
                                tag="article"
                                class="mb-2"
                            >
                                <b-card-img :src="`../${bankAccount.bank.logo}`" alt="Logo del banco" top></b-card-img>
                                <b-card-text>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Banco:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.bank.name}}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Tipo de cuenta:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.bank_account_type.name}}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Tipo de moneda:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.coin_type.name}}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Número de cuenta:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.account_number}}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Número de cuenta CCI:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.account_number_cci}}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Alias:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.alias}}</strong></p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Cuenta propia?:</span>
                                        <p class="text-hover-primary m-0"><strong>{{bankAccount.own_account == 1 ? 'SI' : 'NO'}}</strong></p>
                                    </div>
                                </b-card-text>

                                <div class="text-center">
                                    <a class="btn btn-light-primary font-weight-bold mr-2" v-b-modal.modal-edit-bank-account @click="setSelectedBankAccount(bankAccount.id)">Editar</a>
                                    <a class="btn btn-light-danger font-weight-bold" v-b-modal.modal-confirm-delete @click="setBankAccountIdToDelete(bankAccount.id)">Eliminar</a>
                                </div>
                            </b-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal new bank account -->
        <div>
            <b-modal id="modal-new-bank-account" size="lg" title="Crear nueva cuenta bancaria" ok-title="Guardar" cancel-title="Cancelar" @ok="storeBankAccount($event)" @hidden="resetNewBankAccountData();resetAlertMessage();">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Banco</strong></label>
                            <select class="form-control" v-model="newBankAccount.bank_id">
                                <option value="-1">Seleccione un banco</option>
                                <option v-for="(bank, index) in banks" v-bind:value="bank.id" :key="`bank_${index}`">{{ bank.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Número de cuenta</strong></label>
                            <input type="text" class="form-control" name="account_number" ref="account_number" v-model="newBankAccount.account_number" placeholder="Ingresa el número de cuenta" @change="resetAlertMessage()">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Tipo de cuenta</strong></label>
                            <select class="form-control" v-model="newBankAccount.bank_account_type_id" @change="resetAlertMessage()">
                                <option value="-1">Seleccione un tipo de cuenta</option>
                                <option v-for="(bankAccountType, index) in bankAccountTypes" v-bind:value="bankAccountType.id" :key="`bank_account_type_${index}`">{{ bankAccountType.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Número de cuenta CCI</strong></label>
                            <input type="text" class="form-control" name="account_number_cci" ref="account_number_cci" v-model="newBankAccount.account_number_cci" placeholder="Ingresa el número de cuenta CCI" @change="resetAlertMessage()">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Tipo de moneda</strong></label>
                            <select class="form-control" v-model="newBankAccount.coin_type_id" @change="resetAlertMessage()">
                                <option value="-1">Seleccione un tipo de moneda</option>
                                <option v-for="(coinType, index) in coinTypes" v-bind:value="coinType.id" :key="`coin_${index}`">{{ coinType.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Alias</strong></label>
                            <input type="text" class="form-control" name="alias" ref="alias" v-model="newBankAccount.alias" placeholder="Ingresa el alias" @change="resetAlertMessage()">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Cuenta propia?</strong></label>
                            <b-form-radio name="own_account" v-model="newBankAccount.own_account" value="1">Si</b-form-radio>
                            <b-form-radio name="own_account" v-model="newBankAccount.own_account" value="0">No</b-form-radio>
                        </div>
                    </div>

                    <div v-if="newBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        
                    </div>

                    <div v-if="newBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Nombre del propietario</strong></label>
                            <input type="text" class="form-control" name="owner_name" ref="owner_name" v-model="newBankAccount.owner_name" placeholder="Ingresa el nombre del propietario de la cuenta" @change="resetAlertMessage()">
                        </div>
                    </div>
                    <div v-if="newBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Tipo de documento del propietario</strong></label>
                            <select class="form-control" v-model="newBankAccount.owner_document_type_id" @change="resetAlertMessage()">
                                <option value="-1">Seleccione el tipo de documento</option>
                                <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="newBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Número de documento del propietario</strong></label>
                            <input type="text" class="form-control" name="owner_document_number" ref="owner_document_number" v-model="newBankAccount.owner_document_number" placeholder="Ingresa el número de documento del propietario de la cuenta" @change="resetAlertMessage()">
                        </div>
                    </div>
                    <div v-if="newBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Autoriza depósito?</strong></label>
                            <b-form-radio name="authorized_deposit" v-model="newBankAccount.authorized_deposit" value="1">Si</b-form-radio>
                            <b-form-radio name="authorized_deposit" v-model="newBankAccount.authorized_deposit" value="0">No</b-form-radio>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="savedAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                    </div>
                </div>
            </b-modal>
        </div>

        <!-- Modal edit bank account -->
        <div v-if="Object.keys(selectedBankAccount).length">
            <b-modal id="modal-edit-bank-account" title="Actualizar cuenta bancaria" ok-title="Guardar" cancel-title="Cancelar" @ok="updateBankAccount($event)" @hidden="resetUpdateBankAccountData();resetAlertMessage();">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Banco</strong></label>
                            <select class="form-control" v-model="selectedBankAccount.bank_id">
                                <option value="-1">Seleccione un banco</option>
                                <option v-for="(bank, index) in banks" v-bind:value="bank.id" :key="`bank_${index}`">{{ bank.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Número de cuenta</strong></label>
                            <input type="text" class="form-control" name="account_number" ref="account_number" v-model="selectedBankAccount.account_number" placeholder="Ingresa el número de cuenta" @change="resetAlertMessage()">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Tipo de cuenta</strong></label>
                            <select class="form-control" v-model="selectedBankAccount.bank_account_type_id" @change="resetAlertMessage()">
                                <option value="-1">Seleccione un tipo de cuenta</option>
                                <option v-for="(bankAccountType, index) in bankAccountTypes" v-bind:value="bankAccountType.id" :key="`bank_account_type_${index}`">{{ bankAccountType.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Número de cuenta CCI</strong></label>
                            <input type="text" class="form-control" name="account_number_cci" ref="account_number_cci" v-model="selectedBankAccount.account_number_cci" placeholder="Ingresa el número de cuenta CCI" @change="resetAlertMessage()">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Tipo de moneda</strong></label>
                            <select class="form-control" v-model="selectedBankAccount.coin_type_id" @change="resetAlertMessage()">
                                <option value="-1">Seleccione un tipo de moneda</option>
                                <option v-for="(coinType, index) in coinTypes" v-bind:value="coinType.id" :key="`coin_${index}`">{{ coinType.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Alias</strong></label>
                            <input type="text" class="form-control" name="alias" ref="alias" v-model="selectedBankAccount.alias" placeholder="Ingresa el alias" @change="resetAlertMessage()">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Cuenta propia?</strong></label>
                            <b-form-radio name="own_account" v-model="selectedBankAccount.own_account" value="1">Si</b-form-radio>
                            <b-form-radio name="own_account" v-model="selectedBankAccount.own_account" value="0">No</b-form-radio>
                        </div>
                    </div>

                    <div v-if="selectedBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        
                    </div>

                    <div v-if="selectedBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Nombre del propietario</strong></label>
                            <input type="text" class="form-control" name="owner_name" ref="owner_name" v-model="selectedBankAccount.owner_name" placeholder="Ingresa el nombre del propietario de la cuenta" @change="resetAlertMessage()">
                        </div>
                    </div>
                    <div v-if="selectedBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Tipo de documento del propietario</strong></label>
                            <select class="form-control" v-model="selectedBankAccount.owner_document_type_id" @change="resetAlertMessage()">
                                <option value="-1">Seleccione el tipo de documento</option>
                                <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="selectedBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Número de documento del propietario</strong></label>
                            <input type="text" class="form-control" name="owner_document_number" ref="owner_document_number" v-model="selectedBankAccount.owner_document_number" placeholder="Ingresa el número de documento del propietario de la cuenta" @change="resetAlertMessage()">
                        </div>
                    </div>
                    <div v-if="selectedBankAccount.own_account == 0" class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Autoriza depósito?</strong></label>
                            <b-form-radio name="authorized_deposit" v-model="selectedBankAccount.authorized_deposit" value="1">Si</b-form-radio>
                            <b-form-radio name="authorized_deposit" v-model="selectedBankAccount.authorized_deposit" value="0">No</b-form-radio>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="savedAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                    </div>
                </div>
            </b-modal>
        </div>

        <div>
            <b-modal id="modal-confirm-delete" title="Eliminar cuenta bancaria" ok-title="Eliminar" cancel-title="Cancelar" @ok="deleteBankAccount2($event)" @hidden="resetBankAccountToDelete();">
                <p class="my-4">Esta seguro que desea eliminar esta cuenta bancaria?</p>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import { BANK_ACCOUNTS_GET_ALL, BANK_ACCOUNTS_STORE, BANK_ACCOUNTS_DELETE, BANK_ACCOUNTS_RESTORE, BANK_ACCOUNTS_UPDATE } from "@/core/services/store/bank_account.module";
    import { BANKS_GET_ALL } from "@/core/services/store/bank.module";
    import { BANK_ACCOUNT_TYPES_GET_ALL } from "@/core/services/store/bank_account_type.module";
    import { COIN_TYPES_GET_ALL } from "@/core/services/store/coin_type.module";
    import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";

    export default {
        data() {
            return {
                isAdmin: false,
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: "",
                savedAlert: false,
                savedAlertVariant: '',
                savedAlertMessage: '',
                allBankAccounts: [],
                bankAccounts: [],
                bankAccountSearch: '',
                bankAccountStatus: 'all',
                bankIdSearch: 'all',
                bankAccountTypeId: 'all',
                coinTypeId: 'all',
                newBankAccount: {
                    bank_id: -1,
                    bank_account_type_id: -1,
                    coin_type_id: -1,
                    own_account: 1,
                    owner_document_type_id: -1,
                    authorized_deposit: 1,
                },
                selectedBankAccount: {},
                perPage: 10,
                currentPage: 1,
                fields: [
                    {
                        key: 'id',
                        label: 'ID',
                    },
                    {
                        key: 'bank',
                        label: 'Banco',
                    },
                    {
                        key: 'bank_account_type',
                        label: 'Tipo de cuenta',
                    },
                    {
                        key: 'coin_type',
                        label: 'Tipo de moneda',
                    },
                    {
                        key: 'account_number',
                        label: 'Número de cuenta',
                    },
                    {
                        key: 'account_number_cci',
                        label: 'Número de cuenta CCI',
                    },
                    {
                        key: 'alias',
                        label: 'Alias',
                    },
                    {
                        key: 'own_account',
                        label: 'Cuenta propia',
                    },
                    {
                        key: 'owner_name',
                        label: 'A nombre de:',
                    },
                    {
                        key: 'authorized_deposit',
                        label: 'Autoriza depósito',
                    },
                    {
                        key: 'deleted_at',
                        label: 'Estado',
                    },
                    {
                        key: 'actions',
                        label: 'Acciones',
                    },
                ],
                banks: [],
                bankAccountTypes: [],
                coinTypes: [],
                documentTypes: [],
                bankAccountIdToDelete: -1,
            }
        },
        mounted() {
            
        },
        methods: {
            storeBankAccount(e) {
                e.preventDefault();

                if(this.newBankAccount.bank_id == -1){
                    this.showSavedAlert('danger', 'Debe seleccionar el banco.')
                    return;
                }
                if(this.newBankAccount.account_number == null || this.newBankAccount.account_number == ""){
                    this.showSavedAlert('danger', 'Debe ingresar el número de cuenta.')
                    return;
                }
                if(this.newBankAccount.bank_account_type_id == -1){
                    this.showSavedAlert('danger', 'Debe seleccionar el tipo de cuenta.')
                    return;
                }
                if(this.newBankAccount.account_number_cci == null || this.newBankAccount.account_number_cci == ""){
                    this.showSavedAlert('danger', 'Debe ingresar el número de cuenta CCI.')
                    return;
                }
                if(this.newBankAccount.coin_type_id == -1){
                    this.showSavedAlert('danger', 'Debe seleccionar el tipo de moneda.')
                    return;
                }
                if(this.newBankAccount.alias == null || this.newBankAccount.alias == ""){
                    this.showSavedAlert('danger', 'Debe ingresar el alias.')
                    return;
                }

                if(this.newBankAccount.own_account == 0){
                    if(this.newBankAccount.owner_name == null || this.newBankAccount.owner_name == ""){
                        this.showSavedAlert('danger', 'Debe ingresar nombre del propietario.')
                        return;
                    }
                    if(this.newBankAccount.owner_document_type_id == -1){
                        this.showSavedAlert('danger', 'Debe seleccionar el tipo de documento.')
                        return;
                    }
                    if(this.newBankAccount.owner_document_number == null || this.newBankAccount.owner_document_number == ""){
                        this.showSavedAlert('danger', 'Debe ingresar el número de documento.')
                        return;
                    }
                }
                else {
                    this.newBankAccount.owner_name = null;
                    this.newBankAccount.owner_document_type_id = null;
                    this.newBankAccount.owner_document_number = null;
                    this.newBankAccount.authorized_deposit = null;
                }

                this.$store
                    .dispatch(BANK_ACCOUNTS_STORE, this.newBankAccount)
                    .then((data) => {
                        if (data.code != 201){
                            this.showSavedAlert('danger', data.error)
                            return;
                        }

                        this.allBankAccounts = this.allBankAccounts.concat(data.data);
                        this.bankAccounts = this.bankAccounts.concat(data.data);

                        // close modal
                        this.closeModal('modal-new-bank-account')
                        this.showMainAlert('success', 'Cuenta bancaria creada correctamente!')

                        this.resetNewBankAccountData();
                    })
                    .catch((err) => {
                        this.showSavedAlert('danger', err)
                        return;
                    });
            },

            getBankAccounts () {
                let query = '';
                if (this.isAdmin) {
                    query = '?with_deleted=true';
                }

                this.$store
                    .dispatch(BANK_ACCOUNTS_GET_ALL, query)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas bancarias.')
                            return;
                        }

                        this.allBankAccounts = data.data;
                        this.bankAccounts = this.allBankAccounts;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            deleteBankAccount(bankAccountId) {
                this.$store
                    .dispatch(BANK_ACCOUNTS_DELETE, bankAccountId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        let allBankAccountsIndex = this.allBankAccounts.findIndex(bankAccount => bankAccount.id == data.data.id);
                        if (allBankAccountsIndex > -1) {
                            this.allBankAccounts[allBankAccountsIndex].deleted_at = data.data.deleted_at;
                        }

                        let bankAccountsIndex = this.bankAccounts.findIndex(bankAccount => bankAccount.id == data.data.id);
                        if (bankAccountsIndex > -1) {
                            this.bankAccounts[bankAccountsIndex].deleted_at = data.data.deleted_at;
                        }

                        this.showMainAlert('success', 'Cuenta bancaria eliminada correctamente!')
                        return;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            deleteBankAccount2(e) {
                e.preventDefault();

                this.$store
                    .dispatch(BANK_ACCOUNTS_DELETE, this.bankAccountIdToDelete)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        this.allBankAccounts = this.allBankAccounts.filter(bankAccount => bankAccount.id != data.data.id);
                        this.bankAccounts = this.bankAccounts.filter(bankAccount => bankAccount.id != data.data.id);

                        // close modal
                        this.closeModal('modal-confirm-delete')
                        this.showMainAlert('success', 'Cuenta bancaria eliminada correctamente! <button>Restaurar</button>')
                        this.resetBankAccountToDelete();
                        return;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            restoreBankAccount(bankAccountId) {
                this.$store
                    .dispatch(BANK_ACCOUNTS_RESTORE, bankAccountId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        let allBankAccountsIndex = this.allBankAccounts.findIndex(bankAccount => bankAccount.id == data.data.id);
                        if (allBankAccountsIndex > -1) {
                            this.allBankAccounts[allBankAccountsIndex].deleted_at = null;
                        }

                        let bankAccountsIndex = this.bankAccounts.findIndex(bankAccount => bankAccount.id == data.data.id);
                        if (bankAccountsIndex > -1) {
                            this.bankAccounts[bankAccountsIndex].deleted_at = null;
                        }

                        this.showMainAlert('success', 'Cuenta bancaria restaurada correctamente!')
                        return;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            updateBankAccount(e) {
                e.preventDefault();

                if(this.selectedBankAccount.bank_id == -1){
                    this.showSavedAlert('danger', 'Debe seleccionar el banco.')
                    return;
                }
                if(this.selectedBankAccount.account_number == null || this.selectedBankAccount.account_number == ""){
                    this.showSavedAlert('danger', 'Debe ingresar el número de cuenta.')
                    return;
                }
                if(this.selectedBankAccount.bank_account_type_id == -1){
                    this.showSavedAlert('danger', 'Debe seleccionar el tipo de cuenta.')
                    return;
                }
                if(this.selectedBankAccount.account_number_cci == null || this.selectedBankAccount.account_number_cci == ""){
                    this.showSavedAlert('danger', 'Debe ingresar el número de cuenta CCI.')
                    return;
                }
                if(this.selectedBankAccount.coin_type_id == -1){
                    this.showSavedAlert('danger', 'Debe seleccionar el tipo de moneda.')
                    return;
                }
                if(this.selectedBankAccount.alias == null || this.selectedBankAccount.alias == ""){
                    this.showSavedAlert('danger', 'Debe ingresar el alias.')
                    return;
                }

                if(this.selectedBankAccount.own_account == 0){
                    if(this.selectedBankAccount.owner_name == null || this.selectedBankAccount.owner_name == ""){
                        this.showSavedAlert('danger', 'Debe ingresar nombre del propietario.')
                        return;
                    }
                    if(this.selectedBankAccount.owner_document_type_id == -1){
                        this.showSavedAlert('danger', 'Debe seleccionar el tipo de documento.')
                        return;
                    }
                    if(this.selectedBankAccount.owner_document_number == null || this.selectedBankAccount.owner_document_number == ""){
                        this.showSavedAlert('danger', 'Debe ingresar el número de documento.')
                        return;
                    }
                }
                else {
                    this.selectedBankAccount.owner_name = null;
                    this.selectedBankAccount.owner_document_type_id = null;
                    this.selectedBankAccount.owner_document_number = null;
                    this.selectedBankAccount.authorized_deposit = null;
                }

                this.$store
                    .dispatch(BANK_ACCOUNTS_UPDATE, this.selectedBankAccount)
                    .then((data) => {
                        if (data.code != 200){
                            this.showSavedAlert('danger', data.error)
                            return;
                        }

                        let allBankAccountsIndex = this.allBankAccounts.findIndex(bankAccount => bankAccount.id == data.data.id);
                        if (allBankAccountsIndex > -1) {
                            this.allBankAccounts[allBankAccountsIndex] = data.data
                        }

                        let bankAccountsIndex = this.bankAccounts.findIndex(bankAccount => bankAccount.id == data.data.id);
                        if (bankAccountsIndex > -1) {
                            this.bankAccounts[bankAccountsIndex] = data.data
                        }

                        this.bankAccounts = this.bankAccounts.slice();

                        // close modal
                        this.closeModal('modal-edit-bank-account')
                        this.showMainAlert('success', 'Cuenta bancaria actualizada correctamente!')

                        this.resetUpdateBankAccountData();
                    })
                    .catch((err) => {
                        this.showSavedAlert('danger', err)
                        return;
                    });
            },

            getBanks () {
                this.$store
                    .dispatch(BANKS_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos los bancos.')
                            return;
                        }

                        this.banks = data.data
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getBankAccountTypes () {
                this.$store
                    .dispatch(BANK_ACCOUNT_TYPES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos tipos de cuentas bancarias.')
                            return;
                        }

                        this.bankAccountTypes = data.data
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getCoinTypes () {
                this.$store
                    .dispatch(COIN_TYPES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos tipos de moneda.')
                            return;
                        }

                        this.coinTypes = data.data
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getDocumentTypes () {
                this.$store
                    .dispatch(DOCUMENT_TYPES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos tipos de documento.')
                            return;
                        }

                        this.documentTypes = data.data
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            setSelectedBankAccount(bankAccountId) {
                this.selectedBankAccount = Object.assign({}, this.allBankAccounts.find(bankAccount => bankAccount.id == bankAccountId));
            },

            resetNewBankAccountData(){
                this.newBankAccount = {
                    bank_id: -1,
                    bank_account_type_id: -1,
                    coin_type_id: -1,
                    own_account: 1,
                    owner_document_type_id: -1,
                    authorized_deposit: 1,
                };
            },

            resetUpdateBankAccountData(){
                this.selectedBankAccount = {};
            },

            resetAlertMessage(){
                this.savedAlertVariant = '';
                this.savedAlertMessage = '';
                this.savedAlert = false;
            },

            showMainAlert(variant, message) {
                this.mainAlertVariant = variant;
                this.mainAlertMessage = message;
                this.mainAlert = true;
                window.scrollTo(0,0);
            },

            showSavedAlert(variant, message) {
                this.savedAlertVariant = variant;
                this.savedAlertMessage = message;
                this.savedAlert = true;
            },

            closeModal(modalId){
                this.$root.$emit('bv::hide::modal', modalId);
            },

            filterBankAccounts() {
                if (this.bankAccountSearch == '' && this.bankAccountStatus == 'all' && this.bankIdSearch == 'all' && this.bankAccountTypeId == 'all' && this.coinTypeId == 'all') {
                    this.bankAccounts = this.allBankAccounts;
                    return;
                }

                this.bankAccounts = [];

                for (let i = 0; i < this.allBankAccounts.length; i++) {
                    let includeFilters = true;
                    if (this.bankAccountSearch != '') {
                        if (!this.allBankAccounts[i].account_number.toLowerCase().includes(this.bankAccountSearch.toLowerCase()) 
                            && !this.allBankAccounts[i].account_number_cci.toLowerCase().includes(this.bankAccountSearch.toLowerCase()) 
                            && !this.allBankAccounts[i].alias.toLowerCase().includes(this.bankAccountSearch.toLowerCase())) {
                            includeFilters = false;
                        }
                    }
                    if (this.bankAccountStatus != 'all') {
                        if (this.bankAccountStatus == 'deleted') {
                            if (this.allBankAccounts[i].deleted_at == null) {
                                includeFilters = false;
                            }
                        }
                        else if (this.bankAccountStatus == 'not_deleted') {
                            if (this.allBankAccounts[i].deleted_at != null) {
                                includeFilters = false;
                            }
                        }
                    }
                    if (this.bankIdSearch != 'all') {
                        if (this.allBankAccounts[i].bank_id != this.bankIdSearch) {
                            includeFilters = false;
                        }
                    }
                    if (this.bankAccountTypeId != 'all') {
                        if (this.allBankAccounts[i].bank_account_type_id != this.bankAccountTypeId) {
                            includeFilters = false;
                        }
                    }
                    if (this.coinTypeId != 'all') {
                        if (this.allBankAccounts[i].coin_type_id != this.coinTypeId) {
                            includeFilters = false;
                        }
                    }

                    if (includeFilters) {
                        this.bankAccounts.push(this.allBankAccounts[i])
                    }
                }
            },

            resetFilters() {
                this.bankAccountSearch = '';
                this.bankAccountStatus = 'all';
                this.bankAccounts = this.allBankAccounts;
            },

            getIsAdmin() {
                return window.localStorage.getItem("is_admin") == "true" ? true : false;
            },

            resetBankAccountToDelete() {
                this.bankAccountIdToDelete = -1;
            },

            setBankAccountIdToDelete(bankAccountId) {
                this.bankAccountIdToDelete = bankAccountId;
            }
        },
        computed: {
            rows() {
                return this.bankAccounts.length
            },
        },
        created() {
            this.isAdmin = this.getIsAdmin();
            this.getBankAccounts();
            this.getBanks();
            this.getBankAccountTypes();
            this.getCoinTypes();
            this.getDocumentTypes();
        },
    };
</script>