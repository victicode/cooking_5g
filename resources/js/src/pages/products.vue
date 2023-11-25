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
        <VRow class="ma-0  justify-center justify-md-end pa-2 mb-5">
          <VBtn color="primary"><VIcon icon="bx-plus"/> Agregar nuevo producto</VBtn>
        </VRow>
        <!-- <DemoSimpleTableBasics v-if="ready" :products="products" /> -->
        <table class="display nowrap px-1" id="table">
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
    padding: 15px ;
  }
</style>
<script>
  export default {
    methods:{
      getOperationsDataTable(){

        this.tabled = new DataTable('#table', {
          ajax: {
            // url: 'https://cambio.dlsmoney.com/api/operations-data-table',
            url: 'http://10.10.10.69:8085/api/xs',
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
                  ----
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
    products : {},
    ready:false,
    table:''
    }),
    mounted(){
      // this.getProducts()
      setTimeout(() => {
        
        this.getOperationsDataTable()
      }, 1000);
    }
  };
</script>