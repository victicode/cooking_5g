<script setup >
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import Button from 'datatables.net-buttons';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import ButtonPrint from 'datatables.net-buttons/js/buttons.print';
  import ButtonHTML5 from 'datatables.net-buttons/js/buttons.html5';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';

  DataTable.use(DataTablesCore);
  DataTable.use(Button);
  DataTable.use(ButtonHTML5);
  DataTable.use(ButtonPrint); 
  
</script>

<template>
  <VRow class="">
    <VCol cols="12">
      <VCard title="Listado de Ordenes" class="pa-3 px-2 px-md-3">
        <VRow class="ma-0  justify-center justify-md-start pa-2 px-0 mb-0">
          <VCol cols="4" class="form-group">
            <VTextField
              autofocus
              placeholder="Buscar por Track ID"
              label="Track ID"
              type="text"
              name="email"
            />
          </VCol>
        </VRow>
        <DataTable
          id="data-table"
          ref="table"
          :columns="table.columns"
          :options="table.options"
          :ajax= "table.ajax" 
          class=" display"
        />
      </VCard>
    </VCol>
    <div>

      <div class="modal animate__animated animate__bounceInDown" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="v-card v-theme--light v-card--density-default v-card--variant-elevated">
              <!---->
              <div class="v-card__loader">
                <div class="v-progress-linear v-theme--light v-locale--is-ltr" role="progressbar" aria-hidden="true" aria-valuemin="0" aria-valuemax="100" style="top: 0px; height: 0px; --v-progress-linear-height: 2px; left: 50%; transform: translateX(-50%);">
                  <!----><div class="v-progress-linear__background" style="width: 100%;"></div>
                  <div class="v-progress-linear__indeterminate">
                    <div class="v-progress-linear__indeterminate long"></div>
                    <div class="v-progress-linear__indeterminate short"></div>
                  </div><!---->
                </div>
              </div><!----><!---->
              <header class="v-toolbar v-toolbar--density-default bg-primary v-theme--light v-locale--is-ltr">
                <div class="v-toolbar__content" style="height: 64px;">
                  <div class="v-toolbar-title">
                    <div class="v-toolbar-title__placeholder">
                      Opening from the top<!---->
                    </div>
                  </div>
                </div>
              </header>
              <div class="v-card-text">
                <div class="text-h2 pa-12">
                  Hello world!
                </div>
              </div>
              <div class="v-card-actions justify-end">
                <button type="button" class="v-btn v-theme--light text-primary v-btn--density-default v-btn--size-default v-btn--variant-text">
                  <span class="v-btn__overlay"></span>
                  <span class="v-btn__underlay"></span>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </button>
              </div>
              <span class="v-card__underlay"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </VRow>
</template>
<style lang="scss" >
  thead > tr > th.date{
    width: 10%!important;
  }
  .modal{
    animation-duration: 0.8s; /* don't forget to set a duration! */
  }
</style>
<script>
  
  import * as bootstrap from 'bootstrap'
 
  import moment from 'moment';
  export default {
    methods:{
      initOptionsTable(){
        document.getElementById('data-table').addEventListener('OptionsActionTable', () => this.activeOptionsTable() )	
      },
      activeOptionsTable() {
        document.querySelectorAll('.view').forEach(item => {
          item.addEventListener('click', event => {
            // console.log('ardilla')
            // this.emitter.emit('displayOverlayModal', true)
            this.modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
              keyboard: false,              
              backdrop:'static'
            })
            this.modal.show()

          })	
        })
      },
      // showModal(modal) {
      //   this.showPass = id;
      //   setTimeout(() => {
      //     this.openModal = new bootstrap.Modal(document.getElementById(modal), {
      //       keyboard: false,
      //       backdrop:'static'
      //     })
      //     this.openModal.show()

      //     if(this.showPass == 2){
      //       this.textModal= this.selectedOperation.status == 1 ? 'procesar esta operación' :  'finalizar esta operación'
      //       this.setSelectedOperationChangeStatusData(this.selectedOperation.id, this.selectedOperation.status, this.selectedOperation.status + 1)
      //     }
      //     if(this.showPass == 3){
      //       this.textModal= 'cancelar esta operación' 
      //       this.setSelectedOperationChangeStatusData(this.selectedOperation.id, this.selectedOperation.status, -1)
      //     }
      //   }, 500);
        
      // },
    },
    data: () => ({
      modal: '',
      table:{
        ajax:{
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-orders",
          "contentType": "application/json",
          "type": "POST",
        },
        columns: [
          { 
            title: 'Fecha',  
            class:'text-center d-md-table-cell d-none date',
            render: ( data, type, row, meta ) =>{ 
              return `
              ${ moment(row.created_at).format('DD-MM-YYYY') }
              `
            }   
          },
          { 
            title: 'Track ID',
            class:'text-center',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return `#${row.trancker} `
            } 

          },
          { 
            title: 'Creada por',
            class:'text-center d-md-table-cell d-none',
            orderable: false, 
            render: ( data, type, row, meta ) =>{ 
              return ` ${row.user.name} `
            } 

          },
          { 
            title: 'Estado',
            class:'text-center',
            orderable: true,
            render: ( data, type, row, meta ) =>{ 
              return `
              ${row.status} `

              
            },
            render: ( data, type, row, meta ) =>{ 
              return `
              <span 
                class="v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${row.status == 1 ? 'bg-warning' : row.status == 2 ? 'bg-secondary' : 'bg-success'}" 
                draggable="false"
                >
                  <span class="v-chip__underlay"></span>
                <div class="v-chip__content"> ${row.status_info}</div>
              </span> `
            }

          },
          { 
            title: 'Acciones',
            orderable: false, 
            searchable: false, 
            class:'text-center',
            render: ( data, type, row, meta ) =>{ 
              return `
              <div class="d-md-flex d-none justify-center ">
                <span class="view" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Actualizar estado">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancelar Orden">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                </span>
              </div>
              <!- Vista en moviles ->
              <div class="d-md-none d-flex justify-center position-relative relative ">
                <div class="dropdown dropup ">
                  <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                  </button>
                  <div class="dropdown-menu animate__animated animate__rubberBand">
                    <span class="view" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                      <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Actualizar estado">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancelar Orden">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                    </span>
                  </div>
                </div>
              </div> 

              
              
              
              `
            } 
          },

        ],
        options : {
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
            console.log(settings)
          },
        },
        
      },
    }),
    mounted(){
      this.initOptionsTable()
      this.emitter.emit('displayOverlayLoad', false)

      setTimeout(() => {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
      }, 2000);
    }
  };
</script>