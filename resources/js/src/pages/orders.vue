<script setup >
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import Button from 'datatables.net-buttons';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'

  import ButtonHTML5 from 'datatables.net-buttons/js/buttons.html5';
  import ButtonPrint from 'datatables.net-buttons/js/buttons.print';

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
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
            <!-- <VBtn color="primary" class="w-100 "><VIcon icon="bx-plus"/> Crear nueva orden</VBtn> -->
          </VCol>
        </VRow>
        <DataTable
          :columns="table.columns"
          :options="table.options"
          :ajax= "table.ajax" 
          class=" display"
          
        />
      </VCard>
    </VCol>
  </VRow>
</template>
<style lang="scss" >
  thead > tr > th.date{
    width: 10%!important;
  }
  div.bs-tooltip-auto{
  background: #272727;
    color: white;
    padding: 5px 10px;
    font-size: 85%;
    border-radius: 10px;
  }
  tbody > tr > td{
    padding: 15px 5px!important ;
  }
  .dataTables_filter{
    width: 100%!important;
    padding: 0px!important;

    & label{
      width: 100%!important;
      display: block;
    }
    & input[type="search"]{
      width: 100%!important;
      padding: 10px!important;
      border-radius: 10px!important;
      border-color: #b4b2b2ea!important;
      transition: all ease-out 0.5s;
      &::placeholder{
        color:#b4b2b2ea
      }
      &:focus-visible{
        outline: none;
        border-color: orange!important;

      }
    }
  }
  
</style>
<style>
.dropdown-menu{
  visibility: hidden;
  position: absolute;
  background: white;
  padding: 10px 20px;
  box-shadow: 0px 5px 10px;
  border-radius: 10px;
  position: absolute;
  inset: unset!important;
  margin: 0px;
  top: -50px!important;
  width: auto;
  right: -7%!important;
  display: flex;
}
.dropdown-menu.show{
  visibility: visible;
  animation: bounceIn 0.3s forwards;
}
  @keyframes bounceIn{
    0%,20%,40%,60%,80%,
    to{
      -webkit-animation-timing-function:cubic-bezier(.215,.61,.355,1);
      animation-timing-function:cubic-bezier(.215,.61,.355,1)
    }
    0%{
      opacity:0;
      -webkit-transform:scale3d(.3,.3,.3);
      transform:scale3d(.3,.3,.3)!important;
    }
    20% {
      -webkit-transform:scale3d(1.1,1.1,1.1);
      transform:scale3d(1.1,1.1,1.1)!important;
    }
    40%{
      -webkit-transform:scale3d(.9,.9,.9);
      transform:scale3d(.9,.9,.9)!important;
    }
    60%{
      opacity:1;-webkit-transform:scale3d(1.03,1.03,1.03);
      transform:scale3d(1.03,1.03,1.03)!important;
    }
    80%{
      -webkit-transform:scale3d(.97,.97,.97);
      transform:scale3d(.97,.97,.97)!important;
    }
    to{
      opacity:1;
      -webkit-transform:scaleX(1);
      transform:scaleX(1)!important;
    }
  }
</style>

<script>
  
  import * as bootstrap from 'bootstrap'
 
  import moment from 'moment';
  export default {
    methods:{

    },
    data: () => ({
      
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
              console.log(row)
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
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Actualizar estado">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-5 iconify iconify--icon-park-outline" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4S4 12.954 4 24s8.954 20 20 20"></path><path d="M33.542 27c-1.274 4.057-5.064 7-9.542 7c-4.477 0-8.268-2.943-9.542-7v6m19.084-18v6c-1.274-4.057-5.064-7-9.542-7c-4.477 0-8.268 2.943-9.542 7"></path></g></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancelar Orden">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default  iconify iconify--ic" aria-describedby="v-tooltip-11" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10s10-4.47 10-10S17.53 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.59-13L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"></path></svg>
                </span>
              </div>
              <div class="d-md-none d-flex justify-center position-relative relative ">
                <div class="dropdown dropup ">
                  <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                  </button>
                  <div class="dropdown-menu animate__animated animate__rubberBand">
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver detalles">
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
            '<"v-col v-col-md-6 v-col-12 mb-5 mb-md-0"f>' +
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
        },
        
      },
      ready:false,
      products:[
        {id:1, title:'perrito', stock:2, type_of_unit:'KG'},
        {id:2, title:'perrito', stock:2, type_of_unit:'KG'},
        {id:3, title:'perrito', stock:2, type_of_unit:'KG'}
      ]
    }),
    mounted(){
      // console.log(ProductsOptions.render())
      setTimeout(() => {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
      }, 2000);
    }
  };
</script>