<script setup >
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import Button from 'datatables.net-buttons';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import OrderProductsTables from '@/views/pages/tables/OrderProductsTables.vue'
  import ButtonPrint from 'datatables.net-buttons/js/buttons.print';
  import ButtonHTML5 from 'datatables.net-buttons/js/buttons.html5';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import * as bootstrap from 'bootstrap'
  import flatpickr from "flatpickr";
  import moment from 'moment';
  import 'flatpickr/dist/flatpickr.min.css'
  import { Spanish } from "flatpickr/dist/l10n/es.js"
  import { GET_RECIPES, GET_RECIPE_BY_ID} from "@/core/services/store/recipe.module";


</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de recetas" class="pa-0 px-1 px-md-3 mb-1">
        <VRow class="ma-0  justify-center align-center justify-md-start pa-2 px-0 mb-0 mb-md-2">
          <VCol cols="6" md="4" class="form-group">
            <VTextField
              placeholder="Buscar nombre de receta"
              label="Buscar por receta"
              type="text"
              ref="recipe"
              name="recipe_title"
              @change="filterColumn()"
            />
          </VCol>
          <VCol cols="6" md="4" class="form-group">
            <VTextField
              placeholder="Buscar por chef"
              label="Buscar por chef"
              type="text"
              ref="chef"
              name="chef_name"
              @change="filterColumn()"
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
        <!-- <div class="card-datatable table-responsive">
          <table class="datatables-basic table recipes-table" id="data-table">
          </table>
        </div> -->
      </VCard>
      <template v-if="recipes.length > 0">
          <VCard class="mt-3" v-for="recipe in recipes" :key="recipe.id" >
            <VRow class="ma-0  justify-center align-center justify-md-start pa-0 px-md-5 px-0 mb-0 mb-md-0">
              <VCol cols="12"  class="d-flex align-center">
                <div>
                  <img src="https://casadepaellas.com/wp-content/uploads/2015/11/Paella-de-Marisco-300x300.jpg" width="100" height="100" style="border-radius: 20%;">
                </div>
                <div class="px-2 w-100">
                  <div>

                    <div class="d-flex text-md-start ">
                      <h3 class="w-100"> {{ recipe.title }} </h3>
                      
                    </div>
                    <div class="ms-2 d-flex justify-space-between align-end">
                      <div>
                        <h5 class="text-primary my-1"> Chef: <b class="text-decoration-underline cursor-pointer">{{ recipe.chef.name }}</b> </h5>
                        <v-chip color="primary" class="mt-1 pb-0">
                            <b>
                              {{ recipe.type }}
                            </b>
                        </v-chip>
                      </div>
                      <div class="d-flex">
                        <v-btn size="x-small" class="d-block d-md-none" color="secondary" @click.stop="drawer = !drawer" icon="majesticons:plus-line" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="secondary" icon="carbon:view" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="secondary" icon="line-md:edit-twotone-full" />
                        <v-btn size="small" class="d-none d-md-block mx-2" color="error" icon="mi:delete" />
                      </div>
                    </div>
                  </div>
                </div>
              </VCol>
            </VRow>
          </VCard>
      </template>
    </VCol>
    
  </VRow>
  <v-navigation-drawer
        v-model="drawer"
        location="bottom"
        temporary
      >
      <v-list nav>
          <v-list-item class="my-2 pa-3 item-list-drawer" prepend-icon="mdi-email" title="Inbox" value="inbox"></v-list-item>
          <v-list-item class="my-2 pa-3 item-list-drawer" prepend-icon="mdi-account-supervisor-circle" title="Supervisors" value="supervisors"></v-list-item>
          <v-list-item class="my-2 pa-3 item-list-drawer" prepend-icon="mdi-clock-start" title="Clock-in" value="clockin"></v-list-item>
      </v-list>
  </v-navigation-drawer>
</template>
<style lang="scss" >

table.recipes-table > thead > tr > th:nth-child(n+1){
  width: max-content!important
}

.item-list-drawer{
  border-bottom: 1px solid rgba(165, 165, 165, 0.822); border-radius: 0px;
}
</style>

