<template>
	<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card card-custom gutter-b example example-compact">
							<div class="card-header pt-4 pb-2 min-vh-0">
									<div class="row w-100">
											<div class="col-lg-12 col-md-12 col-sm-12">
													<h3 class="pt-4"><strong>Personas jurídicas</strong></h3>
											</div>
									</div>
							</div>

							<div class="card-body">
									<div class="row" v-if="mainAlert">
											<div class="col-lg-12 col-md-12 col-sm-12">
													<b-alert :show="mainAlert" dismissible fade :variant="mainAlertVariant" @dismissed="mainAlert=false">{{ mainAlertMessage }}</b-alert>
											</div>
											<hr>
									</div>
									<div class="card-body">
                  <form class="dt_adv_search" method="POST">
                    <div class="row">
                      <div class="col-12">
                        <div class="row g-3">
                          <div class="col-12 col-sm-6 col-lg-4 ">
                            <label class="form-label fw-bold">Razon Social:</label>
                            <input
                              id="filter_table_business_name"
                              type="text"
                              class="form-control dt-input dt-full-name"
                              data-column="business_name"
                              placeholder="Buscar..."
                              @change="filterColumn()"
                            />
                          </div>
                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label fw-bold">RUC:</label>
                            <input
                              id="filter_table_ruc"
                              type="text"
                              class="form-control dt-input"
                              data-column="ruc"
                              placeholder="Buscar..."
                              @change="filterColumn()"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
									</div>
									<div class="row overflow-visible">
										<div class="card-datatable table-responsive mt-5 overflow-visible">
                      <div class="col-1 text-center pagination-control">
                        <h6 class="m-0 me-3">Pagina:</h6>
                        <select class="form-control text-center" name="document_type_id" ref="pagination_go" @change="goTo()">
														<template v-for="n in pagesOfTable" >
															<option  v-bind:key="n" :value="n" :selected=" (tabled.page.info().page + 1 ) == n ? true : '' "> {{ n }}</option>
														</template>
														<option value="0" v-if="pagesOfTable==0">0</option>	
													</select>
                      </div>
											<table class="datatables-basic table w-100 mt-3 mb-1" id="table">
												<thead>
														<tr>
														<th></th>
														<th>ID</th>
														<th>Razon social</th>
														<th>RUC</th>
														<th>Representante legal</th>
                            <th>Persona de contacto</th>
														<th>Ocupación</th>
														<th>Telefono</th>
														<th>Email</th>
														<th>País</th>
														<th>Departamento</th>
														<th>Provincia</th>
														<th>Distrito</th>
														<th>Giro de negocio</th>
														<th>Dirección Fiscal</th>
														<th>Cuenta creada</th>
														<th>Estado</th>
														<th>Acciones</th>
														</tr>
												</thead>
											</table>
										</div>
									</div>
							</div>
					</div>
			</div>
			<!-- Modal account info -->
			<div v-if="isModalAction">
          <div class="modal fade modal" id="modal-set-preferential" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
              <div class="modal-content p-3 p-md-3">
                <div class="modal-body">
                  <div class="content-header mb-2">
                    <h4 class="fw-bold pt-3 mb-2 text-center">Tipo de cambio preferencial</h4>
                  </div>
                  <input type="hidden" name="id" ref="id" v-model="selectedAccount.id">
                  <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                          <div class="form-group">
                              <label>Compra</label>
                              <input type="text" class="form-control" name="preferential_purchase" ref="preferential_purchase" placeholder="Ingresa el valor de compra" v-model="selectedAccount.preferential_purchase">
                          </div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                          <div class="form-group">
                              <label>Venta</label>
                              <input type="text" class="form-control" name="preferential_sale" ref="preferential_sale" placeholder="Ingresa el valor de venta" v-model="selectedAccount.preferential_sale">
                          </div>
                      </div>
                  </div>
                  <div class="col-12 d-flex justify-content-between mt-3 ">
                    <button class="btn btn-label-secondary btn-backprev align-items-baseline " @click="closeModal();">
                    <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
                    </button>
                    <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" @click="setPreferential()">
                      <span class="align-middle d-sm-inline-block d-none" >Confirmar!</span>
                    </button>
                  </div>
                  <div class="row" v-if="preferentialAlert">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <b-alert show :variant="preferentialAlertVariant" v-bind:class="{ 'mt-2': selectedAccount.preferential == 1 }">{{preferentialAlertMessage}}</b-alert>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade modal modal-xl" id="validateDoc" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
              <div class="modal-content p-3 p-md-3">
                <div class="modal-body">
                  <div class="content-header mb-2 text-center">
                    <h4 class="fw-bold pt-3 mb-2 ">Verificar Documentos</h4>
                    <span :class="'badge '+ statusLabel[selectedAccount.status].class"> {{statusLabel[selectedAccount.status].title}}</span>
                    <span :class="{'badge bg-label-warning': selectedAccount.user.email_verified_at == null, 'badge bg-label-success': selectedAccount.user.email_verified_at != null, } ">{{selectedAccount.user.email_verified_at != null ? 'Correo electronico verificado':'Correo electronico no verificado'}}</span>
                  </div>
                  <input type="hidden" name="id" ref="id" v-model="selectedAccount.id">
                  <div class="row mb-3"  >
                    <div class="col-sm-12 text-center">
                      <h5 class="my-2">Ficha RUC</h5>
                    </div>
                    <template v-if="loadSendr">
                      <div class=" col-sm-4 mb-3">
                            <div class="form-group text-center">
                                <label>Razón social</label>
                                <div class="mt-2">
                                  <h6>
                                    {{ selectedAccount.business_name }}
                                  </h6>
                                </div>
                            </div>
                        </div>
                        <div class=" col-sm-4 mb-3">
                            <div class="form-group text-center">
                                <label>Tipo de documento</label>
                                <div class="mt-2">
                                  <h6>
                                    RUC
                                  </h6>
                                </div>
                            </div>
                        </div>
                        <div class=" col-sm-4 mb-3">
                            <div class="form-group text-center">
                                <label>N° de documento</label>
                                <div class="mt-2">
                                  <h6>
                                    {{ selectedAccount.ruc }}
                                  </h6>
                                </div>
                            </div>
                        </div>
                        <template v-if="selectedAccount.ruc_card.slice(-3) == 'pdf'">
                          <div class="col-sm-12 mb-3 d-flex justify-content-center align-items-baseline">
                            <i class="fa-solid fa-file-pdf fa-xl text-primary me-2"></i>
                            <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+ruc_cardFormat(selectedAccount.ruc_card)" >Ver documento</a>
                          </div>
                        </template>
                        <template v-else>
                          <div class="col-sm-8 col-md-offset-2 mb-3 d-flex justify-content-center align-items-baseline">
                            <template  v-if="imgRuc">
                              <div class="img__document d-flex flex-column align-items-center position-relative">
                                <label>Ficha ruc</label>
                                <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+selectedAccount.ruc_card" >
                                  <img class="mt-3" :src="'/public/media/images/account_documents/'+selectedAccount.ruc_card" width="100%" @error="imgRuc=false" alt="">
                                </a>
                                <div class="back-drop__document"></div>
                              </div>
                            </template>
                            <template v-else>
                              <h5>Este usuario no ha cargado su ficha ruc</h5>
                            </template>
                          </div>
                        </template>
                      </template>
                      <template v-else>
                          <div class=" col-sm-12 mb-3 text-center">
                            <h5> Este usuario no ha cargado estos documentos.</h5>
                          </div>
                      </template>
                  </div>
                  <div class="row border-top pt-3"  >
                    <div class="col-sm-12 text-center">
                      <h5 class="my-2">Documentos del representante legal</h5>
                    </div>
                    <template v-if="loadSend">
                      <div class=" col-sm-4 mb-3">
                          <div class="form-group text-center">
                            <label>Nombre completo</label>
                            <div class="mt-2">
                              <h6>
                                {{ selectedAccount.name_lr }} {{ selectedAccount.surname_lr }}
                              </h6>
                            </div>
                          </div>
                        </div>
                        <div class=" col-sm-4 mb-3">
                            <div class="form-group text-center">
                                <label>Tipo de documento</label>
                                <div class="mt-2">
                                  <h6>
                                   {{selectedAccount.document_type_lr.name}}
                                  </h6>
                                </div>
                            </div>
                        </div>
                        <div class=" col-sm-4 mb-3">
                            <div class="form-group text-center">
                                <label>N° de documento</label>
                                <div class="mt-2">
                                  <h6>
                                   {{selectedAccount.document_number_lr}}
                                  </h6>
                                </div>
                            </div>
                        </div>
                        <div class=" col-sm-6 mb-3">
                          <template  v-if="imgFrontl">
                            <div class="img__document d-flex flex-column align-items-center position-relative">
                              <label>Documento Frontal</label>
                              <template v-if="selectedAccount.document_front_lr.slice(-3) == 'pdf'">
                                <div class="mt-4 col-sm-12 mb-3 d-flex justify-content-center align-items-baseline">
                                  <i class="fa-solid fa-file-pdf fa-xl text-primary me-2"></i>
                                  <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg(selectedAccount.document_front_lr,'lr')" >Ver documento</a>
                                </div>
                              </template>
                              <template v-else>
                                <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg(selectedAccount.document_front_lr,'lr')" >
                                  <img class="mt-3" :src="'/public/media/images/account_documents/'+bdUrlImg(selectedAccount.document_front_lr,'lr')" width="100%" @error="imgFrontl=false" alt="">
                                </a>
                              </template>
                              <div class="back-drop__document"></div>
                            </div>
                          </template>
                          <template v-else>
                            <h5>Este usuario no ha cargado la foto frontal del documento</h5>
                          </template>
                        </div>
                        <div class=" col-sm-6 mb-3">
                          <template  v-if="imgBackl">
                            <div class="img__document d-flex flex-column align-items-center position-relative">
                              <label>Documento Dorso</label>
                              <template v-if="selectedAccount.document_back_lr.slice(-3) == 'pdf'">
                                <div class="mt-4 col-sm-12 mb-3 d-flex justify-content-center align-items-baseline">
                                  <i class="fa-solid fa-file-pdf fa-xl text-primary me-2"></i>
                                  <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg2(selectedAccount.document_back_lr,'lr')" >Ver documento</a>
                                </div>
                              </template>
                              <template v-else>
                                <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg2(selectedAccount.document_back_lr,'lr')" >
                                  <img class="mt-3" :src="'/public/media/images/account_documents/'+bdUrlImg2(selectedAccount.document_back_lr,'lr')" width="100%" @error="imgBackl=false" alt="">
                                </a>
                              </template>
                              <div class="back-drop__document"></div>
                            </div>
                          </template>
                          <template v-else>
                            <h5>Este usuario no ha cargado la foto del dorso del documento</h5>
                          </template>
                        </div>
                    </template>
                    <template v-else>
                        <div class=" col-sm-12 mb-3 text-center">
                          <h5> Este usuario no ha cargado estos documentos.</h5>
                        </div>
                    </template>
                  </div>
                  <div class="row border-top pt-3"  >
                    <div class="col-sm-12 text-center">
                      <h5 class="my-2">Documentos del contacto</h5>
                    </div>
                    <template v-if="loadSendc">
                      <div class=" col-sm-4 mb-3">
                          <div class="form-group text-center">
                              <label>Nombre completo</label>
                              <div>
                                <h6>
                                  {{ selectedAccount.name_c }} {{ selectedAccount.surname_c }}
                                </h6>
                              </div>
                          </div>
                      </div>
                      <div class=" col-sm-4 mb-3">
                        <div class="form-group text-center">
                            <label>Tipo de documento</label>
                            <div class="mt-2">
                              <h6>
                              {{selectedAccount.document_type_c.name}}
                              </h6>
                            </div>
                        </div>
                      </div>
                      <div class=" col-sm-4 mb-3">
                        <div class="form-group text-center">
                            <label>N° de documento</label>
                            <div class="mt-2">
                              <h6>
                              {{selectedAccount.document_number_c}}
                              </h6>
                            </div>
                        </div>
                      </div>
                    
                      <div class=" col-sm-6 mb-3">
                        <template  v-if="imgFrontc">
                          <div class="img__document d-flex flex-column align-items-center position-relative">
                            <label>Documento Frontal</label>
                            <template v-if="selectedAccount.document_front_c.slice(-3) == 'pdf'">
                              <div class="mt-4 col-sm-12 mb-3 d-flex justify-content-center align-items-baseline">
                                <i class="fa-solid fa-file-pdf fa-xl text-primary me-2"></i>
                                <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg(selectedAccount.document_front_c,'c')" >Ver documento</a>
                              </div>
                            </template>
                            <template v-else>
                              <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg(selectedAccount.document_front_c,'c')" >
                                <img class="mt-3" :src="'/public/media/images/account_documents/'+bdUrlImg(selectedAccount.document_front_c,'c')" width="100%" @error="imgFrontc=false" alt="">
                              </a>
                            </template>
                            <div class="back-drop__document"></div>
                          </div>
                        </template>
                        <template v-else>
                          <h5>Este usuario no ha cargado la foto frontal del documento</h5>
                        </template>
                      </div>
                      <div class=" col-sm-6 mb-3">
                        <template  v-if="imgBackc">
                          <div class="img__document d-flex flex-column align-items-center position-relative">
                            <label>Documento Dorso</label>
                            <template v-if="selectedAccount.document_back_c.slice(-3) == 'pdf'">
                              <div class="mt-4 col-sm-12 mb-3 d-flex justify-content-center align-items-baseline">
                                <i class="fa-solid fa-file-pdf fa-xl text-primary me-2"></i>
                                <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg2(selectedAccount.document_back_c,'c')" >Ver documento</a>
                              </div>
                            </template>
                            <template>
                              <a class="text-center" target="_blank" :href="'/public/media/images/account_documents/'+bdUrlImg2(selectedAccount.document_back_c,'c')" >
                                <img class="mt-3" :src="'/public/media/images/account_documents/'+bdUrlImg2(selectedAccount.document_back_c,'c')" width="100%" @error="imgBackc=false" alt="">
                              </a>
                            </template>
                            <div class="back-drop__document"></div>
                          </div>
                        </template>
                        <template v-else>
                          <h5>Este usuario no ha cargado la foto del dorso del documento</h5>
                        </template>
                      </div>
                    </template>
                    <template v-else>
                      <div class=" col-sm-12 mb-3 text-center">
                        <h5> Este usuario no ha cargado estos documentos.</h5>
                      </div>
                    </template>
                  </div>
                  <div class="col-12 col-md-10 offset-md-1 d-flex justify-content-around mt-3 ">
                    <button class="btn btn-label-secondary btn-backprev align-items-baseline w-25  " @click="closeModal();">
                      <span class="align-middle d-sm-inline-block d-none">Salir</span>
                    </button>
                    <button class="btn btn-label-danger  align-items-baseline w-25  " id="xsendreject" v-if="selectedAccount.status != -1" @click="rejectAccount()">
                      Rechazar documentos
                    </button>
                    <template v-if="loadSend || loadSendc">
                      <button class="btn btn-primary align-items-baseline w-25 " id="sendvalidate" v-if="selectedAccount.status != 1" @click="validateAccount()">
                        Aceptar documentos
                      </button>
                    </template>
                  </div>
                  <div class="row" v-if="preferentialAlert">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <b-alert show :variant="preferentialAlertVariant" v-bind:class="{ 'mt-2': selectedAccount.preferential == 1 }">{{preferentialAlertMessage}}</b-alert>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade modal-xl" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row" >
              <div class="modal-content p-3 p-md-3"  >
                <div class="modal-body"  >
                  <div class="row" v-if="updateAlert">
											<div class="col-lg-12 col-md-12 col-sm-12">
													<b-alert :show="updateAlert" dismissible fade :variant="updateAlertVariant" @dismissed="updateAlert=false">{{ updateAlertMessage }}</b-alert>
											</div>
											<hr>
									</div>
                  <form id="company_data">
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Número de RUC</label>
                                <input type="text" class="form-control" name="ruc_card" ref="ruc" placeholder="Ingresa el número de RUC" v-model="selectedAccount.ruc">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Razón social</label>
                                <input type="text" class="form-control" name="business_name" ref="business_name" placeholder="Ingresa la razón social" v-model="selectedAccount.business_name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Giro de negocio</label>
                                <input type="text" class="form-control" name="business_turn" ref="business_turn" placeholder="Ingresa el giro de negocio" v-model="selectedAccount.business_turn">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Dirección</label>
                                <input type="text" class="form-control" name="fiscal_address" ref="fiscal_address" placeholder="Ingresa la dirección fiscal" v-model="selectedAccount.fiscal_address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>País</label>
                                <select class="select2 form-select" name="country_id" ref="country_id" disabled  v-model="selectedAccount.country_id" data-allow-clear="true">
                                  <option value="">Selecciona el país</option>
                                  <option v-for="country in countries" :value="country.id" :key="country.id" >
                                    {{ country.name }}
                                  </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Departamento</label>
                                <select class="select2 form-select" data-allow-clear="true" name="department_id" ref="department_id" v-model="selectedAccount.department_id" @change="getProvincesByDepartmentId($event.target.value); districts = []">
                                  <option value="">Selecciona el departamento</option>
                                  <option v-for="department in departments" :value="department.id" :key="department.id">
                                    {{ department.name }}
                                  </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Provincia</label>
                                <select class="select2 form-select" name="province_id" ref="province_id" v-model="selectedAccount.province_id" @change="getDistrictsByProvinceId($event.target.value)">
                                  <option value="">Selecciona la Ciudad</option>
                                  <option v-for="province in provinces" :value="province.id" :key="province.id">
                                      {{ province.name }}
                                  </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Distrito</label>
                                <select class="select2 form-select" name="district_id" ref="district_id" v-model="selectedAccount.district_id">
                                  <option value="">Selecciona el distrito</option>
                                  <option v-for="district in districts" :value="district.id" :key="district.id">
                                      {{ district.name }}
                                  </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Correo electrónico</label>
                                <input type="text" class="form-control" name="email" ref="email" placeholder="Ingresa el correo electrónico" v-model="selectedAccount.email">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Telefono</label>
                                <input type="text" class="form-control" name="phone" ref="phone" placeholder="Ingresa el número de teléfono" v-model="selectedAccount.phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Nombre/s del representante legal</label>
                                <input type="text" class="form-control" name="name_lr" ref="name_lr" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name_lr">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Apellido/s del representante legal</label>
                                <input type="text" class="form-control" name="surname_lr" ref="surname_lr" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname_lr">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Tipo de documento del representante legal</label>
                                <select class="form-control" name="document_type_lr_id" ref="document_type_lr_id" v-model="selectedAccount.document_type_lr_id">
                                    <option value="-1">Seleccione el tipo de documento</option>
                                    <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Número de documento del representante legal</label>
                                <input type="text" class="form-control" name="document_number_lr" ref="document_number_lr" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number_lr">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Nombre/s de contacto</label>
                                <input type="text" class="form-control" name="name_c" ref="name_c" placeholder="Ingresa el nombre/s" v-model="selectedAccount.name_c">
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Apellido/s de contacto</label>
                                <input type="text" class="form-control" name="surname_c" ref="surname_c" placeholder="Ingresa el apellido/s" v-model="selectedAccount.surname_c">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Tipo de documento de contacto</label>
                                <select class="form-control" name="document_type_c_id" ref="document_type_c_id" v-model="selectedAccount.document_type_c_id">
                                    <option value="-1">Seleccione el tipo de documento</option>
                                    <option v-for="(documentType, index) in documentTypes" v-bind:value="documentType.id" :key="`document_${index}`">{{ documentType.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <div class="valid-item">
                                <label>Número de documento de contacto</label>
                                <input type="text" class="form-control" name="document_number_c" ref="document_number_c" placeholder="Ingresa el número de documento" v-model="selectedAccount.document_number_c">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <div class="valid-item">
                                <label>Teléfono de contacto</label>
                                <input type="text" class="form-control" name="phone_c" ref="phone_c" placeholder="Ingresa el número de teléfono" v-model="selectedAccount.phone_c">
                            </div>
                        </div>
                    </div>
                  </form>
                  <div class="col-12 d-flex justify-content-between mt-3 ">
                    <button class="btn btn-label-secondary btn-backprev align-items-baseline " @click="formValidated.resetForm(); closeModal(); ">
                    <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
                    </button>
                    <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" id="update-button" @click="updateAccount()">
                      Confirmar!
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade modal" id="deleteUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
              <div class="modal-content p-3 p-md-3">
                <div class="modal-body">
                  <div class="content-header mb-2">
                    <h4 class="fw-bold pt-3 mb-2 text-center">¿Seguro que quieres eliminar esta cuenta?</h4>
                  </div>

                  <div class="row">
                  </div>
                  <div class="col-12 d-flex justify-content-between mt-3 ">
                    <button class="btn btn-label-secondary btn-backprev align-items-baseline " @click="closeModal();">
                    <span class="align-middle d-sm-inline-block d-none">Cancelar</span>
                    </button>
                    <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false" id="sendPref" @click="deleteAccount(selectedAccount.id)">
                      <span class="align-middle d-sm-inline-block d-none" >Confirmar!</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
			</div>
	</div>
</template>
<style lang="scss">
	@import "@/assets/libs/datatables-bs5/datatables.bootstrap5.scss";
  @import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
  @import "@/assets/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.scss";
  @import "@/assets/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.scss";
  @import "@/assets/libs/datatables-responsive-bs5/responsive.bootstrap5.scss";
  @import "@/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.scss";
</style>
<script>
  import { GET_ACCOUNT_BY_ID_AND_TYPE, ACCOUNTS_COMPANY_DELETE, ACCOUNTS_COMPANY_RESTORE, ACCOUNTS_COMPANY_UPDATE, ACCOUNTS_COMPANY_SET_PREFERENTIAL, ACCOUNTS_COMPANY_DELETE_PREFERENTIAL, ACCOUNTS_COMPANY_SET_VALIDATE } from "@/core/services/store/account.module";
  import { bootstrap } from "@/concept/bootstrap";
  import { DOCUMENT_TYPES_GET_ALL } from "@/core/services/store/document_type.module";
  import { OCUPATIONS_GET_ALL } from "@/core/services/store/ocupation.module";
  import { COUNTRIES_GET_ALL } from "@/core/services/store/country.module";
  import { DEPARTMENTS_GET_ALL } from "@/core/services/store/department.module";
  import { PROVINCES_GET_ALL_BY_DEPARTMENT_ID } from "@/core/services/store/province.module";
  import { DISTRICTS_GET_ALL_BY_PROVINCE_ID } from "@/core/services/store/district.module";
	import { GET_SYSTEM_CONFIGURATION_VALUE } from "@/core/services/store/configuration.module";
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";

  // FormValidation plugins
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  export default {
      data() {
          return {
            mainAlert: false,
            mainAlertVariant: "",
            mainAlertMessage: "",
            updateAlert: false,
            updateAlertVariant: '',
            updateAlertMessage: '',
            preferentialAlert: false,
            preferentialAlertVariant: '',
            preferentialAlertMessage: '',
            allCompanyAccounts: [],
            companyAccounts: [],
            selectedAccount: {},
            companyAccountSearchText: '',
            companyAccountStatus: 'all',
            openModal: '',
            documentTypes: [],
            ocupations: [],
            countries: [],
            departments: [],
            provinces: [],
            districts: [],
            tabled: '',
            pagesOfTable:0,
            loadSend: false,
            loadSendc:false,
            loadSendr: false,
            imgFrontl: false,
            imgBackl: false,
            imgFrontc: false,
            imgBackc: false,
            imgRuc: false,
            formValidated:'',
            isModalAction:false,
            statusLabel: {
                '-1': { title: 'Rechazado', class: ' bg-label-danger' },
                '0':	{ title: 'No verificado', class: ' bg-label-warning' },
                '1':	{ title: 'Verificado', class: ' bg-label-success' },
            }
          }
      },
      methods: {
        async selectAccountToAction(idAccount){
					this.$store
							.dispatch(GET_ACCOUNT_BY_ID_AND_TYPE, {id:idAccount, type: 1})
							.then((account) => {
									// console.log(account.status)
									this.preferentialAlert = false;
									this.preferentialAlertVariant = '';
									this.preferentialAlertMessage = '';
									this.selectedAccount = Object.assign({}, account);
									setTimeout(() => {
										this.validateItem()
										this.getProvincesByDepartmentId(this.selectedAccount.department_id);
										this.getDistrictsByProvinceId(this.selectedAccount.province_id);
										this.loadSend = this.selectedAccount.document_front_lr.length > 4 ? true : false
                    this.loadSendc = this.selectedAccount.document_front_c.length > 4 ? true : false
                    this.loadSendr = this.selectedAccount.ruc_card.length > 4 ? true : false
										return new Promise((resolve) => {
											resolve(this.selectedAccount);
										});
									}, 400);
							})
							.catch((err) => {
								console.log(err)
								return new Promise((resolve) => {
									resolve(false);
								});
							});

							

				},
        getCompanyAccountsTable(){
          const dataForSecretKey = new FormData();
					dataForSecretKey.append('id', 2) // KEY FOR API;
					this.$store
					.dispatch(GET_SYSTEM_CONFIGURATION_VALUE, dataForSecretKey).then((keyData)=>{
            this.tabled = new DataTable('#table', {
              ajax: {
                url: 'https://cambio.dlsmoney.com/api/company-accounts-data-table',
                // url: 'http://127.0.0.1:8000/api/company-accounts-data-table',
                type: "POST",
                data: function ( data ) {
                    data.keyForApi = keyData.data.value
                    data.filter_business_name = document.getElementById('filter_table_business_name').value
                    data.filter_ruc = document.getElementById('filter_table_ruc').value
                }
              },
              dataType:'json',
              processing: true,
              serverSide: true,
              columnDefs: [
                {
                  className: 'control',
                  orderable: false,
                  visible: false,
                  targets: 0,
                  render: function (data, type, full, meta) {
                    return '';
                  }
                },
                {
                  className: 'text-center',
                  targets: 1,
                  searchable: false,
                  visible: true,
                  
                  responsivePriority: 0,
                  render: function (data, type, full, meta) {
                    return (
                      `#${full.id}` 
                    )
                  }
                },
                {	  
                  className: 'text-center',										
                  targets: 2,
                  searchable: true,
                  visible:true,
                  responsivePriority: 1,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.business_name} ` 
                    )
                  }
                },
                {	  
                  className: 'text-center',										
                  targets: 3,
                  searchable: true,
                  orderable: true,
                  responsivePriority: 2,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.ruc}` 
                    )
                  }
                },
                { 
                  className: 'text-center',
                  targets: 14,
                  searchable: true,
                  visible: false,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.fiscal_address}` 
                    )
                  }
                },
                { 
                  className: 'text-center',
                  targets: 4,
                  searchable: true,
                  visible: true,
                  responsivePriority: 3,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.name_lr} ${full.surname_lr}` 
                    )
                  }
                },
                {
                  className: 'text-center',
                  targets:6 ,
                  searchable: false,
                  visible: false,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return `${full.ocupation_lr.name}`
                  }
                },
                { 
                  className: 'text-center',
                  targets: 7,
                  searchable: true,
                  visible: false,
                  render: function (data, type, full, meta) {
                    return (
                      `${full.phone}` 
                    )
                  }
                },

                {
                  className: 'text-center',
                  targets: 8,
                  searchable: true,
                  visible: false,
                  orderable: true,
                  render: function (data, type, full, meta) {
                    return `${full.email}`
                  }
                },
                {
                  className: 'text-center',
                  targets: 9,
                  searchable: true,
                  visible: false,
                  orderable: true,
                  render: function (data, type, full, meta) {
                    return `${full.country.name}`
                  }
                },
                {
                  className: 'text-center',
                  searchable: true,
                  visible: false,
                  targets: 10,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return `${full.department.name}`
                  }
                },
                {
                  className: 'text-center',
                  targets: 11,
                  searchable: true,
                  visible: false,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return `${full.province.name}`
                  }
                },
                {
                  className: 'text-center',
                  targets: 12,
                  searchable: true,
                  visible: false,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return `${full.district.name}`
                  }
                },
                {
                  className: 'text-center',
                  targets: 13,
                  searchable: true,
                  visible: false,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return `${full.business_turn}`
                  }
                },
                {
                  className: 'text-center',
                  targets: 5,
                  searchable: false,
                  visible: true,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    return `${full.name_c} ${full.surname_c}`
                  }
                },

                {
                  className: 'text-center',
                  targets: 15,
                  searchable: false,
                  visible: false,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    let date= new Date(full.created_at)
                    return `${date.getDate()}-${(date.getMonth()+1)}-${date.getFullYear()}`
                  }
                },
                
                { 
                  className: 'text-center',
                  targets: 16,
                  searchable: true,
                  visible: true,
                  
                  responsivePriority: 6,
                  render: function (data, type, full, meta) {
                    
                    let html = full.status == 0 ? 
                    `<span class="badge bg-label-warning" >No verificado</span>`: 
                    full.status == 1 ? 
                    `<span class="badge bg-label-success">Verificado</span>`:
                    `<span class="badge bg-label-danger" >Rechazado</span>`

                    return html
                  }
                },
                {
                  // Actions
                  className: 'text-center',
                  targets: 17,
                  orderable: false,
                  responsivePriority: 4,
                  render: function (data, type, full, meta) {
                    var html = `<div class="d-flex align-items-center justify-content-around">`
                    if(full.deleted_at){
                      html += ` 
                        <a href="javascript:;"  class="text-body me-2" title="Restaurar cuenta" ><i data-account =${full.id}  class="fas fa-trash-restore-alt restore-user"></i></a>
                      `
                    }else{
                      
                      html += `	
                        <a href="javascript:;"  class="text-body me-2" data-account =${full.id}  title="Verificar documentos" ><i data-account =${full.id} class="fa-solid fa-address-card verifi-doc"></i></a>
                        <a href="javascript:;"  class="text-body me-2" data-account =${full.id}  title="Editar cuenta" ><i data-account =${full.id}  class="far fa-edit edit-user" ></i></a>
                        <a href="javascript:;"  class="text-body me-2"  data-account =${full.id} title="Setear preferencial" ><i data-account =${full.id}  class="fa fa-cog set-pref"></i></a>
                        <a href="javascript:;"  class="text-body me-2" data-account =${full.id}  title="Eliminar cuenta" ><i data-account =${full.id}  class="fas fa-trash delete-user" ></i></a>
                      
                      `;

                    }
                    html +=`</div>`

                    return html
                  }
                },
                

              ],
              drawCallback: function( settings ) {
                console.log(settings)
                const TableElement = document.getElementById('table');
                const event = new Event("OptionsActionTable")
                TableElement.dispatchEvent(event);

              },
              order: [[0, 'asc']],
              language: {
                sLengthMenu: '_MENU_',
                search: '',
                searchPlaceholder: 'Buscar...',
                processing: "Procesando...",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                info: "Mostrando _END_ de _TOTAL_ registros",
                paginate: {
                  next: "Siguiente",
                  previous: "Anterior"
                },
                
              },
              dom:
                '<"row me-2"' +
                '<"col-md-12"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"B>>' +
                '>t' +
                '<"row mx-2"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
              buttons: [
                {
                  extend: 'collection',
                  className: 'btn btn-primary dropdown-toggle mx-3 text-white',
                  text: '<i class="ti ti-screen-share me-1 ti-xs text-white"></i>Exportar',
                  buttons: [
                    {
                      extend: 'csv',
                      text: '<i class="ti ti-file-text me-2" ></i>Csv',
                      className: 'dropdown-item',
                      title: 'Listado de cuentas de empresas',
                      exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
                        // prevent avatar to be display
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                              if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                              } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                              } else result = result + item.innerText;
                            });
                            return result;
                          }
                        }
                      }
                    },
                    {
                      extend: 'excelHtml5',
                      text: '<i class="ti ti-file-text me-2" ></i>Excel',
                      className: 'dropdown-item',
                      title: 'Listado de cuentas de empresas',
                      autoFilter: true,
                      exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
                        // prevent avatar to be display
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                              if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                              } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                              } else result = result + item.innerText;
                            });
                            return result;
                          }
                        }
                      },
                      
                    },
                    {
                      extend: 'pdf',
                      text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                      className: 'dropdown-item',
                      title: 'Listado de cuentas de empresas',
                      orientation: 'landscape',
                      pageSize: 'A3',
                      exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
                        // prevent avatar to be display
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                              if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                              } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                              } else result = result + item.innerText;
                            });
                            return result;
                          }
                        }
                      },
                      customize: function (doc) {
                        
                        doc.content[1].margin = [0, 0, 0, 0] //left, top, right, bottom
                        doc.styles.tableBodyEven.fillColor = "#d3ecff"
                        doc.styles.tableBodyEven.alignment = 'center'
                        doc.styles.tableBodyOdd.alignment = 'center'
                        doc.styles.tableBodyEven.margin= [2, 10, 2, 10]
                        doc.styles.tableBodyOdd.margin= [2, 10, 2, 10]
                      }
                    },
                  ],
                },
              ],
              responsive: {
                details: {
                  
                  type: 'column',
                  renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                      return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                        ? '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>'
                        : '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                  }
                }
              },
              paging: true,
              pageLength: 50  
                        
            });
            this.initOptionsTable()
          })
          

        },
        initOptionsTable(){
					document.querySelector('.dt-buttons').classList.add('justify-content-center')
					const TableElementContent = document.getElementById('table');

					TableElementContent .addEventListener('OptionsActionTable', event => {
						this.activeOptionsTable()
					})	
				},
        activeOptionsTable(){
          this.pagesOfTable = this.tabled.page.info().pages;
          document.querySelectorAll('.set-pref').forEach(item => {
            item.addEventListener('click', event => {
							this.selectAccountToAction(item.dataset.account).then((data) => {
								
								this.isModalAction = true
								setTimeout(()=>{
									this.showModal('modal-set-preferential')
								},500)
							})
							
						})	
          })
          document.querySelectorAll('.verifi-doc').forEach(item => {
            item.addEventListener('click', event => {
              this.selectAccountToAction(item.dataset.account).then((data) => {
                
                this.isModalAction = true
                this.imgFrontl = true;
                this.imgBackl = true;
                this.imgFrontc = true;
                this.imgBackc = true;
                this.imgRuc = true;
                setTimeout(()=>{
                  this.showModal('validateDoc')
                },500)
              })
            })	
          })
          document.querySelectorAll('.edit-user').forEach(item => {
            item.addEventListener('click', event => {
              this.selectAccountToAction(item.dataset.account).then((data) => {
                
                this.isModalAction = true
                setTimeout(()=>{
                  this.showModal('editUser')
                },500)
              })
              
            })	
          })
          document.querySelectorAll('.delete-user').forEach(item => {
            item.addEventListener('click', event => {
              this.selectAccountToAction(item.dataset.account).then((data) => {
                
                this.isModalAction = true
                setTimeout(()=>{
                  this.showModal('deleteUser')
                },500)
              })
              
            })	
          })
          document.querySelectorAll('.restore-user').forEach(item => {
            item.addEventListener('click', event => {
              setTimeout(()=>{
                this.restoreAccount(item.dataset.account)
              },500)
              
            })	
          })
        },
        goTo(){
          this.tabled.page(parseInt(this.$refs.pagination_go.value)-1).draw( 'page' );
        },
        validateAccount() {
          const buttonSend = document.getElementById('sendvalidate');
          buttonSend.disabled = true;
          const data = {
            id: this.selectedAccount.id,
            status: 1
          }
          this.$store
                .dispatch(ACCOUNTS_COMPANY_SET_VALIDATE, data)
                .then((data) => {
                    if (data.code != 200){
                      buttonSend.disabled = false;
                      this.showUpdateAlert('danger', data.error)
                        return;
                    }
           
                    // close modal
                    setTimeout(() => {
                      buttonSend.disabled = false;
                      this.closeModal();
                      this.tabled.clear();
                      this.tabled.draw('full-hold');
                      this.showMainAlert('success', 'Documentos aprobados correctamente!')

                    }, 500);
                    
                    
                })
                .catch((err) => {
                    this.showUpdateAlert('danger', err)
                    return;
                });
        },
        rejectAccount() {
          const buttonSends = document.getElementById('xsendreject');
          buttonSends.disabled =true;
          const data = {
            id: this.selectedAccount.id,
            status: -1
          }
          this.$store
          .dispatch(ACCOUNTS_COMPANY_SET_VALIDATE, data)
          .then((data) => {
              if (data.code != 200){
                buttonSends.disabled = false;
                this.showUpdateAlert('danger', data.error)
                  return;
              }

              // close modal
              setTimeout(() => {
                buttonSends.disabled = false;
                this.closeModal()
                this.tabled.clear();
                this.tabled.draw('full-hold');
                this.showMainAlert('success', 'Documentos rechazados correctamente!')
    
              }, 500);
              
              
          })
          .catch((err) => {
              this.showUpdateAlert('danger', err)
              return;
          });
        },
        updateAccount (e) {
          this.formValidated.validate().then((status)=>{
            const buttonSend = document.getElementById('update-button');
            buttonSend.disabled= true;
            buttonSend.textContent ="Cargando..."
            if(status == 'Valid'){
              this.$store
                .dispatch(ACCOUNTS_COMPANY_UPDATE, this.selectedAccount)
                .then((data) => {
                    if (data.code != 200){
                        this.showUpdateAlert('danger', data.error)
                        return;
                    }
                    // close modal
                    setTimeout(() => {
                      buttonSend.disabled = false;
                      buttonSend.textContent ="Confirmar!"
                      this.closeModal()
                      this.tabled.clear();
                      this.tabled.draw('full-hold');
                      this.showMainAlert('success', 'Cuenta empresarial actualizada correctamente!')
                      return;
                    }, 500);
                    
                    
                })
                .catch((err) => {
                  console.log(err)
                  this.showUpdateAlert('danger', err)
                  buttonSend.disabled = false;
                  buttonSend.textContent ="Confirmar!"
                  return;
                });
            }else{
              buttonSend.textContent ="Confirmar!"
            }
            
          })
        },
        deleteAccount (companyAccountId) {
            this.$store
                .dispatch(ACCOUNTS_COMPANY_DELETE, companyAccountId)
                .then((data) => {
                    if (data.code != 200){
                        this.showMainAlert('danger', data.error)
                        return;
                    }

                    setTimeout(() => {
                      this.closeModal()
                      this.tabled.clear();
                      this.tabled.draw('full-hold');
                      this.showMainAlert('success', 'Cuenta empresarial eliminada correctamente!')
                      return;
                    }, 500);
                })
                .catch((err) => {
                    this.showMainAlert('danger', err)
                    return;
                });
        },
        restoreAccount (companyAccountId) {
            this.$store
                .dispatch(ACCOUNTS_COMPANY_RESTORE, companyAccountId)
                .then((data) => {
                    if (data.code != 200){
                        this.showMainAlert('danger', data.error)
                        return;
                    }
                    setTimeout(() => {
                      this.tabled.clear();
                      this.tabled.draw('full-hold');
                      this.showMainAlert('success', 'Cuenta empresarial restaurada correctamente!')
                      return;
                    }, 500);
                })
                .catch((err) => {
                    this.showMainAlert('danger', err)
                    return;
                });
        },
        setPreferential(e) {
						const id = this.selectedAccount.id;
            console.log(id)
						const preferential_purchase = this.$refs.preferential_purchase.value;
						const preferential_sale = this.$refs.preferential_sale.value;
						let buttonSend = document.getElementById('sendPref');
						if(!preferential_purchase){
							this.showPreferentialAlert('danger', 'Debe completar el valor de compra.')
							buttonSend.disabled = false;
							buttonSend.textContent ="Confirmar!"
							return;
						}

						if(!preferential_sale){
							this.showPreferentialAlert('danger', 'Debe completar el valor de venta.')
							buttonSend.disabled = false;
							buttonSend.textContent ="Confirmar!"
							return;
						}
						buttonSend.disabled =true;
						buttonSend.textContent ="Cargando..."
						let data;
						data = { id, preferential_purchase, preferential_sale }
            
						this.$store
								.dispatch(ACCOUNTS_COMPANY_SET_PREFERENTIAL, data)
								.then((data) => {
										if (data.code != 200){
												this.showPreferentialAlert('danger', data.error)
												buttonSend.disabled = false;
												buttonSend.textContent ="Confirmar!"
												return;
										}

										// close modal
										this.closeModal()
										this.showMainAlert('success', 'Preferencial seteado correctamente!')
										return;
								})
								.catch((err) => {
										this.showPreferentialAlert('danger', err)
										return;
								})
								.finally(()=>{
									buttonSend.disabled = false;
									buttonSend.textContent ="Confirmar!"
								})
				},
        deletePreferential () {
            this.$store
                .dispatch(ACCOUNTS_COMPANY_DELETE_PREFERENTIAL, this.selectedAccount.id)
                .then((data) => {
                    if (data.code != 200){
                        this.showPreferentialAlert('danger', data.error)
                        return;
                    }

                    let allCompanyAccountIndex = this.allCompanyAccounts.findIndex((companyAccount => companyAccount.id == data.data.id));
                    this.allCompanyAccounts[allCompanyAccountIndex].preferential = 0;
                    this.allCompanyAccounts[allCompanyAccountIndex].preferential_purchase = null;
                    this.allCompanyAccounts[allCompanyAccountIndex].preferential_sale = null;

                    let companyAccountIndex = this.companyAccounts.findIndex((companyAccount => companyAccount.id == data.data.id));
                    this.companyAccounts[companyAccountIndex].preferential = 0;
                    this.companyAccounts[companyAccountIndex].preferential_purchase = null;
                    this.companyAccounts[companyAccountIndex].preferential_sale = null;

                    // close modal
                    this.closeModal()
                    this.showMainAlert('success', 'Preferencial eliminado correctamente!')
                    return;
                })
                .catch((err) => {
                    this.showPreferentialAlert('danger', err)
                    return;
                });
        },
        showMainAlert(variant, message) {
            this.mainAlertVariant = variant;
            this.mainAlertMessage = message;
            this.mainAlert = true;
            window.scrollTo(0,0);
        },
        showUpdateAlert(variant, message) {
            this.updateAlertVariant = variant;
            this.updateAlertMessage = message;
            this.updateAlert = true;
            document.getElementById('editUser').scrollTo(0,0);
        },
        showPreferentialAlert(variant, message) {
            this.preferentialAlertVariant = variant;
            this.preferentialAlertMessage = message;
            this.preferentialAlert = true;
        },
        showModal(id) {
          this.openModal = new bootstrap.Modal(document.getElementById(id), {
            keyboard: false,
          })
          this.openModal.show()
        },
        closeModal(){
          this.openModal.hide();
          this.isModalAction = false
          setTimeout(() => {
            let trashElement = document.querySelectorAll('.modal-backdrop');
            trashElement.forEach((item)=>{
              document.querySelector('body').removeChild(item);
            })
          }, 200);
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
        getOcupations () {
          this.$store
            .dispatch(OCUPATIONS_GET_ALL)
            .then((data) => {
                if (data.code != 200){
                    this.showMainAlert('danger', 'Error desconocido al obtener todas las ocupaciones.')
                    return;
                }

                this.ocupations = data.data
            })
            .catch((err) => {
                this.showMainAlert('danger', err)
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
        filterColumn() {
          this.tabled.clear();
          this.tabled.draw('full-hold');
        },
        bdUrlImg(url,type){
          let newurl =url
          if(url.length < 33){

            newurl=url.replace('document_front_'+type,'documento_frente')
          }
          return newurl
        },
        bdUrlImg2(url,type){
          let newurl =url
          if(url.length < 33){

            newurl=url.replace('document_back_'+type,'documento_dorso')
          }
          return newurl
        },
        ruc_cardFormat(url){
          let newurl =url
          if(url.length < 25){

            newurl=url.replace('ruc_card','ficha_ruc')
          }
          return newurl
        },
        validateItem(){
          
          const fields = {
            ruc_card: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            business_name: {
              validators: {
                notEmpty: {
                  message: 'La razón social no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ& .,\- ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            business_turn: {
              validators: {
                notEmpty: {
                  message: 'El giro del negocio no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ .,\-]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            department_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Departamento'
                },
              }
            },
            province_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar una Provincia'
                },
              }
            },
            district_id: {
              validators: {
                notEmpty: {
                  message: 'Tienes que selecionar un Distrito'
                },
              }
            },
            fiscal_address: {
              validators: {
                notEmpty: {
                  message: 'Debes colocar tu dirección'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ .#\-,/°]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                },
              }
            },
            phone: {
              validators: {
                notEmpty: {
                  message: 'El Teléfono no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            email: {
              validators: {
                emailAddress: {
                  message: 'Debes colocar una dirección de correo valida',
                },
                notEmpty: {
                  message: 'Debes colocar tu dirección'
                },
              }
            },
            document_type_lr_id: {
              validators: {
                notEmpty: {
                  message: 'El Tipo de documento debe ser seleccionado'
                },
              }
            },
            document_number_lr: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            name_lr: {
              validators: {
                notEmpty: {
                  message: 'El nombre del representante legal no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            surname_lr: {
              validators: {
                notEmpty: {
                  message: 'El apellido de representantel legal no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            document_type_c_id: {
              validators: {
                notEmpty: {
                  message: 'El Tipo de documento debe ser seleccionado'
                },
              }
            },
            document_number_c: {
              validators: {
                notEmpty: {
                  message: 'El número de documento no puede quedar vacio'
                },
                numeric: {
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
            name_c: {
              validators: {
                notEmpty: {
                  message: 'El nombre del contacto no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            surname_c: {
              validators: {
                notEmpty: {
                  message: 'El apellido del contacto no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[A-Za-z0-9À-ÿ. ]+$/i,
                  message: 'Este campo no admite caracteres especiales'
                }
              }
            },
            phone_c:{
              validators: {
                notEmpty: {
                  message: 'El Teléfono no puede quedar vacio'
                },
                regexp: {
                  regexp: /^[0-9 ]+$/i,
                  message: 'Este campo tiene que ser númerico'
                }
              }
            },
          };
          const password_change_form = document.getElementById('company_data');

          
          this.formValidated = formValidation(password_change_form, {
            fields: fields,
            plugins: {
              trigger: new Trigger(),
              bootstrap: new Bootstrap({
                eleValidClass: '',
                rowSelector: '.valid-item'
              }),
              submitButton: new SubmitButton()
            }
          }).on("core.field.invalid", () => {
            document.getElementById('update-button').disabled = true;
            console.log('error al input')
          }).on("core.field.valid", () => {
            document.getElementById('update-button').disabled = false;
          }).on("core.form.valid", () => {
            document.getElementById('update-button').disabled = false;
          }).on("core.form.invalid", () => {
            document.getElementById('update-button').disabled = true;
            window.scrollTo(0,50);

          })
        }
      },
      mounted(){
        this.getCompanyAccountsTable()
      },
      created() {
        this.getDocumentTypes();
        this.getOcupations();
        this.getCountries();
        this.getDepartments();
      },
  };
</script>