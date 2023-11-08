<template>  
  <div class="modal fade scll-hidden modal-sm" id="addNewCCModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row justify-content-center">
      <div class="modal-content p-3 p-md-5 w-50">
        <div class="modal-body">
          <div class="row showSavedAlert" v-if="savedAlert">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
              </div>
          </div>
          <button type="button" class="btn-close" @click="closeModal(1,typeAction)"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Agregar nueva cuenta</h3>
            <p class="text-muted">La cuenta debe estar a tu nombre o a nombre de la empresa donde seas el representante legal.</p>
          </div>
          <form id="addNewCCForm" class="row g-3" onsubmit="return false">
            <BankTag :banks="banks"  @change="TagifyInput" ></BankTag>
            <div class="form-floating">
              <input
                type="text"
                class="form-control"
                id="floatingInput"
                placeholder="135 6321 5654 8781"
                aria-describedby="floatingInputHelp"
                ref="account_number" 
                autocomplete="off"
                maxlength="24"
                @keyup="checkInputNumber($event)"
                v-model="newBankAccount.account_number"
              />
              <label for="floatingInput">Número de cuenta</label>
              <div id="floatingInputHelp" class="form-text">
                Deben ser 20 números
              </div>
            </div>
            <div class="form-floating">
              <input
                type="text"
                class="form-control"
                placeholder="Ingresa el número de cuenta CCI"
                name="account_number_cci" 
                ref="account_number_cci" 
                id="cci"
                maxlength="24"
                autocomplete="off"
                @keyup="checkInputNumber($event)"
                v-model="newBankAccount.account_number_cci"
              />
              <label for="">Número de cuenta CCI</label>
              <div id="" class="form-text">
                
              </div>
            </div>
            <div class="mb-3 col-6">
              <label for="exampleFormControlSelect1" class="form-label">Tipo de cuenta</label>
              <select class="form-select" v-model="newBankAccount.bank_account_type_id">
                <option value="-1">Seleccione un tipo de cuenta</option>
                <option v-for="(bankAccountType, index) in bankAccountTypes" v-bind:value="bankAccountType.id" :key="`bank_account_type_${index}`">{{ bankAccountType.name }}</option>
              </select>
            </div>

            <div class="col-6">
              <label for="exampleFormControlSelect1" class="form-label">Moneda</label>
              <select class="form-select" v-model="newBankAccount.coin_type_id" @change="resetAlertMessage()">
                <option value="-1">Seleccione un tipo de moneda</option>
                <option v-for="(coinType, index) in coinTypes" v-bind:value="coinType.id" :key="`coin_${index}`">{{ coinType.name }}</option>
              </select>
            </div>
            <div>
              <label for="" class="form-label">Alias de la cuenta</label>
              <input
                type="text"
                class="form-control"
                id=""
                placeholder="Ej. Mi Bcp Dolares"
                aria-describedby="defaultFormControlHelp"
                name="alias" 
                ref="alias" v-model="newBankAccount.alias"  
                @keyup="checkInputText($event)"
              />
              <div id="defaultFormControlHelp" class="form-text">
                Ingresa un alias que te permita indentificar más rápido tu cuenta
              </div>
            </div>

            <div class="col-8" v-if="newBankAccount.own_account != 1" >
                <label for="" class="form-label">Tipo de documento del propietario</label>
                <select class="form-select" ref="nw_owner"  v-model="newBankAccount.owner_document_type_id" @change="queryPersonByDocumentNumber($refs.nebak.value); resetAlertMessage()">
                  <option value="-1">Seleccione un tipo de cuenta</option>
                  <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_type_${index}`">{{ documentType.name }}</option>
                </select>
            </div>

            <div class="col-12" v-if="newBankAccount.own_account != 1">
                <label for="" class="form-label"> Número de documento del propietario</label>
                <input
                  type="text"
                  class="form-control"
                  id=""
                  ref="nebak"
                  placeholder="Numero de documento de la persona propietaria de la cuenta" 
                  aria-describedby="defaultFormControlHelp"
                  v-model="newBankAccount.owner_document_number"
                  @keyup="checkInputNumber($event)"
                  @change="queryPersonByDocumentNumber($event.target.value)"
                />
                <div id="defaultFormControlHelp" class="form-text">
                  Número de documento del tercero titular
                </div>
            </div>

            <div class="col-12" v-if="newBankAccount.own_account != 1 " >
                <label for="" class="form-label">Nombre o razón social del dueño de la cuenta</label>
                <input
                  type="text"
                  class="form-control"
                  id="newPpr"
                  placeholder="Nombre de la persona propietaria de la cuenta"
                  aria-describedby="defaultFormControlHelp"
                  ref="newPpr"
                  @keyup="checkInputText($event)"
                  v-model="newBankAccount.owner_name"
                />
                <div id="defaultFormControlHelp" class="form-text">
                  Nombre o razón social del dueño de la cuenta
                </div>
            </div>
            <div class="form-check form-check-primary mt-3">
              <input class="form-check-input" type="checkbox" v-model="newBankAccount.own_account"  value="1" id="customCheckPrimary" checked />
              <label class="form-check-label" for="customCheckPrimary">Declaro que es mi cuenta personal o de la empresa donde soy el representante legal.</label>
            </div>

            
            <div class="col-12 text-center">
              <button id="send-button" type="submit" class="btn btn-primary me-sm-3 me-1 text-white" @click="storeBankAccountxx($event)">Agregar cuenta</button>
              <button
                @click="closeModal(1,typeAction)"
                type="reset"
                class="btn btn-label-dark btn-reset"
                data-bs-dismiss="modal"
                aria-label="Cerrar"
              >
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import {  BANK_ACCOUNTS_STORE } from "@/core/services/store/bank_account.module";
  import { BANKS_GET_SET } from "@/core/services/store/bank.module";
  import { BANK_ACCOUNT_TYPES_GET_ALL } from "@/core/services/store/bank_account_type.module";
  import { COIN_TYPES_GET_ALL } from "@/core/services/store/coin_type.module";
  import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
  import { bootstrap } from "@/concept/bootstrap";
  import { QUERY_PERSON_BY_DNI, QUERY_COMPANY_BY_RUC } from "@/core/services/store/peru_consult.module";
  import BankTag from "@/view/pages/bank_accounts/Banktag.vue";
  import Cleave from 'cleave.js';
  
    export default {
      name:'addBankModal',
      props:['user','typeAction'],

      data() {
          return {
              savedAlert: false,
              savedAlertVariant: '',
              savedAlertMessage: '',
              allBankAccounts: [],
              bankAccounts: [],
              userSelected:{},
              newBankAccount: {
                  bank_id: -1,
                  bank_account_type_id: -1,
                  coin_type_id: -1,
                  own_account: 1,
                  owner_document_type_id: -1,
                  authorized_deposit: 1,
              },
              selectedBankAccount: {},
              openModal: '',
              banks: [],
              bankAccountTypes: [],
              coinTypes: [],
              documentTypes: [],
              
          }
      },
      components: {
        BankTag
      },
      mounted() {
        this.userSelected= this.user;
      },
      methods: {
        TagifyInput(info) { 
          this.newBankAccount.bank_id = info[0].value
        },
        accountNumberFormat() { 
          setTimeout(function () {
            new Cleave(document.querySelector('#floatingInput'), {
              blocks: [4, 4, 4, 4, 4],
            });
            new Cleave(document.querySelector('#cci'), {
              blocks: [4, 4, 4, 4, 4],
            });
          },300)
          
          
        },
        checkInputNumber(e) {
          let input = e.srcElement;
          input.value = input.value.replace(/[^0-9 -]/, '');
        }, 
        checkInputText(e) {
          let input = e.srcElement;
          input.value = input.value.replace(/[^a-zA-Z .,´]/, '');
        }, 
        storeBankAccountxx(e) {
          const button = e.srcElement
          button.textContent='Cargando...'
          button.disabled=true
          // console.log(e)
          e.preventDefault();
          if(this.newBankAccount.bank_id == ''){
             button.textContent='Agregar cuenta'
             button.disabled=false
                 
            this.showSavedAlert('danger', 'Debe seleccionar el banco.')
              return;
          }
          if(this.newBankAccount.account_number == null || this.newBankAccount.account_number == ""){
             button.textContent='Agregar cuenta'
             button.disabled=false
                 
            this.showSavedAlert('danger', 'Debe ingresar el número de cuenta.')
              return;
          }
          if(this.newBankAccount.bank_account_type_id == -1){
             button.textContent='Agregar cuenta'
             button.disabled=false
                 
            this.showSavedAlert('danger', 'Debe seleccionar el tipo de cuenta.')
              return;
          }
          if(this.newBankAccount.coin_type_id == -1){
             button.textContent='Agregar cuenta'
             button.disabled=false
                 
            this.showSavedAlert('danger', 'Debe seleccionar el tipo de moneda.')
              return;
          }
          if(this.newBankAccount.alias == null || this.newBankAccount.alias == ""){
             button.textContent='Agregar cuenta'
             button.disabled=false
                 
            this.showSavedAlert('danger', 'Debe ingresar el alias.')
              return;
          }

          if (this.newBankAccount.own_account != 1) {
              this.newBankAccount.authorized_deposit = 1
              if(this.newBankAccount.owner_name == null || this.newBankAccount.owner_name == ""){
                button.textContent='Agregar cuenta'
                button.disabled=false
                 
                this.showSavedAlert('danger', 'Debe ingresar nombre del propietario.')
                  return;
              }
              if(this.newBankAccount.owner_document_type_id == -1){
                button.textContent='Agregar cuenta'
                button.disabled=false
                 
                this.showSavedAlert('danger', 'Debe seleccionar el tipo de documento.')
                  return;
              }
              if(this.newBankAccount.owner_document_number == null || this.newBankAccount.owner_document_number == ""){
                button.textContent='Agregar cuenta'
                button.disabled=false
                 
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

          this.newBankAccount.account_id = this.userSelected.id; 
          this.newBankAccount.account_type = this.userSelected.type;
          this.newBankAccount.active_account = this.userSelected.id; 

          this.$store
              .dispatch(BANK_ACCOUNTS_STORE, this.newBankAccount)
              .then((data) => {
                if (data.code != 201){
                  // console.log(data)
                    this.showSavedAlert('danger', data.error)
                    return;
                }
                // close modal
                // console.log(data)
                this.closeModal(2,this.typeAction)
              })
              .catch((err) => {
                  this.showSavedAlert('danger', err)
                  return;
              });
        },
        getBanks () {
          this.$store
              .dispatch(BANKS_GET_SET)
              .then((data) => {
                  if (data.code != 200){
                      this.showMainAlert('danger', 'Error desconocido al obtener todos los bancos.')
                      return;
                  }

                this.banks = data.data
                this.banks.map((item) => {
                  return item.value = item.id
                })
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
        queryPersonByDocumentNumber(documentNumber) {
          let documentType = this.$refs.nw_owner != undefined ?
            this.$refs.nw_owner.value:
            this.selectedBankAccount.owner_document_type_id;
          let el = this.$refs.nw_owner != undefined ?
            this.$refs.newPpr :
            this.$refs.EditPpr
            
          if ((documentType == 1 || documentType == 3 || documentType == 4) && documentNumber != '') {
            this.loadData(el)
            if (documentType == 1) {
              
              this.$store
              .dispatch(QUERY_PERSON_BY_DNI, documentNumber)
              .then((data) => {
                if (data.code != 200){
                  this.resetData(el)
                  this.showSavedAlert('danger', 'Tu número de documento de identidad no se encuentra en el registro')
                    return;
                }
                const personData = JSON.parse(data.data);
                let name = personData.nombres
                let surname  = personData.apellidoPaterno;
                let ssurname = personData.apellidoMaterno;

                if (this.$refs.nw_owner != undefined) {
                  this.newBankAccount.owner_document_number = name +' '+surname+' '+ssurname;
                } else {
                  this.selectedBankAccount.owner_name = name +' '+surname+' '+ssurname;
                }
                // this.$refs.sname.value = sname;
                // this.$refs.surname.value = surname;
                // this.$refs.ssurname.value = ssurname;
              })
              .catch((err) => {
                this.resetData(el)
                this.showSavedAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
              });
            } else {
              this.$store
              .dispatch(QUERY_COMPANY_BY_RUC, documentNumber)
              .then((data) => {
                if (data.code != 200){
                  this.resetData(el)
                  this.showSavedAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
                    return;
                }
                const personData = JSON.parse(data.data);
                let name = personData.razonSocial;
                if (this.$refs.nw_owner != undefined) {
                  this.newBankAccount.owner_document_number = name;
                } else {
                  this.selectedBankAccount.owner_name = name;
                }
              })
              .catch((err) => {
                this.resetData(el)
                this.showSavedAlert('danger','Tu número de documento de identidad no se encuentra en el registro')
              });
            }
            
          } else {
            
          }
        },
        resetData(el){
          el.classList.remove('readOnly')
          el.readOnly = false
          if (this.$refs.nw_owner != undefined) {
            this.newBankAccount.owner_document_number = '';
          } else {
            this.selectedBankAccount.owner_name = '';
          }

        },
        loadData(el){
          el.classList.add('readOnly')
          el.readOnly = true
          el.value = 'Cargando...';
          
        },
        resetAlertMessage(){
              this.savedAlertVariant = '';
              this.savedAlertMessage = '';
              this.savedAlert = false;
        },
        showSavedAlert(variant, message) {
          this.savedAlertVariant = variant;
          this.savedAlertMessage = message;
          this.savedAlert = true;
          document.getElementById('addNewCCModal').scrollTo(0,0);
        },
        
        closeModal(id = 0, type=0){
          this.$emit("closeAddModal",id, type);
        },
      },
      computed: {
          rows() {
              return this.bankAccounts.length
          },
      },
      created() {
        this.getBanks();
        this.getBankAccountTypes();
        this.getCoinTypes();
        this.getDocumentTypes();
        this.accountNumberFormat();
      },
    };
</script>