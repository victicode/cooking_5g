<template>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header pt-4 pb-2 min-vh-0">
                    <div class="row w-100">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="pt-4"><strong>Cuentas Empresariales</strong></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row" v-if="mainAlert">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
                        </div>
                        <hr>
                    </div>

                    <div class="row m-2">
                        <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." v-model="companyAccountSearchText">
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Estado:</label>
                                <select class="form-control" v-model="companyAccountStatus">
                                    <option value="all">Todos</option>
                                    <option value="not_deleted">Activos</option>
                                    <option value="deleted">Eliminados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mr-2">
                            <a class="btn btn-light-primary px-6 font-weight-bold cursor-pointer mr-2" @click="filterCompanyAccounts()">Buscar</a>
                            <a class="btn btn-light-primary px-6 font-weight-bold cursor-pointer mr-2" @click="resetFilters()">Resetear</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <b-table
                                id="my-table"
                                :fields="fields"
                                :items="companyAccounts"
                                :per-page="perPage"
                                :current-page="currentPage"
                                head-variant="light"
                                table-variant="light"
                                hover
                            >
                            <template #cell(full_name_lr)="data">
                                {{ data.item.name_lr }} {{ data.item.surname_lr }}
                            </template>
                            <template #cell(full_name_c)="data">
                                {{ data.item.name_c }} {{ data.item.surname_c }}{{ data.item.phone_c == null ? '' : ' - ' + data.item.phone_c }}
                            </template>
                            <template #cell(preferential)="data">
                                <span class="label label-lg label-light-success label-inline" v-if="data.item.preferential == 1">Activo</span>
                                <span class="label label-lg label-light-danger label-inline" v-else>Inactivo</span>
                            </template>
                            <template #cell(deleted_at)="data">
                                <span class="label label-lg label-light-danger label-inline" v-if="data.item.deleted_at">Eliminado</span>
                                <span class="label label-lg label-light-success label-inline" v-else>Activo</span>
                            </template>
                            <template #cell(actions)="data">
                                <a v-if="data.item.deleted_at == null" class="m-2" v-b-modal.modal-edit-info @click="updateSelectedAccount(data.item.id)" title="Editar cuenta"><i class="far fa-edit"></i></a>
                                <a v-if="data.item.deleted_at == null" class="m-2" v-b-modal.modal-set-preferential @click="updateSelectedAccount(data.item.id)" title="Setear preferencial"><i class="fa fa-cog"></i></a>
                                <a v-if="data.item.deleted_at == null" class="m-2 cursor-pointer" @click="deleteAccount(data.item.id)" title="Eliminar cuenta"><i class="fas fa-trash"></i></a>
                                <a v-if="data.item.deleted_at != null" class="m-2 cursor-pointer" @click="restoreAccount(data.item.id)" title="Restaurar cuenta"><i class="fas fa-trash-restore-alt"></i></a>
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
            </div>
        </div>
        <!-- Modal account info -->
        <div v-if="Object.keys(selectedAccount).length">
            <b-modal id="modal-edit-info" size="lg" title="Información de la cuenta" ok-title="Actualizar" cancel-title="Cancelar" @ok="updateAccount($event)">
                <input type="hidden" name="id" ref="id" v-model="selectedAccount.id">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Número de RUC</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="ruc" ref="ruc" placeholder="Ingresa el número de RUC" v-model="selectedAccount.ruc">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Razón social</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="business_name" ref="business_name" placeholder="Ingresa la razón social" v-model="selectedAccount.business_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Giro de negocio</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="business_turn" ref="business_turn" placeholder="Ingresa el giro de negocio" v-model="selectedAccount.business_turn">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="fiscal_address" ref="fiscal_address" placeholder="Ingresa la dirección fiscal" v-model="selectedAccount.fiscal_address">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>País</label>
                            <select class="form-control form-control-solid form-control-lg" name="country_id" ref="country_id" v-model="selectedAccount.country_id">
                                <option value="">Selecciona el país</option>
                                <option value="1">Perú</option>
                                <option value="AF">RELLENAR CON EL RESTO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Departamento</label>
                            <select class="form-control form-control-solid form-control-lg" name="department_id" ref="department_id" v-model="selectedAccount.department_id">
                                <option value="">Selecciona el departamento</option>
                                <option value="1">LIMA</option>
                                <option value="AF">RELLENAR CON EL RESTO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Provincia</label>
                            <select class="form-control form-control-solid form-control-lg" name="province_id" ref="province_id" v-model="selectedAccount.province_id">
                                <option value="">Selecciona la provincia</option>
                                <option value="1">LIMA</option>
                                <option value="AF">RELLENAR CON EL RESTO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Distrito</label>
                            <select class="form-control form-control-solid form-control-lg" name="district_id" ref="district_id" v-model="selectedAccount.district_id">
                                <option value="">Selecciona el distrito</option>
                                <option value="1">LIMA</option>
                                <option value="AF">RELLENAR CON EL RESTO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="email" ref="email" placeholder="Ingresa el correo electrónico" v-model="selectedAccount.email">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Telefono</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="phone" ref="phone" placeholder="Ingresa el número de teléfono" v-model="selectedAccount.phone">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Nombre/s</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="name_lr" ref="name_lr" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name_lr">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Apellido/s</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="surname_lr" ref="surname_lr" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname_lr">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Tipo de documento</label>
                            <select class="form-control form-control-solid form-control-lg" name="document_type_lr_id" ref="document_type_lr_id" v-model="selectedAccount.document_type_lr.id">
                                <option value="">Selecciona el tipo de documento</option>
                                <option value="1">DNI</option>
                                <option value="AF">RELLENAR CON EL RESTO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Número de documento</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="document_number_lr" ref="document_number_lr" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number_lr">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Nombre/s</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="name_c" ref="name_c" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name_c">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Apellido/s</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="surname_c" ref="surname_c" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname_c">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Tipo de documento</label>
                            <select class="form-control form-control-solid form-control-lg" name="document_type_c_id" ref="document_type_c_id" v-model="selectedAccount.document_type_c.id">
                                <option value="">Selecciona el tipo de documento</option>
                                <option value="1">DNI</option>
                                <option value="AF">RELLENAR CON EL RESTO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Número de documento</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="document_number_c" ref="document_number_c" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number_c">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" class="form-control form-control-solid form-control-lg" name="phone_c" ref="phone_c" placeholder="Ingresa el número de teléfono" v-model="selectedAccount.phone_c">
                        </div>
                    </div>
                </div>
                <div class="row" v-if="updateAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert show :variant="updateAlertStatus">{{updateAlertMessage}}</b-alert>
                    </div>
                </div>
            </b-modal>

            <b-modal id="modal-set-preferential" title="Tipo de cambio preferencial" ok-title="Guardar" cancel-title="Cancelar" @ok="setPreferential($event)">
                <input type="hidden" name="id" ref="id" v-model="selectedAccount.id">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>Compra</label>
                            <input type="text" class="form-control" name="preferential_purchase" ref="preferential_purchase" placeholder="Ingresa el valor de compra" v-model="selectedAccount.preferential_purchase">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>Venta</label>
                            <input type="text" class="form-control" name="preferential_sale" ref="preferential_sale" placeholder="Ingresa el valor de venta" v-model="selectedAccount.preferential_sale">
                        </div>
                    </div>
                </div>
                <b-button class="mt-3" variant="outline-danger" block @click="deletePreferential()" v-if="selectedAccount.preferential == 1">Eliminar tipo de cambio preferencial</b-button>
                <div class="row" v-if="preferentialAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert show :variant="preferentialAlertStatus" v-bind:class="{ 'mt-2': selectedAccount.preferential == 1 }">{{preferentialAlertMessage}}</b-alert>
                    </div>
                </div>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import { ACCOUNTS_COMPANY_GET_ALL, ACCOUNTS_COMPANY_DELETE, ACCOUNTS_COMPANY_RESTORE, ACCOUNTS_COMPANY_UPDATE, ACCOUNTS_COMPANY_SET_PREFERENTIAL, ACCOUNTS_COMPANY_DELETE_PREFERENTIAL } from "@/core/services/store/account.module";

    export default {
        data() {
            return {
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: "",
                updateAlert: false,
                updateAlertVariant: '',
                updateAlertMessage: '',
                preferentialAlert: false,
                preferentialAlertVariant: '',
                preferentialAlertMessage: '',
                perPage: 10,
                currentPage: 1,
                allCompanyAccounts: [],
                companyAccounts: [],
                selectedAccount: {},
                fields: [
                    {
                        key: 'id',
                        label: 'ID',
                    },
                    {
                        key: 'ruc',
                        label: 'Número de RUC',
                    },
                    {
                        key: 'business_name',
                        label: 'Razón social',
                    },
                    {
                        key: 'business_turn',
                        label: 'Giro de negocio',
                    },
                    {
                        key: 'fiscal_address',
                        label: 'Dirección',
                    },
                    {
                        key: 'email',
                        label: 'Email',
                    },
                    {
                        key: 'phone',
                        label: 'Teléfono',
                    },
                    {
                        key: 'full_name_lr',
                        label: 'Representante legal',
                    },
                    {
                        key: 'full_name_c',
                        label: 'Contacto',
                    },
                    {
                        key: 'preferential',
                        label: 'Preferencial',
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
                companyAccountSearchText: '',
                companyAccountStatus: 'all',
            }
        },
        mounted() {
            
        },
        methods: {
            getCompanyAccounts () {
                this.$store
                    .dispatch(ACCOUNTS_COMPANY_GET_ALL, '?with_deleted=true')
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos las cuentas empresariales.')
                            return;
                        }

                        this.allCompanyAccounts = data.data;

                        this.companyAccounts = this.allCompanyAccounts.map((companyAccount) => {
                            return {
                                ...companyAccount,
                            };
                        });
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            updateAccount (e) {
                // Prevent modal hide
                e.preventDefault();

                const id = this.$refs.id.value;
                const ruc = this.$refs.ruc.value;
                const business_name = this.$refs.business_name.value;
                const business_turn = this.$refs.business_turn.value;
                const fiscal_address = this.$refs.fiscal_address.value;
                const country_id = this.$refs.country_id.value;
                const department_id = this.$refs.department_id.value;
                const province_id = this.$refs.province_id.value;
                const district_id = this.$refs.district_id.value;
                const email = this.$refs.email.value;
                const phone = this.$refs.phone.value;
                const name_lr = this.$refs.name_lr.value;
                const surname_lr = this.$refs.surname_lr.value;
                const document_type_lr_id = this.$refs.document_type_lr_id.value;
                const document_number_lr = this.$refs.document_number_lr.value;
                const name_c = this.$refs.name_c.value;
                const surname_c = this.$refs.surname_c.value;
                const document_type_c_id = this.$refs.document_type_c_id.value;
                const document_number_c = this.$refs.document_number_c.value;
                const phone_c = this.$refs.phone_c.value;

                let data;
                data = { id, ruc, business_name, business_turn, fiscal_address, country_id, department_id, province_id, district_id, email, phone, name_lr, surname_lr, document_type_lr_id, document_number_lr, name_c, surname_c, document_type_c_id, document_number_c, phone_c }; 

                this.$store
                    .dispatch(ACCOUNTS_COMPANY_UPDATE, data)
                    .then((data) => {
                        if (data.code != 200){
                            this.showUpdateAlert('danger', data.error)
                            return;
                        }

                        let allCompanyAccountIndex = this.allCompanyAccounts.findIndex(companyAccount => companyAccount.id == data.data.id);

                        if (allCompanyAccountIndex > -1) {
                            this.allCompanyAccounts[allCompanyAccountIndex] = data.data;
                        }

                        let companyAccountIndex = this.companyAccounts.findIndex(companyAccount => companyAccount.id == data.data.id);

                        if (companyAccountIndex > -1) {
                            this.companyAccounts[companyAccountIndex] = {
                                ...this.allCompanyAccounts[allCompanyAccountIndex],
                            };
                        }

                        // Slice method is used to 'refresh' the companyAccounts variable to vuejs detect a change.
                        this.companyAccounts = this.companyAccounts.slice();

                        // close modal
                        this.closeModal('modal-edit-info')
                        this.showMainAlert('success', 'Cuenta empresarial actualizada correctamente!')
                        return;
                    })
                    .catch((err) => {
                        this.showUpdateAlert('danger', err)
                        return;
                    });
            },

            deleteAccount (companyAccountId) {
                this.$store
                    .dispatch(ACCOUNTS_COMPANY_DELETE, companyAccountId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        let allCompanyAccountIndex = this.allCompanyAccounts.findIndex(companyAccount => companyAccount.id == data.data.id);

                        if (allCompanyAccountIndex > -1) {
                            this.allCompanyAccounts[allCompanyAccountIndex].deleted_at = data.data.deleted_at;
                        }

                        let companyAccountIndex = this.companyAccounts.findIndex(companyAccount => companyAccount.id == data.data.id);

                        if (companyAccountIndex > -1) {
                            this.companyAccounts[companyAccountIndex].deleted_at = data.data.deleted_at;
                        }

                        this.showMainAlert('success', 'Cuenta empresarial eliminada correctamente!')
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            restoreAccount (companyAccountId) {
                this.$store
                    .dispatch(ACCOUNTS_COMPANY_RESTORE, companyAccountId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        let allCompanyAccountIndex = this.allCompanyAccounts.findIndex(companyAccount => companyAccount.id == data.data.id);

                        if (allCompanyAccountIndex > -1) {
                            this.allCompanyAccounts[allCompanyAccountIndex].deleted_at = null;
                        }

                        let companyAccountIndex = this.companyAccounts.findIndex(companyAccount => companyAccount.id == data.data.id);

                        if (companyAccountIndex > -1) {
                            this.companyAccounts[companyAccountIndex].deleted_at = null;
                        }

                        this.showMainAlert('success', 'Cuenta empresarial restaurada correctamente!')
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            setPreferential (e) {
                e.preventDefault();

                const id = this.selectedAccount.id;
                const preferential_purchase = this.$refs.preferential_purchase.value;
                const preferential_sale = this.$refs.preferential_sale.value;

                if(!preferential_purchase){
                    this.showPreferentialAlert('danger', 'Debe completar el valor de compra.')
                    return;
                }

                if(!preferential_sale){
                    this.showPreferentialAlert('danger', 'Debe completar el valor de venta.')
                    return;
                }

                let data;
                data = { id, preferential_purchase, preferential_sale }

                this.$store
                    .dispatch(ACCOUNTS_COMPANY_SET_PREFERENTIAL, data)
                    .then((data) => {
                        if (data.code != 200){
                            this.showPreferentialAlert('danger', data.error)
                            return;
                        }

                        let allCompanyAccountIndex = this.allCompanyAccounts.findIndex((companyAccount => companyAccount.id == data.data.id));
                        this.allCompanyAccounts[allCompanyAccountIndex].preferential = 1;
                        this.allCompanyAccounts[allCompanyAccountIndex].preferential_purchase = data.data.preferential_purchase;
                        this.allCompanyAccounts[allCompanyAccountIndex].preferential_sale = data.data.preferential_sale;

                        let companyAccountIndex = this.companyAccounts.findIndex((companyAccount => companyAccount.id == data.data.id));
                        this.companyAccounts[companyAccountIndex].preferential = 1;
                        this.companyAccounts[companyAccountIndex].preferential_purchase = data.data.preferential_purchase;
                        this.companyAccounts[companyAccountIndex].preferential_sale = data.data.preferential_sale;

                        // close modal
                        this.closeModal('modal-set-preferential')
                        this.showMainAlert('success', 'Preferencial seteado correctamente!')
                        return;
                    })
                    .catch((err) => {
                        this.showPreferentialAlert('danger', err)
                        return;
                    });
            },

            deletePreferential () {
                this.$store
                    .dispatch(ACCOUNTS_COMPANY_DELETE_PREFERENTIAL, this.selectedAccount.id)
                    .then((data) => {
                        if (data.code != 200){
                            this.showPreferentialAlert('danger', data.error)
                            return;
                        }

                        let allCompanyAccountIndex = this.allCompanyAccounts.findIndex((companyAccount => companyAccount.id == data.data.id));
                        this.allCompanyAccounts[allCompanyAccountIndex].preferential = 0;
                        this.allCompanyAccounts[allCompanyAccountIndex].preferential_purchase = null;
                        this.allCompanyAccounts[allCompanyAccountIndex].preferential_sale = null;

                        let companyAccountIndex = this.companyAccounts.findIndex((companyAccount => companyAccount.id == data.data.id));
                        this.companyAccounts[companyAccountIndex].preferential = 0;
                        this.companyAccounts[companyAccountIndex].preferential_purchase = null;
                        this.companyAccounts[companyAccountIndex].preferential_sale = null;

                        // close modal
                        this.closeModal('modal-set-preferential')
                        this.showMainAlert('success', 'Preferencial eliminado correctamente!')
                        return;
                    })
                    .catch((err) => {
                        this.showPreferentialAlert('danger', err)
                        return;
                    });
            },

            updateSelectedAccount (id) {
                this.updateAlert = false;
                this.updateAlertMessage = '';
                this.updateAlertVariant = '';
                this.preferentialAlert = false;
                this.preferentialAlertVariant = '';
                this.preferentialAlertMessage = '';

                this.selectedAccount = Object.assign({}, this.allCompanyAccounts.find(account => account.id === id));
            },

            showMainAlert(variant, message) {
                this.mainAlertVariant = variant;
                this.mainAlertMessage = message;
                this.mainAlert = true;
                window.scrollTo(0,0);
            },

            showUpdateAlert(variant, message) {
                this.updateAlertVariant = variant;
                this.updateAlertMessage = message;
                this.updateAlert = true;
            },

            showPreferentialAlert(variant, message) {
                this.preferentialAlertVariant = variant;
                this.preferentialAlertMessage = message;
                this.preferentialAlert = true;
            },

            closeModal(modalId){
                this.$root.$emit('bv::hide::modal', modalId);
            },

            filterCompanyAccounts() {
                if (this.companyAccountSearchText == '' && this.companyAccountStatus == 'all') {
                    this.resetFilters();
                    return;
                }

                this.companyAccounts = [];

                for (let i = 0; i < this.allCompanyAccounts.length; i++) {
                    let includeFilters = false;
                    if (this.companyAccountSearchText != '') {
                        if (this.allCompanyAccounts[i].ruc.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].business_name.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].business_turn.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].fiscal_address.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].email.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].phone.includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].name_lr.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].surname_lr.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].name_c.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].surname_c.toLowerCase().includes(this.companyAccountSearchText.toLowerCase())) {
                            includeFilters = true;
                        }
                        if (this.allCompanyAccounts[i].phone_c != null) {
                            if (this.allCompanyAccounts[i].phone_c.includes(this.companyAccountSearchText.toLowerCase())) {
                                includeFilters = true;
                            }
                        }
                    }

                    if (this.companyAccountStatus != 'all') {
                        if (this.companyAccountStatus == 'deleted') {
                            if (this.allCompanyAccounts[i].deleted_at != null) {
                                includeFilters = true;
                            }
                        }
                        else if (this.companyAccountStatus == 'not_deleted') {
                            if (this.allCompanyAccounts[i].deleted_at == null) {
                                includeFilters = true;
                            }
                        }
                    }

                    if (includeFilters) {
                        this.companyAccounts.push({
                            ...this.allCompanyAccounts[i],
                        })
                    }
                }
            },

            resetFilters() {
                this.companyAccountSearchText = '';
                this.companyAccountStatus = 'all';
                this.companyAccounts = this.allCompanyAccounts.map((companyAccount) => {
                    return {
                        ...companyAccount,
                    };
                });
            },
        },
        computed: {
            rows() {
                return this.companyAccounts.length
            },
        },
        created() {
            this.getCompanyAccounts();
        },
    };
</script>