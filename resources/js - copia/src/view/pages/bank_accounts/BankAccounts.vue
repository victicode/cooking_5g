<template>
  <div>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inicio / </span> Mis cuentas</h4>
    <div class="row" v-if="mainAlert">
        <div class="col-lg-10 col-md-10 col-sm-12 new-alerts">
          <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
        </div>
        <div class="col-lg-3 col-md-3 restore-button" @click="restoreBankAccount(bankAccountIdToDelete)" v-if="mainAlertMessage=='Cuenta bancaria eliminada correctamente!'">
          <button class="btn btn-success me-sm-3 me-1 text-white"> Restaurar Cuenta</button>
        </div>
        <hr>
    </div>
    <div class="row">
      <!-- User Content -->
      <div class="col-xl-12 col-lg-12 col-sm-12 order-0 order-md-1">
        <!-- Payment Methods -->
        <div class="card card-action mb-4">
          <div class="card-header align-items-center">
            <h5 class="card-action-title mb-0">Mis cuentas</h5>
            <div class="card-action-element">
              <button
                class="btn btn-primary btn-sm text-white"
                type="button"
                @click="showModal('addNewCCModal')"
              >
                <i class="ti ti-plus ti-xs me-1"></i>Agregar nueva cuenta
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="added-cards">
              <template v-for="account in bankAccounts">
                <div class="cardMaster border p-3 rounded mb-3 position-relative">
                  <div class="d-flex justify-content-between flex-sm-row flex-column">
                    <div class="card-information">
                      <img 
                        :src="account.bank.logo"
                        @error="getDefaultIconBank($event)"
                        class=" img-fluid"
                        width="80"
                        alt="Master Card"
                      />
                      <h6 class="mb-0">
                        A nombre: 
                        {{ 
                          account.owner_name != null ? 
                            account.owner_name : 
                            account.personal_account != null ?
                            getFullName(account.personal_account)  :
                            account.company_account.business_name
                        }}
                      </h6>
                      <h6 class="mb-2 pt-1">Alias: {{ account.alias }}</h6>
                      
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Número de cta.</span>
                      </span>
                      <input type="text" name="clipboard" class="d-none" :ref="'clone'+account.id" :value="account.account_number">
                      <span class="card-number">{{account.account_number}}</span>
                      <span class="clipboard-zone">
                        <button
                          class="clipboard-btn btn  me-1"
                          @click="copyOnClipboard($event)"   data-bs-toggle="tooltip" data-bs-placement="top" title="Copiar"  
                        >
                          <i class="fa-regular fa-paste fa-lg text-primary" :data-key="'clone'+account.id" ></i>
                          <div class="tooltip-text" role="tooltip">Copiar</div>
                        </button>
                        
                      </span>
                    </div>
                    <div class="d-flex flex-column text-start text-lg-end">
                      <div class="d-flex order-sm-0 order-1 mt-3 justify-content-end">
                        <button
                          class="btn btn-label-secondary text-primary me-3"
                          @click="setSelectedBankAccount(account.id); showModal('editCCModal')"
                        >
                        <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-label-dark" @click="setBankAccountIdToDelete(account.id); showModal('deleteModal')" title="Editar cuenta bancaria">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                      
                      <small class="mt-sm-auto mt-2 order-sm-1 order-0 ">Cuenta {{ account.bank_account_type.name }} <span class="h6 mb-0 text-primary ms-1"> {{ account.coin_type.name }}</span></small>
                    </div>
                  </div>
                  <div v-if="account.bank.status == 0" class="banco-desabilitado d-flex flex-column justify-content-center align-items-center">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                      <div>
                        <h3><i class="fa-solid fa-triangle-exclamation text-secondary"></i></h3>
                      </div>
                      <div class="mt-1 text-center">
                        <h5 class="mb-0 text-white">Las transacciones con este se encuentra de momento inactivas</h5> 
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
        <!--/ Payment Methods -->     
      </div>
      <!--/ User Content -->
    </div>
    <!-- Modal -->
    <!-- Editar Cuenta -->
    <div class=" modal fade" id="editCCModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
          <div class="row showSavedAlert" v-if="savedAlert">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
            </div>
          </div>
          <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="text-center mb-4">
                <h3 class="mb-2">Editar Cuenta bancaria</h3>
                <p class="text-muted">Edite la información guardada de su cuenta bancaria</p>
              </div>
              <form id="editCCForm" class="row g-3" >
                <div class="col-12">
                  <label class="form-label w-100" for="modalEditCard">Número de cuenta</label>
                  <div class="input-group input-group-merge">
                    <input
                      id="modalEditCard"
                      name="modalEditCard"
                      class="form-control credit-card-mask-edit"
                      type="text"
                      maxlength="24"
                      placeholder="4356 3215 6548 7898"
                      v-model="selectedBankAccount.account_number"
                      aria-describedby="modalEditCard2"
                    />
                    <span class="input-group-text cursor-pointer p-1" id="modalEditCard2"
                      ><span class="card-type-image"></span
                    ></span>
                  </div>
                </div>
                <div class="col-6">
                  <label for="exampleFormControlSelect1" class="form-label">Tipo de cuenta</label>
                  <select class="form-select"  v-model="selectedBankAccount.bank_account_type_id" @change="resetAlertMessage()">
                    <option value="-1">Seleccione un tipo de cuenta</option>
                    <option v-for="(bankAccountType, index) in bankAccountTypes" v-bind:value="bankAccountType.id" :key="`bank_account_type_${index}`">{{ bankAccountType.name }}</option>
                  </select>
                </div>
                <div class="col-6">
                  <label for="exampleFormControlSelect2" class="form-label">Moneda</label>
                  <select class="form-select" v-model="selectedBankAccount.coin_type_id" @change="resetAlertMessage()">
                    <option value="-1">Seleccione un tipo de moneda</option>
                    <option v-for="(coinType, index) in coinTypes" v-bind:value="coinType.id" :key="`coin_${index}`">{{ coinType.name }}</option>
                  </select>

                </div>
                <div class="col-12">
                  <label for="" class="form-label">Alias de la cuenta</label>
                  <input
                    type="text"
                    class="form-control"
                    id=""
                    placeholder="Mi Bcp Dolares"
                    aria-describedby="defaultFormControlHelp"
                    v-model="selectedBankAccount.alias"
                    @keyup="checkInputText($event)"
                  />
                  <div id="defaultFormControlHelp" class="form-text">
                    Ingresa un alias que te permita indentificar más rápido tu cuenta
                  </div>
                </div>

                <div class="col-8" v-if="selectedBankAccount.own_account == 0" >
                  <label for="" class="form-label">Tipo de documento del propietario</label>
                  <select class="form-select"  v-model="selectedBankAccount.owner_document_type_id" @change="queryPersonByDocumentNumber($refs.editBank.value); resetAlertMessage()">
                    <option value="-1">Seleccione un tipo de cuenta</option>
                    <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_type_${index}`">{{ documentType.name }}</option>
                  </select>
                </div>

                <div class="col-12" v-if="selectedBankAccount.own_account == 0">
                  <label for="" class="form-label"> Número de documento del propietario</label>
                  <input
                    type="text"
                    class="form-control"
                    id=""
                    ref="editBank"
                    placeholder="Numero de documento de la persona propietaria de la cuenta" 
                    aria-describedby="defaultFormControlHelp"
                    v-model="selectedBankAccount.owner_document_number"
                    @change="queryPersonByDocumentNumber($event.target.value)"
                    @keyup="checkInputNumber($event)"
                  />
                  <div id="defaultFormControlHelp" class="form-text">
                    Número de documento del tercero titular
                  </div>
                </div>

                <div class="col-12" v-if="selectedBankAccount.own_account == 0" >
                  <label for="" class="form-label">Nombre o razón social del dueño de la cuenta</label>
                  <input
                    type="text"
                    class="form-control"
                    id="EditPpr"
                    ref="EditPpr"
                    placeholder="Nombre de la persona propietaria de la cuenta"
                    aria-describedby="defaultFormControlHelp"
                    @keyup="checkInputText($event)"
                    v-model="selectedBankAccount.owner_name"
                  />
                  <div id="defaultFormControlHelp" class="form-text">
                    Nombre o razón social del dueño de la cuenta
                  </div>
                </div>
                <div class="form-check form-check-primary mt-3">
                  <input class="form-check-input" type="checkbox" v-model="selectedBankAccount.own_account"  id="customCheckPrimary" checked />
                  <label class="form-check-label" for="customCheckPrimary">Declaro que es mi cuenta personal o de la empresa donde soy el representante legal.</label>
                </div>
                <div class="col-12 text-center">
                  <button @click="updateBankAccount($event)" class="btn btn-primary me-sm-3 me-1 text-white">Actualizar cuenta</button>
                  <button type="reset" class="btn btn-label-danger text-danger" data-bs-dismiss="modal" aria-label="Close">
                    Cancelar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
    <!-- Agrgar nueva cuenta -->
    <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-5">
          <div class="modal-body">
            <div class="row showSavedAlert" v-if="savedAlert">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-4">
              <h3 class="mb-2">Agregar nueva cuenta</h3>
              <p class="text-muted">La cuenta debe estar a tu nombre o a nombre de la empresa donde seas el representante legal.</p>
            </div>
            <form id="addNewCCForm" class="row g-3" onsubmit="return false">
              <BankTag :banks="banks" v-if="isBanklistReady" @change="TagifyInput" ></BankTag>
              <div class="form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="floatingInput"
                  placeholder="135 6321 5654 8781"
                  aria-describedby="floatingInputHelp"
                  ref="account_number" 
                  maxlength="24"
                  autocomplete="off"
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
                <label for="floatingInput">Número de cuenta CCI</label>
                <div id="floatingInputHelp" class="form-text">
                  
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
                  autocomplete="off"
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
                <button type="submit" class="btn btn-primary me-sm-3 me-1 text-white" @click="storeBankAccount($event)">Agregar cuenta</button>
                <button
                  @click="resetNewBankAccountData()"
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
    <!--/ agregar nueva cuenta -->
    <div class=" modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="row" v-if="savedAlert">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <b-alert :show="savedAlert" dismissible fade :variant="savedAlertVariant" @dismissed="savedAlert=false">{{ savedAlertMessage }}</b-alert>
                </div>
            </div>
            <div class="modal-content p-3 p-md-5">
              <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                  <h3 class="mb-2">Eliminar Cuenta bancaria</h3>
                  <p class="text-muted">¿Estas seguro de que quiere eliminar esta cuenta bancaria?</p>
                  <div class="row ">
                    <div class="col-12 text-center mt-4">
                      <button class="btn btn-danger me-sm-5 me-1 text-white" @click="deleteBankAccount2($event)">Eliminar</button>
                      <button type="reset" class="btn btn-label-primary text-primary" data-bs-dismiss="modal" aria-label="Close">
                        Salir
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->
  </div> 
</template>

<script>
  import { BANK_ACCOUNTS_GET_ALL, BANK_ACCOUNTS_STORE, BANK_ACCOUNTS_DELETE, BANK_ACCOUNTS_RESTORE, BANK_ACCOUNTS_UPDATE } from "@/core/services/store/bank_account.module";
  import { BANKS_GET_SET } from "@/core/services/store/bank.module";
  import { BANK_ACCOUNT_TYPES_GET_ALL } from "@/core/services/store/bank_account_type.module";
  import { COIN_TYPES_GET_ALL } from "@/core/services/store/coin_type.module";
  import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
  import { bootstrap } from "@/concept/bootstrap";
  import { QUERY_PERSON_BY_DNI, QUERY_COMPANY_BY_RUC } from "@/core/services/store/peru_consult.module";
  import BankTag from "@/view/pages/bank_accounts/Banktag.vue";
  import Cleave from 'cleave.js';
  
    export default {
        data() {
            return {
                isAdmin: false,
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: '',
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
                openModal: '',
                currentPage: 1,
                banks: [],
                bankAccountTypes: [],
                coinTypes: [],
                documentTypes: [],
                currentBank:'',
                bankAccountIdToDelete: -1,
                addAllSuggestionsEl: '',
                isBanklistReady:false
                
            }
        },
        components: {
          BankTag
        },
        mounted() {
            // sirve para el breadcums console.log(this.$route.path.split('/'))
            // this.accountNumberFormat();
          
        },
        methods: {
          TagifyInput(info) { 
            this.newBankAccount.bank_id = info[0].value
          },
          accountNumberFormat(logo = '') { 
            const editCreditCardMaskEdit = document.querySelector('.credit-card-mask-edit');
            const accountNumberFormat = document.querySelector('#floatingInput');
            const accountNumberCCI = document.querySelector('#cci');
            if (editCreditCardMaskEdit) {
              setTimeout(function () {
                new Cleave(editCreditCardMaskEdit, {
                  blocks: [4, 4, 4, 4, 4],
                  
                });
                new Cleave(accountNumberFormat, {
                  blocks: [4, 4, 4, 4, 4],
                });
                new Cleave(accountNumberCCI, {
                  blocks: [4, 4, 4, 4, 4],
                });
              },500)
            }
            setTimeout(() => {
              document.querySelector('.card-type-image').innerHTML = '<img src="' + logo + '" height="38"/>'
            },510)
            
          },
          getDefaultIconBank(event){
            event.srcElement.src = 'media/icons/payments/mastercard.png'
                  
          },
          checkInputNumber(e) {
            let input = e.srcElement;
            input.value = input.value.replace(/[^0-9 ]/, '');
          }, 
          checkInputText(e) {
            let input = e.srcElement;
            input.value = input.value.replace(/[^a-zA-Z .,´]/, '');
          }, 
          storeBankAccount(e) {
            e.preventDefault();
                      if(this.newBankAccount.bank_id == ''){
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
                      if(this.newBankAccount.coin_type_id == -1){
                          this.showSavedAlert('danger', 'Debe seleccionar el tipo de moneda.')
                          return;
                      }
                      if(this.newBankAccount.alias == null || this.newBankAccount.alias == ""){
                          this.showSavedAlert('danger', 'Debe ingresar el alias.')
                          return;
                      }

                      if (this.newBankAccount.own_account != 1) {
                          this.newBankAccount.authorized_deposit = 1
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
                                // console.log(data)
                                  this.showSavedAlert('danger', data.error)
                                  return;
                              }

                              this.allBankAccounts = this.allBankAccounts.concat(data.data);
                              this.bankAccounts = this.bankAccounts.concat(data.data);

                              // close modal
                              this.closeModal('modal-new-bank-account')
                              this.showMainAlert('success', 'Cuenta bancaria creada correctamente!')
                              document.getElementById('TagifyUserList').value=""
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
                          query = '?with_deleted=false&account_id=1&account_type=0';
                      }

                      this.$store
                          .dispatch(BANK_ACCOUNTS_GET_ALL, query)
                          .then((data) => {
                              if (data.code != 200 && data.code != 202){
                                  this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas bancarias.')
                                  return;
                              }
                              // console.log(data)
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
                  // console.log(data)
                  this.allBankAccounts = this.allBankAccounts.filter(bankAccount => bankAccount.id != data.data.id);
                  this.bankAccounts = this.bankAccounts.filter(bankAccount => bankAccount.id != data.data.id);

                  // close modal
                  this.closeModal('modal-confirm-delete')
                  this.showMainAlert('success', 'Cuenta bancaria eliminada correctamente!')
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
                              //agregar getBankAccounts
                              this.showMainAlert('success', 'Cuenta bancaria restaurada correctamente!')
                              return;
                          })
                          .catch((err) => {
                              this.showMainAlert('danger', err)
                              return;
                          });
                          this.getBankAccounts()
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
                              this.closeModal()
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
                  // console.log(this.banks)
                  this.isBanklistReady = true;
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
          getFullName(user) {
            return `${user.name} ${user.surname}`
              
          },
          copyOnClipboard(event) {
            let elm = event.srcElement
            let value = this.$refs[event.srcElement.dataset.key]
            let allClipboarReset = document.querySelectorAll('.tooltip-text')
            allClipboarReset.forEach((clip) => {
              return clip.textContent = 'Copiar'  
            }) 
            elm.nextElementSibling.textContent = 'Copiado!';
            navigator.clipboard.writeText(value[0].value)

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
            window.scrollTo(0,0);
          },
          showModal(id) {
            this.openModal = new bootstrap.Modal(document.getElementById(id), {
              keyboard: false
            })
            this.openModal.show();
            let logx = Object.keys(this.selectedBankAccount).length > 0 ?
              this.selectedBankAccount.bank.logo :
              ''
            this.accountNumberFormat(logx)
          },
          closeModal(){
            this.openModal.hide();
            this.resetAlertMessage();
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