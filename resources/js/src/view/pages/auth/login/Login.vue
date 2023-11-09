<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Sign In</h5>
            <form>
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Correo electronico o nombre de usuario</label>
              </div>
              <div class="form-floating mb-3 input-goup d-flex align-items-center">
                <input type="password" class="form-control w-75 border-end-0 input-with-addons" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Contraseña</label>
                <div class="w-25 addons-input form-control">
                  <i class="fa fa-eye fa-md"></i>
                </div>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                <label class="form-check-label" for="rememberPasswordCheck">
                  Recordar Contraseña
                </label>
              </div>
              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Ingresar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Load login custom page styles -->
<style lang="scss">
  @import "@/assets/sass/login.scss";
 
</style>

<script>
import formValidation from "@/assets/plugins/formvalidation/dist/es6/core/Core";
// import 'bootstrap/scss/bootstrap'
// FormValidation plugins
import Trigger from "@/assets/plugins/formvalidation/dist/es6/plugins/Trigger";
import Bootstrap from "@/assets/plugins/formvalidation/dist/es6/plugins/Bootstrap";
import SubmitButton from "@/assets/plugins/formvalidation/dist/es6/plugins/SubmitButton";


export default {
  data() {
    return {
      state: "signin",
    };
  },
  computed: {
  },
  mounted() {},
  methods: {
  }
};
</script>
