<template>
    <div>
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
                            <h3 class="wizard-title"><span>2</span>Domicilio</h3>
                            <div class="wizard-bar"></div>
                        </div>
                    </div>
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-label">
                            <h3 class="wizard-title"><span>3</span>Ocupación</h3>
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
                            
                            <!--end: Wizard Actions -->
                        </form>
                    </div>
                    <!--end: Wizard-->
                </div>
            </div>
            <!--end: Wizard Body -->
        </div>
        <!--end: Wizard-->
    </div>
</template>

<style lang="scss">
    @import "@/assets/sass/pages/page-auth.scss";
    @import "@/assets/libs/bs-stepper/bs-stepper.scss";
</style>

<script>
    import KTWizard from "@/assets/js/components/wizard";
    import Swal from "sweetalert2";
    import { ACCOUNTS_PERSONAL_STORE } from "@/core/services/store/account.module";
    import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
    import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
    import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
    import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
    import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
    import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
    import { QUERY_PERSON_BY_DNI } from "@/core/services/store/peru_consult.module";

    export default {
        data() {
            return {
                exposed_person: "0",
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
                                !thisAux.$refs.document_type_id.value ||
                                !thisAux.$refs.document_number.value ||
                                !thisAux.$refs.document_front.files[0] ||
                                !thisAux.$refs.document_back.files[0] ||
                                !thisAux.$refs.name.value ||
                                !thisAux.$refs.surname.value ||
                                !thisAux.$refs.birthdate.value
                            ) {
                                canContinue = false;
                                if (!thisAux.$refs.document_type_id.value) {
                                    thisAux.showDangerAlert('Debe seleccionar el tipo de documento.');
                                }
                                else if (!thisAux.$refs.document_number.value) {
                                    thisAux.showDangerAlert('Debe ingresar el número de documento.');
                                }
                                else if (!thisAux.$refs.document_front.files[0]) {
                                    thisAux.showDangerAlert('Debe seleccionar documento (frente).');
                                }
                                else if (!thisAux.$refs.document_back.files[0]) {
                                    thisAux.showDangerAlert('Debe seleccionar documento (dorso).');
                                }
                                else if (!thisAux.$refs.name.value) {
                                    thisAux.showDangerAlert('Debe ingresar el/los nombre/s.');
                                }
                                else if (!thisAux.$refs.surname.value) {
                                    thisAux.showDangerAlert('Debe ingresar el/los apellidos/s.');
                                }
                                else if (!thisAux.$refs.birthdate.value) {
                                    thisAux.showDangerAlert('Debe ingresar la fecha de nacimiento.');
                                }
                            }
                            break;
                        case 2:
                            if (
                                !thisAux.$refs.country_id.value ||
                                !thisAux.$refs.department_id.value ||
                                !thisAux.$refs.province_id.value ||
                                !thisAux.$refs.district_id.value ||
                                !thisAux.$refs.address.value
                            ) {
                                canContinue = false;
                                if (!thisAux.$refs.country_id.value) {
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
                                else if (!thisAux.$refs.address.value) {
                                    thisAux.showDangerAlert('Debe ingresar la dirección.');
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

                wizard.on("submit", function(wizardObj) {
                    let canContinue = true;
                    if (
                        !thisAux.$refs.ocupation_id.value ||
                        (thisAux.exposed_person != 0 && thisAux.exposed_person != 1)
                    ) {
                        canContinue = false;
                        if (!thisAux.$refs.ocupation_id.value) {
                            thisAux.showDangerAlert('Debe seleccionar la ocupación.');
                        }
                        else if (thisAux.exposed_person != 0 && thisAux.exposed_person != 1) {
                            thisAux.showDangerAlert('Debe seleccionar si es una persona expuesta.');
                        }
                    }

                    if (canContinue && thisAux.exposed_person == 1) {
                        if (
                            !thisAux.$refs.position.value ||
                            !thisAux.$refs.workplace.value
                        ) {
                            canContinue = false;
                            if (!thisAux.$refs.position.value) {
                                thisAux.showDangerAlert('Debe ingresar el cargo.');
                            }
                            else if (!thisAux.$refs.workplace.value) {
                                thisAux.showDangerAlert('Debe ingresar el lugar de trabajo.');
                            }
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

                formData.append('document_type_id', this.$refs.document_type_id.value);
                formData.append('document_number', this.$refs.document_number.value);
                formData.append('document_front', this.$refs.document_front.files[0]);
                formData.append('document_back', this.$refs.document_back.files[0]);
                formData.append('name', this.$refs.name.value);
                formData.append('surname', this.$refs.surname.value);
                formData.append('birthdate', this.$refs.birthdate.value);
                formData.append('phone', this.$refs.phone.value);
                formData.append('cellphone1', this.$refs.cellphone1.value);
                formData.append('cellphone2', this.$refs.cellphone2.value);
                formData.append('country_id', this.$refs.country_id.value);
                formData.append('department_id', this.$refs.department_id.value);
                formData.append('province_id', this.$refs.province_id.value);
                formData.append('district_id', this.$refs.district_id.value);
                formData.append('address', this.$refs.address.value);
                formData.append('ocupation_id', this.$refs.ocupation_id.value);
                formData.append('exposed_person', this.exposed_person);

                if(this.exposed_person == 1){
                    formData.append('position', this.$refs.position.value);
                    formData.append('workplace', this.$refs.workplace.value);
                }

                this.$store
                    .dispatch(ACCOUNTS_PERSONAL_STORE, formData)
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

            queryPersonByDocumentNumber (documentNumber) {
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

                        this.$refs.name.value = name;
                        this.$refs.surname.value = surname;
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