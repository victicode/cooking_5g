<template>
	<div class="col-lg-12 mb-12 mb-lg-0 mt-4">
			<div class="card h-100">
				<div class="card-header d-flex justify-content-between">
					<h5 class="card-title m-0 me-2">Últimos usuario registrados</h5>
					
				</div>
				<div class="table-responsive">
					<table class="table table-borderless border-top" v-if="showOperation =='ready' " >
						<thead class="border-bottom">
							<tr>
								<th>NOMBRE COMPLETO / RAZÓN SOCIAL</th>
								<th>CORREO</th>
								<th class="text-center">TIPO DOCUMENTO</th>
								<th class="text-center">N° DE DOCUMENTO</th>
								<th class="w-110px">ESTADO</th>
							</tr>
						</thead>
						<tbody>
							<template v-for="account in Accounts">
								<tr>
									<td>
											<div class="d-flex justify-content-start align-items-center">
												<div class="last_user--img">
													<div class="avatar avatar-online me-3 d-none d-md-flex">
														{{ account.globalname.charAt(0) }}
													</div>
												</div>
												<div class="d-flex flex-column">
													<p class="mb-0 fw-semibold">{{account.globalname}}</p>
													<small class="text-muted">{{ account.type == 0 ? 'Cuenta Personal' : 'Cuenta Jurídica' }}</small>
												</div>
											</div>
									</td>
									<td>
										<p class="mb-0 fw-semibold">{{ account.user.email }}</p>
									</td>
									<td class="text-center">
										<p class="mb-0 fw-semibold ">{{ account.type == 0 ? account.document_type.name : 'RUC'}}</p>
									</td>
									<td class="text-center">
										<p class="mb-0 fw-semibold">{{ account.type == 0 ? account.document_number : account.ruc }}</p>
									</td>
									<td class="p-1">
										<span 
											class=" rounded-pill mx-1 mb-2" 
											:class="'badge '+ statusLabel[account.status].class"
											data-bs-toggle="tooltip" 
											data-bs-placement="top" :data-bs-title="statusLabel[account.status].title"
										>
											<i class="fa-solid fa-address-card fa-lg align-middle"></i>
											<!-- {{statusLabel[AllAccount[n-1].status].title}} -->
										</span>
										<span 
											data-bs-toggle="tooltip" 
											data-bs-placement="top" :data-bs-title="account.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'"
											class=" rounded-pill " 
											:class="{'badge bg-label-warning': account.user.email_verified_at == null, 'badge bg-label-success': account.user.email_verified_at != null, } "
										>
											<i class="fa-solid fa-envelope-circle-check fa-lg align-middle"></i>
											<!-- {{account.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
										</span>
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
	import { ACCOUNTS_GET_ALL_USER_BY_SEARCH } from "@/core/services/store/account.module";
  import { bootstrap } from "@/concept/bootstrap";
	
	export default {
		name: "lastUser",
		data() {
			return {
				mainAlert: false,
				mainAlertVariant: "",
				mainAlertMessage: "",
				operationDataAlert: false,
				operationDataAlertVariant: '',
				operationDataAlertMessage: '',
				allAccounts: [],
				Accounts: 'i',
				currentAccountid: '',
				showOperation:'load',
				isAdmin:false,
				statusLabel: {
					'-1':	{ title: 'Rechazado', class: ' bg-label-danger' },
					'0':	{ title: 'No verificado', class: ' bg-label-warning' },
					'1':	{ title: 'Verificado', class: ' bg-label-success' },
				}
			}
		},
		mounted() {
			
		},
		methods: {
			getAccounts() {
				let query = '?status=0&' 
				setTimeout(() => {
						this.$store
						.dispatch(ACCOUNTS_GET_ALL_USER_BY_SEARCH, query)
						.then((data) => {
                if (data.code != 200){
                    // this.showMainAlert('danger', 'Error desconocido al obtener las operaciones.')
                    return;
                }
								console.log(data.data.allAccounts)
								this.Accounts = data.data.allAccounts;
								this.showOperation = this.Accounts.length > 0 ? 'ready' : 'not-yet'
                setTimeout(()=>{	
									const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
              		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
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
			this.getAccounts();
		}
	};
</script>