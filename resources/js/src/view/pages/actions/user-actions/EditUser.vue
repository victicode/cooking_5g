<template>
  <div class="modal fade" id="shareProject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-enable-otp modal-dialog-centered">
      <div class="modal-content p-3 p-md-3" >
        <div class="modal-body">
          <template v-if="viewAtReady">
            <button type="button" class="btn-close close-2" @click="hiddeModal()"></button>
            <div class="row align-items-center position-relative" v-if="mainAlert">
                <div class="col-lg-12 col-md-12 col-sm-12 p-0 ">
                    <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12  p-0 mb-3 position-absolute end-0 d-flex justify-content-end"  v-if="buttonRestoreAccount">
                  <button class="btn btn-success p-2 text-white" @click="restoreAccount(SelectedUser)"> Restaurar Cuenta</button>
                </div>
            </div>
            <div class="text-center">
              <h3 class="mb-2" >Editar Usuarios</h3>
              <p>Modifica de forma rapida tus usuarios</p>
            </div>
            <div class="mb-4 pb-2">
              <label for="select2Basic" class="form-label">Buscar cliente</label>
                <div class="position-relative">
                  <select
                    id="select2Basic"
                    class="form-select form-select-lg share-project-select"
                    data-allow-clear="true"
                    ref="department_id"
                  >
                    <!-- <option value="" data-name=""></option>
                    <template v-for="acc in AllAccount">
                      <option :value="acc.id" :data-name="acc.globalname">{{acc.globalname}}</option>
                    </template> -->
                  </select>
              </div>
            </div>
            <h4 class="mb-4 pb-2">{{ TotalAccountNumber }} clientes</h4>
            <ul class="p-0 m-0">
              <template v-if="!viewListorSeleceted" >
                <li class="d-flex flex-wrap my-4"  v-for="n in 10">
                  <div class="accordion-item w-100">
                    <div class="collapsed d-flex" >
                      <div class="avatar me-3">
                        <div  class="avatar avatar-online">
                          {{ AllAccount[n-1].globalname.charAt(0) }}
                        </div>
                      </div>
                      <div class="row justify-content-between flex-grow-1 "  >
                        <div class=" col-sm-9">
                          <div class="d-flex w-100 align-items-center">
                            <p class="mb-0 me-3">{{ AllAccount[n-1].globalname }}</p>
                            
                            <span class=" rounded-pill mx-1"  :class="'badge '+ statusLabel[AllAccount[n-1].status].class">
                              <i class="fa-solid fa-address-card fa-lg align-middle" data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-title="statusLabel[AllAccount[n-1].status].title" ></i>
                            </span>
                            <div class="dropdown card-title text-center d-flex justify-content-center">
                              <button 
                                type="button"  data-bs-toggle="dropdown" aria-expanded="false"
                                class="rounded-pill mx-1" 
                                :class="{
                                  'badge bg-label-warning': AllAccount[n-1].user.email_verified_at == null, 
                                  'badge bg-label-success': AllAccount[n-1].user.email_verified_at != null, 
                                  } "
                              >
                                <i 
                                  class="fa-solid fa-envelope-circle-check fa-lg align-middle"
                                  data-bs-toggle="tooltip" data-bs-placement="top" 
                                  :data-bs-title="AllAccount[n-1].user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'" 
                                ></i>
                                <!-- {{AllAccount[n-1].user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
                              </button>
                              <div class="dropdown-menu p-4 user-info-dropdown" aria-labelledby="dropdownMenuButton1">
                                <div class="d-flex justify-content-between align-items-center ">
                                  <h6 class="mb-0 me-2"><strong>Email:</strong></h6>
                                  <h6 class="mb-0 ">
                                    {{ AllAccount[n-1].user.email }}
                                  </h6>
                                  <i 
                                      class="fa-solid fa-envelope-circle-check fa-lg align-middle mx-2" 
                                      :class="{
                                        'text-warning': AllAccount[n-1].user.email_verified_at == null, 
                                        'text-success': AllAccount[n-1].user.email_verified_at != null, 
                                        } "
                                    ></i>
                                </div>
                              </div>
                            </div>
                            
                            <button 
                              class="rounded-pill badge bg-label-primary mx-1" 
                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cuentas de bancos"
                              @click="showModal('viewBankAccounts', AllAccount[n-1].globalkey)"
                            >
                              {{ AllAccount[n-1].bank_accounts_count }}
                              <!-- <i class="fa-solid fa-envelope-circle-check fa-lg align-middle"></i> -->
                              <!-- {{AllAccount[n-1].user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
                            </button>
                            <button 
                              class="rounded-pill badge bg-label-primary mx-1" 
                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Transacciones realizadas"
                              @click="showModal('viewOperations', AllAccount[n-1].globalkey)"
                            >
                              {{ AllAccount[n-1].operations_count }}
                              <!-- <i class="fa-solid fa-envelope-circle-check fa-lg align-middle"></i> -->
                              <!-- {{AllAccount[n-1].user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
                            </button>
                          </div>
                          <p class="w-25 mb-0 text-muted">Cuenta {{ AllAccount[n-1].type == 1 ? 'jurídica' : 'natural' }}</p>
                            
                        </div>
                        <div class="dropdown col-sm-3 text-end">
                          <button
                            type="button"
                            class="btn dropdown-toggle p-2"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            
                          >
                            <span class="text-muted fw-normal me-2 d-none d-sm-inline-block">Editar</span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                              <a class="dropdown-item" href="javascript:void(0);" @click="showModal('deleteUser', AllAccount[n-1].globalkey)">Eliminar</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="javascript:void(0);"  @click="showModal('formEdit', AllAccount[n-1].globalkey)">Editar</a>
                            </li>
                            <!-- <li>
                              <a class="dropdown-item" href="javascript:void(0);">Can Comment</a>
                            </li> -->
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </template>
              <template v-else>
                <li class="d-flex flex-wrap mb-3"  >
                  <div class="accordion-item w-100">
                    <div class="collapsed d-flex" >
                      <div class="avatar me-3">
                        <div  class="avatar avatar-online">
                          {{ SelectedUser.globalname.charAt(0) }}
                        </div>
                      </div>
              
                      <div class="row justify-content-between flex-grow-1 "  >
                        <div class=" col-sm-9">
                          <div class="d-flex w-100 align-items-center">
                            <p class="mb-0">{{ SelectedUser.globalname }}</p>
                            
                            <span 
                              class=" rounded-pill mx-1" 
                              :class="'badge '+ statusLabel[SelectedUser.status].class"
                              data-bs-toggle="tooltip" 
                              data-bs-placement="top" :data-bs-title="statusLabel[SelectedUser.status].title"
                            >
                              <i class="fa-solid fa-address-card fa-lg align-middle"></i>
                              <!-- {{statusLabel[AllAccount[n-1].status].title}} -->
                            </span>
                            <div class="dropdown card-title text-center d-flex justify-content-center">
                              <button 
                                type="button"  data-bs-toggle="dropdown" aria-expanded="false"
                                class="rounded-pill mx-1" 
                                :class="{
                                  'badge bg-label-warning': SelectedUser.user.email_verified_at == null, 
                                  'badge bg-label-success': SelectedUser.user.email_verified_at != null, 
                                  } "
                              >
                                <i 
                                  class="fa-solid fa-envelope-circle-check fa-lg align-middle"
                                  data-bs-toggle="tooltip" data-bs-placement="top" 
                                  :data-bs-title="SelectedUser.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'" 
                                ></i>
                                <!-- {{SelectedUser.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
                              </button>
                              <div class="dropdown-menu p-4 user-info-dropdown" aria-labelledby="dropdownMenuButton1">
                                <div class="d-flex justify-content-between ">
                                  <h6 class="mb-0 me-2"><strong>Email:</strong></h6>
                                  <h6 class="mb-0">
                                    {{ SelectedUser.user.email }}
                                  </h6>
                                </div>
                              </div>
                            </div>
                            
                            <button 
                              class="rounded-pill badge bg-label-primary mx-1" 
                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cuentas de bancos"
                              @click="showModal('viewBankAccounts', SelectedUser.globalkey)"
                            >
                              {{ SelectedUser.bank_accounts_count }}
                              <!-- <i class="fa-solid fa-envelope-circle-check fa-lg align-middle"></i> -->
                              <!-- {{SelectedUser.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
                            </button>
                            <button 
                              class="rounded-pill badge bg-label-primary mx-1" 
                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Transacciones realizadas"
                              @click="showModal('viewOperations', SelectedUser.globalkey)"
                            >
                              {{ SelectedUser.operations_count }}
                              <!-- <i class="fa-solid fa-envelope-circle-check fa-lg align-middle"></i> -->
                              <!-- {{SelectedUser.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}} -->
                            </button>
                          </div>
                          <p class="w-25 mb-0 text-muted">Cuenta {{ SelectedUser.type == 1 ? 'jurídica' : 'natural' }}</p>
                        </div>
                        <div class="dropdown col-sm-3 text-end">
                          <button
                            type="button"
                            class="btn dropdown-toggle p-2"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                          >
                            <span class="text-muted fw-normal me-2 d-none d-sm-inline-block">Editar</span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                              <a class="dropdown-item" href="javascript:void(0);" @click="showModal('deleteUser', SelectedUser.globalkey)">Eliminar</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="javascript:void(0);" @click="showModal('formEdit', SelectedUser.globalkey)">Editar</a>
                            </li>
                            <!-- <li>
                              <a class="dropdown-item" href="javascript:void(0);">Can Comment</a>
                            </li> -->
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </template>
            </ul>
          </template>
        </div>
      </div>
    </div>
    <!-- OPTIONS MODALS -->
    <div class="modal fade modal" id="deleteUser" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        
        <div class="modal-content p-3 p-md-3">
          <div class="modal-body">
            <div class="content-header mb-2">
              <h4 class="fw-bold pt-3 mb-2 text-center">¿Estas seguro que deseas eliminar esta cuenta?</h4>
            </div>
            <div class="col-12 d-flex justify-content-between mt-3 ">
              <button class="btn btn-label-secondary  align-items-baseline " @click="openModal.hide()">
              <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
              </button>
              <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" id="sendPref" @click="deleteAccount(SelectedUser)">
                <span class="align-middle d-sm-inline-block d-none" >Confirmar!</span>
              </button>
            </div>
          </div>
        </div>
      </div>
		</div>
    <edit-user-form v-if="viewModals" :selectedUser="this.SelectedUser" @hiddeModal="AfterUpdateAccount"></edit-user-form>
    
    <div class="modal fade modal-lg" id="viewBankAccounts" v-if="viewModals" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content p-3 p-md-3">
          <div class="modal-body">
            <template v-if="SelectedUser.bank_accounts.length > 0"  >
              <div v-for="account in SelectedUser.bank_accounts" class="cardMaster border py-1 px-3 rounded mb-2 position-relative">
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
                        account.owner_name != null 
                        ? account.owner_name : 
                        SelectedUser.globalname
                      }}
                    </h6>
                    <h6 class="mb-2 pt-1">Alias: {{ account.alias }}</h6>
                    
                    <span class="custom-option-header">
                      <span class="h6 mb-0">Número de cta.</span>
                    </span>
                    <input type="text" name="clipboard" class="d-none" :ref="'clone'+account.id" :value="account.account_number">
                    <span class="card-number">{{account.account_number}}</span>
                  </div>
                  <div class="d-flex flex-column text-start text-lg-end">
                    <small class="mt-sm-auto mt-1 order-sm-1 order-0 ">Cuenta {{ account.bank_account_type.name }} <span class="h6 mb-0 text-primary ms-1"> {{ account.coin_type.name }}</span></small>
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
            <template v-else > 
              <div class="w-100 text-center">
                <h3>Este usuario no posee cuentas bancarias registradas</h3>
              </div>
            </template>
            <div class="col-12 d-flex justify-content-center mt-3 ">
              <button class="btn btn-label-secondary btn-backprev align-items-baseline me-3" @click="openModal.hide()">
                <span class="align-middle d-sm-inline-block d-none">Cerrar</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade modal-xl" id="viewOperations" v-if="viewModals" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
        <div class="modal-content px-0">
          <div class="modal-body p-0">
            <div v-if="tableOperationLoader">

              <template v-if="SelectedUser.operations.length > 0"  >
                  <div class="w-100 mb-3 px-4">
                    <h3>Ultimas {{ SelectedUser.operations.length }} operaciones</h3>
                  </div>
                  <table class="w-100">
                    <thead class="text-center border-bottom p-1">
                      <th class="p-2">Fecha</th>
                      <th class="p-2">Referencia bancaria</th>
                      <th class="p-2">Cuenta que envia</th>
                      <th class="p-2">Cuenta a la que transfirieron</th>
                      <th class="p-2">Monto enviado</th>
                      <th class="p-2">Tasa</th>
                      <th class="p-2">Monto recibido</th>
                      <th class="p-2">Tipo de cambio</th>
                    </thead>
                    <tbody>
                      <tr v-for="operation in SelectedUser.operations" class="border-bottom ">
                        <td class="text-center p-2">
                          <div>
                            <h6>
                              {{ tableFormatDate(operation.bank_account_transfer.created_at)}}
                            </h6>
                          </div> 
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <p class="mb-0">
                              <strong>
                                #{{ operation.operation_number}}
                              </strong>
                            </p>
                          </div> 
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <img :src="operation.bank_account_send.bank.logo"  height="50"  alt="logo de banco">
                            <p class="mb-0"> N° de cuenta: </p>
                            <p class="mb-0">
                              <strong>
                                {{ operation.bank_account_send.account_number}}
                              </strong>
                            </p>
                          </div> 
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <img :src="operation.bank_account_transfer.bank.logo"  height="50"  alt="logo de banco">
                            <p class="mb-0"> N° de cuenta: </p>
                            <p class="mb-0">
                              <strong>
                                {{ operation.bank_account_transfer.account_number}}
                              </strong>
                            </p>
                          </div> 
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <h6>
                              {{ operation.bank_account_send.coin_type.code == 'dolar' ? '$':'S/' }}{{ $func.numberFormat(parseFloat(operation.amount))}} 
                            </h6>
                          </div>
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <h6>
                              {{  operation.exchange_rate.toFixed(2) }}
                            </h6>
                          </div>
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <h6>
                              {{ operation.bank_account_receive.coin_type.code == 'dolar' ? '$':'S/' }}{{ $func.numberFormat(parseFloat(operation.change_amount))}}
                            </h6>
                          </div>
                        </td>
                        <td class="text-center p-2">
                          <div>
                            <h6>
                              {{ operation.bank_account_receive.coin_type.code == 'dolar' ? "Compra de dolares" : "Venta de dolares"}}
                            </h6>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </template>
              <template v-else > 
                <div class="w-100 text-center">
                  <h3>Este usuario no ha realizado operaciones en la plataforma</h3>
                </div>
              </template>
            </div>
            <div class="w-100 text-center" v-else>
              <h3>Cargando...</h3>
            </div>
            <div class="col-12 d-flex justify-content-center mt-3 ">
              <button class="btn btn-label-secondary btn-backprev align-items-baseline me-3" @click="openModal.hide()">
                <span class="align-middle d-sm-inline-block d-none">Cerrar</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
	@import "@/assets/libs/select2/select2.scss";
</style>
<script>
  import { bootstrap } from "@/concept/bootstrap";
  import { ACCOUNTS_GET_ALL_USER, ACCOUNTS_PERSONAL_DELETE, ACCOUNTS_PERSONAL_RESTORE, ACCOUNTS_PERSONAL_UPDATE,  ACCOUNTS_COMPANY_DELETE, ACCOUNTS_COMPANY_RESTORE, ACCOUNTS_COMPANY_UPDATE } from "@/core/services/store/account.module";
  import editUserForm from "@/view/pages/actions/user-actions/EditForm.vue";
  import { OPERATIONS_GET_LAST_FIVE_BY_USER } from "@/core/services/store/operation.module";
  export default {
    name:'EditUser',
    data() {
        return {
          mainAlert: false,
          mainAlertVariant: "",
          mainAlertMessage: "",
          viewAtReady:false,
          AllAccount: [],
          SelectedUser:{},
          TotalAccountNumber: 0,
          openModal: '',
          viewListorSeleceted: false,
          viewModals: false,
          select2ShareProject: '',
          buttonRestoreAccount: false,
          restoreAcc: {},
          tableOperationLoader:false,
          statusLabel: {
            '-1':	{ title: 'Documentos rechazado', class: ' bg-label-danger' },
            '0':	{ title: 'Documentos no verificado', class: ' bg-label-warning' },
            '1':	{ title: 'Documentos verificado', class: ' bg-label-success' },
          }
        }
    },
    components: {
      editUserForm:editUserForm,
    },
    mounted() {
      this.getAllUser()
    },
    methods: {
      getAllUser () {
        this.$store
          .dispatch(ACCOUNTS_GET_ALL_USER)
          .then((data) => { 
            this.AllAccount = data.data.allAccounts
            this.TotalAccountNumber = data.data.count
            
            setTimeout(() => {
              this.viewAtReady = true
            }, 200);
            setTimeout(() => { 

              this.select2()
              const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
              const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
            },500)
          })
          .catch((err) => {
              this.showMainAlert('danger', err)
          });
          
          
      },
      hiddeModal(){
        this.$emit("click");
      },
      getTeenOperations(id){
        
        let query = {
          id: id,
          query:'?type=' + this.SelectedUser.type,
        }
        setTimeout(()=>{
            this.$store
            .dispatch(OPERATIONS_GET_LAST_FIVE_BY_USER, query)
            .then((data) => {
                if (data.code != 200){
                    // this.showMainAlert('danger', 'Error desconocido al obtener las operaciones.')
                    return;
                }
                this.SelectedUser.operations=data.data;
                this.tableOperationLoader = true;
                // console.log(this.SelectedUser)
            })
            .catch((err) => {
                // this.showMainAlert('danger', err)
                console.log(err)
            });
        },200)
      },
      tableFormatDate(date){
        let initDate = new Date(date);
        let MonthOfYear = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago','Sep', 'Oct', 'Nov', 'Dic']

        return `${initDate.getDate()} ${MonthOfYear[initDate.getMonth()]} ${initDate.getFullYear()}`

      },
      select2() {
         this.select2ShareProject = jQuery('.share-project-select');
        var shareProject = document.getElementById('shareProject');

        if (this.select2ShareProject.length) {
          function renderAvatar(option) {
            if (!option.id) {
              return option.text;
            }
            
            var optionEle = `
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-xs me-2 d-flex">
                    ${option.globalname.charAt(0)}
                  </div>
                  <div class="name">
                  ${option.globalname}
                  </div>
                </div>`;
            return optionEle;
          }
          this.select2ShareProject.wrap('<div class="position-relative"></div>').select2({
            ajax: {
              // url: 'http://127.0.0.1:8000/api/get-all-by-search',
              url:'https://cambio.dlsmoney.com/api/get-all-by-search',
              data: function (params) {
                var query = {
                  search: params.term,
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
              },
              processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data.data.allAccounts
                };
              }
            },
            dropdownParent: shareProject,
            templateResult: renderAvatar,
            templateSelection: renderAvatar,
            placeholder: 'Buscar cliente',
            escapeMarkup: function (es) {
              return es;
            }
          });
        }
        this.select2ShareProject.on('select2:select', function (e) {
          this.dispatchEvent(new CustomEvent('selectUser', { detail: e.params.data }));
        });
        this.select2ShareProject.on('select2:unselecting', function (e) {
          this.dispatchEvent(new CustomEvent('unSelectUser', { detail: e.params.data }));
        });
        
        document.querySelector('.select2-selection__arrow').style.display = "none";
        document.querySelector('.share-project-select').addEventListener('selectUser', event => {
          // console.log(event.detail)
          this.selectAccount(event.detail)

        })
        document.querySelector('.share-project-select').addEventListener('unSelectUser', event => {
          this.selectAccount(event.detail)
        })

      },
      selectAccount(user) {
        this.viewModals = false
        this.SelectedUser = user == null ? {} : user;
        // console.log(this.SelectedUser)
        this.viewListorSeleceted = user == null ? false : true;
        setTimeout(() => {
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
          const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        }, 400);
        
      },  
      showModal(idModal, key) {
        if (idModal !== 'deleteUser') {
          this.viewModals = true;
        }
        let idAccount = this.AllAccount.findIndex(account => account.globalkey == key);
        if (idAccount > -1) {
          this.SelectedUser = this.AllAccount[idAccount];
        }

        if(idModal == 'viewOperations'){
          this.tableOperationLoader =false;
          this.getTeenOperations(this.SelectedUser.id,this.SelectedUser.type)
        }
        
        setTimeout(() => {
          
          this.openModal = new bootstrap.Modal(document.getElementById(idModal), {
            keyboard: false,
          })

          this.openModal.show()
        }, 400);
        
      }, 
      AfterUpdateAccount (id) {
        this.openModal.hide()
        this.viewModals = false;
        if(id == 1){
          this.viewAtReady = false;
          this.showMainAlert('success', `Cuenta ${this.SelectedUser.type == 0 ? 'personal':'jurídica'} editada correctamente!`);
          this.SelectedUser = {};
          this.select2ShareProject.val(null).trigger("change");
          this.viewListorSeleceted=false;
          this.getAllUser();
        }

        
        
      },
      deleteAccount(user) {
          let action = user.type == 0 ? ACCOUNTS_PERSONAL_DELETE : ACCOUNTS_COMPANY_DELETE
          this.$store
              .dispatch(action , user.id)
              .then((data) => {
                  if (data.code != 200){
                      this.showMainAlert('danger', data.error)
                      return;
                  }

                  // let AllAccountIndex = this.AllAccount.findIndex(account => account.globalkey == data.data.globalkey);

                  // if (AllAccountIndex > -1) {
                  //   this.AllAccount[AllAccountIndex].deleted_at = data.data.deleted_at;
                  // }
                  this.getAllUser()
                  this.viewListorSeleceted=false,
                  
                 
                  
                  setTimeout(() => {
                    this.openModal.hide()
                   
                    this.showMainAlert('success', `Cuenta ${user.type == 0 ? 'personal' : 'jurídica'} eliminada correctamente!`)
                    // this.SelectedUser = {}
                    this.buttonRestoreAccount = true
                    this.select2ShareProject.val(null).trigger("change");
                    return;
                  }, 500);
                  
              })
              .catch((err) => {
                  this.showMainAlert('danger', err)
                  return;
              });
      },
      restoreAccount (user) {

          let action = user.type == 0 ? ACCOUNTS_PERSONAL_RESTORE : ACCOUNTS_COMPANY_RESTORE
          this.$store
              .dispatch(action , user.id)
              .then((data) => {
                  if (data.code != 200){
                      this.showMainAlert('danger', data.error)
                      return;
                  }

                  // let AllAccountIndex = this.AllAccount.findIndex(account => account.globalkey == data.data.globalkey);

                  // if (AllAccountIndex > -1) {
                  //   this.AllAccount[AllAccountIndex].deleted_at = data.data.deleted_at;
                  // }
                  this.getAllUser()
                  this.viewListorSeleceted=false,
                  
                 
                  
                  setTimeout(() => {
                    this.buttonRestoreAccount = false
                    this.showMainAlert('success', `Cuenta ${user.type == 0 ? 'personal' : 'jurídica'} restaurada correctamente!`)
                    // this.SelectedUser = {}
                    
                    this.select2ShareProject.val(null).trigger("change");
                    return;
                  }, 500);
                  
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
  };
</script>