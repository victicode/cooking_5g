<template>

    <div class="row" v-if="dangerAlert">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <b-alert :show="dangerAlert" dismissible fade variant="danger" @dismissed="dangerAlert=false">{{ dangerAlertMessage }}</b-alert>
            </div>
            <hr>
    </div>
    <!--begin: Wizard-->
    <div class="wizard wizard-3" id="kt_wizard_v3" data-wizard-state="step-first" data-wizard-clickable="true">
            <!--begin: Wizard Nav -->
            <div class="wizard-nav border-right px-8">
                <div class="wizard-steps">
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-label">
                            <h3 class="wizard-title"><span>1</span>Datos</h3>
                            <div class="wizard-bar"></div>
                        </div>
                    </div>
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-label">
                            <h3 class="wizard-title"><span>2</span>Legal</h3>
                            <div class="wizard-bar"></div>
                        </div>
                    </div>
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-label">
                            <h3 class="wizard-title"><span>3</span>Contacto</h3>
                            <div class="wizard-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end: Wizard Nav -->

            <!--begin: Wizard Body -->
            <div class="wizard-body px-8">
                <!--begin: Wizard Form-->
                <div class="row">
                    <div class="offset-xxl-2 col-xxl-8">
                        <form class="form" id="kt_form">
                            <!--begin: Wizard Step 1-->
                            <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                <h4 class="mb-10 font-weight-bold text-dark">
                                    Ingresa los datos de la empresa
                                </h4>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Número de RUC</label>
                                            <input type="text" class="form-control form-control-lg" name="ruc" ref="ruc" placeholder="Ingresa el número de RUC"  @change="queryCompanyByRuc($event.target.value)">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Razón social</label>
                                            <input type="text" class="form-control form-control-lg" name="business_name" ref="business_name" placeholder="Ingresa la razón social">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Giro de negocio</label>
                                            <input type="text" class="form-control form-control-lg" name="business_turn" ref="business_turn" placeholder="Ingresa el giro de negocio">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input type="text" class="form-control form-control-lg" name="fiscal_address" ref="fiscal_address" placeholder="Ingresa la dirección fiscal">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>País</label>
                                            <select class="form-control form-control-lg" name="country_id" ref="country_id">
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
                                            <select class="form-control form-control-lg" name="department_id" ref="department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                                                <option value="">Selecciona el departamento</option>
                                                <option v-for="department in departments" :value="department.id" :key="department.id">
                                                    {{ department.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Provincia</label>
                                            <select class="form-control form-control-lg" name="province_id" ref="province_id" @change="getDistrictsByProvinceId($event.target.value)">
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
                                            <select class="form-control form-control-lg" name="district_id" ref="district_id">
                                                <option value="">Selecciona el distrito</option>
                                                <option v-for="district in districts" :value="district.id" :key="district.id">
                                                    {{ district.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Correo electrónico</label>
                                            <input type="text" class="form-control form-control-lg" name="email" ref="email" placeholder="Ingresa el correo electrónico">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control form-control-lg" name="phone" ref="phone" placeholder="Ingresa el número de teléfono">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end: Wizard Step 1-->

                            <!--begin: Wizard Step 2-->
                            <div class="pb-5" data-wizard-type="step-content">
                                <h4 class="mb-10 font-weight-bold text-dark">
                                    Ingresa los datos del representante legal
                                </h4>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Tipo de documento</label>
                                            <select class="form-control form-control-lg" name="document_type_lr_id" ref="document_type_lr_id">
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
                                            <input type="text" class="form-control form-control-lg" name="document_number_lr" ref="document_number_lr" placeholder="Ingresa el número de documento" @change="queryPersonByDocumentNumber($event.target.value, 'lr')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Nombre/s</label>
                                            <input type="text" class="form-control form-control-lg" name="name_lr" ref="name_lr" placeholder="Ingresa el nombre/s">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Apellido/s</label>
                                            <input type="text" class="form-control form-control-lg" name="surname_lr" ref="surname_lr" placeholder="Ingresa el apellido/s">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Ocupación</label>
                                            <select class="form-control form-control-lg" name="ocupation_lr_id" ref="ocupation_lr_id">
                                                <option value="">Selecciona la ocupación</option>
                                                <option v-for="ocupation in ocupations" :value="ocupation.id" :key="ocupation.id">
                                                    {{ ocupation.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end: Wizard Step 2-->

                            <!--begin: Wizard Step 3-->
                            <div class="pb-5" data-wizard-type="step-content">
                                <h4 class="mb-10 font-weight-bold text-dark">
                                    Ingresa los datos de contacto
                                </h4>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Tipo de documento</label>
                                            <select class="form-control form-control-lg" name="document_type_c_id" ref="document_type_c_id">
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
                                            <input type="text" class="form-control form-control-lg" name="document_number_c" ref="document_number_c" placeholder="Ingresa el número de documento" @change="queryPersonByDocumentNumber($event.target.value, 'c')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Nombre/s</label>
                                            <input type="text" class="form-control form-control-lg" name="name_c" ref="name_c" placeholder="Ingresa el/los nombre/s">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Apellido/s</label>
                                            <input type="text" class="form-control form-control-lg" name="surname_c" ref="surname_c" placeholder="Ingresa el/los apellido/s">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Fecha de nacimiento</label>
                                            <input type="date" class="form-control form-control-lg" name="birthdate_c" ref="birthdate_c" placeholder="Selecciona la fecha de nacimiento">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control form-control-lg" name="phone_c" ref="phone_c" placeholder="Ingresa el número de teléfono">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end: Wizard Step 3-->

                            <!--begin: Wizard Actions -->
                            <div class="d-flex justify-content-between border-top pt-10">
                                <div class="mr-2">
                                    <button class="btn btn-light-primary font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-prev">
                                        Anterior
                                    </button>
                                </div>
                                <div>
                                    <button class="btn btn-success font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-submit">
                                        Crear cuenta
                                    </button>
                                    <button class="btn btn-primary font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-next">
                                        Siguiente
                                    </button>
                                </div>
                            </div>
                            <!--end: Wizard Actions -->
                        </form>
                    </div>
                    <!--end: Wizard-->
                </div>
            </div>
            <!--end: Wizard Body -->
    </div>
    <!--end: Wizard-->
</template>

<style lang="scss">
    @import "@/assets/sass/pages/wizard/wizard-3.scss";
</style>

<script>
    import KTWizard from "@/assets/js/components/wizard";
    import Swal from "sweetalert2";
    import { ACCOUNTS_COMPANY_STORE } from "@/core/services/store/account.module";
    import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
    import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
    import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
    import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
    import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
    import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
    import { QUERY_PERSON_BY_DNI, QUERY_COMPANY_BY_RUC } from "@/core/services/store/peru_consult.module";

    export default {
        data() {
            return {
                countries: [],
                departments: [],
                provinces: [],
                districts: [],
                documentTypes: [],
                ocupations: [],
                dangerAlert: false,
                dangerAlertMessage: "",
            }
        },
        components: {

        },
        mounted() {
            // Initialize form wizard
            const wizard = new KTWizard("kt_wizard_v3", {
                startStep: 1, // initial active step number
                clickableSteps: false // allow step clicking
            });

            // Validation before going to next page
            this.$nextTick().then((thisAux) => {
                wizard.on("change", function(wizardObj) {
                    // If press prev button, dont validate form data
                    if (wizardObj.getStep() > wizard.getNewStep()) {
                        return;
                    }

                    let canContinue = true;
                    switch (wizardObj.getStep()) {
                        case 1:
                            if (
                                !thisAux.$refs.ruc.value ||
                                !thisAux.$refs.business_name.value ||
                                !thisAux.$refs.business_turn.value ||
                                !thisAux.$refs.fiscal_address.value ||
                                !thisAux.$refs.country_id.value ||
                                !thisAux.$refs.department_id.value ||
                                !thisAux.$refs.province_id.value ||
                                !thisAux.$refs.district_id.value ||
                                !thisAux.$refs.email.value ||
                                !thisAux.$refs.phone.value
                            ) {
                                canContinue = false;
                                if (!thisAux.$refs.ruc.value) {
                                    thisAux.showDangerAlert('Debe ingresar el RUC.');
                                }
                                else if (!thisAux.$refs.business_name.value) {
                                    thisAux.showDangerAlert('Debe ingresar la razón social.');
                                }
                                else if (!thisAux.$refs.business_turn.value) {
                                    thisAux.showDangerAlert('Debe ingresar el giro de negocio.');
                                }
                                else if (!thisAux.$refs.fiscal_address.value) {
                                    thisAux.showDangerAlert('Debe ingresar la dirección.');
                                }
                                else if (!thisAux.$refs.country_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar el país.');
                                }
                                else if (!thisAux.$refs.department_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar el departamento.');
                                }
                                else if (!thisAux.$refs.province_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar la provincia.');
                                }
                                else if (!thisAux.$refs.district_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar el distrito.');
                                }
                                else if (!thisAux.$refs.email.value) {
                                    thisAux.showDangerAlert('Debe ingresar el correo electrónico.');
                                }
                                else if (!thisAux.$refs.phone.value) {
                                    thisAux.showDangerAlert('Debe ingresar el teléfono.');
                                }
                            }
                            break;
                        case 2:
                            if (
                                !thisAux.$refs.document_type_lr_id.value ||
                                !thisAux.$refs.document_number_lr.value ||
                                !thisAux.$refs.document_front_lr.files[0] ||
                                !thisAux.$refs.document_back_lr.files[0] ||
                                !thisAux.$refs.name_lr.value ||
                                !thisAux.$refs.surname_lr.value ||
                                !thisAux.$refs.ocupation_lr_id.value
                            ) {
                                canContinue = false;
                                if (!thisAux.$refs.document_type_lr_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar el tipo de documento.');
                                }
                                else if (!thisAux.$refs.document_number_lr.value) {
                                    thisAux.showDangerAlert('Debe ingresar el número de documento.');
                                }
                                else if (!thisAux.$refs.document_front_lr.files[0]) {
                                    thisAux.showDangerAlert('Debe seleccionar documento (frente).');
                                }
                                else if (!thisAux.$refs.document_back_lr.files[0]) {
                                    thisAux.showDangerAlert('Debe seleccionar documento (dorso).');
                                }
                                else if (!thisAux.$refs.name_lr.value) {
                                    thisAux.showDangerAlert('Debe ingresar el/los nombre/s.');
                                }
                                else if (!thisAux.$refs.surname_lr.value) {
                                    thisAux.showDangerAlert('Debe ingresar el/los apellidos/s.');
                                }
                                else if (!thisAux.$refs.ocupation_lr_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar la ocupación.');
                                }
                            }
                            break;
                        default:
                            canContinue = false;
                            break;
                    }

                    if (!canContinue) {
                        wizardObj.stop();
                        return;
                    }

                    thisAux.hideDangerAlert();
                });

                // Change event
                wizard.on("submit", function(wizardObj) {
                    let canContinue = true;
                    if (
                        !thisAux.$refs.document_type_c_id.value ||
                        !thisAux.$refs.document_number_c.value ||
                        !thisAux.$refs.document_front_c.files[0] ||
                        !thisAux.$refs.document_back_c.files[0] ||
                        !thisAux.$refs.name_c.value ||
                        !thisAux.$refs.surname_c.value ||
                        !thisAux.$refs.birthdate_c.value
                    ) {
                        canContinue = false;
                        if (!thisAux.$refs.document_type_c_id.value) {
                            thisAux.showDangerAlert('Debe seleccionar el tipo de documento.');
                        }
                        else if (!thisAux.$refs.document_number_c.value) {
                            thisAux.showDangerAlert('Debe ingresar el número de documento.');
                        }
                        else if (!thisAux.$refs.document_front_c.files[0]) {
                            thisAux.showDangerAlert('Debe seleccionar documento (frente).');
                        }
                        else if (!thisAux.$refs.document_back_c.files[0]) {
                            thisAux.showDangerAlert('Debe seleccionar documento (dorso).');
                        }
                        else if (!thisAux.$refs.name_c.value) {
                            thisAux.showDangerAlert('Debe ingresar el/los nombre/s.');
                        }
                        else if (!thisAux.$refs.surname_c.value) {
                            thisAux.showDangerAlert('Debe ingresar el/los apellidos/s.');
                        }
                        else if (!thisAux.$refs.birthdate_c.value) {
                            thisAux.showDangerAlert('Debe ingresar la fecha de nacimiento.');
                        }
                    }

                    if (!canContinue) {
                        wizardObj.stop();
                        return;
                    }

                    thisAux.store()
                });
            });

        },
        methods: {
            store () {
                let formData = new FormData();

                formData.append('ruc', this.$refs.ruc.value);
                formData.append('business_name', this.$refs.business_name.value);
                formData.append('business_turn', this.$refs.business_turn.value);
                formData.append('fiscal_address', this.$refs.fiscal_address.value);
                formData.append('country_id', this.$refs.country_id.value);
                formData.append('department_id', this.$refs.department_id.value);
                formData.append('province_id', this.$refs.province_id.value);
                formData.append('district_id', this.$refs.district_id.value);
                formData.append('email', this.$refs.email.value);
                formData.append('phone', this.$refs.phone.value);
                formData.append('document_type_lr_id', this.$refs.document_type_lr_id.value);
                formData.append('document_number_lr', this.$refs.document_number_lr.value);
                formData.append('document_front_lr', this.$refs.document_front_lr.files[0]);
                formData.append('document_back_lr', this.$refs.document_back_lr.files[0]);
                formData.append('name_lr', this.$refs.name_lr.value);
                formData.append('surname_lr', this.$refs.surname_lr.value);
                formData.append('ocupation_lr_id', this.$refs.ocupation_lr_id.value);
                formData.append('document_type_c_id', this.$refs.document_type_c_id.value);
                formData.append('document_number_c', this.$refs.document_number_c.value);
                formData.append('document_front_c', this.$refs.document_front_c.files[0]);
                formData.append('document_back_c', this.$refs.document_back_c.files[0]);
                formData.append('name_c', this.$refs.name_c.value);
                formData.append('surname_c', this.$refs.surname_c.value);
                formData.append('birthdate_c', this.$refs.birthdate_c.value);
                formData.append('phone_c', this.$refs.phone_c.value);

                this.$store
                    .dispatch(ACCOUNTS_COMPANY_STORE, formData)
                    .then((data) => {
                        if (data.code != 201){
                            this.showDangerAlert('Error desconocido al guardar el perfil.');
                            return;
                        }

                        Swal.fire({
                            title: 'Perfil creado correctamente!',
                            text: 'Hemos creado su perfil correctamente, lo estamos redireccionando al inicio!',
                            icon: "success",
                            confirmButtonClass: "btn btn-primary",
                        });

                        window.localStorage.setItem("has_account",  true);

                        setTimeout(() => {
                            this.$router.push({ name: "dashboard" })
                        }, 1000);

                    })
                    .catch((err) => {
                        this.showDangerAlert(err);
                    });
            },

            getCountries () {
                this.$store
                    .dispatch(COUNTRIES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showDangerAlert('Error desconocido al obtener todos los países.')
                            return;
                        }

                        this.countries = data.data;
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            getDepartments () {
                this.$store
                    .dispatch(DEPARTMENTS_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showDangerAlert('Error desconocido al obtener todos los departamentos.')
                            return;
                        }

                        this.departments = data.data;
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            getProvincesByDepartmentId (departmentId) {
                this.$store
                    .dispatch(PROVINCES_GET_ALL_BY_DEPARTMENT_ID, departmentId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showDangerAlert('Error desconocido al obtener todas las provincias.')
                            return;
                        }

                        this.provinces = data.data;
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            getDistrictsByProvinceId (provinceId) {
                this.$store
                    .dispatch(DISTRICTS_GET_ALL_BY_PROVINCE_ID, provinceId)
                    .then((data) => {
                        if (data.code != 200){
                            this.showDangerAlert('Error desconocido al obtener todos los distritos.')
                            return;
                        }

                        this.districts = data.data;
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            getDocumentTypes () {
                this.$store
                    .dispatch(DOCUMENT_TYPES_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showDangerAlert('Error desconocido al obtener todos los tipos de documento.')
                            return;
                        }

                        this.documentTypes = data.data;
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            getOcupations () {
                this.$store
                    .dispatch(OCUPATIONS_GET_ALL)
                    .then((data) => {
                        if (data.code != 200){
                            this.showDangerAlert('Error desconocido al obtener todas las ocupaciones.')
                            return;
                        }

                        this.ocupations = data.data;
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            queryPersonByDocumentNumber (documentNumber, personType) {
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

                        if (personType == 'lr') {
                            this.$refs.name_lr.value = name;
                            this.$refs.surname_lr.value = surname;
                        } else if (personType == 'c'){
                            this.$refs.name_c.value = name;
                            this.$refs.surname_c.value = surname;
                        }

                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
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
                            this.$refs.business_name.value = businessName;
						}

						if (businessTurn != null && businessTurn.length > 0) {
							let mainBusinessTurn = businessTurn[0].split(' - ');
							if (mainBusinessTurn.length > 0 && mainBusinessTurn[2] != null) {
                                this.$refs.business_turn.value = mainBusinessTurn[2];
							}
						}

						if (address != null && address != "") {
                            this.$refs.fiscal_address.value = address;
						}

						if (department != null && department != "") {
                            let departmentIndex = this.departments.findIndex(dep => dep.name.replaceAll('Á','A').replaceAll('É','E').replaceAll('Í','I').replaceAll('Ó','O').replaceAll('Ú','U') == department);
                            if (departmentIndex > -1) {
                                this.$refs.department_id.value = this.departments[departmentIndex].id;
                                this.getProvincesByDepartmentId(this.departments[departmentIndex].id);
                            }

                            if (province != null && province != "") {
                                setTimeout(function(){
                                    let provinceIndex = this.provinces.findIndex(prov => prov.name.replaceAll('Á','A').replaceAll('É','E').replaceAll('Í','I').replaceAll('Ó','O').replaceAll('Ú','U') == province);
                                    if (provinceIndex > -1) {
                                        this.$refs.province_id.value = this.provinces[provinceIndex].id;
                                        this.getDistrictsByProvinceId(this.provinces[provinceIndex].id);
                                    }
                                }.bind(this), 175);

                                if (district != null && district != "") {
                                    setTimeout(function(){ 
                                        let districtIndex = this.districts.findIndex(dis => dis.name.replaceAll('Á','A').replaceAll('É','E').replaceAll('Í','I').replaceAll('Ó','O').replaceAll('Ú','U') == district);
                                        if (districtIndex > -1) {
                                            this.$refs.district_id.value = this.districts[districtIndex].id;
                                        }
                                    }.bind(this), 350);
                                }
                            }
						}
                    })
                    .catch((err) => {
                        this.showDangerAlert(err)
                    });
            },

            showDangerAlert(message) {
                this.dangerAlertMessage = message;
                this.dangerAlert = true;
            },
            hideDangerAlert() {
                this.dangerAlertMessage = '';
                this.dangerAlert = false;
            },
        },
        created() {
            this.getDocumentTypes();
            this.getCountries();
            this.getDepartments();
            this.getProvincesByDepartmentId(15);
            this.getDistrictsByProvinceId(128);
            this.getOcupations();
        },
    };
</script>