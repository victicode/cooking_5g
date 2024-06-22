<script setup >
  import DataTablesCore from 'datatables.net';
  import 'datatables.net-responsive-dt';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';
  
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import OrderProductsTables from '@/views/pages/tables/OrderProductsTables.vue'
  import UserOrdersTables from '@/views/pages/tables/UserOrdersTable.vue'
  import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
  import "@/assets/plugins/formvalidation/dist/css/formValidation.min.css";
  import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
  import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
  import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";
  import viewTimelineOrderModal from '@/views/pages/modals/viewTimelineOrderModal.vue';
  import debounce from 'debounce';
  import * as bootstrap from 'bootstrap'
  import { GET_USER_BY_ID, CREATE_USER, DELETE_USER, UPDATE_USER } from "@/core/services/store/user.module";
  import viewOrderModal from '@/views/orders/viewOrderModal.vue';
 
</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de clientes" class="pa-3 px-1 px-md-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0 pb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
          <VBtn @click=" showModal('createNewUser')" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo usuario</VBtn>

          </VCol>
        </VRow>
        <VRow class="ma-0 justify-center align-center justify-md-start pa-2 px-0 mb-10 mb-md-2">
          <VCol cols="12" md="4" class="form-group">
            <VTextField
              placeholder="Buscar por nombre"
              label="Buscar por nombre"
              type="text"
              ref="client_name"
              name="client_name"
              @keyup="filterColumn()"
            />
          </VCol>
          <VCol cols="12" md="4" class="my-0 py-0">
            <VBtn
              color="white"
              class="bg-primary text-white w-30 mx-0 mx-md-5 my-2"
              @click="clearFilters()"
            >
              <span class="">Restaurar</span>
            </VBtn>
          </VCol>
        </VRow>
        <div class="card-datatable table-responsive ">
          <table class="datatable table display client_table" id="data-table">
          </table>
        </div>
      </VCard>
    </VCol>
    <div v-if="Object.keys(selectedUser).length > 2">
      <div class="modal animate__animated animate__slideInLeft" id="editUser" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-0 ma-0" style="width: 100%; height: 100vh;">
          <div class="modal-content h-100">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
              <VCol
                cols="12"
                class="pa-0"
              >
                <VCard class="modal__content h-100 rounded-0">
                  <div class="modal__close-button__cart" >
                    <v-col  class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
                  <div class="d-flex justify-space-between  flex-column pa-2 px-3 pa-md-5 ">
                  <VCardItem class="justify-center w-100  py-md-6  py-4  my-5  ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1">Editar usuario</div>
                        
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <VCardText class="w-100 pb-5 px-3 px-md-6">
                    <VForm  id="edit_user_form">
                      <VRow class="align-center">
                        <VCol cols="12" class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <VTextField
                            placeholder="Nombre y apellido"
                            label="Nombre y apellido"
                            type="text"
                            name="user_name"
                            v-model="selectedUser.name"
                            autocomplete="off"
                          />
                        </VCol>
                        <VCol cols="12" class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <VTextField
                            placeholder="Email"
                            label="Email"
                            type="text"
                            name="user_email"
                            v-model="selectedUser.email"
                            autocomplete="off"
                          />
                        </VCol>
                        <VCol cols="12" class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <VTextField
                            v-model="selectedUser.password"
                            label="Nueva contraseña"
                            placeholder="············"
                            autocomplete="off"
                            name="user_password"
                            :type="isPasswordVisible ? 'text' : 'password'"
                            :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                            @click:append-inner="isPasswordVisible = !isPasswordVisible"
                          />
                        </VCol>
                        <VCol cols="12"  class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <v-textarea
                            label="Dirección"
                            auto-grow
                            variant="outlined"
                            rows="3"
                            autocomplete="off"
                            row-height="25"
                            shaped
                            name="user_address"
                            v-model="selectedUser.user_address"
                          ></v-textarea>
                        </VCol>
                      </VRow>
                      <VRow class="ma-0 pa-0  mt-8 align-center">
                        <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                          <v-col cols="auto" class="">
                            <VBtn  color="primary" class="w-100 " type="submit" disabled id="edit_user_form_button"> Guardar</VBtn>
                          </v-col>
                        </VCol>
                      </VRow>
                    </VForm>
                  </VCardText>
                </div>
              </VCard>
            </VCol>
          </VCol>
        </div>
        </div>
      </div>
      <div class="modal animate__animated animate__fadeInDown" id="deleteUser" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-10" >
          <div class="modal-content">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
            
              <VCol
                cols="12"
              >
              <VCard class="modal__content">
                  <div class="modal__close-button" >
                    <v-col class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
                  <div class="d-flex justify-space-between  flex-column pa-2 pa-md-5 ">
                    <VRow  class="mb-2 ma-0">
                      <VCol
                        cols="12"
                        class="py-0"
                      >
                        <div class="my-md-4 my-2 text-center">
                          <h2>Eliminar Usuario</h2>
                        </div>
                      </VCol>
                      <VCol
                        cols="12"
                        class="px-md-10 px-0 text-center"
                        style=""
                      >
                        <h2>¿Seguro que deseas eliminar a {{ selectedUser.name}} de los usuarios?</h2>
                      </VCol>
                    </VRow>
                      
                    <VDivider  />
                    <div class="mt-5 w-100 d-md-flex  d-block justify-center">
                      <VCardActions class=" justify-center w-100 d-md-flex  d-flex">
                        <VBtn
                          color="white"
                          class="bg-error text-white w-50 mx-0 mx-md-5 my-2"
                          id="delete_form_button"
                          @click="deleteUser()"
                        >
                          <span class="">Eliminar</span>
                        </VBtn>
                      </VCardActions>
                    </div>
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
      <div class="modal animate__animated animate__slideInLeft" id="ordersUser" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-0 ma-0" style="width: 100%; height: 100vh;">
          <div class="modal-content h-100">
            <VCol
              cols="12"
              class="pa-0 d-flex justify-center"
              style="position: relative;"
            >
              <VCol
                cols="12"
                class="pa-0"
              >
                <VCard class="modal__content h-100 rounded-0">
                  <div class="modal__close-button__cart" >
                    <v-col  class="pa-0 pe-4">
                      <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                    </v-col>
                  </div>
                  <div class="d-flex justify-space-between  flex-column pa-2 px-3 pa-md-5 ">
                    <VRow  class="mb-0 ma-0 header-content">
                      <VCol
                        cols="12"
                        class="py-0 text-center"
                      >
                        <div class="my-md-4 my-2 text-center mt-15 mb-5">
                          <h2>Ordenes realizadas por: </h2>
                          <h2>{{ selectedUser.name }}</h2>
                          
                        </div>
                      </VCol>
                    </VRow>
                    <div v-if="selectedUser.orders.length > 0" class="w-100">
                      <UserOrdersTables :orders="selectedUser.orders" @selectedOrder="selectedOrder"/>
                    </div>
                    <div v-else class="w-100 my-8">
                      <div class="text-center">
                        <h2> Este usuario no ha realizado ordenes</h2>
                      </div>
                    </div>
                    <VDivider  />
                  </div>
                </VCard>
              </VCol>
            </VCol>
          </div>
        </div>
      </div>
      <viewOrderModal :order="selectedUser.selectedOrder" v-if="selectedUser.selectedOrder" @actionModal="hideInternalModal()"  />
      <viewTimelineOrderModal :order="selectedUser.selectedOrder" v-if="selectedUser.selectedOrder" @actionModal="hideInternalModal()"/>

    </div>
    <div class="modal animate__animated animate__slideInLeft" id="createNewUser" tabindex="-1" aria-labelledby="showCartLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg mt-0 ma-0" style="width: 100%; height: 100vh;">
        <div class="modal-content h-100">
          <VCol
            cols="12"
            class="pa-0 d-flex justify-center"
            style="position: relative;"
          >
            <VCol
              cols="12"
              class="pa-0"
            >
              <VCard class="modal__content h-100 rounded-0">
                <div class="modal__close-button__cart" >
                  <v-col  class="pa-0 pe-4">
                    <v-btn icon="mingcute:close-fill" class="bg-secondary" @click="hideModal()" ></v-btn>
                  </v-col>
                </div>
                <div class="d-flex justify-space-between  flex-column pa-2 px-3 pa-md-5 ">
                  <VCardItem class="justify-center w-100  py-md-6  py-4  my-5  ">
                    <VCardTitle class="text-2xl font-weight-bold">
                      <div class="card-title d-flex ">
                        <div class="form-title__part1">Crear nuevo usuario</div>
                        
                      </div>
                    </VCardTitle>
                  </VCardItem>
                  <VCardText class="w-100 pb-5 px-3 px-md-6">
                    <VForm  id="new_user_form">
                      <VRow class="align-center">
                        <VCol cols="12" class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <VTextField
                            placeholder="Nombre y apellido"
                            label="Nombre y apellido"
                            type="text"
                            name="new_user_name"
                            v-model="newUser.name"
                            autocomplete="off"
                          />
                        </VCol>
                        <VCol cols="12" class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <VTextField
                            placeholder="Email"
                            label="Email"
                            type="text"
                            name="new_user_email"
                            v-model="newUser.email"
                            autocomplete="off"
                          />
                        </VCol>
                        <VCol cols="12" class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <VTextField
                            v-model="newUser.password"
                            label="Password"
                            placeholder="············"
                            autocomplete="off"
                            name="new_user_password"
                            :type="isPasswordVisible ? 'text' : 'password'"
                            :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                            @click:append-inner="isPasswordVisible = !isPasswordVisible"
                          />
                        </VCol>
                        <VCol cols="12"  class="form-group mt-md-2 mt-0 pt-1 pt-md-2">
                          <v-textarea
                            label="Dirección"
                            auto-grow
                            variant="outlined"
                            rows="3"
                            autocomplete="off"
                            row-height="25"
                            shaped
                            name="new_user_address"
                            v-model="newUser.address"
                          ></v-textarea>
                        </VCol>
                      </VRow>
                      <VRow class="ma-0 pa-0  mt-8 align-center">
                        <VCol cols="12" md="4" offset-md="4" class="mt-0 py-0 px-0">
                          <v-col cols="auto" class="">
                            <VBtn  color="primary" class="w-100 " type="submit" disabled id="new_user_form_button"> Guardar</VBtn>
                          </v-col>
                        </VCol>
                      </VRow>
                    </VForm>
                  </VCardText>
                </div>
              </VCard>
            </VCol>
          </VCol>
        </div>
      </div>
    </div>
    <v-snackbar
      v-model="snackShow"
      :color="snackType"
      rounded="pill"
      :timeout="snacktimeOut"
      width="max-content"
      class="text-center"
    >
     <h4 class="text-white w-100 text-center">

       {{snackMessage}}
     </h4>
        <template
          v-slot:actions
        >
        <VBtn  color="white" class="text-white" @click="snackShow=false"> Cerrar</VBtn>
        </template>
    </v-snackbar>
  </VRow>
