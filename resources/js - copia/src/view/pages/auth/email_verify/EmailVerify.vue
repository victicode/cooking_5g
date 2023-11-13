<template>
    <div  class="authentication-wrapper authentication-cover authentication-bg vh-100 pt-4 ps-2"  :style="{ backgroundImage: `url(${backgroundImage.background})` }">
      <div id="kt_login" class="login login-1  authentication-inner text-center ">
        <div class="w-100  d-flex justify-content-center p-4 align-items-center"  >
          <div class="spinner-border me-2 text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <h1 class=" text-center m-0 text-primary">Validando tu correo...</h1>
        </div>
        <h2 class=" text-center m-0 text-primary" >Este proceso puede tardar unos pocos minutos, al finalizar seras redirigido a tu dashboard.</h2>
      </div>
      <div class="modal fade" id="redirect-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc row">
          <div class="modal-content p-3 p-md-5">
            <div class="modal-body" id="declaracionj">
              <div class="text-center mb-4 d-flex flex-column align-items-center ">
                <div class="circle-icon" :class="{'circle-ok': this.state == 'ok', 'circle-error': this.state == 'error', }">
    
                  <i class="fa-solid  fa-xl" :class="{'fa-check': this.state == 'ok', 'fa-xmark': this.state == 'error', }"></i>
                </div>
                
                <h3 class="mt-3">{{ modalMsg }}</h3>
              </div>
              <div class="col-12 d-flex justify-content-center mt-3 ">
                <button class="btn btn-primary btn-next align-items-baseline" onsubmit="false"  @click="toRedirect(uriRedirect)">
                  
                  <span class="align-middle d-sm-inline-block d-none">Aceptar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  import { VALIDATE_EMAIL } from "@/core/services/store/auth.module";
  import { bootstrap } from "@/concept/bootstrap";
  export default {
    data() {
      return {
        openModal:'',
        modalMsg:'',
        uriRedirect:'',
        state:''
      };
    },
    computed: {
      backgroundImage() {
        return (
          {
            leftSide: process.env.BASE_URL + "media/illustrations/registrong.png",
            background: process.env.BASE_URL + "media/backgrounds/bg-blanco.jpg",
          }
        );
      }
    },

    mounted(){
      this.userEmailValidate()
    },
    methods: {
      userEmailValidate(){
        // clear existing errors
        const expire = this.$route.query.expires
        const code   = this.$route.query.signature;
        const user   = this.$route.params.id;
        this.$store
          .dispatch(VALIDATE_EMAIL, {code: code, expire: expire, user: user} )
          .then((data) => {
            this.modalMsg = data.message;
            this.state = data.status;
            this.uriRedirect = '/login'; 
            console.log(this.state)
            setTimeout(() => {
              this.showModal(); 
            }, 1000);
          });
      },
      showModal() {
        this.openModal = new bootstrap.Modal(document.getElementById('redirect-modal'), {
          keyboard: false,
          backdrop:'static'
        })
        this.openModal.show()
      },
      toRedirect(uri){
        this.openModal.hide();
        this.$router.push({ name: "login" })
      }
    }
  };
  </script>
  