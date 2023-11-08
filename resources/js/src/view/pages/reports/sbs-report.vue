<template>
	<div>
		
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="fw-bold py-3 mb-0
        "><span class="text-muted fw-light">Inicio / </span> Todas las operaciones</h4>

			</div>
      <div class="card-body mt-3">
        <div class="row justify-content-center ">
          <div class="col-12">
            <div class="row g-3 align-items-end ">
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold">Fecha:</label>
                <div class="mb-0">
                  <input
                      type="text"
                      class="form-control dt-date flatpickr-range dt-input"
                      data-column="5"
                      placeholder="Buscar tasa desde - hasta"
                      data-column-index="5"
                      name="dt_date"
                      ref="date_range"
                    />
                    <input
                      type="hidden"
                      ref="start_date"
                      class="form-control dt-date start_date dt-input"
                      data-column="5"
                      data-column-index="5"
                      name="value_from_start_date"
                    />
                    <input
                      type="hidden"
                      ref="end_date"
                      class="form-control dt-date end_date dt-input"
                      name="value_from_end_date"
                      data-column="5"
                      data-column-index="5"
                    />
                </div>
              </div>
              <div class="col-6 col-md-2" >
                <button class="btn btn-label-terciary font-weight-bold ml-2  col-12  px-sm-2" onsubmit="void()" type="submit" @click="dateSearch(1)">
                  Ultimos 7 días
                </button>
              </div>
              <div class="col-6 col-md-2" >
                <button class="btn btn-label-terciary font-weight-bold ml-2  col-12  px-sm-2" onsubmit="void()" type="submit" @click="dateSearch(2)">
                  Ultimos 15 días
                </button>
              </div>
              <div class="col-6 col-md-2" >
                <button class="btn btn-label-terciary font-weight-bold ml-2  col-12  px-sm-2" onsubmit="void()" type="submit" @click="dateSearch(3)">
                  Mes pasado
                </button>
              </div>
              <div class="col-6 col-md-2" >
                <button class="btn btn-label-terciary font-weight-bold ml-2  col-12  px-sm-2" onsubmit="void()" type="submit" @click="dateSearch(4)">
                  Este mes
                </button>
              </div>
              <div class="col-6 col-md-1" >
                <button class="btn btn-label-secondary font-weight-bold ml-2 col-sm-12  px-sm-2" onsubmit="void()" type="submit" @click="resetFilter()">
                  Resetear
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
			<div class="card-datatable table-responsive">
				<table class="datatables-basic table" id="table">
					<thead>
						<tr>
              <th class="text-center"> N° de registro de la operación</th>
              <th class="text-center"> Fecha de la operación</th>
							<th class="text-center"> Departamento </th>
							<th class="text-center"> Provincia </th>
							<th class="text-center"> Distrito </th>
              <th class="text-center"> Tipo de documento EJE</th>
              <th class="text-center"> N° documento EJE</th>
              <th class="text-center"> Apellido paterno EJE </th>
              <th class="text-center"> Apellido materno EJE </th>
              <th class="text-center"> Nombres EJE </th>
              <th class="text-center"> País de nacionalidad EJE </th>
              <th class="text-center"> Ocupación EJE </th>
              <th class="text-center"> Persona a favor de quien se realiza la operación </th>
              <th class="text-center"> Nº de documento BEN </th>
              <th class="text-center"> Apellido paterno o razón social BEN</th>
              <th class="text-center"> Apellido materno BEN </th>
              <th class="text-center"> Nombres BEN </th>
              <th class="text-center"> Ocupación BEN </th>
              <th class="text-center"> Tipo de fondo con el que se realizó la operación</th>
              <th class="text-center"> Tipo de operación </th>
              <th class="text-center"> Origen de los fondos o activos involucrados en la operación  </th>
              <th class="text-center"> Moneda origen </th>
              <th class="text-center"> Monto origen </th>
              <th class="text-center"> Moneda destino </th>
              <th class="text-center"> Monto destino </th>
              <th class="text-center"> N° de comprobante de pago (incluir N° de serie) </th>
              <th class="text-center"> Tipo de cambio </th>
              <th class="text-center"> Tipo de documento BEN </th>
              <th class="text-center"> País de nacionalidad BEN </th>
              <th class="text-center"> Tipo de documento ORD </th>
              <th class="text-center"> Nº documento ORD</th>
              <th class="text-center"> Apellido paterno o razón social ORD </th>
              <th class="text-center"> Apellido materno ORD </th>
              <th class="text-center"> Nombres ORD </th>
              <th class="text-center"> País de  nacionalidad ORD </th>
              <th class="text-center"> Ocupación ORD </th>
              
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

