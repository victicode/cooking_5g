<template>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header pt-4 pb-2 min-vh-0">
                    <div class="row w-100">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <h3 class="pt-4"><strong>Nueva Operación Manual</strong></h3>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="mainAlert">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}{{ redirectMessage == '' ? '' : redirectMessage + redirectMessageSeconds + ' segundos.' }}</b-alert>
                    </div>
                    <hr>
                </div>

                <div class="card-body">
                    <form class="form" id="new_manual_operation_form">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                <div class="form-group">
                                    <b-form-group label="Tipo de cuenta de usuario" style="font-weight: bold">
                                        <b-form-radio-group v-model="selectedAccountType" :options="optionsUserType" buttons button-variant="outline-primary" size="md" name="radio-btn-outline" @change="changeSelectedAccountType('reset')"></b-form-radio-group>
                                    </b-form-group>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Cuenta del usuario</strong></label>
                                    <select class="form-control form-control-lg" v-model="selectedAccount.id" @change="resetBankAccountsSelected(); getBankAccounts()">
                                        <option value="">Selecciona la cuenta del usuario</option>
                                        <option v-for="account in accounts" :value="account.id" :key="account.id">
                                            {{ selectedAccountType == 0 ? account.name + ' ' + account.surname :  account.business_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 text-left my-auto">
                                <a class="btn btn-primary font-weight-bold ml-2" v-b-modal.modal-new-user>Nueva cuenta de usuario</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Cuenta de envío</strong></label>
                                    <select class="form-control form-control-lg" v-model="selectedBankAccountSend.id" @change="filterBankAccounts()">
                                        <option value="">Selecciona la cuenta bancaria de envío</option>
                                        <option v-for="(bankAccount, index) in allBankAccounts"  v-bind:value="bankAccount.id" :key="`bank_account_send_${index}`">
                                            {{ bankAccount.bank.name }} | {{ bankAccount.account_number }} | {{ bankAccount.account_number_cci }} | {{ bankAccount.alias }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Cuenta de depósito</strong></label>
                                    <select class="form-control form-control-lg" v-model="selectedBankAccountReceive.id" @change="setReceiveBankAccount()">
                                        <option value="">Selecciona la cuenta bancaria de depósito</option>
                                        <option v-for="(bankAccount, index) in bankAccountsToReceive"  v-bind:value="bankAccount.id" :key="`bank_account_receive_${index}`">
                                            {{ bankAccount.bank.name }} | {{ bankAccount.account_number }} | {{ bankAccount.account_number_cci }} | {{ bankAccount.alias }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 text-left my-auto">
                                <a class="btn btn-primary font-weight-bold ml-2" v-b-modal.modal-new-bank-account :class="{disabled: !((selectedAccountType == '0' || selectedAccountType == '1') && selectedAccount.id != '')}">Nueva cuenta bancaria</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Cuenta a la que transfiere</strong></label>
                                    <select class="form-control form-control-lg" v-model="selectedBankAccountTransfer.id" @change="setTransferAccount()">
                                        <option value="">Selecciona la cuenta bancaria a la que transfiere</option>
                                        <option v-for="(bankAccount, index) in bankAccountsToTransfer"  v-bind:value="bankAccount.id" :key="`bank_account_transfer_${index}`">
                                            {{ bankAccount.bank.name }} | {{ bankAccount.account_number }} | {{ bankAccount.account_number_cci }} | {{ bankAccount.alias }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Tipo de cambio: compra</strong></label>
                                    <input type="text" class="form-control" v-model="purchaseFormatted" placeholder="Ingrese el tipo de cambio de compra" @keypress="$func.isValidNumberFormat($event.target.value, $event)" @keyup="purchaseFormatted = $func.numberFormat($event.target.value)" @change="setNumberWithoutFormat($event.target.value, 'purchase')">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Tipo de cambio: venta</strong></label>
                                    <input type="text" class="form-control" v-model="saleFormatted" placeholder="Ingrese el tipo de cambio venta" @keypress="$func.isValidNumberFormat($event.target.value, $event)" @keyup="saleFormatted = $func.numberFormat($event.target.value)" @change="setNumberWithoutFormat($event.target.value, 'sale')">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Monto</strong></label>
                                    <input type="text" class="form-control" v-model="amountFormatted" placeholder="Ingrese el monto" @keypress="$func.isValidNumberFormat($event.target.value, $event)" @keyup="amountFormatted = $func.numberFormat($event.target.value); calculateChangeAmount()" @change="setNumberWithoutFormat($event.target.value, 'amount'); calculateChangeAmount()">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-7">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                                <div class="card card-custom" style="background-color: #e4d7f1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column mx-auto text-center">
                                                <h4 class="text-dark-75 mb-3">El cliente transfiere:</h4>
                                                <h2 class="font-weight-bold"><strong>{{ coinTypeSendAbbreviation }} {{ amountFormatted }}</strong></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                                <div class="card card-custom" style="background-color: #fff6d8">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column mx-auto text-center">
                                                <h4 class="text-dark-75 mb-3">El cliente recibe:</h4>
                                                <h2 class="font-weight-bold"><strong>{{ coinTypeReceiveAbbreviation }} {{ changeAmountFormatted }}</strong></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label><strong>Origen de fondos</strong></label>
                                            <select class="form-control form-control-lg" v-model="selectedFundOrigin.code" @change="setFundOrigin()">
                                                <option value="">Selecciona el origen de fondos</option>
                                                <option v-for="fundOrigin in fundOrigins" v-bind:value="fundOrigin.code" :key="`fund_origin_${fundOrigin.id}`">
                                                    {{ fundOrigin.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="selectedFundOrigin.code == 'otros'">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label><strong>Otro origen de fondos</strong></label>
                                            <input type="text" class="form-control" v-model="selectedFundOrigin.other_fund_origin" placeholder="Ingrese otro origen de fondos">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label><strong>Número de operación</strong></label>
                                    <input type="text" class="form-control" v-model="operationNumber" placeholder="Ingrese el número de operación">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <a class="btn btn-primary font-weight-bold ml-2" @click="storeOperation($event)">Crear Operación</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal new user account -->
        <div>
            <b-modal id="modal-new-user" size="xl" title="Crear nueva cuenta de usuario" ok-title="Guardar" cancel-title="Cancelar" @ok="storeNewUser($event)" @hidden="resetNewUserData();resetAlertMessage();" @show="loadNewUserData()" :ok-disabled="saveUserButtonDisabled">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="user-username">Usuario</label>
                            <input type="text" class="form-control" v-model="newUser.user_username" placeholder="Usuario">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="user-email">Email</label>
                            <input type="email" class="form-control" v-model="newUser.user_email" placeholder="Email">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="user-password">Password</label>
                            <input type="password" class="form-control" v-model="newUser.user_password" placeholder="Password">
                        </div>
                    </div>
                        <hr class="mt-2 mb-2">
                </div>

                <div class="row" v-bind:class="{ 'd-none': !(newUser.user_username && newUser.user_email && newUser.user_password && $func.isValidEmail(this.newUser.user_email) && newUser.user_password.length >= 8) }">
                    <div class="col-lg-12 col-mg-12 col-sm-12">
                        <div class="form-group">
                            <b-form-group label="Seleccione el tipo de cuenta de usuario">
                                <b-form-radio-group v-model="selectedNewUserType" :options="optionsUserType" buttons button-variant="outline-primary" size="md" name="radio-btn-outline"></b-form-radio-group>
                            </b-form-group>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2">
                </div>

                <div v-bind:class="{ 'd-none': !(newUser.user_username && newUser.user_email && newUser.user_password && $func.isValidEmail(this.newUser.user_email) && newUser.user_password.length >= 8 && (selectedNewUserType == '0' || selectedNewUserType == '1')) }">
                    <!-- Personal Account -->
                    <div class="row" v-if="selectedNewUserType == '0'">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <select class="form-control form-control-lg" v-model="newUser.document_type_id">
                                    <option value="">Selecciona el tipo de documento</option>
                                    <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                                        {{ documentType.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Número de documento</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.document_number" placeholder="Ingresa el número de documento" @change="queryPersonByDocumentNumber($event.target.value, 'personal')">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Documento (frente)</label>
                                <b-form-file
                                    name="document_front"
                                    ref="document_front"
                                    accept=".png, .jpg, .jpeg"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Documento (dorso)</label>
                                <b-form-file
                                    name="document_back"
                                    ref="document_back"
                                    accept=".png, .jpg, .jpeg"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nombre/s</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.name" placeholder="Ingresa el/los nombre/s">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Apellido/s</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.surname" placeholder="Ingresa el/los apellido/s">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Fecha de nacimiento</label>
                                <input type="date" class="form-control form-control-lg" v-model="newUser.birthdate" placeholder="Selecciona la fecha de nacimiento">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.phone" placeholder="Ingresa el número de telefono">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.cellphone1" placeholder="Ingresa el número de celular">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Otro celular</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.cellphone2" placeholder="Ingresa otro número de celular">
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>País</label>
                                <select class="form-control form-control-lg" v-model="newUser.country_id">
                                    <option value="">Selecciona el país</option>
                                    <option v-for="country in countries" :value="country.id" :key="country.id" :selected="country.id == 139">
                                        {{ country.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Departamento</label>
                                <select class="form-control form-control-lg" v-model="newUser.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []; newUser.district_id = ''">
                                    <option value="">Selecciona el departamento</option>
                                    <option v-for="department in departments" :value="department.id" :key="department.id">
                                        {{ department.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Provincia</label>
                                <select class="form-control form-control-lg" v-model="newUser.province_id" @change="getDistrictsByProvinceId($event.target.value)">
                                    <option value="">Selecciona la provincia</option>
                                    <option v-for="province in provinces" :value="province.id" :key="province.id">
                                        {{ province.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Distrito</label>
                                <select class="form-control form-control-lg" v-model="newUser.district_id">
                                    <option value="">Selecciona el distrito</option>
                                    <option v-for="district in districts" :value="district.id" :key="district.id">
                                        {{ district.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.address" placeholder="Ingresa el domicilio">
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Ocupación</label>
                                <select class="form-control form-control-lg" v-model="newUser.ocupation_id">
                                    <option value="">Selecciona la ocupación</option>
                                    <option v-for="ocupation in ocupations" :value="ocupation.id" :key="ocupation.id">
                                        {{ ocupation.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <b-form-group label="Confirme por favor si usted es una persona políticamente expuesta.">
                                    <b-form-radio v-model="newUser.exposed_person" size="lg" value="1">Si, soy</b-form-radio>
                                    <b-form-radio v-model="newUser.exposed_person" size="lg" value="0">No, no soy</b-form-radio>
                                </b-form-group>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" v-if="newUser.exposed_person == 1">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Cargo</label>
                                        <input type="text" class="form-control form-control-lg" v-model="newUser.position" placeholder="Ingresa el cargo">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Lugar de trabajo</label>
                                        <input type="text" class="form-control form-control-lg" v-model="newUser.workplace" placeholder="Ingresa el lugar de trabajo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Account -->
                    <div class="row" v-if="selectedNewUserType == '1'">
                        <!--begin: Wizard Step 1-->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Número de RUC</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.ruc" placeholder="Ingresa el número de RUC"  @change="queryCompanyByRuc($event.target.value)">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Razón social</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.business_name" placeholder="Ingresa la razón social">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Giro de negocio</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.business_turn" placeholder="Ingresa el giro de negocio">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.fiscal_address" placeholder="Ingresa la dirección fiscal">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>País</label>
                                <select class="form-control form-control-lg" v-model="newUser.country_id">
                                    <option value="">Selecciona el país</option>
                                    <option v-for="country in countries" :value="country.id" :key="country.id" :selected="country.id == 139">
                                        {{ country.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Departamento</label>
                                <select class="form-control form-control-lg" v-model="newUser.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                                    <option value="">Selecciona el departamento</option>
                                    <option v-for="department in departments" :value="department.id" :key="department.id">
                                        {{ department.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Provincia</label>
                                <select class="form-control form-control-lg" v-model="newUser.province_id" @change="getDistrictsByProvinceId($event.target.value)">
                                    <option value="">Selecciona la provincia</option>
                                    <option v-for="province in provinces" :value="province.id" :key="province.id">
                                        {{ province.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Distrito</label>
                                <select class="form-control form-control-lg" v-model="newUser.district_id">
                                    <option value="">Selecciona el distrito</option>
                                    <option v-for="district in districts" :value="district.id" :key="district.id">
                                        {{ district.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.email" placeholder="Ingresa el correo electrónico">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.phone" placeholder="Ingresa el número de teléfono">
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <select class="form-control form-control-lg" v-model="newUser.document_type_lr_id">
                                    <option value="">Selecciona el tipo de documento</option>
                                    <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                                        {{ documentType.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Número de documento</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.document_number_lr" placeholder="Ingresa el número de documento" @change="queryPersonByDocumentNumber($event.target.value, 'company_lr')">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Documento (frente)</label>
                                <b-form-file
                                    name="document_front_lr"
                                    ref="document_front_lr"
                                    accept=".png, .jpg, .jpeg"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Documento (dorso)</label>
                                <b-form-file
                                    name="document_back_lr"
                                    ref="document_back_lr"
                                    accept=".png, .jpg, .jpeg"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nombre/s</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.name_lr" placeholder="Ingresa el nombre/s">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Apellido/s</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.surname_lr" placeholder="Ingresa el apellido/s">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Ocupación</label>
                                <select class="form-control form-control-lg" v-model="newUser.ocupation_lr_id">
                                    <option value="">Selecciona la ocupación</option>
                                    <option v-for="ocupation in ocupations" :value="ocupation.id" :key="ocupation.id">
                                        {{ ocupation.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12"></div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <select class="form-control form-control-lg" v-model="newUser.document_type_c_id">
                                    <option value="">Selecciona el tipo de documento</option>
                                    <option v-for="documentType in documentTypes" :value="documentType.id" :key="documentType.id">
                                        {{ documentType.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Número de documento</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.document_number_c" placeholder="Ingresa el número de documento" @change="queryPersonByDocumentNumber($event.target.value, 'company_c')">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Documento (frente)</label>
                                <b-form-file
                                    name="document_front_c"
                                    ref="document_front_c"
                                    accept=".png, .jpg, .jpeg"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Documento (dorso)</label>
                                <b-form-file
                                    name="document_back_c"
                                    ref="document_back_c"
                                    accept=".png, .jpg, .jpeg"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nombre/s</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.name_c" placeholder="Ingresa el/los nombre/s">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Apellido/s</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.surname_c" placeholder="Ingresa el/los apellido/s">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Fecha de nacimiento</label>
                                <input type="date" class="form-control form-control-lg" v-model="newUser.birthdate_c" placeholder="Selecciona la fecha de nacimiento">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control form-control-lg" v-model="newUser.phone_c" placeholder="Ingresa el número de teléfono">
                            </div>
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

        <!-- Modal new bank account -->
        <div>
            <b-modal id="modal-new-bank-account" size="lg" title="Crear nueva cuenta bancaria" ok-title="Guardar" cancel-title="Cancelar" @ok="storeBankAccount($event)" @hidden="resetNewBankAccountData();resetAlertMessage();" @show="loadNewBankAccountData()" :ok-disabled="saveBankAccountButtonDisabled">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><strong>Banco</strong></label>
                            <select class="form-control" v-model="newBankAccount.bank_id">
                                <option value="">Seleccione un banco</option>
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
                                <option value="">Seleccione un tipo de cuenta</option>
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
                                <option value="">Seleccione un tipo de moneda</option>
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
                                <option value="">Seleccione el tipo de documento</option>
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
    </div>
</template>

<script>
    import { OPERATIONS_STORE } from "@/core/services/store/operation.module";
    import { ACCOUNTS_PERSONAL_GET_ALL, ACCOUNTS_COMPANY_GET_ALL, ACCOUNTS_PERSONAL_STORE, ACCOUNTS_COMPANY_STORE } from "@/core/services/store/account.module";
    import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
    import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
    import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
    import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
    import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
    import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
    import { QUERY_PERSON_BY_DNI, QUERY_COMPANY_BY_RUC } from "@/core/services/store/peru_consult.module";
    import { USERS_STORE } from "@/core/services/store/user.module";
    import { BANK_ACCOUNTS_GET_ALL, BANK_ACCOUNTS_GET_ALL_TO_TRANSFER, BANK_ACCOUNTS_STORE } from "@/core/services/store/bank_account.module";
    import { BANKS_GET_ALL } from "@/core/services/store/bank.module";
    import { BANK_ACCOUNT_TYPES_GET_ALL } from "@/core/services/store/bank_account_type.module";
    import { COIN_TYPES_GET_ALL } from "@/core/services/store/coin_type.module";
    import { EXCHANGE_RATES_GET_LAST } from "@/core/services/store/exchange_rate.module";
    import { FUND_ORIGINS_GET_ALL } from "@/core/services/store/fund_origin.module";

    export default {
        data() {
            return {
                // BEGIN: alerts
                mainAlert: false,
                mainAlertVariant: "",
                mainAlertMessage: "",
                savedAlert: false,
                savedAlertVariant: '',
                savedAlertMessage: '',
                // END: alerts

                newManualOperation: {
                    bank_account_send_id: "",
                    bank_account_receive_id: "",
                    amount: "",
                    bank_account_transfer_id: "",
                    change_amount: "",
                    exchange_rate: "",
                },
                file1: null,
                file2: null,
                file3: null,
                file4: null,
                showFile2: false,
                showFile3: false,
                showFile4: false,
                filesShown: 1,

                // BEGIN: User Account
                selectedAccountType: "0",
                selectedNewUserType: "",
                optionsUserType: [
                    { text: "Personal", value: "0" },
                    { text: "Empresa", value: "1" },
                ],
                selectedAccount: {
                    id: ""
                },
                personalAccounts: [],
                companyAccounts: [],
                accounts: [],
                newUser: {
                    user_username: "",
                    user_email: "",
                    user_password: "",
                    user_id: -1,

                    country_id: 139,
                    department_id: "",
                    province_id: "",
                    district_id: "",
                    phone: "",

                    // Personal account
                    document_type_id: "",
                    document_number: "",
                    name: "",
                    surname: "",
                    birthdate: "",
                    cellphone1: "",
                    cellphone2: "",
                    address: "",
                    ocupation_id: "",
                    exposed_person: 0,
                    position: "",
                    workplace: "",

                    // Company account
                    ruc: "",
                    business_name: "",
                    business_turn: "",
                    fiscal_address: "",
                    email: "",
                    document_type_lr_id: "",
                    document_number_lr: "",
                    name_lr: "",
                    surname_lr: "",
                    ocupation_lr_id: "",
                    document_type_c_id: "",
                    document_number_c: "",
                    name_c: "",
                    surname_c: "",
                    birthdate_c: "",
                    phone_c: "",
                },
                countries: [],
                departments: [],
                provinces: [],
                districts: [],
                documentTypes: [],
                ocupations: [],
                // END: User Account

                // BEGIN: Bank Account
                bankAccountsToReceive: [],
                allBankAccounts: [],
                bankAccountsToTransfer: [],
                allBankAccountsToTransfer: [],
                selectedBankAccountSend: {
                    id: ""
                },
                selectedBankAccountReceive: {
                    id: ""
                },
                selectedBankAccountTransfer: {
                    id: ""
                },
                newBankAccount: {
                    bank_id: "",
                    account_number: "",
                    bank_account_type_id: "",
                    account_number_cci: "",
                    coin_type_id: "",
                    alias: "",
                    own_account: 1,
                },
                banks: [],
                bankAccountTypes: [],
                coinTypes: [],
                // END: Bank Account

                // BEGIN: Coin Type
                coinTypeSendAbbreviation: '',
                coinTypeReceiveAbbreviation: '',

                coinTypeSend: null,
                coinTypeReceive: null,
                // END: Coin Type

                // BEGIN: Exchange Rate & Amount
                sale: 0,
                saleFormatted: "",
                purchase: 0,
                purchaseFormatted: "",
                amount: 0,
                amountFormatted: "",
                changeAmount: 0,
                changeAmountFormatted: "",
                // END: Exchange Rate & Amount

                // BEGIN: Buttons modals
                saveUserButtonDisabled: false,
                saveBankAccountButtonDisabled: false,
                // END: Buttons modals

                // BEGIN: Redirect timer
                redirectMessageSeconds: 5,
                redirectMessage: '',
                // END: Redirect timer

                // BEGIN: Fund origins
                fundOrigins: [],
                selectedFundOrigin: {
                    code: ""
                },
                // END: Fund origins

                operationNumber: "",
            }
        },
        watch: {
            'newManualOperation': {
                handler: function (after, before) {
                    if (this.mainAlert) {
                        this.hideMainAlert()
                    }
                },
                deep: true
            },
            'selectedNewUserType': {
                handler: function (after, before) {
                    this.newUser.country_id = 139;
                    this.newUser.department_id = "";
                    this.newUser.province_id = "";
                    this.newUser.district_id = "";
                    this.newUser.phone = "";

                    // Personal account
                    this.newUser.document_type_id = "";
                    this.newUser.document_number = "";
                    this.newUser.name = "";
                    this.newUser.surname = "";
                    this.newUser.birthdate = "";
                    this.newUser.cellphone1 = "";
                    this.newUser.cellphone2 = "";
                    this.newUser.address = "";
                    this.newUser.ocupation_id = "";
                    this.newUser.exposed_person = 0;
                    this.newUser.position = "";
                    this.newUser.workplace = "";

                    // Company account
                    this.newUser.ruc = "";
                    this.newUser.business_name = "";
                    this.newUser.business_turn = "";
                    this.newUser.fiscal_address = "";
                    this.newUser.email = "";
                    this.newUser.document_type_lr_id = "";
                    this.newUser.document_number_lr = "";
                    this.newUser.name_lr = "";
                    this.newUser.surname_lr = "";
                    this.newUser.ocupation_lr_id = "";
                    this.newUser.document_type_c_id = "";
                    this.newUser.document_number_c = "";
                    this.newUser.name_c = "";
                    this.newUser.surname_c = "";
                    this.newUser.birthdate_c = "";
                    this.newUser.phone_c = "";

                    this.$refs.document_front = null;
                    this.$refs.document_back = null;
                    this.$refs.document_front_lr = null;
                    this.$refs.document_back_lr = null;
                    this.$refs.document_front_c = null;
                    this.$refs.document_back_c = null;
                },
                deep: true
            }
        },
        methods: {
            storeOperation (event) {
                event.preventDefault();

                let validated = this.validateManualOperation();
                if (!validated.status) {
                    this.showMainAlert('danger', validated.message);
                    return;
                }

                const data = new FormData();
                data.append('bank_account_send_id', this.selectedBankAccountSend.id);
                data.append('bank_account_receive_id', this.selectedBankAccountReceive.id);
                data.append('amount', this.amount);
                data.append('bank_account_transfer_id', this.selectedBankAccountTransfer.id);
                data.append('change_amount', this.changeAmount);

                if (this.selectedBankAccountSend.coin_type_id == 1) { // Soles
                    data.append('exchange_rate', this.sale);
                } else if (this.selectedBankAccountSend.coin_type_id == 2) { // Dólares
                    data.append('exchange_rate', this.purchase);
                }

                data.append('operation_number', this.operationNumber);
                data.append('fund_origin_id', this.selectedFundOrigin.id);

                if (this.selectedFundOrigin.code == 'otros') {
                    data.append('other_fund_origin', this.selectedFundOrigin.other_fund_origin);
                }

                data.append('account_type', this.selectedAccountType);
                data.append('active_account', this.selectedAccount.id);

                this.$store
                    .dispatch(OPERATIONS_STORE, data)
                    .then((data) => {
                        if (data.code != 201){
                            this.showMainAlert('danger', 'Error desconocido al guardar la operación.');
                            return;
                        }

                        this.showMainAlert('success', 'Operación realizada con éxito!');
                        this.redirectTimer(' . Redireccionando a "Todas las Operaciones" en: ', '/all-operations');
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err);
                    });
            },

            validateManualOperation() {
                if (this.selectedBankAccountSend.id == "") {
                    return { status: false, message: "La cuenta bancaria de envío es requerida" };
                }
                if (this.selectedBankAccountReceive.id == "") {
                    return { status: false, message: "La cuenta bancaria de depósito es requerida" };
                }
                if (this.amount == 0 || this.amountFormatted == "") {
                    return { status: false, message: "El monto es requerido" };
                }
                if (this.selectedBankAccountTransfer.id == "") {
                    return { status: false, message: "La cuenta bancaria a la que transfiere es requerida" };
                }
                if (this.changeAmount == 0 || this.changeAmountFormatted == "") {
                    return { status: false, message: "El monto de cambio es requerido" };
                }
                if (this.changeAmount == 0 || this.changeAmountFormatted == "") {
                    return { status: false, message: "El monto de cambio es requerido" };
                }
                if (this.selectedBankAccountSend.coin_type_id == 1) { // Soles
                    if (this.sale == 0 || this.saleFormatted == "" || this.saleFormatted == "0.000") {
                        return { status: false, message: "El tipo de cambio de venta es requerido" };
                    }
                } else if (this.selectedBankAccountSend.coin_type_id == 2) { // Dólares
                    if (this.purchase == 0 || this.purchaseFormatted == "" || this.purchaseFormatted == "0.000") {
                        return { status: false, message: "El tipo de cambio de compra es requerido" };
                    }
                }
                if (this.selectedFundOrigin.code == "" || this.selectedFundOrigin.id == null) {
                    return { status: false, message: "El origen de los fondos es requerido" };
                }
                if (this.selectedFundOrigin.code == "otros") {
                    if (this.selectedFundOrigin.other_fund_origin == null || this.selectedFundOrigin.other_fund_origin == "") {
                        return { status: false, message: "El origen de los fondos es requerido" };
                    }
                }

                return { status: true, message: "" };
            },

            getPersonalAccounts () {
                if (this.personalAccounts.length > 0) {
                    this.accounts = this.personalAccounts
                    return;
                }

                this.$store
                    .dispatch(ACCOUNTS_PERSONAL_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas peronales.')
                            return;
                        }

                        this.personalAccounts = data.data;
                        this.accounts = this.personalAccounts;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getCompanyAccounts () {
                if (this.companyAccounts.length > 0) {
                    this.accounts = this.companyAccounts
                    return;
                }

                this.$store
                    .dispatch(ACCOUNTS_COMPANY_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos las cuentas empresariales.')
                            return;
                        }

                        this.companyAccounts = data.data;
                        this.accounts = this.companyAccounts;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getCountries () {
                if (this.countries.length > 0) {
                    return;
                }
                this.$store
                    .dispatch(COUNTRIES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos los países.')
                            return;
                        }

                        this.countries = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getDepartments () {
                if (this.departments.length > 0) {
                    return;
                }
                this.$store
                    .dispatch(DEPARTMENTS_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos los departamentos.')
                            return;
                        }

                        this.departments = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getProvincesByDepartmentId (departmentId) {
                this.$store
                    .dispatch(PROVINCES_GET_ALL_BY_DEPARTMENT_ID, departmentId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todas las provincias.')
                            return;
                        }

                        this.provinces = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getDistrictsByProvinceId (provinceId) {
                this.$store
                    .dispatch(DISTRICTS_GET_ALL_BY_PROVINCE_ID, provinceId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos los distritos.')
                            return;
                        }

                        this.districts = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getDocumentTypes () {
                if (this.documentTypes.length > 0) {
                    return;
                }
                this.$store
                    .dispatch(DOCUMENT_TYPES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos los tipos de documento.')
                            return;
                        }

                        this.documentTypes = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getOcupations () {
                if (this.ocupations.length > 0) {
                    return;
                }
                this.$store
                    .dispatch(OCUPATIONS_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todas las ocupaciones.')
                            return;
                        }

                        this.ocupations = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },
            
            getFundOrigins () {
                if (this.fundOrigins.length > 0) {
                    return;
                }
                this.$store
                    .dispatch(FUND_ORIGINS_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todos origenes de fondos.')
                            return;
                        }

                        this.fundOrigins = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getBankAccounts () {
                this.$store
                    .dispatch(BANK_ACCOUNTS_GET_ALL, '?account_id=' + this.selectedAccount.id + '&account_type=' + this.selectedAccountType)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas bancarias.')
                            return;
                        }

                        this.allBankAccounts = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                    });
            },

            getBankAccountsToTransfer () {
                this.$store
                    .dispatch(BANK_ACCOUNTS_GET_ALL_TO_TRANSFER)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', 'Error desconocido al obtener todas las cuentas bancarias para transferir.')
                            return;
                        }

                        this.allBankAccountsToTransfer = data.data;
                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
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

            getLastExchangeRate () {
                this.$store
                    .dispatch(EXCHANGE_RATES_GET_LAST)
                    .then((data) => {
                        if (data.code != 200){
                            this.showMainAlert('danger', data.error)
                            return;
                        }

                        if (data.data != null) {
                            this.purchase = data.data.purchase;
                            this.sale = data.data.sale;

                            this.purchaseFormatted = this.$func.numberFormat(this.purchase.toFixed(3));
                            this.saleFormatted = this.$func.numberFormat(this.sale.toFixed(3));
                        }

                    })
                    .catch((err) => {
                        this.showMainAlert('danger', err)
                        return;
                    });
            },


            queryPersonByDocumentNumber (documentNumber, field) {
                this.$store
                    .dispatch(QUERY_PERSON_BY_DNI, documentNumber)
                    .then((data) => {
                        if (data.code != 200){
                            return;
                        }

                        let name = data.data.nombres;
                        let surname = data.data.apellidoPaterno;
                        if (data.data.apellidoMaterno != "") {
                            surname += ' ' + data.data.apellidoMaterno
                        }

                        if (field == "personal") {
                            this.newUser.name = name;
                            this.newUser.surname = surname;
                        } else if (field == "company_lr") {
                            this.newUser.name_lr = name;
                            this.newUser.surname_lr = surname;
                        } else if (field == "company_c") {
                            this.newUser.name_c = name;
                            this.newUser.surname_c = surname;
                        }
                    })
                    .catch((err) => {
                        // this.showModalAlert('danger', err)
                    });
            },

            queryCompanyByRuc (ruc) {
                this.$store
                    .dispatch(QUERY_COMPANY_BY_RUC, ruc)
                    .then((data) => {
                        if (data.code != 200){
                            return;
                        }

                        let businessName = data.data.razonSocial;
						let businessTurn = data.data.actEconomicas
						let address = data.data.direccion;
						let department = data.data.departamento;
						let province = data.data.provincia;
						let district = data.data.distrito;

						if (businessName != null && businessName != "") {
                            this.newUser.business_name = businessName;
						}

						if (businessTurn != null && businessTurn.length > 0) {
							let mainBusinessTurn = businessTurn[0].split(' - ');
							if (mainBusinessTurn.length > 0 && mainBusinessTurn[2] != null) {
                                this.newUser.business_turn = mainBusinessTurn[2];
							}
						}

						if (address != null && address != "") {
                            this.newUser.fiscal_address = address;
						}

						if (department != null && department != "") {
                            let departmentIndex = this.departments.findIndex(dep => dep.name.replaceAll('Á','A').replaceAll('É','E').replaceAll('Í','I').replaceAll('Ó','O').replaceAll('Ú','U') == department);
                            if (departmentIndex > -1) {
                                this.newUser.department_id = this.departments[departmentIndex].id;
                                this.getProvincesByDepartmentId(this.departments[departmentIndex].id);
                            }

                            if (province != null && province != "") {
                                setTimeout(function(){
                                    let provinceIndex = this.provinces.findIndex(prov => prov.name.replaceAll('Á','A').replaceAll('É','E').replaceAll('Í','I').replaceAll('Ó','O').replaceAll('Ú','U') == province);
                                    if (provinceIndex > -1) {
                                        this.newUser.province_id = this.provinces[provinceIndex].id;
                                        this.getDistrictsByProvinceId(this.provinces[provinceIndex].id);
                                    }
                                }.bind(this), 175);

                                if (district != null && district != "") {
                                    setTimeout(function(){ 
                                        let districtIndex = this.districts.findIndex(dis => dis.name.replaceAll('Á','A').replaceAll('É','E').replaceAll('Í','I').replaceAll('Ó','O').replaceAll('Ú','U') == district);
                                        if (districtIndex > -1) {
                                            this.newUser.district_id = this.districts[districtIndex].id;
                                        }
                                    }.bind(this), 350);
                                }
                            }
						}
                    })
                    .catch((err) => {
                        // this.showModalAlert('danger', err)
                    });
            },

            resetNewUserData () {
                this.newUser = {
                    user_username: "",
                    user_email: "",
                    user_password: "",
                    user_id: -1,

                    country_id: 139,
                    department_id: "",
                    province_id: "",
                    district_id: "",
                    phone: "",

                    // Personal account
                    document_type_id: "",
                    document_number: "",
                    name: "",
                    surname: "",
                    birthdate: "",
                    cellphone1: "",
                    cellphone2: "",
                    address: "",
                    ocupation_id: "",
                    exposed_person: 0,
                    position: "",
                    workplace: "",

                    // Company account
                    ruc: "",
                    business_name: "",
                    business_turn: "",
                    fiscal_address: "",
                    email: "",
                    document_type_lr_id: "",
                    document_number_lr: "",
                    name_lr: "",
                    surname_lr: "",
                    ocupation_lr_id: "",
                    document_type_c_id: "",
                    document_number_c: "",
                    name_c: "",
                    surname_c: "",
                    birthdate_c: "",
                    phone_c: "",
                };
                this.selectedNewUserType = "";
                this.$refs.document_front = null;
                this.$refs.document_back = null;
                this.$refs.document_front_lr = null;
                this.$refs.document_back_lr = null;
                this.$refs.document_front_c = null;
                this.$refs.document_back_c = null;
            },

            loadNewUserData () {
                this.getDocumentTypes();
                this.getCountries();
                this.getDepartments();
                this.getProvincesByDepartmentId(15);
                this.getDistrictsByProvinceId(128);
                this.getOcupations();  
            },

            resetNewBankAccountData () {
                this.newBankAccount = {
                    bank_id: "",
                    bank_account_type_id: "",
                    coin_type_id: "",
                    own_account: 1,
                    owner_document_type_id: "",
                };
            },

            loadNewBankAccountData () {
                this.getBanks();
                this.getBankAccountTypes();
                this.getCoinTypes();
                this.getDocumentTypes();
            },

            resetBankAccountsSelected () {
                this.selectedBankAccountSend = {
                    id: ""
                };
                this.selectedBankAccountReceive = {
                    id: ""
                };
                this.selectedBankAccountTransfer = {
                    id: ""
                };

                this.resetCoinTypesAbbreviations();
                this.resetChangeAmount();
            },

            changeSelectedAccountType(eventType = '') {
                if (this.selectedAccountType == "0") {
                    this.getPersonalAccounts();
                } else if (this.selectedAccountType == "1") {
                    this.getCompanyAccounts();
                }

                if (eventType == 'reset') {
                    this.selectedAccount = { id: "" }
                }
            },

            filterBankAccounts () {
                this.resetBankAccounts();

                // If NO bank account to send is selected
                if (this.selectedBankAccountSend.id == "") {
                    return;
                }

                this.setSendBankAccount();

                // Filter bank accounts to select by coin type
                this.bankAccountsToReceive = this.allBankAccounts.filter(bankAccount => bankAccount.coin_type_id != this.selectedBankAccountSend.coin_type_id);
                this.bankAccountsToTransfer = this.allBankAccountsToTransfer.filter(bankAccount => bankAccount.coin_type_id == this.selectedBankAccountSend.coin_type_id);
            },

            setSendBankAccount () {
                let selectedBankAccountSendAux = this.allBankAccounts.find(bankAccount => bankAccount.id == this.selectedBankAccountSend.id);
                this.selectedBankAccountSend = Object.assign(this.selectedBankAccountSend, selectedBankAccountSendAux);

                this.coinTypeSend = this.selectedBankAccountSend.coin_type;
                this.coinTypeSendAbbreviation = this.coinTypeSend.abbreviation;
            },

            setReceiveBankAccount () {
                this.selectedBankAccountReceive = this.bankAccountsToReceive.find(bankAccount => bankAccount.id == this.selectedBankAccountReceive.id);

                this.coinTypeReceive = this.selectedBankAccountReceive.coin_type;
                this.coinTypeReceiveAbbreviation = this.coinTypeReceive.abbreviation;
            },

            setTransferAccount () {
                this.selectedBankAccountTransfer = this.allBankAccountsToTransfer.find(bankAccount => bankAccount.id == this.selectedBankAccountTransfer.id);
            },

            setFundOrigin () {
                let selectedFundOriginAux = this.fundOrigins.find(fundOrigin => fundOrigin.code == this.selectedFundOrigin.code);
                this.selectedFundOrigin = Object.assign(this.selectedFundOrigin, selectedFundOriginAux);
            },

            setNumberWithoutFormat (number, field) {
                if (!number) {
                    number = "0.0";
                }

                let formattedNumber = this.$func.numberFormat(eval(number.replace(/,/g, '')).toFixed(3));

                switch (field) {
                    case 'purchase':
                        this.purchaseFormatted = formattedNumber;
                        this.purchase = eval(this.purchaseFormatted.replace(/,/g, '')).toFixed(3);
                        break;
                    case 'sale':
                        this.saleFormatted = formattedNumber;
                        this.sale = eval(this.saleFormatted.replace(/,/g, '')).toFixed(3);
                        break;
                    case 'amount':
                        this.amountFormatted = formattedNumber;
                        this.amount = eval(this.amountFormatted.replace(/,/g, '')).toFixed(3);
                        break;
                    default:
                        break;
                }
            },

            calculateChangeAmount () {
                this.hideMainAlert();

                if (this.selectedBankAccountSend.coin_type_id == null) {
                    this.showMainAlert('danger', 'Debe seleccionar que tipo de moneda envía.');
                    return;
                }

                /*
                    Si transfiere soles, chequeo que el tipo de cambio 'venta' NO sea 0.000 o nulo.
                    Si transfiere dólares, chequeo que el tipo de cambio 'compra' NO sea 0.000 o nulo.
                */
                if (this.selectedBankAccountSend.coin_type_id == 1) { // Soles
                    if (this.saleFormatted == "0.000") {
                        this.showMainAlert('danger', 'Debe ingresar el tipo de cambio: venta.');
                        return;
                    }
                } else if (this.selectedBankAccountSend.coin_type_id == 2) { // Dólares
                    if (this.purchaseFormatted == "0.000") {
                        this.showMainAlert('danger', 'Debe ingresar el tipo de cambio: compra.');
                        return;
                    }
                }

                /*
                    Si transfiere soles, seteo el cambio haciendo monto / tipo cambio venta
                    Si transfiere dólares, seteo el cambio haciendo monto * tipo cambio compra
                */
                if (this.selectedBankAccountSend.coin_type_id == 1) { // Soles
                    this.changeAmount = (this.amount / this.sale).toFixed(3);
                    this.changeAmountFormatted = this.$func.numberFormat(this.changeAmount);
                } else if (this.selectedBankAccountSend.coin_type_id == 2) { // Dólares
                    this.changeAmount = (this.amount * this.purchase).toFixed(3);
                    this.changeAmountFormatted = this.$func.numberFormat(this.changeAmount);
                }
            },


            resetCoinTypesAbbreviations () {
                this.coinTypeSendAbbreviation = "";
                this.coinTypeReceiveAbbreviation = "";
            },

            resetChangeAmount () {
                this.changeAmount = 0;
                this.changeAmountFormatted = "";
            },

            resetBankAccounts () {
                this.selectedBankAccountReceive = {
                    id: ""
                };
                this.bankAccountsToReceive = [];
                this.bankAccountsToTransfer = [];
                this.coinTypeSendData = null;
                this.coinTypeSendAbbreviation = '';
                this.coinTypeReceiveAbbreviation = '';
            },

            storeNewUser(e) {
                e.preventDefault();

                let validated = this.validateNewUser();
                if (!validated.status) {
                    this.showModalAlert('danger', validated.message);
                    return;
                }

                this.saveUserButtonDisabled = true

                let userFormData = new FormData();
                userFormData.append('username', this.newUser.user_username);
                userFormData.append('email', this.newUser.user_email);
                userFormData.append('password', this.newUser.user_password);

                this.$store
                    .dispatch(USERS_STORE, userFormData)
                    .then((data) => {
                        if (!(data.code == 200 || data.code == 201)){
                            this.showModalAlert('danger', data.error)
                            this.saveUserButtonDisabled = false
                            return;
                        }

                        this.newUser.user_id = data.id;

                        let dispatch;
                        let accoutFormData = new FormData();
                        accoutFormData.append('user_id', this.newUser.user_id);
                        
                        if (this.selectedNewUserType == "0") {
                            dispatch = ACCOUNTS_PERSONAL_STORE;

                            accoutFormData.append('document_type_id', this.newUser.document_type_id);
                            accoutFormData.append('document_number', this.newUser.document_number);
                            accoutFormData.append('document_front', this.$refs.document_front.files[0]);
                            accoutFormData.append('document_back', this.$refs.document_back.files[0]);
                            accoutFormData.append('name', this.newUser.name);
                            accoutFormData.append('surname', this.newUser.surname);
                            accoutFormData.append('birthdate', this.newUser.birthdate);
                            accoutFormData.append('phone', this.newUser.phone);
                            accoutFormData.append('cellphone1', this.newUser.cellphone1);
                            accoutFormData.append('cellphone2', this.newUser.cellphone2);
                            accoutFormData.append('country_id', this.newUser.country_id);
                            accoutFormData.append('department_id', this.newUser.department_id);
                            accoutFormData.append('province_id', this.newUser.province_id);
                            accoutFormData.append('district_id', this.newUser.district_id);
                            accoutFormData.append('address', this.newUser.address);
                            accoutFormData.append('ocupation_id', this.newUser.ocupation_id);
                            accoutFormData.append('exposed_person', this.exposed_person == 1 ? 1 : 0);

                            if(this.newUser.exposed_person == 1){
                                accoutFormData.append('position', this.newUser.position);
                                accoutFormData.append('workplace', this.newUser.workplace);
                            }
                        } else if (this.selectedNewUserType == "1") {
                            dispatch = ACCOUNTS_COMPANY_STORE;

                            accoutFormData.append('ruc', this.newUser.ruc);
                            accoutFormData.append('business_name', this.newUser.business_name);
                            accoutFormData.append('business_turn', this.newUser.business_turn);
                            accoutFormData.append('fiscal_address', this.newUser.fiscal_address);
                            accoutFormData.append('country_id', this.newUser.country_id);
                            accoutFormData.append('department_id', this.newUser.department_id);
                            accoutFormData.append('province_id', this.newUser.province_id);
                            accoutFormData.append('district_id', this.newUser.district_id);
                            accoutFormData.append('email', this.newUser.email);
                            accoutFormData.append('phone', this.newUser.phone);
                            accoutFormData.append('document_type_lr_id', this.newUser.document_type_lr_id);
                            accoutFormData.append('document_number_lr', this.newUser.document_number_lr);
                            accoutFormData.append('document_front_lr', this.$refs.document_front_lr.files[0]);
                            accoutFormData.append('document_back_lr', this.$refs.document_back_lr.files[0]);
                            accoutFormData.append('name_lr', this.newUser.name_lr);
                            accoutFormData.append('surname_lr', this.newUser.surname_lr);
                            accoutFormData.append('ocupation_lr_id', this.newUser.ocupation_lr_id);
                            accoutFormData.append('document_type_c_id', this.newUser.document_type_c_id);
                            accoutFormData.append('document_number_c', this.newUser.document_number_c);
                            accoutFormData.append('document_front_c', this.$refs.document_front_c.files[0]);
                            accoutFormData.append('document_back_c', this.$refs.document_back_c.files[0]);
                            accoutFormData.append('name_c', this.newUser.name_c);
                            accoutFormData.append('surname_c', this.newUser.surname_c);
                            accoutFormData.append('birthdate_c', this.newUser.birthdate_c);
                            accoutFormData.append('phone_c', this.newUser.phone_c);
                        }

                        this.$store
                            .dispatch(dispatch, accoutFormData)
                            .then((data) => {
                                if (data.code != 201){
                                    this.showModalAlert('danger', data.error)
                                    this.saveUserButtonDisabled = false
                                    return;
                                }

                                if (this.selectedNewUserType == "0") {
                                    this.personalAccounts.push(data.data)
                                } else if (this.selectedNewUserType == "1") {
                                    this.companyAccounts.push(data.data)
                                }

                                this.selectedAccountType = this.selectedNewUserType
                                this.selectedAccount = data.data;
                                this.changeSelectedAccountType('');

                                // close modal
                                this.showModalAlert('success', 'Cuenta de usuario creada correctamente!');

                                setTimeout(function(){
                                    this.closeModal('modal-new-user')
                                }.bind(this), 1000); // After 1 second, hide the modal
                            })
                            .catch((err) => {
                                this.showModalAlert('danger', err)
                                this.saveUserButtonDisabled = false
                                return;
                            });
                        })
                    .catch((err) => {
                        this.showModalAlert('danger', err)
                        this.saveUserButtonDisabled = false
                        return;
                    });
                this.saveUserButtonDisabled = false
            },

            validateNewUser () {
                if (this.newUser.user_username == "") {
                    return { status: false, message: "El nombre de usuario es requerido." };
                }
                if (this.newUser.user_email == "") {
                    return { status: false, message: "El email es requerido." };
                }
                if (!this.$func.isValidEmail(this.newUser.user_email)) {
                    return { status: false, message: "El email es inválido." };
                }
                if (this.newUser.user_password == "") {
                    return { status: false, message: "La password es requerida." };
                }
                if (this.newUser.user_password.length < 8) {
                    return { status: false, message: "La password debe tener al menos 8 caracteres." };
                }

                if (this.selectedNewUserType == "") {
                    return { status: false, message: "Debe seleccionar el tipo de cuenta de usuario." };
                }

                if (this.selectedNewUserType == "0") {
                    return this.validatePersonalAccount();
                } else if (this.selectedNewUserType == "1") {
                    return this.validateCompanyAccount();
                }

                return { status: false, message: "Error desconocido." };
            },

            validatePersonalAccount () {
                if (!this.newUser.document_type_id) {
                    return { status: false, message: "Debe seleccionar el tipo de documento." };
                }
                if (!this.newUser.document_number) {
                    return { status: false, message: "Debe ingresar el número de documento." };
                }
                if (!this.$refs.document_front.files[0]) {
                    return { status: false, message: "Debe seleccionar documento (frente)." };
                }
                if (!this.$refs.document_back.files[0]) {
                    return { status: false, message: "Debe seleccionar documento (dorso)." };
                }
                if (!this.newUser.name) {
                    return { status: false, message: "Debe ingresar el/los nombre/s." };
                }
                if (!this.newUser.surname) {
                    return { status: false, message: "Debe ingresar el/los apellidos/s." };
                }
                if (!this.newUser.birthdate) {
                    return { status: false, message: "Debe ingresar la fecha de nacimiento." };
                }

                if (!this.newUser.country_id) {
                    return { status: false, message: "Debe seleccionar el país." };
                }
                if (!this.newUser.department_id) {
                    return { status: false, message: "Debe seleccionar el departamento." };
                }
                if (!this.newUser.province_id) {
                    return { status: false, message: "Debe seleccionar la provincia." };
                }
                if (!this.newUser.district_id) {
                    return { status: false, message: "Debe seleccionar el distrito." };
                }
                if (!this.newUser.address) {
                    return { status: false, message: "Debe ingresar la dirección." };
                }

                if (!this.newUser.ocupation_id) {
                    return { status: false, message: "Debe seleccionar la ocupación." };
                }
                if (this.newUser.exposed_person != 0 && this.newUser.exposed_person != 1) {
                    return { status: false, message: "Debe seleccionar si es una persona expuesta." };
                }

                if (this.newUser.exposed_person == 1) {
                    if (!this.newUser.position) {
                        return { status: false, message: "Debe ingresar el cargo." };
                    }
                    else if (!this.newUser.workplace) {
                        return { status: false, message: "Debe ingresar el lugar de trabajo." };
                    }
                }

                return { status: true, message: "" };
            },

            validateCompanyAccount () {
                if (!this.newUser.ruc) {
                    return { status: false, message: "Debe ingresar el RUC." };
                }
                if (!this.newUser.business_name) {
                    return { status: false, message: "Debe ingresar la razón social." };
                }
                if (!this.newUser.business_turn) {
                    return { status: false, message: "Debe ingresar el giro de negocio." };
                }
                if (!this.newUser.fiscal_address) {
                    return { status: false, message: "Debe ingresar la dirección." };
                }
                if (!this.newUser.country_id) {
                    return { status: false, message: "Debe seleccionar el país." };
                }
                if (!this.newUser.department_id) {
                    return { status: false, message: "Debe seleccionar el departamento." };
                }
                if (!this.newUser.province_id) {
                    return { status: false, message: "Debe seleccionar la provincia." };
                }
                if (!this.newUser.district_id) {
                    return { status: false, message: "Debe seleccionar el distrito." };
                }
                if (!this.newUser.email) {
                    return { status: false, message: "Debe ingresar el correo electrónico." };
                }
                if (!this.newUser.phone) {
                    return { status: false, message: "Debe ingresar el teléfono." };
                }
                if (!this.newUser.document_type_lr_id) {
                    return { status: false, message: "Debe seleccionar el tipo de documento del representante legal." };
                }
                if (!this.newUser.document_number_lr) {
                    return { status: false, message: "Debe ingresar el número de documento del representante legal." };
                }
                if (!this.$refs.document_front_lr.files[0]) {
                    return { status: false, message: "Debe seleccionar documento (frente) del representante legal." };
                }
                if (!this.$refs.document_back_lr.files[0]) {
                    return { status: false, message: "Debe seleccionar documento (dorso) del representante legal." };
                }
                if (!this.newUser.name_lr) {
                    return { status: false, message: "Debe ingresar el/los nombre/s del representante legal." };
                }
                if (!this.newUser.surname_lr) {
                    return { status: false, message: "Debe ingresar el/los apellido/s del representante legal." };
                }
                if (!this.newUser.ocupation_lr_id) {
                    return { status: false, message: "Debe seleccionar la ocupación del representante legal." };
                }
                if (!this.newUser.document_type_c_id) {
                    return { status: false, message: "Debe seleccionar el tipo de documento del contacto." };
                }
                if (!this.newUser.document_number_c) {
                    return { status: false, message: "Debe ingresar el número de documento del contacto." };
                }
                if (!this.$refs.document_front_c.files[0]) {
                    return { status: false, message: "Debe seleccionar documento (frente) del contacto." };
                }
                if (!this.$refs.document_back_c.files[0]) {
                    return { status: false, message: "Debe seleccionar documento (dorso) del contacto." };
                }
                if (!this.newUser.name_c) {
                    return { status: false, message: "Debe ingresar el/los nombre/s del del contacto." };
                }
                if (!this.newUser.surname_c) {
                    return { status: false, message: "Debe ingresar el/los apellido/s del del contacto." };
                }
                if (!this.newUser.birthdate_c) {
                    return { status: false, message: "Debe ingresar la fecha de nacimiento del contacto." };
                }

                return { status: true, message: "" };
            },

            storeBankAccount(e) {
                e.preventDefault();

                let validated = this.validateNewBankAccount();
                if (!validated.status) {
                    this.showModalAlert('danger', validated.message);
                    return;
                }

                let body = this.newBankAccount
                body.account_type = this.selectedAccountType;
                body.active_account = this.selectedAccount.id;

                this.$store
                    .dispatch(BANK_ACCOUNTS_STORE, body)
                    .then((data) => {
                        if (data.code != 201){
                            this.showModalAlert('danger', data.error)
                            return;
                        }

                        this.allBankAccounts = this.allBankAccounts.concat(data.data);

                        if (this.selectedBankAccountSend.id != "") {
                            if (this.selectedBankAccountSend.coin_type_id != data.data.coin_type_id) {
                                this.bankAccountsToReceive.push(data.data);
                            }
                        }

                        // close modal
                        this.showModalAlert('success', 'Cuenta bancaria creada correctamente!');

                        setTimeout(function(){
                            this.closeModal('modal-new-bank-account')
                        }.bind(this), 1000); // After 1 second, hide the modal
                    })
                    .catch((err) => {
                        this.showModalAlert('danger', err)
                        return;
                    });
            },

            validateNewBankAccount () {
                if(this.newBankAccount.bank_id == ""){
                    return { status: false, message: 'Debe seleccionar el banco.' };
                }
                if(this.newBankAccount.account_number == ""){
                    return { status: false, message: 'Debe ingresar el número de cuenta.' };
                }
                if(this.newBankAccount.bank_account_type_id == ""){
                    return { status: false, message: 'Debe seleccionar el tipo de cuenta.' };
                }
                if(this.newBankAccount.account_number_cci == ""){
                    return { status: false, message: 'Debe ingresar el número de cuenta CCI.' };
                }
                if(this.newBankAccount.coin_type_id == ""){
                    return { status: false, message: 'Debe seleccionar el tipo de moneda.' };
                }
                if(this.newBankAccount.alias == ""){
                    return { status: false, message: 'Debe ingresar el alias.' };
                }

                if(this.newBankAccount.own_account == 0){
                    if(this.newBankAccount.owner_name == null || this.newBankAccount.owner_name == ""){
                        return { status:false, message: 'Debe ingresar nombre del propietario.' };
                    }
                    if(this.newBankAccount.owner_document_type_id == ""){
                        return { status:false, message: 'Debe seleccionar el tipo de documento.' };
                    }
                    if(this.newBankAccount.owner_document_number == null || this.newBankAccount.owner_document_number == ""){
                        return { status:false, message: 'Debe ingresar el número de documento.' };
                    }
                    if(!(this.newBankAccount.authorized_deposit == 0 || this.newBankAccount.authorized_deposit == 1)){
                        return { status:false, message: 'Debe seleccionar si autoriza el depósito.' };
                    }
                }
                else {
                    this.newBankAccount.owner_name = null;
                    this.newBankAccount.owner_document_type_id = null;
                    this.newBankAccount.owner_document_number = null;
                    this.newBankAccount.authorized_deposit = null;
                    return { status: true, message: "" };
                }

                return { status: false, message: "Error desconocido." };
            },

            showMainAlert(variant, message) {
                this.mainAlertVariant = variant;
                this.mainAlertMessage = message;
                this.mainAlert = true;
                window.scrollTo(0,0);
            },

            hideMainAlert () {
                this.mainAlertVariant = '';
                this.mainAlertMessage = '';
                this.mainAlert = false;
            },

            showModalAlert(variant, message) {
                this.savedAlertVariant = variant;
                this.savedAlertMessage = message;
                this.savedAlert = true;
            },

            resetAlertMessage(){
                this.savedAlertVariant = '';
                this.savedAlertMessage = '';
                this.savedAlert = false;
            },

            redirectTimer (redirectMessage, url) {
                this.redirectMessage = redirectMessage;
                let _this = this;

                let redirectTime = window.setInterval(function () {
                    if (_this.redirectMessageSeconds === 0) {
                        _this.redirectMessageSeconds = 0;
                        window.clearInterval(redirectTime);
                        if (url != '') {
                            _this.$router.push(url);
                        }
                        else {
                            _this.$router.go(); // Reload the page
                        }
                    } else {
                        _this.redirectMessageSeconds -= 1;
                    }
                }, 1000);
            },

            closeModal(modalId){
                this.$root.$emit('bv::hide::modal', modalId);
            },
        },
        computed: {

        },
        created() {
            this.getPersonalAccounts();
            this.getBankAccountsToTransfer()
            this.getLastExchangeRate();
            this.getFundOrigins();
        },
        mounted() {},
    };
</script>