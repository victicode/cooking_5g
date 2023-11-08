<template>
	
	<div id="userSettings" class="col-12 operation__form-converted">
		
		<!-- Change Password -->
		<div class="card mb-4">
			
			<h5 class="card-header">Cambiar contraseña</h5>
			<div class="mt-3 d-flex justify-content-center" v-if="mainAlert">
				<div class="col-lg-11 col-md-11 col-sm-12">
					<b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5 order-md-0 order-1">
					<div class="card-body pt-0">
						<form id="formAccountSettingsApiKey"  onsubmit="return false">
							<div class="row">
								<div class="mb-3 col-12">
									<div class="form-group form-password-toggle">
											<label class="form-label" for="currentPassword">Contraseña actual</label>
											<div class="input-group input-group-merge">
												<input
													class="form-control"
													type="password"
													name="current_password"
													ref="current_password"
													
													placeholder="***********"
												/>
												<span class="input-group-text cursor-pointer" @click="initPasswordToggle($event)" ><i class="ti ti-eye-off"></i></span>
											</div>
									</div>
									<div class="form-group form-password-toggle">
										<label class="form-label" for="newPassword">Nueva contraseña</label>
										<div class="input-group input-group-merge">
											<input
												class="form-control"
												type="password"
												name="new_password"
												ref="new_password"
												
												placeholder="***********"
											/>
											<span class="input-group-text cursor-pointer" @click="initPasswordToggle($event)" ><i class="ti ti-eye-off"></i></span>
										</div>
									</div>

									<div class="form-group form-password-toggle">
										<label class="form-label" for="confirmPassword">Confirmar nueva contraseña</label>
										<div class="input-group input-group-merge">
											<input
												class="form-control"
												type="password"
												name="verify_password"
												ref="verify_password"
												placeholder="***********"
											/>
											<span class="input-group-text cursor-pointer" @click="initPasswordToggle($event)" ><i class="ti ti-eye-off"></i></span>
										</div>
									</div>
								</div>
								<div class="col-12 mb-4">
									<h6>Requerimiento de contraseña:</h6>
									<ul class="ps-3 mb-0">
										<li class="mb-1">Mínimo 8 caracteres</li>
										<li class="mb-1">Al menos una letra mayúsculas</li>
										<li>al menos un número, símbolo o caracter especial</li>
									</ul>
								</div>
								<div>
									<button ref="UpadateButton" class="btn btn-primary me-2 cursor-pointer" :disabled="Updatedisabled"  @click="savePassword()" >Cambiar contraseña</button>
									<button type="reset" class="btn btn-label-secondary">Cancelar</button>
								</div>

							</div>
						</form>
					</div>
				</div>
				<div class="col-md-7 order-md-1 order-0">
					<div class="text-center mt-4 mx-3 mx-md-0">
						<img
							:src="leftImg.background"
							class="img-fluid"
							alt="Api Key Image"
							width="202"
						/>
					</div>
				</div>
			</div>
		</div>
		<!--/ Change Password -->
	</div>
</template>
<script>
import { mapGetters} from "vuex";
import { UPDATE_PASSWORD } from "@/core/services/store/auth.module";

import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";

// FormValidation plugins
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";

import Swal from "sweetalert2";

