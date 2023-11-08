<template>
    <div class="modal fade modal-xl" id="ManualOperation" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        
        <div class="modal-content p-3 p-md-5 py-md-3">
          
          <div class="modal-body py-md-0 px-0">
            <button type="button" class="btn-close exchange-rate-close" @click="hiddeModal()"></button>
            
            <div class="">
              <div class="row" v-if="mainAlert">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
                </div>
              </div>
              <div class="card-header pt-3 mb-0  min-vh-0">
                <div class="row w-100 ms-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <h3 class="pt-4"><strong>Operación manual</strong></h3>
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row ">
                  <form onSubmit="return false" >
                    
                    <div id="exchange" class=" card-custom gutter-b example example-compact row justify-content-center align-items-center">
                      <div class="col-md-7 col-sm-12 ">
                        <div class="row">
                          <user-tag v-if="loadSend1" :accounts="AllUser" @change="selectUser" :idTag="'usersFind'" :labelTag="'Selecciona el usuario'"></user-tag>
                          <div class="col-lg-12 col-md-12 col-sm-12 mb-3 valid-item " v-if="loadSend3">
                            <label class="" for="dealTitle"><strong> Seleciona el tipo de cuenta</strong></label>
                            <select class="selectpicker w-100 select2 form-select"  name="originFound" ref="typeOperation" style="width: 100%!important" @change="setTypeAccount()">
                              <option value="-1">Seleciona el tipo de cuenta</option>
                              <option value="0">Cuenta Personal</option>
                              <option value="1">Cuenta De Empresa</option>
                            </select>
                          </div>
                          <account-tags v-if="loadSend2" :accounts="AllAccount" @change="selectAccount" :idTag="'accountsFind'" :labelTag="'Selecciona una cuenta'"></account-tags>
                        </div>
                        <div class="row justify-content-center" v-if="loadSend4">
                          <div class="col-sm-6 valid-item mb-5 mt-2">
                            <label><strong>Compra</strong></label>
                            <input
                              type="text"
                              autocomplete="off"
                              class="form-control"
                              id="basic-default-name"
                              name="operation_number" 
                              ref="operation_number"
                              v-model="purchase"
                              placeholder="Ingrese la tasa de compra "
                            
                            />
                          </div>
                          <div class="col-sm-6 valid-item mb-5 mt-2">
                            <label><strong>Venta</strong></label>
                            <input
                              type="text"
                              autocomplete="off"
                              class="form-control"
                              id="basic-default-name"
                              name="operation_number" 
                              ref="operation_number"
                              v-model="sale"
                              placeholder="Ingrese la tasa de venta"
                            
                            />
                          </div>
                        </div>
                      </div>
  
                      <!-- USER LIST/ -->
                      <div class=" col-md-5 col-sm-12  mb-4 px-4" v-if="loadSend4">
                          <div class="card-body px-0 py-1">
                            
                            <div class="card-body">
                              <div >
                                <div id="step-section-1" class="position-relative h-195">
                                    <div class="d-flex  bg-primary convert__input up"  >
                                        <div class="col-6 ">
                                        <div class="">
                                            <h6 class="mb-0 text-center text-white purchase-text">Tengo soles</h6>
                                            <input  @keyup="calculadora()" autocomplete="off"  name="sol" class="form-convert sol form-control-lg text-center text-white" type="text" placeholder="1,000"/>
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
                                            <input  @keyup="calculadora()" autocomplete="off" disabled name="usd" class="form-convert usd form-control-lg text-center text-white" type="text" placeholder="1,000"/>
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
                    <form-manual :exchange=newOperation :count=countR v-if="wizardinit == true" @hidde="hiddeModal" :users="selectedAccount" ></form-manual>
                  </form>
                </div>
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
  import { mapGetters } from "vuex";
  import Cleave from 'cleave.js';
  import Stepper from 'bs-stepper';
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import userTag from "@/view/pages/actions/manual-operation/tags/userTag.vue";
  import accountTags from "@/view/pages/actions/manual-operation/tags/accountTags.vue";
  import formManual from "@/view/pages/actions/manual-operation/formManual.vue";  
  import { ALL_USERS } from "@/core/services/store/auth.module";
  import { ACCOUNTS_GET_ALL_BY_USER } from "@/core/services/store/account.module";
  
  export default {
    name:'operationManual',
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
          operationType:''
        },
        // Exchange rate & timer
        sale: 0,
        purchase: 0,

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
        loadSend1: false,
        loadSend2: false,
        loadSend3: false,
        loadSend4: false,
        AllAccount: [],
        AllUser:[],
        selectedUser: {},
        selectedAccount: {
          type:0
        }
      }
    },
    components: {
      formManual:formManual,
      userTag: userTag,
      accountTags:accountTags
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
      this.getAllUser();
     
    },
    methods: {
      getAllUser () {
        this.$store
          .dispatch(ALL_USERS)
          .then((data) => {
              if (data.code != 200){
                  this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas peronales.')
                  return;
              }

              
              setTimeout(() => {
                this.AllUser = data.data
                this.AllUser.map(user => {
                  return user.value = user.id
                })
                this.loadSend1 = true
              },200);
                  
            })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
              
      },
      selectUser(user) { 
        this.loadSend3=false
        this.loadSend4=false
        this.loadSend2=false
        if (Object.keys(user).length  > 0) {
          this.selectedUser = user[0]
          this.loadSend3=true
        }
      },
      selectAccount(bank) { 
        this.loadSend4=false
        if (Object.keys(bank).length  > 0) {
          this.selectedAccount = bank[0]
          this.selectedAccount.type = this.$refs.typeOperation.value
          this.loadSend4=true
          setTimeout(() => {
            this.formatMountInput();
            this.exchangeValidate();
          }, 200);
        }
      },
      rotate(e) {
        let elex = document.querySelector('.fa-arrows-rotate');
        elex.classList.toggle('rotate')
        this.changeInputAndElements()
        
      },
      getAllAccounts() {
        this.hideMainAlert()
						this.$store
							.dispatch(ACCOUNTS_GET_ALL_BY_USER, this.selectedUser)
							.then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger', 'Error desconocido al obtener todos los perfiles.')
                    return;
                }
                
                this.AllAccount = data.data.activeAccountType == 0 ? data.data.personalAccounts : data.data.companyAccounts
                
                if(this.AllAccount.length > 0){
                  this.AllAccount.map((account) => {
                    return account.value = account.id
                  })
                  setTimeout(() => {
                    this.loadSend2=true
                  },100)
                }else{
                  this.showMainAlert('danger', `Este usuario no poseé cuentas ${data.data.activeAccountType == 0 ? 'personales' : 'de empresas'} registradas`)
                }
							})
							.catch((err) => {
									this.showMainAlert('danger', err)
							});
      },
      setTypeAccount() {
        this.loadSend4=false
        this.loadSend2=false
        this.selectedUser.operationType = this.$refs.typeOperation.value
        setTimeout(() => {
          this.getAllAccounts()
        },100)
      },
      wizardForm() {
					this.countR = this.countR + 1;

					this.exchangeValidateI.validate().then((data)=>{
						if(data == 'Valid'){
							let ex = document.querySelector('#exchange')
							this.wizardinit = true
							ex.classList.add('out-convert')
			
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
									let validationStepper = new Stepper(this.wizardCreateDeal, {
										linear: true,
										animation: true
									});	
			
          				// Deal Type
                  this.validateForm(validationStepper)
									

									
									
								}else{
									form.classList.remove('h-0')
								}
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
      // changeInputAndElements() {
      //   let buy = document.querySelector('.sol'),
      //     send = document.querySelector('.usd'),
      //     down = document.querySelector('.down'),
      //     sale = {
      //       'rate': document.querySelector('.sale-rate'),
      //       'text': document.querySelector('.sale-text')
      //     },
      //     purchase = {
      //       'rate': document.querySelector('.purchase-rate'),
      //       'text': document.querySelector('.purchase-text')
      //     };
        
      //   this.operationType = this.operationType == 'buy' ? 'sell' : 'buy' ;
      //   down.classList.remove('down')
      //   down.classList.add('up')
      //   this.newOperation.operationType = this.operationType
      //   if (this.operationType == 'buy') {
      //     down.nextElementSibling.classList.remove('up')
      //     down.nextElementSibling.classList.add('down')
      //     buy.removeAttribute('disabled');
      //     send.setAttribute('disabled', '');
      //     sale.text.textContent = ' Quiero dólares';
      //     purchase.text.textContent = "Tengo soles";
      //     buy.value = send.value;
      //     send.value = this.$func.numberFormat((parseFloat(buy.value.replace(/,/g, '')) / this.purchase).toFixed(2));
          
      //   } else {
      //     down.previousElementSibling.classList.remove('up')
      //     down.previousElementSibling.classList.add('down')
      //     send.removeAttribute('disabled');
      //     buy.setAttribute('disabled', '');
      //     sale.text.textContent = 'Tengo dólares';
      //     purchase.text.textContent = "Quiero soles";
      //     send.value = buy.value;
      //     buy.value = this.$func.numberFormat((parseFloat(send.value.replace(/,/g, '')) * this.sale).toFixed(2));
          
          
      //   }
        
      // },
      changeInputAndElements() {
        let buy = document.querySelector('.sol'),
          send = document.querySelector('.usd'),
          down = document.querySelector('.down'),
          sale = {
            'text': document.querySelector('.sale-text'),
          },
          purchase = {
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
				
        this.newOperation.exchange_rate = this.operationType == 'buy' ? this.purchase : this.sale ;
        console.log(this.newOperation)
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
      
      showSavedAlert(variant, message) {
      this.savedAlertVariant = variant;
      this.savedAlertMessage = message;
      this.savedAlert = true;
      },
      showMainAlert(variant, message) {
          this.mainAlertVariant = variant;
          this.mainAlertMessage = message;
          this.mainAlert = true;
          window.scrollTo(0,0);
      },

      hideMainAlert() {
          this.mainAlertVariant = '';
          this.mainAlertMessage = '';
          this.mainAlert = false;
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
                // FormValidation5.validate();
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
      hiddeModal(){
        this.$emit("click");
      }
      
    },
    computed: {
      ...mapGetters(["currentUser"]),
    },
    created() {
    },
  };
</script>