</template>
<style lang="scss">
	@import "@/assets/libs/datatables-bs5/datatables.bootstrap5.scss";
  @import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
  @import "@/assets/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.scss";
  @import "@/assets/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.scss";
  @import "@/assets/libs/datatables-responsive-bs5/responsive.bootstrap5.scss";
  @import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
  @import "@/assets/libs/flatpickr/flatpickr.scss";
</style>
<script>
    import { GET_SYSTEM_CONFIGURATION_VALUE } from "@/core/services/store/configuration.module";

    import { bootstrap } from "@/concept/bootstrap";
    import flatpickr from "flatpickr";
    import { Spanish } from "flatpickr/dist/l10n/es.js"
    import moment from 'moment';
    export default {
        data() {
            return {
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: "",
                datePickSelector:'',
                tabled:'',
                statusLabel: {
                  0:	{ title: 'Cancelado', class: ' bg-label-danger' },
                  1:	{ title: 'Pendiente', class: ' bg-label-warning' },
                  2: 	{ title: 'Procesando', class: 'bg-label-primary' },
                  3: 	{ title: 'Finalizado', class: ' bg-label-success' },
                }
            }
        },
        mounted() {
            this.datepicker()
            this.getOperationsForSbs()
        },
        methods: {
          getOperationsForSbs(){
            const dataForSecretKey = new FormData();
            dataForSecretKey.append('id', 2) // KEY FOR API;
            this.$store
            .dispatch(GET_SYSTEM_CONFIGURATION_VALUE, dataForSecretKey).then((keyData)=>{
                this.tabled = new DataTable('#table', {
                  ajax: {
                    // url: 'https://cambio.dlsmoney.com/api/sbs-data-table',
                    url: 'http://127.0.0.1:8000/api/sbs-data-table',
                    type: "POST",
                    data: function ( data ) {
                        data.keyForApi = keyData.data.value
                        data.filter_start_date = document.querySelector('.start_date').value;
                        data.filter_end_date = document.querySelector('.end_date').value;
                        // data.filter_bank_id = document.getElementById('filter_table_bank').value
                        // data.filter_operation_number = document.getElementById('filter_table_operation_number').value
                        // data.filter_start_date = document.querySelector('.start_date').value;
                        // data.filter_end_date = document.querySelector('.end_date').value;
                    }
                  },
                  dataType:'json',
                  processing: true,
                  serverSide: true,
                  scrollX: true,
                  columnDefs: [
                    // { className: 'text-center',
                    //   targets: 0,
                    //   searchable: true,
                    //   visible: false,
                    //   render: function (data, type, full, meta) {
                        
                    //     return meta.row < 10 ? '0' + meta.row : meta.row
                    //   }
                    // },
                    {
                      className: 'text-center',
                      targets: 0,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        console.log(full)
                        return meta.row < 9 ? '0' + (meta.row+1) : meta.row
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 1,
                      searchable: true,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        let date = new Date(full.created_at)
                        return (
                        `${date.getDate() >= 10 ? date.getDate() : '0' + date.getDate()}/${date.getMonth() >= 9 ? (date.getMonth()+1) : '0' + (date.getMonth()+1)}/${date.getFullYear()} ` 
                        )
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 2,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.department_id + ': ' + full.bank_account_send.personal_account.department.name
                        : full.bank_account_send.company_account.department_id + ': ' + full.bank_account_send.company_account.department.name

                      }
                    },
                    {
                      className: 'text-center',
                      targets: 3,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.province_id + ': ' + full.bank_account_send.personal_account.province.name
                        : full.bank_account_send.company_account.province_id + ': ' + full.bank_account_send.company_account.province.name

                      }
                    },
                    {
                      className: 'text-center report__section-divider',
                      targets: 4,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.district_id + ': ' + full.bank_account_send.personal_account.district.name
                        : full.bank_account_send.company_account.district_id + ': ' + full.bank_account_send.company_account.district.name

                      }
                    },
                    {
                      className: 'text-center px-2 ',
                      targets: 5,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.document_type_id+': '+full.bank_account_send.personal_account.document_type.name 
                        : full.bank_account_send.company_account.document_type_lr_id +': '+ full.bank_account_send.company_account.document_type_lr.name
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 6,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.document_number
                        : full.bank_account_send.company_account.document_number_lr
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 7,
                      searchable: false,
                      visible: true,
                      
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        const fullSurname = full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.surname
                        : full.bank_account_send.company_account.surname_lr

                        let surname = fullSurname.split(' ')[0].length <= 3 
                        ? fullSurname.split(' ')[0] +' '+fullSurname.split(' ')[1]
                        : fullSurname.split(' ')[0];

                        return surname 
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 8,
                      searchable: false,
                      visible: false,
                      
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        const fullSurname = full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.surname
                        : full.bank_account_send.company_account.surname_lr

                        let surname = fullSurname.split(' ')[0].length <= 3 
                        ? fullSurname.split(' ')[0] +' '+fullSurname.split(' ')[1]
                        : fullSurname.split(' ')[0];

                        return  fullSurname.slice(surname.length)
                        
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 9,
                      searchable: false,
                      visible: true,
                      
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.name
                        : full.bank_account_send.company_account.name_lr
                        
                      }
                    },
                    {
                      className: 'text-center report__section-divider',
                      targets: 10,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.country_id +': '+full.bank_account_send.personal_account.country.name
                        : full.bank_account_send.company_account.country_id +': '+full.bank_account_send.company_account.country.name
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 11,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.ocupation_id +':'+full.bank_account_send.personal_account.ocupation.name
                        : full.bank_account_send.company_account.ocupation_lr_id +':'+full.bank_account_send.company_account.ocupation_lr.name
                      }
                    },
                    {
                      className: 'text-center px-2',
                      targets: 12,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.personal_account != null 
                        ? '002: Persona Natural'
                        : '003: Persona jurídica/Ente jurídico domiciliado en el Perú'
                      }
                    },
                    {
                      className: 'text-center ',
                      targets: 13,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.document_number
                        : full.bank_account_receive.company_account.ruc
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 14,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        let fullSurname = full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.surname
                        : full.bank_account_receive.company_account.business_name

                        let surname = fullSurname.split(' ')[0].length <= 3 && full.bank_account_receive.personal_account != null 
                        ? fullSurname.split(' ')[0] +' '+fullSurname.split(' ')[1]
                        : fullSurname.split(' ')[0];

                        return full.bank_account_receive.personal_account != null  ? surname : fullSurname
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 15,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        let fullSurname = full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.surname
                        : ' '

                        let surname = fullSurname.split(' ')[0].length <= 3 && full.bank_account_receive.personal_account != null 
                        ? fullSurname.split(' ')[0] +' '+fullSurname.split(' ')[1]
                        : fullSurname.split(' ')[0];

                        return  full.bank_account_receive.personal_account != null ? fullSurname.slice(surname.length) : fullSurname

                      }
                    },
                    {
                      className: 'text-center',
                      targets: 16,
                      searchable: false,
                      visible: false,
                      
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.name
                        :' '
                        
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 17,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.ocupation_id +':'+full.bank_account_receive.personal_account.ocupation.name
                        : ' '
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 18,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  '004 Transferencia bancaria'	
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 19,
                      searchable: false,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  '003 Compraventa de divisas'	
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 20,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.other_fund_origin == null 
                        ? full.fund_origin_id + ': ' + full.fund_origin.name
                        : '099: '+full.other_fund_origin
                      }
                    },
                    {
                      className: 'text-center ',
                      targets: 21,
                      searchable: false,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.coin_type_id == 1
                        ? 'PEN: Sol (Peruano)'
                        : 'USD: Dólar Estadounidense'
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 22,
                      searchable: false,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return $number_format(full.amount.toFixed(2))
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 23,
                      searchable: false,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.coin_type_id == 1
                        ? 'PEN: Sol (Peruano)'
                        : 'USD: Dólar Estadounidense'
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 24,
                      searchable: false,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return $number_format(full.change_amount.toFixed(2))
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 25,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return full.operation_number
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 26,
                      searchable: false,
                      visible: true,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return $number_format(full.exchange_rate.toFixed(3)) 
                      }
                    },
                    {
                      className: 'text-center p-0',
                      targets: 27,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.document_type_id +': '+full.bank_account_receive.personal_account.document_type.name 
                        : '007: RUC'
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 28,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_receive.personal_account != null 
                        ? full.bank_account_receive.personal_account.country_id +': '+full.bank_account_receive.personal_account.country.name
                        : full.bank_account_receive.company_account.country_id +': '+full.bank_account_receive.company_account.country.name
                      }
                    },
                    {
                      className: 'text-center p-0',
                      targets: 29,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.document_type_id +': '+full.bank_account_send.personal_account.document_type.name 
                        : '007: RUC'
                      }
                    },
                    {
                      className: 'text-center ',
                      targets: 30,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.document_number
                        : full.bank_account_send.company_account.ruc
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 31,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        let fullSurname = full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.surname
                        : full.bank_account_send.company_account.business_name

                        let surname = fullSurname.split(' ')[0].length <= 3 && full.bank_account_send.personal_account != null 
                        ? fullSurname.split(' ')[0] +' '+fullSurname.split(' ')[1]
                        : fullSurname.split(' ')[0];

                        return full.bank_account_send.personal_account != null  ? surname : fullSurname
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 32,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        let fullSurname = full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.surname
                        : ' '

                        let surname = fullSurname.split(' ')[0].length <= 3 && full.bank_account_send.personal_account != null 
                        ? fullSurname.split(' ')[0] +' '+fullSurname.split(' ')[1]
                        : fullSurname.split(' ')[0];

                        return  full.bank_account_send.personal_account != null ? fullSurname.slice(surname.length) : fullSurname

                      }
                    },
                    {
                      className: 'text-center',
                      targets: 33,
                      searchable: false,
                      visible: false,
                      
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.name
                        :' '
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 34,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.country_id +': '+full.bank_account_send.personal_account.country.name
                        : full.bank_account_send.company_account.country_id +': '+full.bank_account_send.company_account.country.name
                      }
                    },
                    {
                      className: 'text-center',
                      targets: 35,
                      searchable: false,
                      visible: false,
                      responsivePriority: 0,
                      render: function (data, type, full, meta) {
                        return  full.bank_account_send.personal_account != null 
                        ? full.bank_account_send.personal_account.ocupation_id +':'+full.bank_account_send.personal_account.ocupation.name
                        : '100 No aplica'
                      }
                    },

                  ],
                  language: {
                    sLengthMenu: '_MENU_',
                    search: '',
                    searchPlaceholder: 'Buscar...',
                    processing: "Procesando...",
                    lengthMenu: "Mostrar _MENU_ registros",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "Ningún dato disponible en esta tabla",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    info: "Mostrando _END_ de _TOTAL_ registros",
                    paginate: {
                      next: "Siguiente",
                      previous: "Anterior"
                    },
                    
                  },
                  order: [[0, 'desc']],
                  dom:
                    '<"row me-2"' +
                    '<"col-md-2"<"me-3"l>>' +
                    '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0 mt-3"B>>' +
                    '>t' +
                    '<"row mx-2"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',												
                  buttons: [
                    {
                      extend: 'collection',
                      className: 'btn btn-primary dropdown-toggle mx-3 text-white',
                      text: '<i class="ti ti-screen-share me-1 ti-xs text-white"></i>Exportar',
                      buttons: [
                        {
                          extend: 'excelHtml5',
                          text: '<i class="ti ti-file-text me-2" ></i>Excel',
                          className: 'dropdown-item',
                          title: 'Reporte de movimientos',
                          exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35],
                            // prevent avatar to be display
                            format: {
                              body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                  if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                  } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                  } else result = result + item.innerText;
                                });
                                return result;
                              }
                            }
                          },
                          customize: function( xlsx ) {
                              var sheet = xlsx.xl.worksheets['sheet1.xml'];
                              $('row:first c', sheet).attr( 's', '47' );
                              
                          }
                        },
                        {
                          extend: 'pdf',
                          text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                          className: 'dropdown-item',
                          title: 'Reporte de movimientos',
                          exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                            // prevent avatar to be display
                            format: {
                              body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                  if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                  } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                  } else result = result + item.innerText;
                                });
                                return result;
                              }
                            }
                          }
                        },
                      ]
                    },
                  ],
                            
                });
                document.querySelector('.dt-buttons').classList.add('justify-content-center')
                this.activeFilterInputs();
            })
            
          },
          activeFilterInputs(){
            setTimeout(() => {
              document.querySelectorAll('input.dt-input').forEach(item => {
                item.addEventListener('selectDates', event => {
                  this.filterColumn()
                })	
              })
            }, 200);
          },
          resetFilter(){
            this.$refs.start_date.value = '';
            this.$refs.end_date.value = '';
            this.$refs.date_range.value = '';
            this.datePickSelector.clear()
            this.tabled.draw('full-hold')
            this.tabled.clear();
          },
          datepicker() {
            setTimeout(() => {
              let rangePickr = document.querySelector('.flatpickr-range');
              this.datePickSelector = flatpickr(rangePickr,{
                  mode: 'range',
                  dateFormat: 'd/m/Y',
                  maxDate: "today",
                  orientation: 'auto left',
                  locale: Spanish,  
                  onClose: function (selectedDates,) {
                    var startDate = '',
                    endDate = new Date();

                    if (selectedDates[0] != undefined) {
                      startDate = moment(selectedDates[0]).format('YYYY-MM-DD');
                      document.querySelector('.start_date').value = startDate+ ' 00:00:00';

                    }
                    if (selectedDates[1] != undefined) {
                      endDate = moment(selectedDates[1]).format('YYYY-MM-DD');
                      document.querySelector('.end_date').value= endDate + ' 23:59:00';

                    }
                    let event = new Event("selectDates")
                    rangePickr.dispatchEvent(event);
                  }
                }); 
            }, 300);
          },
          filterColumn() {
            this.tabled.clear();
            this.tabled.draw('full-hold');
          },
          dateSearch(value){

            switch (value) {
              case 1:
                this.lastDays(7)
                break;
              case 2:
                this.lastDays(15)  
                break;
              case 3:
                this.InMonth(-1)
                break;
              case 4:
                this.InMonth(0)
                break;
              default:
                break;
            }

            

          },
          InMonth(month){
            const fecha = new Date;
            const lastMonth = new Date(fecha.getFullYear(), fecha.getMonth(), 0)

            const whichStartMonth = month == 0 
            ? (fecha.getMonth()) < 9 ? '0' + (fecha.getMonth()+1) : (fecha.getMonth()+1) 
            : (fecha.getMonth()) < 10 ? '0' + (fecha.getMonth()) : (fecha.getMonth()) ;

            const whichEndtMonth = fecha.getMonth() < 10 
            ? '0' + (fecha.getMonth()) 
            : (fecha.getMonth());

            const startDateInput = `${fecha.getFullYear()}-${whichStartMonth}-01`;

            const endDateInput = month == 0 
              ? moment(new Date()).format('YYYY-MM-DD')
              : `${fecha.getFullYear()}-${whichEndtMonth}-${lastMonth.getDate()}`


            document.querySelector('.start_date').value= startDateInput + ' 00:00:00';
            document.querySelector('.end_date').value= endDateInput + ' 23:59:00';
            
            this.datePickSelector.setDate(
              [
                moment(startDateInput).format('DD-MM-YYYY'), 
                moment(endDateInput).format('DD-MM-YYYY')
              ], true)
              
            this.filterColumn()
          },
          lastDays(day){
            const dateFrom = new Date(Date.now() - (86400000*day)),
            dateTo   = new Date();

            document.querySelector('.start_date').value= moment(dateFrom).format('YYYY/MM/DD') + ' 00:00:00';
            document.querySelector('.end_date').value= moment(dateTo).format('YYYY/MM/DD') + ' 23:59:00';
            this.datePickSelector.setDate(
              [
                moment(dateFrom).format('DD/MM/YYYY'),
                moment(dateTo).format('DD/MM/YYYY')
              ], true)

            this.filterColumn()
          },
        },
    };
</script>