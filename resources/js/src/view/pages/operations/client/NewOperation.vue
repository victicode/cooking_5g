<template>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 ml-3" >
				<div class=" " >
					<div class="row" v-if="mainAlert">
						<div class="col-lg-10 col-md-10 col-sm-12">
							<b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}{{ redirectMessage == '' ? '' : redirectMessage + redirectMessageSeconds + ' segundos.' }}</b-alert>
						</div>
						<hr>
					</div>
						<!-- USER LIST -->
					<form onSubmit="return false" v-if="currentAccount.status == 1" >
						<div id="exchange" class=" card-custom gutter-b example example-compact row justify-content-center">
							<div class="col-md-7 col-xs-12 offset-md-1 " >

								
								<ul class="navbar-nav flex-row  ms-auto col-12 align-items-center justify-content-end">
									<li class="nav-item navbar-dropdown dropdown-user dropdown">
											<a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
													<button type="button" class="btn btn-outline-secondary text-secondary">
															<i class="fa-solid fa-user-group"></i>&nbsp; &nbsp; Cambiar de perfil
													</button>
											</a>
											<ul class="dropdown-menu dropdown-menu-end">
												<li v-if="personalAccounts.length > 0">
													<div class="dropdown-divider" ></div>
												</li>
												<li class="text-center" v-if="personalAccounts.length > 0">
													<span class="fw-bold"> Cuentas personales</span>
												</li>
												<template v-for="account in personalAccounts" v-if="personalAccounts.length > 0">
													<li>
														<a class="dropdown-item" href="" @click="changeActiveAccount($event, account.id, 0)">
															<i class="ti me-2 ti-sm" :class="{ 'ti-user-check': activeAccountType == 0 && account.id == activeAccountId, 'ti-user': !(activeAccountType == 0 && account.id == activeAccountId)}"></i>
															<span class="align-middle" :class="{ 'active': activeAccountType == 0 && account.id == activeAccountId}">{{ account.name }} {{ account.surname }}</span>
														</a>
													</li>
												</template>
												<li v-if="companyAccounts.length > 0">
													<div class="dropdown-divider"></div>
												</li>
												<li class="text-center" v-if="companyAccounts.length > 0">
													<span class="fw-bold">Cuentas de Empresas</span>
												</li>
												<template v-for="account in companyAccounts" v-if="companyAccounts.length > 0">
													<li>
														<a class="dropdown-item" href="" @click="changeActiveAccount($event, account.id, 1)">
															<i class="ti me-2 ti-sm" :class="{ 'ti-user-check': activeAccountType == 1 && account.id == activeAccountId, 'ti-user': !(activeAccountType == 1 && account.id == activeAccountId)}"></i>
															<span class="align-middle" :class="{ 'active': activeAccountType == 1 && account.id == activeAccountId}" >{{ account.business_name }}</span>
														</a>
													</li>
												</template>
												<li>
													<div class="dropdown-divider"></div>
												</li>
											</ul>
									</li>
								</ul>
							</div>
							<!-- USER LIST/ -->
							<div class="card col-md-7 col-lg-5 col-xs-12  mb-4">
									<div class="card-body px-0 py-1">
										<div class="card-title header-elements">
										
											<div class="card-title-elements ms-auto">
												<div class="card-title-elements ms-auto">
													
												</div>
											</div>
										</div>
										<!-- calculadora-->
										<div class="card-header text-center">
											<h4 class="card-title mb-1 text-center">Comience a hacer cambio</h4>
											<small class="text-center">{{ DateFormat() }}</small>
										</div>
										
										<div class="card-body">
											<div >
												<div class="d-flex justify-content-center">
													<div class="col-5 mb-3">
															<div class="me-2">
																	<h6 class="mb-0 text-center ">Compramos Dólares</h6>
																	<small  class="text-muted d-block text-center badge  text-white purchase-rate">{{ purchase.toFixed(3) }}</small>
																</div>
													</div>
													<div class="col-5 border-start border-3 ps-2">
														<div class="me-2">
																<h6 class="mb-0 text-center ">Vendemos Dólares</h6>
																<small  class="text-muted d-block text-center sale-rate badge bg-label-secondary">{{ sale.toFixed(3) }}</small>
																</div>
													</div>
												</div>
												<div class="card-timer d-flex justify-content-between px-2 mb-3">
													<h6 class="fw-light">La tasa se actualizara en:</h6>
													<div class="d-flex w-25 justify-content-end">
														<i class="fa-solid mt-0-1 fa-hourglass-half fa-bounce"></i>
														<h6 class="fw-bold ms-1">{{ minutes.toString().length == 1 ? '0' + minutes : minutes }}:{{ seconds.toString().length == 1 ? '0' + seconds : seconds }}</h6>
													</div>
													
												</div>
												<div id="step-section-1" class="position-relative h-195">
														<div class="d-flex  bg-primary convert__input up"  >
																<div class="col-6 ">
																<div class="">
																		<h6 class="mb-0 text-center text-white purchase-text">Tengo soles</h6>
																		<input  @keyup="calculadora()" onSubmit="false"   autocomplete="off"  name="sol" class="form-convert sol form-control-lg text-center text-white" type="text" placeholder="1,000"/>
																		</div>
																</div>
		
		
																<div class="col-6 d-flex align-items-center justify-content-center">
																<div class="">
																		
																		<h2 class="text-center text-white mb-0">S/</h2>
																		</div>
																</div>
														</div>
														
														<div class="d-flex  bg-warning convert__input down">
																<div class="col-6 ">
																	<div class="">
																		<h6 class="mb-0 text-center text-white sale-text">Quiero dólares</h6>
																		<input  @keyup="calculadora()" onSubmit="false"  autocomplete="off" disabled name="usd" class="form-convert usd form-control-lg text-center text-white" type="text" placeholder="1,000"/>
																	</div>
																</div>
																<div class="col-6 d-flex align-items-center justify-content-center">
																<div class="">
																		
																		<h2 class=" text-center text-white mb-0">$</h2>
																		</div>
																</div>
														</div>
														<div class="convert__change position-absolute">
																<div class="convert__change--content" @click="rotate($event)">
																<span class=" p-0 text-secondary "><i class="fa-solid fa-arrows-rotate"></i></span>
																</div>
														</div>
												</div>
												<div class="input-group mb-3">
													<input type="text" ref="coupon" class="form-control input-coupon border-end-0" placeholder="Agrega un cupón"  >
													<div class="input-group-append">
														<div class="btn btn-coupon border-start-0 rounded-end" >
															<button type="button" class="border-0 " ref="setcoupon"  onsubmit="false" onkeyup="false" @click="validateCoupon()" >Agregar</button>
														</div>
													</div>
												</div>
												<div class="d-flex mt-4">
													<div class="d-grid gap-4 col-lg-10 mx-auto">
															<button id="init-step" class="btn btn-primary btn-lg text-white" type="button" disabled @click="wizardForm()" ><i class="fa-solid fa-calculator"></i> &nbsp;&nbsp; Comenzar cambio</button>
													</div>
												</div>
											</div>
										</div>
										
										<!-- / calculadora -->
									</div>
							</div>
						</div>
						<form-operation :exchange=newOperation :count=countR v-if="wizardinit == true" @hiddenModal="hideModal()"></form-operation>
					</form>
					<div v-else-if="currentAccount.status == 0" class="text-center mt-5">
						<h4>Tu cuenta esta siendo verificada!</h4>
						<h6>Por los momentos no podrás realizar operaciones.</h6>
					</div>
					<div v-else class="text-center mt-5">
						<div class="w-100 border border-base border-end-0 border-start-0 d-flex justify-content-center p-4 align-items-center"  >
							<div class="spinner-border me-2 text-primary" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							<h3 class=" text-center m-0 text-primary">Cargando</h3>
						</div>
					</div>
				</div>
		</div>
		<div class="modal fade modal-lg" id="operations-desactive" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
				<div class="modal-content p-5 py-2">
					<div class="modal-body" >
						<button type="button" class="btn-close exchange-rate-close" @click="hideModal(); $router.push('/dashboard')"></button>
						<div class="text-center mb-4">
							<h4 class="mb-2 text-primary fw-bold">
								¡Estás fuera del horario de atención!
							</h4>
							<h6 class="px-md-5">Puedes realizar tu operación y transferir, pero te atenderemos a partir de mañana a las 09:30am</h6>
						</div>
						<div class="d-flex justify-content-center w-100">
							<button class="btn btn-primary text-white" @click="hideModal(); timer();">
								Continuar
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<style lang="scss">
		@import "@/assets/libs/bs-stepper/bs-stepper.scss";
