<script setup >
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'

  import { GET_PRODUCTS } from "@/core/services/store/product.module";
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import Button from 'datatables.net-buttons';
  import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';

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
      <VCard title="Listado de productos" class="pa-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
            <VBtn color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>
          </VCol>
        </VRow>
        <DataTable
          :columns="table.columns"
          :options="table.options"
          :ajax= "table.ajax" 
          class="table table-hover display"
          
        />
        <DemoSimpleTableBasics v-if="ready" :products="products" />
        <!-- <table class="display px-0 mx-0 " id="table">
          <thead>
            <tr>
              <th class="text-center">id</th>
              <th class="">Nombre del producto</th>
              <th class="">Stock</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
        </table> -->
      </VCard>
    </VCol>
  </VRow>
</template>
<style lang="scss">
// .v-tooltip{
  
// }
div.bs-tooltip-auto{
  background: #272727;
    color: white;
    padding: 5px 10px;
    font-size: 85%;
    border-radius: 10px;
}
  thead > tr > th:nth-child(n+2){
    width: 15%!important;
  }
  tbody > tr > td{
    padding: 15px 10px!important ;
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

<script>
  import ProductsOptions from '@/views/pages/products/ProductsOption.vue'
  import * as bootstrap from 'bootstrap'

  export default {
    template:{
      options: ProductsOptions 
    },
    methods:{
    initOptionsTable(){
          // document.querySelector('.dt-buttons').classList.add('justify-content-end')
          const TableElementContent = document.getElementById('table');
          // this.activeFilterInputs()
          TableElementContent .addEventListener('OptionsActionTable', () => this.ready = true )	
        },

    },
    data: () => ({
      table:{
        columns: [
          { title: 'Nombre del producto', data: 'title', class:'text-start'  },
          { 
            title: 'Stock', 
            data: 'stock', 
            class:'text-center',
            render: ( data, type, row, meta ) =>{ 
              return `
              <span 
                class="v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${row.stock < 1 ? 'bg-error' : row.stock >= 30 ? 'bg-success' : 'bg-warning'}" 
                draggable="false"
                >
                  <span class="v-chip__underlay"></span>
                <div class="v-chip__content">${$number_format(row.stock)} ${row.type_of_unit}</div>
              </span> `
            } 

          },
          { 
            title: 'Acciones', 
            data: 'stock', 
            orderable: false, 
            searchable: false, 
            class:'text-center',
            render: ( data, type, row, meta ) =>{ 
              return `
              <div class="d-none d-sm-flex">
                
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cuentas de bancos">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cuentas de bancos">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cuentas de bancos">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M13 19.3v-6.7l6-3.4V13c.7 0 1.4.1 2 .4V7.5c0-.4-.2-.7-.5-.9l-7.9-4.4c-.2-.1-.4-.2-.6-.2s-.4.1-.6.2L3.5 6.6c-.3.2-.5.5-.5.9v9c0 .4.2.7.5.9l7.9 4.4c.2.1.4.2.6.2s.4-.1.6-.2l.9-.5c-.3-.6-.4-1.3-.5-2M12 4.2l6 3.3l-2 1.1l-5.9-3.4l1.9-1m-1 15.1l-6-3.4V9.2l6 3.4v6.7m1-8.5L6 7.5l2-1.2l6 3.5l-2 1m8 4.2v3h3v2h-3v3h-2v-3h-3v-2h3v-3h2Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cuentas de bancos">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--bx" aria-describedby="v-tooltip-22" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path fill="currentColor" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
                </span>
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
            '<"v-col v-col-md-6 v-col-12"f>' +
            // '<"v-col v-col-md-6 v-col-12 "<" justify-center justify-md-end  d-flex "B>>' +
            '>t' +
            '<"v-row  mt-2 mx-2"' +
            '<"v-col v-col-md-6 v-col-12 mt-0 text-primary"i>' +
            '<"v-col v-col-md-6 v-col-12 mt-0 text-primary"p>' +
            '>',	   
          language: {
            sLengthMenu: '_MENU_',
            search: '',
            searchPlaceholder: 'Ingresa el nombre del producto',
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
        ajax:{
          "url": "http://10.10.10.69:8085/api/get-orders",
          "contentType": "application/json",
          "type": "POST",
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
      console.log(ProductsOptions.render())
      setTimeout(() => {
        this.ready = true
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
      }, 1000);
    }
  };
</script>