<template>
<div class="row">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inicio / </span> Acciones administrador</h4>

  <div class="row mb-4 ps-4" id="sortable-row">
    <div class="col-sm-6 col-xl-4">
      <div class="card bg-success text-white mb-3">
          <div class="card-body text-center">
              <i class="fa-solid fa-dollar-sign fa-xl mb-3"></i>
              <h4 class="card-title text-white cursor-move">Tipo de cambio</h4>
              <p class="action-text">Actualizar tipo de cambio compra y venta.</p>
              <button type="button" class="btn rounded-pill btn-label-secondary-200 text-primary" @click="showModal('exchangeRate',1)">
              Mostrar
              </button>
            </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-4">
      <div class="card bg-info text-white mb-3">
        <div class="card-body text-center">
          <i class="fa-solid fa-percent fa-xl mb-3"></i>
            <h4 class="card-title text-white cursor-move">Agregar Cupón</h4>
            <p class="action-text">Edita o crea un nuevo cupón de descuento.</p>
            <button type="button" class="btn rounded-pill btn-label-secondary-200 text-primary" @click="showModal('couponModal',5)">
              Mostrar
            </button>
          </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-4">
      <div class="card bg-warning text-white mb-3">
        <div class="card-body text-center">
          <i class="fa-solid fa-calculator fa-xl mb-3"></i>
            <h4 class="card-title text-white cursor-move">Operación Manual</h4>
            <p class="action-text">Crear operación manual para persona natural o empresa.</p>
            <button type="button" class="btn rounded-pill btn-label-secondary-200 text-primary" @click="showModal('ManualOperation',3)">
            Mostrar
            </button>
          </div>
      </div>
    </div>
    
    <div class="col-sm-6 col-xl-4">
      <div class="card bg-primary text-white mb-3">
        <div class="card-body text-center">
            <i class="fa-solid fa-user-pen fa-xl mb-3"></i>
            <h4 class="card-title text-white cursor-move">Editar cliente</h4>
            <p class="action-text">Editar datos de clientes y/o empresa</p>
            <button type="button" class="btn rounded-pill btn-label-secondary-200 text-primary" @click="showModal('shareProject',2)">
              Mostrar
            </button>
          </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-4">
      <div class="card bg-secondary text-white mb-3">
        <div class="card-body text-center">
            <i class="fa-solid fa-user-plus fa-xl  mb-3"></i>
            
            <h4 class="card-title text-white cursor-move">Agregar nuevo cliente</h4>
            <p class="action-text">Registro manual de clientes.</p>
            <button type="button" class="btn rounded-pill btn-label-secondary-200 text-primary" @click="showModal('createdUser',4)" >
              Mostrar
            </button>
          </div>
      </div>
    </div>

  </div>
  <exchange-rate v-if="selectModal==1" @click="hideModal"></exchange-rate>
  <edit-user v-if="selectModal==2" @click="hideModal"></edit-user>
  <operation-manual v-if="selectModal==3" @click="hideModal"></operation-manual>
  <created-user v-if="selectModal==4" @click="hideModal"></created-user>
  <coupon v-if="selectModal==5" @click="hideModal"></coupon>
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

  import ExchangeRate from "@/view/pages/actions/exchange-rate/ExchangeRate.vue";
  import EditUser from "@/view/pages/actions/user-actions/EditUser.vue";
  import Coupon from "@/view/pages/actions/coupon/Coupon.vue";
  import CreatedUser from "@/view/pages/actions/user-actions/CreatedUser.vue";
  import OperationManual from "@/view/pages/actions/manual-operation/OperationManual.vue";
  import { bootstrap } from "@/concept/bootstrap";
  import Sortable from 'sortablejs';
  export default {
      data() {
          return {
            mainAlert: false,
            mainAlertVariant: "",
            mainAlertMessage: "",
            savedAlert: false,
            savedAlertVariant: '',
            openModal:'',
            selectModal:0,
          }
      },
      components:{
        ExchangeRate: ExchangeRate,
        EditUser: EditUser,
        OperationManual: OperationManual,
        CreatedUser: CreatedUser,
        Coupon: Coupon,
      },
      mounted() {
        this.dragAction()
      },
      methods: {
       
        showMainAlert(variant, message) {
          this.mainAlertVariant = variant;
          this.mainAlertMessage = message;
          this.mainAlert = true;
          window.scrollTo(0,0);
        },

        showSavedAlert(variant, message) {
          this.savedAlertVariant = variant;
          this.savedAlertMessage = message;
          this.savedAlert = true;
        },
        showModal(id,numberOfModal) {
          this.selectModal = numberOfModal;
          setTimeout(() => {
            this.openModal = new bootstrap.Modal(document.getElementById(id), {
              keyboard: false,
              backdrop:'static'
            })
            this.openModal.show()
          }, 200);
        },
        hideModal(){
          this.selectModal = 0;
          setTimeout(() => {
            this.openModal.hide()
          }, 50);
          
        },
        dragAction() {
          let cardDnD = document.getElementById('sortable-row');
          if (typeof cardDnD !== undefined && cardDnD !== null) {
            Sortable.create(cardDnD, {
              animation: 500,
              handle: '.card'
            });
          }
        }
   
      },
      computed: {
          logo() {
              return this.current_logo == null
                  ? this.default_logo
                  : this.current_logo;
          },
      },
      created() {
      },
  };
</script>