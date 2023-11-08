<template>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header pt-4 pb-2 min-vh-0">
                    <div class="row w-100">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <h3 class="pt-4"><strong>Tipos de cambio</strong></h3>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <div class="row justify-content-end">
                                <form class="form">
                                    <div class="form-inline">
                                        <div class="form-group p-2">
                                            <input type="text" class="form-control" name="purchase" ref="purchase" placeholder="Compra">
                                        </div>
                                        <div class="form-group p-2">
                                            <input type="text" class="form-control" name="sale" ref="sale" placeholder="Venta">
                                        </div>
                                        <button class="btn btn-primary font-weight-bold ml-2" v-on:click="storeExchangeRate">Guardar</button>
                                    </div>
                                </form>
                            </div>
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

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <b-table
                                id="my-table"
                                :items="exchangeRates"
                                :per-page="perPage"
                                :current-page="currentPage"
                                :fields="fields"
                                head-variant="light"
                                table-variant="light"
                                hover
                            >
                                <template #cell(actions)="row">
                                    <a class="m-2 cursor-pointer" @click="deleteExchangeRate(row.item.id)" title="Eliminar tipo de cambio"><i class="fas fa-trash"></i></a>
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
    </div>
</template>

<script>
    import { EXCHANGE_RATES_GET_ALL, EXCHANGE_RATES_STORE, EXCHANGE_RATES_DELETE } from "@/core/services/store/exchange_rate.module";

    export default {
        data() {
            return {
                perPage: 10,
                currentPage: 1,
                fields: [
                    { key: 'updated_at', label: 'Fecha' },
                    { key: 'purchase', label: 'Compra' },
                    { key: 'sale', label: 'Venta' },
                    { key: 'created_by', label: 'Creador' },
                    { key: 'actions', label: 'Acciones' },
                ],
                exchangeRates: [],
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: "",
            }
        },
        mounted() {
            
        },
        methods: {
            storeExchangeRate: function(e) {
                e.preventDefault();

                const purchase = this.$refs.purchase.value;
                const sale = this.$refs.sale.value;

                if(!purchase){
                    this.showMainAlert('danger', 'Debe ingresar el valor de compra')
                    return;
                }

                if(!sale){
                    this.showMainAlert('danger', 'Debe ingresar el valor de venta')
                    return;
                }

                let data;
                data = { purchase, sale };

                this.$store
                    .dispatch(EXCHANGE_RATES_STORE, data)
                    .then((data) => {
                        if (data.code != 201){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        let milliseconds = Date.parse(data.data.updated_at);
                        let date = new Date(milliseconds);
                        let formatDate = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' - ' + date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();

                        data.data.updated_at = formatDate
                        this.exchangeRates = [data.data].concat(this.exchangeRates)

                        this.showMainAlert('success', 'Tipo de cambio creado correctamente!')
                        this.$refs.purchase.value = ""
                        this.$refs.sale.value = ""
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            getExchangeRates () {
                this.$store
                    .dispatch(EXCHANGE_RATES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos los tipos de cambio.')
                            return;
                        }

                        for(let i = 0; i < data.data.length; i++){
                            let milliseconds = Date.parse(data.data[i].updated_at);
                            let date = new Date(milliseconds);
                            let formatDate = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()

                            data.data[i].updated_at = formatDate;
                        }

                        this.exchangeRates = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            deleteExchangeRate(exchangeRateId) {
                this.$store
                    .dispatch(EXCHANGE_RATES_DELETE, exchangeRateId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        this.exchangeRates = this.exchangeRates.filter(exchangeRate => exchangeRate.id != exchangeRateId);
                        this.showMainAlert('success', 'Tipo de cambio eliminado correctamente!')
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },

            showMainAlert(variant, message) {
                this.mainAlertVariant = variant;
                this.mainAlertMessage = message;
                this.mainAlert = true;
                window.scrollTo(0,0);
            },
        },
        computed: {
            rows() {
                return this.exchangeRates.length
            },
        },
        created() {
            this.getExchangeRates();
        },
    };
</script>