export default {
  name: "UserSetting",
  data() {
    return {
			mainAlert: false,
			mainAlertVariant: "",
			mainAlertMessage: "",
			currentUser:'',
      newPassword: "",
			currentPassword:'',
      status: "",
      valid: true,
			Updatedisabled: true
    };
  },
  mounted() {
    const password_change_form = document.getElementById('formAccountSettingsApiKey');
		
    this.fv = formValidation(password_change_form, {
      fields: {
				current_password: {
          validators: {
            notEmpty: {
              message: "Por favor, introduzca su contraseña"
            },
            regexp: {
              regexp: /^[A-Za-z0-9.#-+*/@$_]+$/i,
              message: 'El nombre de usuario no debe contener espacios ni los siguientes caracteres: "[]{}!¡¿?=()&|',
            },
            stringLength: {
              min: 8,
              message: 'La contraseña debe contener minimo 8 caracteres',
            },
          }
        },
        new_password: {
          validators: {
            notEmpty: {
              message: "Por favor, introduzca su contraseña"
            },
						regexp: {
              regexp: /^[A-Za-z0-9.#-+*/@$_]+$/i,
              message: 'El nombre de usuario no debe contener espacios ni los siguientes caracteres: "[]{}!¡¿?=()&|',
            },
            stringLength: {
              min: 8,
              message: 'La contraseña debe contener minimo 8 caracteres',
            },
          }
        },
        verify_password: {
          validators: {
            notEmpty: {
              message: "Por favor, introduzca nuevamente su nueva contraseña"
            },
            identical: {
              compare: function() {
                return password_change_form.querySelector(
                  '[name="new_password"]'
                ).value;
              },
              message: "La contraseña no coincide"
            }
          }
        }
      },
      plugins: {
        trigger: new Trigger(),
        bootstrap: new Bootstrap(),
        submitButton: new SubmitButton()
      }
    }).on("core.field.invalid", () => {
			this.Updatedisabled= true;
    }).on("core.field.valid", () => {
			this.Updatedisabled= false;
		})
  },
  methods: {
    savePassword() {
			this.fv.validate().then((data)=>{
				if(data == 'Valid'){
					console.log(this.currentAccount.active_account.user_id)
					const password = {
						id:this.currentAccount.active_account.user_id, 
						newPassword: this.$refs.new_password.value,
						currentPass:this.$refs.current_password.value,
					}
					this.Updatedisabled= true;
					const submitButton = this.$refs["UpadateButton"];
					submitButton.textContent = 'Cargando...'
					// set spinner to submit button

					// dummy delay
					setTimeout(() => {
					  // send update request
					  this.$store
					    .dispatch(UPDATE_PASSWORD, password)
					    // go to which page after successfully update
					    .then((data) => {
								if (data.status == 'error'){
									let msg = data.message == undefined ? data.errors.newPassword[0] : data.message
									this.showMainAlert('danger', msg)
									this.Updatedisabled= false;
									submitButton.textContent = 'Cambiar contraseña'
									return;
								}
								this.showMainAlert('success', 'Contraseña cambiada de forma exitosa.')
									this.Updatedisabled= false;
								submitButton.textContent = 'Cambiar contraseña'
								this.$refs.new_password.value = '' 
								this.$refs.current_password.value = '' 
								this.$refs.verify_password.value = ''
							})
					}, 2000);
			
				}else{
					Swal.fire({
						title: "",
						text: "Error en los datos ingresado!",
						icon: "error",
						confirmButtonClass: "btn btn-secondary"
        	});
					this.Updatedisabled= true;
				}
			});

    },
    cancel() {
      this.fv.resetForm();
      this.$refs.current_password.value = "";
      this.$refs.new_password.value = "";
      this.$refs.verify_password.value = "";
    },
		showMainAlert(variant, message) {
				this.mainAlertVariant = variant;
				this.mainAlertMessage = message;
				this.mainAlert = true;
				window.scrollTo(0,0);
		},
		initPasswordToggle(event) {
      let elx = event.srcElement;
        const formPasswordToggle = elx.closest('.form-password-toggle')
        const formPasswordToggleIcon = formPasswordToggle.querySelector('i')
        const formPasswordToggleInput = formPasswordToggle.querySelector('input')
        if (formPasswordToggleInput.getAttribute('type') == 'text') {
          formPasswordToggleInput.setAttribute('type', 'password')
          formPasswordToggleIcon.classList.replace('ti-eye', 'ti-eye-off')
        }else{
          formPasswordToggleInput.setAttribute('type', 'text')
          formPasswordToggleIcon.classList.replace('ti-eye-off', 'ti-eye')
        }
    },

		hideMainAlert () {
				this.mainAlertVariant = '';
				this.mainAlertMessage = '';
				this.mainAlert = false;
		},
  },
  computed: {
    ...mapGetters(["currentAccount"]),
		leftImg() {
			return (
				{
					background: process.env.BASE_URL + "media/illustrations/girl-with-laptop.png",
				}
			);
		}
  }
};
</script>