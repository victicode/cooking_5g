<script setup >
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'

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
      <VCard title="Listado de productos" class="pa-3 px-2 px-md-3">
        <VRow class="ma-0  justify-center justify-md-end pa-2 px-0 mb-0">
          <VCol
            cols="11"
            md="3"
            class="ma-0 px-0 justify-center justify-md-end d-flex"
          >
            <v-dialog width="500">
              <template v-slot:activator="{ props }">
                <VBtn v-bind="props" color="primary" class="w-100 "><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>
              </template>
            
              <template v-slot:default="{ isActive }">
                <v-card title="Dialog">
                  <v-card-text>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  </v-card-text>
            
                  <v-card-actions>
                    <v-spacer></v-spacer>
            
                    <v-btn
                      text="Close Dialog"
                      @click="isActive.value = false"
                    ></v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
          </VCol>
        </VRow>
        <DataTable
          :columns="table.columns"
          :options="table.options"
          :ajax= "table.ajax" 
          ref="table"
          class=" display"
          
        />
        <!-- <DemoSimpleTableBasics v-if="ready" :products="products" /> -->
      </VCard>
    </VCol>
    
  </VRow>
</template>
<style lang="scss">
  div.bs-tooltip-auto{
  background: #272727;
    color: white;
    padding: 5px 10px;
    font-size: 85%;
    border-radius: 10px;
  }
  thead > tr > th.title-th{
    width: 70%!important;
  }
  thead > tr > th:nth-child(n+2){
    width: 15%!important;
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
  width: 160px;
  right: -7%!important;
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
  import * as bootstrap from 'bootstrap';
  import { STORE_PRODUCTS } from "@/core/services/store/product.module";
  
  export default {
    methods:{
      addItem(){
        this.$store
          .dispatch(STORE_PRODUCTS, { email, password })
          .then((data) => {
            if(data.code === 500 ){
              submitButton.textContent = 'Ingresar';
              submitButton.blur();
              this.showAlert('alert-danger',data.messagge)
              return
            }
            this.showAlert('alert-success','Acceso Exitoso')
            submitButton.textContent = 'Acceso Exitoso'
            setTimeout(() => {
              
              this.$router.push('/dashboard') 
            }, 1000);
          })
          .catch((e) => {

            // console.log(e)
          });
      },
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
          { title: 'Nombre del producto', class:'text-start title-th',
          render: ( data, type, row, meta ) =>{ 
              return row.title
            }   
          },
          { 
            title: 'Stock',
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
            orderable: false, 
            searchable: false, 
            class:'text-center',
            render: ( data, type, row, meta ) =>{ 
              return `
              <div class="d-md-flex d-none justify-center">
                
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver ficha de producto">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar producto">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Agregar stock">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M13 19.3v-6.7l6-3.4V13c.7 0 1.4.1 2 .4V7.5c0-.4-.2-.7-.5-.9l-7.9-4.4c-.2-.1-.4-.2-.6-.2s-.4.1-.6.2L3.5 6.6c-.3.2-.5.5-.5.9v9c0 .4.2.7.5.9l7.9 4.4c.2.1.4.2.6.2s.4-.1.6-.2l.9-.5c-.3-.6-.4-1.3-.5-2M12 4.2l6 3.3l-2 1.1l-5.9-3.4l1.9-1m-1 15.1l-6-3.4V9.2l6 3.4v6.7m1-8.5L6 7.5l2-1.2l6 3.5l-2 1m8 4.2v3h3v2h-3v3h-2v-3h-3v-2h3v-3h2Z"></path></svg>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar producto">
                  <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path fill="currentColor" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
                </span>
              </div>
              <div class="d-md-none d-flex justify-center position-relative relative ">
                <div class="dropdown dropup ">
                  <button type="button dropup" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-46" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg>
                  </button>
                  <div class="dropdown-menu animate__animated animate__rubberBand">
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver ficha de producto">
                      <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-19" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar producto">
                      <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-20" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Agregar stock">
                      <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-4 iconify iconify--mdi" aria-describedby="v-tooltip-21" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M13 19.3v-6.7l6-3.4V13c.7 0 1.4.1 2 .4V7.5c0-.4-.2-.7-.5-.9l-7.9-4.4c-.2-.1-.4-.2-.6-.2s-.4.1-.6.2L3.5 6.6c-.3.2-.5.5-.5.9v9c0 .4.2.7.5.9l7.9 4.4c.2.1.4.2.6.2s.4-.1.6-.2l.9-.5c-.3-.6-.4-1.3-.5-2M12 4.2l6 3.3l-2 1.1l-5.9-3.4l1.9-1m-1 15.1l-6-3.4V9.2l6 3.4v6.7m1-8.5L6 7.5l2-1.2l6 3.5l-2 1m8 4.2v3h3v2h-3v3h-2v-3h-3v-2h3v-3h2Z"></path></svg>
                    </span>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar producto">
                      <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default iconify iconify--bx" aria-describedby="v-tooltip-22" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path fill="currentColor" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
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
          "url": import.meta.env.VITE_VUE_APP_BACKEND_URL+"api/get-products",
          "contentType": "application/json",
          "type": "POST",
          "crossDomain": true,
          "beforeSend": function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer" + window.localStorage.getItem('id_token'))
          },
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
        this.ready = true
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
      }, 2000);
    }
  };
</script>