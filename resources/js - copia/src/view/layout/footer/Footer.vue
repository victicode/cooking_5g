<template>
  <footer class="content-footer footer bg-footer-theme" :style="{ backgroundImage: `url('${img.backgroundImage}')` }">
    <div class="container-xxl">
      <div
        class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column"
      >
        <div>
          ©

            {{ new Date().getFullYear()}}
           Desarrollado 💙 por <a href="#" target="_blank" class="fw-semibold">Bluecomunicadores.com</a>
        </div>
        <div>
        

          <a
            href="#"
            target="_blank"
            class="footer-link me-4"
            >Términos y condiciones</a
          >

          <a href="#" target="_blank" class="footer-link d-none d-sm-inline-block"
            >FAQ</a
          >
        </div>
      </div>
    </div>
  </footer>
  <!-- begin:: Footer -->

  <!-- end:: Footer -->
</template>

<script>
import { mapGetters } from "vuex";
import background from "@/assets/media/backgrounds/bg-blanco.jpg"
export default {
  name: "KTFooter",

  computed: {
    ...mapGetters(["layoutConfig"]),
    img(){
      return({
        backgroundImage : background
      })
    },
    /**
     * Check if footer container is fluid
     */
    widthFluid() {
      return this.layoutConfig("footer.width") === "fluid";
    }
  }
};
</script>