<script>

  export default {
    data: () => ({
      modal: '',
      items:['editar','eliminar', 'ver'],
      drawer:false,
      selectedOrder:{},
      recipes:[],
      table:'',
      tableData:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-recipes",
          "type": "POST",
          data: function ( data ) {
            // data.filter_tracker_id = document.querySelector('[name="chef_name"]').value;
            // data.filter_start_date = document.querySelector('[name="recipe_title"]').value;
            // data.filter_end_date = document.querySelector('[name="end_date"]').value;
          },
          "crossDomain": true,
          "beforeSend": function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer" + window.localStorage.getItem('id_token'))
          },
        },
        dataType:'json',
        processing: true,
        serverSide: true,
        columns: [
          { 
            title: 'Receta',
            class:'text-start',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return ` ${row.title} `
            } 

          },
          { 
            title: 'Chef',  
            class:'text-center date',
            render: ( data, type, row, meta ) =>{ 
              return `
              ${row.chef.name}
              `
            }   
          },
          { 
            title: 'Estilo',
            class:'text-center justify-center px-0 px-md-3',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return ` ${ moment(row.created_at).format('DD-MM-YYYY') }`
            } 

          },
          { 
            title: 'Acciones',
            orderable: false, 
            searchable: false, 
            class:'text-center px-0 px-md-3',
            render: ( data, type, row, meta ) =>{ 
              return `
              <div class="d-md-flex d-none justify-center ">
                <span data-id="${row.id}" class="view" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                  <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-id="${row.id}" class="change" data-bs-placement="top" data-bs-title="Actualizar estado">
                  <svg data-id="${row.id}"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48">
                    <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
                      <path data-id="${row.id}" d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                </span>
                <span data-bs-toggle="tooltip"  data-id="${row.id}"class="cancel data-bs-placement="top" data-bs-title="Cancelar Orden">
                  <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                    <path data-id="${row.id}" fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                </span>
              </div>
              <!- Vista en moviles ->
              <div class="d-md-none d-flex justify-center position-relative relative ">
                <div class="dropdown dropup ">
                  <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                  </button>
                  <div class="dropdown-menu animate__animated animate__rubberBand">
                    <span  data-id="${row.id}" class="view me-5" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="change me-5"  data-bs-placement="top" data-bs-title="Actualizar estado">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48">
                        <g data-id="${row.id}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
                          <path data-id="${row.id}" d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-id="${row.id}" class="cancel" data-bs-placement="top" data-bs-title="Cancelar Orden">
                      <svg data-id="${row.id}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24">
                        <path data-id="${row.id}" fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                    </span>
                  </div>
                </div>
              </div> 

              
              
              
              `
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
      getRecipes(){
        this.$store.dispatch(GET_RECIPES).then((data)=>{
          this.recipes = data.data.data
        })
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('viewOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.change').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('changeStatusOrder')
              }, 500);
            })
          })	
        })
        document.querySelectorAll('.cancel').forEach(item => {
          item.addEventListener('click', event => {
            this.selectOrder(event.target.dataset.id).finally((data)=>{
              setTimeout(() => {
                this.showModal('cancelOrder')
              }, 500);
            })
          })	
        })
      },
      async selectOrder(idAccount){
        this.$store
          .dispatch(GET_RECIPE_BY_ID, idAccount)
          .then((response) => {
            this.selectedOrder = Object.assign({}, response.data);
            return new Promise((resolve) => {
                resolve(response.data);
            });
          })
          .catch((err) => {
            console.log(err)
            return new Promise((resolve) => {
              resolve(false);
            });
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
        this.table.clear();
        this.table.draw('full-hold');
      },
      clearFilters(){
        document.querySelector('[name="chef_name"]').value = '';
        document.querySelector('[name="recipe_title"]').value = '';
        this.table.clear();
        this.table.columns().search('').draw('full-hold')
      },
      showModal(modal) {
        this.modal = new bootstrap.Modal(document.getElementById(modal), {
          keyboard: false,              
          backdrop:'static'
        })
        this.modal.show()
      },
    },
    mounted(){
      // this.initOptionsTable()
      // this.table = new DataTablesCore('#data-table', this.tableData)
      // this.bootstrapOptions();
      this.getRecipes()
    },
    created(){
      
      this.emitter.emit('displayOverlayLoad', false)
    }
    
  };
</script>