</template>
<style lang="scss" >
table.client_table tbody th, table.client_table tbody td{
  padding: 15px 10px!important;
}
.card-datatable > thead > tr > th.client_name{
  width: 25%!important;
}

.fv-plugins-message-container{
  position: relative!important;
}
.header-content{
  border-bottom:  1px solid rgb(216, 216, 216);
}
@media screen and (max-width: 780px){
  .card-datatable > thead > tr > th.options{
    width: 1%!important;
}
  .dataTables_scrollHeadInner{
    width: 500px!important;
  }
  // .max-width-700{
  //   width: 500px!important;
  //   margin-top: 30px!important;
  // }
  .card-datatable > thead > tr > th.client_name{
    width: 80%!important;
  }
  .card-datatable > thead > tr > th:nth-child(n+2) {
      width: 0%!important;
  }
  .dataTables_scrollBody{
    overflow: auto!important;
  }
}
</style>
<script>

  export default {
    data: () => ({
      modal: '',
      internalModal:'',
      isPasswordVisible: false,
      forms:[],
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:5000,
      newUser:{
        name :'',
        email:'',
        password:'',
        address:''
      },
      selectedUser:{},
      table:'',
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-users",
          "type": "POST",
          data: function ( data ) {
            // data.filter_start_date = document.querySelector('[name="start_date"]').value;
            data.filter_name = document.querySelector('[name="client_name"]').value;

            data.order_name = data.order[0].column == 0 ? data.order[0].dir : ''


            console.log(data)
            // data.order_sort_status = data.order[0].column ==  0 ? '' : data.order[0].dir
          },
          "crossDomain": true,
          "beforeSend": function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer" + window.localStorage.getItem('id_token'))
          },
        },
        dataType:'json',
        processing: true,
        serverSide: true,
        responsive: true,
        columnDefs: [
          {
            defaultContent: "",
            targets: "_all"
          },
        ],
        columns: [
          
          { 
            title: 'Nombre',  
            responsivePriority: 0,
            class:'text-start client_name ',
            render: ( data, type, row, meta ) =>{ 
              return row.name
              
            }   
          },
          { 
            title: 'Email',
            responsivePriority: 3,
            class:'text-justify ',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return row.email
            } 
          },
          { 
            title: 'Ubicación',
            class:'',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return row.user_address
            } 
          },
          { 
            title: '<span class="d-none d-md-block">Acciones</span> <span class="d-flex justify-center d-md-none text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#8c8c8c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4H7.2c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C4 5.52 4 6.08 4 7.2v9.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h9.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.987.218-2.105V14m-4-9l-6 6v3h3l6-6m-3-3l3-3l3 3l-3 3m-3-3l3 3"/></svg></span>',
            orderable: false, 
            searchable: false, 
            class:'text-center ',
            responsivePriority: 1,
            render: ( data, type, row, meta ) =>{ 
              let html = `
                <div class="d-md-flex d-none justify-center ">
                  <span data-id="${row.id}" class="edit mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar usuario">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24">
                      <g data-id="${row.id}"  fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path data-id="${row.id}"  d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path data-id="${row.id}"  d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1l1-4Z"/>
                      </g>
                    </svg>
                  </span>
                  <span data-bs-toggle="tooltip" data-id="${row.id}" class="orders mx-2" data-bs-placement="top" data-bs-title="Ver ordenes">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                      <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                        <rect data-id="${row.id}" width="30" height="36" x="9" y="8" rx="2"/>
                        <path data-id="${row.id}" stroke-linecap="round" d="M18 4v6m12-6v6m-14 9h16m-16 8h12m-12 8h8"/></g></svg>
                  </span>
                  <span data-id="${row.id}" class="delete mx-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar chef">
                    <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" " viewBox="0 0 24 24">
                      <g data-id="${row.id}" fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M5.47 6.015v12.514a2.72 2.72 0 0 0 2.721 2.721h7.618a2.72 2.72 0 0 0 2.72-2.72V6.014m-15.235.001h17.412"/>
                        <path data-id="${row.id}" d="M8.735 6.015V4.382a1.632 1.632 0 0 1 1.633-1.632h3.264a1.632 1.632 0 0 1 1.633 1.632v1.633M9.824 16.992v-5.439m4.353 5.439v-5.439"/>
                      </g>
                    </svg>
                  </span>
              `
              html +=`</div>`

              html +=`
                <div class="d-md-none d-flex justify-center position-relative relative ">
                  <div class="dropdown dropup ">
                    <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                    </button>
                    <div class="d-flex justify-center dropdown-menu animate__animated animate__rubberBand">
                      <span  data-id="${row.id}" class="edit mx-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar usuario">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" data-id="${row.id}"  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24">
                          <g data-id="${row.id}"  fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path data-id="${row.id}"  d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path data-id="${row.id}"  d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1l1-4Z"/>
                          </g>
                        </svg>
                      </span>
                      <span data-id="${row.id}" data-bs-toggle="tooltip" data-id="${row.id}" class="orders mx-3" data-bs-placement="top" data-bs-title="Ver ordenes">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" " viewBox="0 0 24 24">
                          <path data-id="${row.id}" fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3z"/>
                        </svg>
                      </span>
                      <span  data-id="${row.id}" class="delete mx-3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar chef">
                        <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" " viewBox="0 0 24 24">
                          <g data-id="${row.id}" fill="none" stroke="#808080" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M5.47 6.015v12.514a2.72 2.72 0 0 0 2.721 2.721h7.618a2.72 2.72 0 0 0 2.72-2.72V6.014m-15.235.001h17.412"/>
                            <path data-id="${row.id}" d="M8.735 6.015V4.382a1.632 1.632 0 0 1 1.633-1.632h3.264a1.632 1.632 0 0 1 1.633 1.632v1.633M9.824 16.992v-5.439m4.353 5.439v-5.439"/>
                          </g>
                        </svg>
                      </span>
              `
              html +=`
                    </div>
                  </div>
                </div> 
              `

              return html
            } 
          },

        ],
        pageLength: 25,
        lengthChange: false,
        dom:
          '<"v-row mx-0 mb-md-5"' +
          // '<"v-col v-col-md-6 v-col-12 mb-5 mb-md-0"f>' +
          // '<"v-col v-col-md-6 v-col-12 "<" justify-center justify-md-end  d-flex "B>>' +
          '>t' +
          '<"v-row  mt-2 mx-2"' +
          '<"v-col v-col-md-6 v-col-12 mt-0 text-primary"i>' +
          '<"v-col v-col-md-6 v-col-12 mt-0 text-primary"p>' +
          '>',	   
        language: {
          sLengthMenu: '_MENU_',
          search: '',
          searchPlaceholder: 'Ingresa el Tracker ID',
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
        buttons: [
          {
            extend: 'collection',
            className: 'v-btn v-btn--elevated v-theme--light bg-secondary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100',
            text: '<i class="ti ti-screen-share me-1 ti-xs text-white"></i>Exportar',
            buttons: [
              {
                extend: 'csv',
                text: '<i class="ti ti-file-text me-2" ></i>Csv',
                className: 'dropdown-item',
                title: 'Reporte de movimientos',
              },
              {
                extend: 'excel',
                text: '<i class="ti ti-file-text me-2" ></i>Excel',
                className: 'dropdown-item',
                title: 'Reporte de movimientos',
              },
              {
                extend: 'pdf',
                text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                className: 'dropdown-item',
                title: 'Reporte de movimientos',
              },
            ]
          },
        ],
        drawCallback: function( settings ) {
          const TableElement = document.getElementById('data-table');
          const event = new Event("OptionsActionTable")
          TableElement.dispatchEvent(event);
        },
      },
    }),
    methods:{
      
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.edit').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectUser(event.target.dataset.id).finally((data)=>{
                this.showModal('editUser')
            })
          })	
        })
        document.querySelectorAll('.orders').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectUser(event.target.dataset.id).finally((data)=>{
                this.showModal('ordersUser')
            })
          })	
        })
        document.querySelectorAll('.delete').forEach(item => {
          item.addEventListener('click', async event => {
            await this.selectUser(event.target.dataset.id).finally((data)=>{
                this.showModal('deleteUser')
            })
          })	
        })
      },
      selectUser(idAccount){
        this.emitter.emit('displayOverlayLoad', true)
        return new Promise((resolve) => {
          this.$store
            .dispatch(GET_USER_BY_ID, idAccount)
            .then((response) => {
              this.selectedUser = Object.assign({}, response.data);
              setTimeout(() => {
                this.validateFormItem('edit_user_form')
                this.emitter.emit('displayOverlayLoad', false)
                resolve(response.data);
              }, 500);
            })
          })
          .catch((err) => {
            console.log(err)
            resolve(false);
          });
      },
      bootstrapOptions(){
        setTimeout(() => {
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
          const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
          const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
          const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
        }, 2000);
      },
      filterColumn(){
        debounce(()=>{
          this.table.clear();
          this.table.draw('full-hold');
        }, 200)()
      },
      clearFilters(){
        document.querySelector('[name="tracker_id"]').value = '';
        document.querySelector('[name="start_date"]').value = '';
        document.querySelector('[name="end_date"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showModal(modal) {
        try {
          this.modal.hide()
        } catch (error) {
          
        }
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
      showInternalModal(modal) {
        this.internalModal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.internalModal.show()
      },
      hideInternalModal(modal) {
        
        this.internalModal.hide()
        this.modal.show()
      },
      hideModal(){
        this.modal.hide()
        try {
          this.forms['edit_user_form'].destroy()
        } catch (error) {
          
        }
        
      },
      createUser(){
        this.sendingButton('new_user_form_button')
        const formData = new FormData();
        formData.append('name', this.newUser.name);
        formData.append('email', this.newUser.email);
        formData.append('password', this.newUser.password);
        formData.append('user_address', this.newUser.address);
        formData.append('rol_id', 3);
        console.log(this.newUser)

        this.$store
          .dispatch(CREATE_USER, formData)
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Usuario creado con exito')
            this.readyButton('new_user_form_button')
            this.resetNewUserForm()
          })
          .catch((err) => {
            console.log(err)
            this.showSnackbar('error', err)
            this.notSendButton('new_user_form_button')
          
          });
        
      },
      updatedUser(){
        this.sendingButton('edit_user_form_button')
        console.log(this.selectedUser)

        this.$store
          .dispatch(UPDATE_USER, this.selectedUser)
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('success', 'Usuario creado con exito')
            this.readyButton('edit_user_form_button')
            
          })
          .catch((err) => {
            console.log(err)
            this.showSnackbar('error', err)
            this.notSendButton('new_user_form_button')
          
          });
        
      },
      resetNewUserForm(){
        this.newUser={
          name :'',
          email:'',
          password:'',
          address:''
        }
        this.forms['new_user_form'].resetForm()
      },
      deleteUser(){
        console.log(this.selectedUser)
        this.sendingButton('delete_form_button')
        this.$store
          .dispatch(DELETE_USER, this.selectedUser.id )
          .then((response) => {
            this.filterColumn()
            this.hideModal()
            this.showSnackbar('error', 'Usuario Eliminado')
            this.notSendButton('delete_form_button')
          })
          .catch((err) => {
            console.log(err)
            this.showSnackbar('warning', err)
            this.notSendButton('delete_form_button')
          
          });
      },
      showSnackbar(type, messagge){
        this.snackShow = true;
        this.snackType = type
        this.snackMessage = messagge
      },
      sendingButton(id){
        document.getElementById(id).disabled = true
      },
      readyButton(id){
        document.getElementById(id).disabled = true
        
        document.getElementById(id).setAttribute('class','v-btn v-btn--disabled v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-elevated w-100')
      },
      notSendButton(id){
        document.getElementById(id).disabled = false
      },
      validateFormItem(id){
        const fieldToValidate = this.itemsValidateByForm(id)
        this.forms[id] = formValidation(document.getElementById(id), {
          fields: fieldToValidate,
          plugins: {
            trigger: new Trigger(),
            submitButton: new SubmitButton(),
            bootstrap: new Bootstrap({
                  // Use this for enabling/changing valid/invalid class
                  // eleInvalidClass: '',
                  // eleValidClass: '',
                }),
          }
        });
        setTimeout( ()=>this.formsActions(id), 500)
      },
      itemsValidateByForm(id){
        let fieldByForm = {}
        switch (id) {
          case 'new_user_form':
            fieldByForm = {
              new_user_name: {
                validators: {
                  notEmpty: {
                    message: "El nombre es requerido"
                  },
                  regexp: {
                    regexp: /^[A-Za-zÀ-ÿ ñ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_user_email: {
                validators: {
                  notEmpty: {
                    message: "El email es requerido"
                  },
                  emailAddress: {
                    message: 'Email no valido',
                  },
                }
              },
              new_user_password: {
                validators: {
                  notEmpty: {
                    message: "La contraseña es requerida"
                  },
                  stringLength: {
                    min: 8,
                    message: 'La contraseña debe contener minimo 8 caracteres',
                  },
                  regexp: {
                    regexp: /^[A-Za-z1-9À-ÿ.*-+/&-\-@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              new_user_address: {
                validators: {
                  notEmpty: {
                    message: "La dirección es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&\-@,#$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'edit_user_form':
            fieldByForm = {
              user_name: {
                validators: {
                  notEmpty: {
                    message: "El nombre es requerido"
                  },
                  regexp: {
                    regexp: /^[A-Za-zÀ-ÿ ñ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              user_email: {
                validators: {
                  notEmpty: {
                    message: "El email es requerido"
                  },
                  emailAddress: {
                    message: 'Email no valido',
                  },
                }
              },
              user_password: {
                validators: {
                  stringLength: {
                    min: 8,
                    message: 'La contraseña debe contener minimo 8 caracteres',
                  },
                  regexp: {
                    regexp: /^[A-Za-z1-9À-ÿ.*-+/&-\-@,$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
              user_address: {
                validators: {
                  notEmpty: {
                    message: "La dirección es necesaria"
                  },
                  regexp: {
                    regexp: /^[A-Za-z0-9À-ÿ .*-+/&\-@,#$_ñ_ ]+$/i,
                    message: 'No debe contener los siguientes caracteres: "[]{}!¡¿?=()|;',
                  },
                }
              },
            }
            break;
          case 'add_stock_form':
            fieldByForm = {
              stock_form_quatity: {
                validators: {
                  notEmpty: {
                    message: "La cantidad es obligatoria"
                  },
                  regexp: {
                    regexp: /^[0-9]+$/i,
                    message: 'Debe ser numerico',
                  },
                }
              },
              stock_add_lot:{
                validators: {
                  notEmpty: {
                    message: "El número de lote es obligatorio"
                  },
                }
              },
              stock_dis_lot:{
                validators: {
                  notEmpty: {
                    message: "El número de lote es obligatorio"
                  },
                }
              },
              stock_due_date:{
                validators: {
                  notEmpty: {
                    message: "La fecha de vencimiento es obligatorio"
                  }
                }
              }
            }
            break;
          default:
            break;
        }
        return fieldByForm
      },
      formsActions(id){
        const sendButton = document.getElementById(id+'_button')
       
        this.forms[id].on("core.form.valid", () => {
          switch (id) {
            case 'new_user_form':
              this.createUser()
              
              break;
            case 'edit_user_form':
              this.updatedUser()
              break;
            default:
              break;
          }

        }).on("core.field.valid", () => {
          sendButton.disabled = false
          sendButton.classList.remove('v-btn--disabled')

        }).on("core.form.invalid", () => {
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')

        }).on("core.field.invalid", () => {
          sendButton.disabled = true
          sendButton.classList.add('v-btn--disabled')

        });
      },
      selectedOrder(order){
        this.selectedUser.selectedOrder =  order
        setTimeout(() => {
          this.hideModal()
          this.showInternalModal('viewOrder')
        }, 200);
      }
        
    },
    mounted(){
      this.initOptionsTable()
      this.table = new DataTablesCore('#data-table', this.tableData)
      this.bootstrapOptions();
      this.validateFormItem('new_user_form')
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>