</style>

<script>
	import { EXCHANGE_RATES_GET_LAST } from "@/core/services/store/exchange_rate.module";
	import { COUPON_VERIFY } from "@/core/services/store/coupon.module";
	import { ACCOUNTS_GET_ALL, SET_ACTIVE_ACCOUNT} from "@/core/services/store/account.module";
	import { mapGetters } from "vuex";
	import Cleave from 'cleave.js';
	import Stepper from 'bs-stepper';
	import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
	import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
	import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
	import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
	import formOperation from "@/view/pages/operations/client/formOperation.vue";
	import { bootstrap } from "@/concept/bootstrap";
	import { GET_SYSTEM_CONFIGURATION_VALUE } from "@/core/services/store/configuration.module";
	export default {
		data() {
			return {
				mainAlert: false,
				mainAlertVariant: "",
				mainAlertMessage: "",
				personalAccounts: [],
				companyAccounts: [],
				newOperation: {
					bank_account_send_id: -1,
					bank_account_receive_id: -1,
					amount: null,
					change_amount: null,
					exchange_rate: null,
					bank_account_transfer_id: -1,
					fund_origin_code: -1,
					operation_number: null,
					operationType:''
				},
				// Exchange rate & timer
				sale: 0,
				purchase: 0,
				minutes: 5,
				seconds: '00',
				redirectMessageSeconds: 5,
				redirectMessage: '',
				coinTypeSendAbbreviation: '',
				coinTypeReceiveAbbreviation: '',
				coinTypeSendData: null,
				coinTypeReceiveData: null,
				amountIsDisabled: true,
				operationType:'buy',
				wizardCreateDeal: "",
				wizardCreateDealForm:"",
				wizardCreateDealFormStep1:"",
				wizardCreateDealFormStep2: "",
				wizardCreateDealFormStep3:"",
				wizardCreateDealFormStep4:"",
				wizardCreateDealFormStep5:"",
				wizardCreateDealNext:"",
				wizardCreateDealPrev: "",
				wizardinit: false,
				timerEl:"",
				exchangeValidateI:"",
				stepWatcher:0,
				countR: 0,
				isDesactivate: '',
				openModal:'',
				currentAccount:'',
				validationStepper:'',
				
			}
		},
		components: {
			formOperation:formOperation
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
			// this.timer();
			this.getAllAccounts();
		},
		methods: {
			rotate(e) {
				let elex = document.querySelector('.fa-arrows-rotate');
				elex.classList.toggle('rotate')
				this.changeInputAndElements()
				
			},
			validateCoupon(){
				this.$refs.coupon.disabled = true;
				this.$refs.setcoupon.disabled = true;
				this.$refs.setcoupon.textContent = 'Validando...';
				
				this.$store
					.dispatch(COUPON_VERIFY,this.$refs.coupon.value)
					.then((data) => {
							if (data.code != 200){
								this.showMainAlert('danger', data.error)
								this.$refs.coupon.disabled = false;
								this.$refs.setcoupon.disabled = false;
								this.$refs.setcoupon.textContent = 'Agregar';
								return;
									
							}

							this.$refs.setcoupon.textContent = 'Agregado!';
							this.showMainAlert('success', 'Cupón agregado con exito.')
					})
					.catch((err) => {
						this.showMainAlert('danger', err);
						this.$refs.coupon.disabled = false;
						this.$refs.setcoupon.disabled = false;
						this.$refs.setcoupon.textContent = 'Agregar';
						return;
					});
			},
			wizardForm() {
				this.newOperation.limit = this.operationType == 'buy' ?  [(10000*this.sale),10000] : [10000, (10000*this.purchase)] 
				this.countR = this.countR + 1;

				this.exchangeValidateI.validate().then((data)=>{
					if(data == 'Valid'){
						let ex = document.querySelector('#exchange')
						this.wizardinit = true
						ex.classList.add('out-convert')
						clearInterval(this.timerEl)
						setTimeout(() => {
							
							let form = document.querySelector('#operationForm')
							ex.classList.add('position-absolute')
							form.classList.add('active')
							if(this.stepWatcher === 0){
								this.backCalculadora()
								// document.getElementById('deal-type').classList.remove("d-block", "visibility",)  
								this.wizardCreateDeal = document.querySelector('#wizardForm');
								this.wizardCreateDealForm = this.wizardCreateDeal.querySelector('#wizard-content');
								// Wizard steps
								
								this.wizardCreateDealFormStep2 = this.wizardCreateDealForm.querySelector('#account-details-vertical');
								this.wizardCreateDealFormStep3 = this.wizardCreateDealForm.querySelector('#personal-info-vertical');
								this.wizardCreateDealFormStep5 = this.wizardCreateDealForm.querySelector('#complete');
								// Wizard next prev button
								this.wizardCreateDealNext = [].slice.call(this.wizardCreateDealForm.querySelectorAll('.btn-next'));
								this.wizardCreateDealPrev = [].slice.call(this.wizardCreateDealForm.querySelectorAll('.btn-prev'));
								this.validationStepper = new Stepper(this.wizardCreateDeal, {
									linear: true,
									animation: true
								});	
		
								// Deal Type
								
							}else{
								form.classList.remove('h-0')
							}
							this.validateForm(this.validationStepper)
							this.stepWatcher = 1;
						},300);
						
					}
				})
			},
			backCalculadora(){
				document.querySelectorAll('.btn-backprev').forEach(item => {
						item.addEventListener('click', event => {
						let ex = document.querySelector('#exchange')
						
						let form = document.querySelector('#operationForm')
						
						form.classList.remove('active')
						setTimeout(()=>{
							form.classList.add('h-0')
							ex.classList.remove('out-convert')
							ex.classList.remove('position-absolute')
						},400)
						this.timer();
					})
				})
				
			},
			changeInputAndElements() {
        let buy = document.querySelector('.sol'),
          send = document.querySelector('.usd'),
          down = document.querySelector('.down'),
          sale = {
            'rate': document.querySelector('.sale-rate'),
            'text': document.querySelector('.sale-text'),
          },
          purchase = {
            'rate': document.querySelector('.purchase-rate'),
            'text': document.querySelector('.purchase-text'),
          };
        
        this.operationType = this.operationType == 'buy' ? 'sell' : 'buy' 
				this.newOperation.limit = this.operationType == 'buy' ? [(10000*this.sale),10000] : [10000, (10000*this.purchase)] 
				
        down.classList.remove('down')
        down.classList.add('up')
        this.newOperation.operationType = this.operationType
        if (this.operationType == 'buy') {
          down.nextElementSibling.classList.remove('up')
          down.nextElementSibling.classList.add('down')
          buy.removeAttribute('disabled');
          send.setAttribute('disabled', '');
          sale.text.textContent = ' Quiero dólares';
					sale.rate.classList.add('bg-label-secondary')
					purchase.rate.classList.remove('bg-label-secondary')
          purchase.text.textContent = "Tengo soles";
          buy.value = send.value;
					console.log(buy.value.length)

          send.value = buy.value.length > 0 
					? this.$func.numberFormat((parseFloat(buy.value.replace(/,/g, '')) / this.sale).toFixed(2)) 
					:'';
					this.newOperation.amount = parseFloat(buy.value.replace(/,/g, '')).toFixed(2)
					this.newOperation.change_amount = parseFloat(send.value.replace(/,/g, '')).toFixed(2)
          
        } else {
          down.previousElementSibling.classList.remove('up')
          down.previousElementSibling.classList.add('down')
					sale.rate.classList.remove('bg-label-secondary')
					purchase.rate.classList.add('bg-label-secondary')
          send.removeAttribute('disabled');
          buy.setAttribute('disabled', '');
          sale.text.textContent = 'Tengo dólares';
          purchase.text.textContent = "Quiero soles";
          send.value = buy.value;
					console.log(send.value.length)
          buy.value = send.value.length > 0 
					? this.$func.numberFormat((parseFloat(send.value.replace(/,/g, '')) * this.purchase).toFixed(2))
					:'';
					this.newOperation.amount = parseFloat(send.value.replace(/,/g, '')).toFixed(2)
					this.newOperation.change_amount = parseFloat(buy.value.replace(/,/g, '')).toFixed(2)
          
        }
				
        this.newOperation.exchange_rate = this.operationType == 'buy' ? this.sale : this.purchase ;
      },
			formatMountInput() {
				var cleave = new Cleave('.sol', {
						numeral: true,
						numeralThousandsGroupStyle: 'thousand'
				});
				var cleave = new Cleave('.usd', {
						numeral: true,
						numeralThousandsGroupStyle: 'thousand'
				});
			},
			calculadora() {
				let buy = document.querySelector('.sol');
				let send = document.querySelector('.usd');
				if (this.operationType =='buy') {
					
					send.value = this.$func.numberFormat((parseFloat(buy.value.replace(/,/g, '')) / this.sale).toFixed(2))

					this.newOperation.amount = parseFloat(buy.value.replace(/,/g, '')).toFixed(2)
					this.newOperation.change_amount = parseFloat(send.value.replace(/,/g, '')).toFixed(2)
				} else {
					buy.value= this.$func.numberFormat((parseFloat(send.value.replace(/,/g, '')) * this.purchase).toFixed(2))

					this.newOperation.amount = parseFloat(send.value.replace(/,/g, '')).toFixed(2)
					this.newOperation.change_amount = parseFloat(buy.value.replace(/,/g, '')).toFixed(2)
				}

				this.newOperation.exchange_rate = this.operationType == 'buy' ?  this.sale : this.purchase;
				this.newOperation.operationType =  this.operationType
			},
			DateFormat(){
				let date        = new Date();
				let daysOfWeek  = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
				let MonthOfYear = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto','Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
				return `${daysOfWeek[date.getDay()]} ${date.getDate()} de ${MonthOfYear[date.getMonth()]} ${date.getFullYear()}`
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
			changeActiveAccount (e, id, type) {
				e.preventDefault();

				let data = {
						active_account_type: type,
						active_account: id
				};

				this.$store
						.dispatch(SET_ACTIVE_ACCOUNT, data)
						.then((data) => {
								if (data.code != 200){
										this.showMainAlert('danger', 'Error desconocido al cambiar la cuenta activa.')
										return;
								}

								// close modal
								this.showMainAlert('success', 'Perfil activo cambiado correctamente!')
								this.redirectTimer(' . Recargando página en: ');
						})
						.catch((err) => {
								this.showMainAlert('danger', err)
						});
			},
			showSavedAlert(variant, message) {
				this.savedAlertVariant = variant;
				this.savedAlertMessage = message;
				this.savedAlert = true;
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
									_this.redirectMessageSeconds =_this.redirectMessageSeconds  - 1;
							}
					}, 1000);
			},
			getAllAccounts () {
					this.$store
						.dispatch(ACCOUNTS_GET_ALL)
						.then((data) => {
								if (data.code != 200){
										this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
										return;
								}

								this.personalAccounts = data.data.personalAccounts;
								for (let i = 0; i < this.personalAccounts.length; i++) {
										let label = this.personalAccounts[i].name + ' ' + this.personalAccounts[i].surname;
										let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
										this.personalAccounts[i].label = matches.join('.'); // J.S.O.N
								}

								this.companyAccounts = data.data.companyAccounts;
								for (let i = 0; i < this.companyAccounts.length; i++) {
										let label = this.companyAccounts[i].business_name;
										let matches = label.match(/\b(\w)/g); // ['J','S','O','N']
										this.companyAccounts[i].label = matches.join('.'); // J.S.O.N
								}
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
								this.currentAccount.fullName = `${item.name} ${item.surname}`
						}
						})
				}
				this.currentAccount.type = this.activeAccountType;
				this.$store.currentAccount = this.currentAccount;
				this.Isdesactivate()

			},
			showMainAlert(variant, message) {
					this.mainAlertVariant = variant;
					this.mainAlertMessage = message;
					this.mainAlert = true;
					window.scrollTo(0,0);
			},
			hideModal(){
				this.openModal.hide();
				setTimeout(() => {
					let trashElement = document.querySelectorAll('.modal-backdrop');
					trashElement.forEach((item)=>{
						document.querySelector('body').removeChild(item);
					})
				}, 200);
				
			
			},
			Isdesactivate(){
				const data = new FormData();
				data.append('id', 1);
				this.$store
					.dispatch(GET_SYSTEM_CONFIGURATION_VALUE, data)
					.then((data) => {
						this.isDesactivate = data.data.value
						setTimeout(() => {
							if (this.isDesactivate == '0') {
								this.showModal('operations-desactive')
							} else {
								if (this.currentAccount.status == 1) {
									
									this.formatMountInput();
									this.exchangeValidate();
									this.timer();
								}
							}
						}, 300);
					})
			},
			headerEmit(id){
				this.isDesactivate = id;
				setTimeout(() => {
					if (this.isDesactivate == '0') {
						this.showModal('operations-desactive')
					}
				}, 200);
			},
			showModal(id) {
				this.openModal = new bootstrap.Modal(document.getElementById(id), {
					keyboard: false,
					backdrop:'static'
				})
				this.openModal.show()
			},
			hideMainAlert () {
					this.mainAlertVariant = '';
					this.mainAlertMessage = '';
					this.mainAlert = false;
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
			validateForm(stepper) {
				this.wizardCreateDealPrev.forEach(item => {
					item.addEventListener('click', event => {
						switch (stepper._currentIndex) {
							case 3:
								stepper.previous();
								break;

							case 2:
								stepper.previous();
								break;

							case 1:
								stepper.previous();
								break;

							case 0:

							default:
								break;
						}
					});
				});

				const declaracion = formValidation(document.querySelector('#declaracionj'), {
					fields: {
						// * Validate the fields here based on your requirements
						agree:{
							validators: {
								notEmpty: {
									message: 'Debes aceptar la declaración jurada'
								},
							}
						},

					},
					plugins: {
						trigger: new Trigger(),
						submitButton: new SubmitButton(),
					}
				}).on('core.form.valid', () => {
					document.getElementById('declaracion-button').disabled = false
				}).on('core.field.valid', () => {
					document.getElementById('declaracion-button').disabled = false
				}).on('core.form.invalid', () => {
					document.getElementById('declaracion-button').disabled = true
				}).on('core.field.invalid', () => {
					document.getElementById('declaracion-button').disabled = true
				});

				const FormValidation2 = formValidation(this.wizardCreateDealFormStep2, {
					fields: {
						// * Validate the fields here based on your requirements
						bankSendInput:{
							validators: {
								notEmpty: {
									message: 'Este campo no puede quedar vacio'
								},
								greaterThan: {
									message: 'The value must be greater than or equal to 18',
									min: 0,
								}
							}
						},
						bankRecivedInput:{
							validators: {
								notEmpty: {
									message: 'Este campo no puede quedar vacio'
								},
								greaterThan: {
									message: 'The value must be greater than or equal to 18',
									min: 0,
								}
							}
						},

					},
					plugins: {
						trigger: new Trigger(),
						submitButton: new SubmitButton()
					}
				}).on('core.form.valid', () => {
					document.getElementById('step2').disabled = false
					stepper.next();
				}).on('core.field.valid', () => {
					
					document.getElementById('step2').disabled = false
				}).on('core.form.invalid', () => {
					
					document.getElementById('step2').disabled = true
				}).on('core.field.invalid', () => {
					
					document.getElementById('step2').disabled = true
				});
				// Deal Usage
				const FormValidation3 = formValidation(this.wizardCreateDealFormStep3, {
					fields: {
						// * Validate the fields here based on your requirements
						bankToTransferInput:{
							validators: {
								notEmpty: {
									message: 'Este campo no puede quedar vacio'
								},
								greaterThan: {
									message: 'The value must be greater than or equal to 18',
									min: 0,
								}
							}
						}
					},
					plugins: {
						trigger: new Trigger(),
						// bootstrap: new Bootstrap({
						// 	// Use this for enabling/changing valid/invalid class
						// 	// eleInvalidClass: '',
						// 	eleValidClass: '',
						// 	rowSelector: '.valid-item'
						// }),
						submitButton: new SubmitButton()
					}
				}).on('core.form.valid', () => {
					document.getElementById('step3').disabled = false
					stepper.next();
				}).on('core.field.valid', () => {
					document.getElementById('step3').disabled = false
					
				}).on('core.form.invalid', () => {
					document.getElementById('step3').disabled = true
					
				}).on('core.field.invalid', () => {
					document.getElementById('step3').disabled = true
					
				});

				// Deal Usage
				const FormValidation5 = formValidation(this.wizardCreateDealFormStep5, {
					fields: {
						operation_number:{
							validators: {
								notEmpty: {
									message: 'Este campo no puede quedar vacio'
								},
								regexp: {
									regexp: /^[0-9]+$/i,
									message: 'Este solo admite valores numericos'
								}
							}
						},
						vaucher:{
							validators: {
                notEmpty: {
                  message: ' Tienes que subir un archivo como comprobante'
                },
                file: {
                  extension: 'jpeg,jpg,PNG,png',
                  type: 'image/jpeg,image/png',
                  message: 'Solo puedes subir archivos .jpg, .jpeg, .png',
                }
              }
						}
					},
					plugins: {
						trigger: new Trigger(),
						bootstrap: new Bootstrap({
							// Use this for enabling/changing valid/invalid class
							// eleInvalidClass: '',
							eleValidClass: '',
							rowSelector: '.valid-item'
						}),
						submitButton: new SubmitButton()
					}
				}).on('core.form.valid', () => {
					document.getElementById('step5').disabled = false;
					this.showModal('confirm')
					stepper.next();
				}).on('core.field.valid', () => {
					document.getElementById('step5').disabled = false
				}).on('core.form.invalid', () => {
					document.getElementById('step5').disabled = true
				}).on('core.field.invalid', () => {
					document.getElementById('step5').disabled = true
				});

				this.wizardCreateDealNext.forEach(item => {
					item.addEventListener('click', event => {
						// When click the Next button, we will validate the current step
						switch (stepper._currentIndex) {
							case 0:
								FormValidation2.validate();
								break;

							case 1:
								FormValidation3.validate();
								break;

							case 2:
								FormValidation5.validate();
								break;


							default:
								break;
						}
					});
				});

				
			
			},
			exchangeValidate() {
				this.wizardCreateDealFormStep1 = document.querySelector('#step-section-1')
				
					this.exchangeValidateI = formValidation(this.wizardCreateDealFormStep1, {
					fields: {
						// * Validate the fields here based on your requirements
						sol: {
							validators: {
								notEmpty: {
									message: 'El campo del monto en soles no puede quedar vacio'
								},
								regexp: {
									regexp: /^[0-9.,]+$/i,
									message: 'Este campo no admite caracteres especiales'
								}
							}
						},
						usd: {
							validators: {
								notEmpty: {
									message: 'El campo del monto en usd no puede quedar vacio'
								},
								regexp: {
									regexp: /^[0-9.,]+$/i,
									message: 'Este campo no admite caracteres especiales'
								}
							}
						}

					},
					plugins: {
						trigger: new Trigger(),
						submitButton: new SubmitButton()
					}
				}).on('core.form.valid', () => {
					document.getElementById('init-step').disabled = false
				}).on('core.field.valid', () => {
					document.getElementById('init-step').disabled = false
				}).on('core.form.invalid', () => {
					document.getElementById('init-step').disabled = true;
					alert('Los campos de monto en soles y usd no puede quedar vacio')
				}).on('core.field.invalid', () => {
					document.getElementById('init-step').disabled = true
				});
				
			},
			
			
		},
		computed: {
			...mapGetters(["currentUser"]),
			
		},
		created() {
			this.getLastExchangeRate();
		},
	};
</script>