<script setup >
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
  import { GET_PRODUCTS } from "@/core/services/store/product.module";
  import DataTable from 'datatables.net-dt';
  import 'datatables.net-responsive-dt';
  import 'datatables.net-dt/css/jquery.dataTables.min.css';
  import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';

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
        <!-- <DemoSimpleTableBasics v-if="ready" :products="products" /> -->
        <table class="display px-0 mx-0 cell-border" id="table">
          <thead>
            <tr>
              <th class="text-center">id</th>
              <th class="">Nombre del producto</th>
              <th class="">Stock</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
        </table>
      </VCard>
    </VCol>
  </VRow>
</template>
<style>
  #table > thead > tr > th:nth-child(n+2){
    width: 15%!important;
  }
  #table > tbody > tr > td{
    padding: 15px 10px ;
  }
</style>
<script>
  export default {
    methods:{
      getOperationsDataTable(){

        this.tabled = new DataTable('#table', {
          ajax: {
            url: 'http://192.168.42.226:8085/api/xs',
            // url: 'http://10.10.10.69:8085/api/xs',
            type: "POST",
            // data: function ( data ) {
            //     data.keyForApi = keyData.data.value
            //     data.filter_bank_id = document.getElementById('filter_table_bank').value
            //     data.filter_operation_number = document.getElementById('filter_table_operation_number').value
            //     data.filter_start_date = document.querySelector('.start_date').value;
            //     data.filter_end_date = document.querySelector('.end_date').value;
            // }
          },
          dataType:'json',
          processing: true,
          serverSide: true,
          
          drawCallback: function( settings ) {
            const TableElement = document.getElementById('table');
            const event = new Event("OptionsActionTable")
            TableElement.dispatchEvent(event);
          },
          columnDefs: [
            { className: 'text-center',
              targets: 0,
              searchable: false,
              visible: false,
              render: function (data, type, full, meta) {
                return full.title
              }
            },  
            { className: '',
              targets: 1,
              searchable: true,
              visible: true,
              render: function (data, type, full, meta) {
                return full.title
              }
            },
            {
              className: 'text-center',
              targets: 2,
              searchable: true,
              visible: true,
              sorteable:true,
              render: function (data, type, full, meta) {
                let date = new Date(full.created_at)
                return (
                  `<span 
                    class="v-chip v-theme--light v-chip--density-comfortable elevation-0 v-chip--size-default v-chip--variant-tonal ${full.stock < 1 ? 'bg-error' : full.stock >= 30 ? 'bg-success' : 'bg-warning'}" 
                    draggable="false"
                    >
                      <span class="v-chip__underlay"></span>
                    <div class="v-chip__content">${$number_format(full.stock)} ${full.type_of_unit}</div>

                    </span> `
                  
                )
              }
            },
            {
              // Actions
              className: 'text-center',
              targets: 3,
              orderable: false,
              render: function (data, type, full, meta) {
                return (
                  `
                  <div class="d-block d-sm-none"><div aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-34"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="button" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default v-icon--clickable me-2 iconify iconify--mdi" aria-haspopup="menu" aria-expanded="false" aria-owns="v-menu-34" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path></svg></div><!--teleport start--><!--teleport end--></div>
                  <div class="d-none d-sm-flex"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--mdi" aria-describedby="v-tooltip-35" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0Z"></path></svg><!--teleport start--><!--teleport end--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--heroicons-outline" aria-describedby="v-tooltip-36" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586Z"></path></svg><!--teleport start--><!--teleport end--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--mdi" aria-describedby="v-tooltip-37" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M13 19.3v-6.7l6-3.4V13c.7 0 1.4.1 2 .4V7.5c0-.4-.2-.7-.5-.9l-7.9-4.4c-.2-.1-.4-.2-.6-.2s-.4.1-.6.2L3.5 6.6c-.3.2-.5.5-.5.9v9c0 .4.2.7.5.9l7.9 4.4c.2.1.4.2.6.2s.4-.1.6-.2l.9-.5c-.3-.6-.4-1.3-.5-2M12 4.2l6 3.3l-2 1.1l-5.9-3.4l1.9-1m-1 15.1l-6-3.4V9.2l6 3.4v6.7m1-8.5L6 7.5l2-1.2l6 3.5l-2 1m8 4.2v3h3v2h-3v3h-2v-3h-3v-2h3v-3h2Z"></path></svg><!--teleport start--><!--teleport end--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default me-2 iconify iconify--bx" aria-describedby="v-tooltip-38" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path fill="currentColor" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg><!--teleport start--><!--teleport end--></div>
                  `
                );
              }
            }

          ],
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
          order: [[0, 'desc']],
          dom:
            '<"row me-2"' +
            '<"col-md-2">' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"B>>' +
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
                  title: 'Reporte de movimientos',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
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
                  extend: 'excel',
                  text: '<i class="ti ti-file-text me-2" ></i>Excel',
                  className: 'dropdown-item',
                  title: 'Reporte de movimientos',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
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
                  extend: 'pdf',
                  text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                  className: 'dropdown-item',
                  title: 'Reporte de movimientos',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
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
              ]
            },
          ],
          // responsive: true,
          // rowReorder: {
          //     selector: 'td:nth-child(2)'
          // }
        });
        this.initOptionsTable()
      },
      initOptionsTable(){
          // document.querySelector('.dt-buttons').classList.add('justify-content-end')
          const TableElementContent = document.getElementById('table');
          // this.activeFilterInputs()
          TableElementContent .addEventListener('OptionsActionTable', () => this.ready = true )	
        },

    },
    data: () => ({
    products : [
      {id:1, title:'porro', stock:30, type_of_unit:'KG'},
      {id:1, title:'porro', stock:30, type_of_unit:'KG'},
      {id:1, title:'porro', stock:30, type_of_unit:'KG'}

      
    ],
    ready:false,
    table:''
    }),
    mounted(){
      // this.getProducts()
      setTimeout(() => {
        // this.ready = true
        this.getOperationsDataTable()
      }, 1000);
    }
  };
</script>