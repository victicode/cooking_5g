<template>
  <!-- begin:: Header Topbar -->
  <div class="topbar">
    <!--begin: Account bar -->
    <div class="topbar-item">
      <div>
        <label class="btn btn-md btn-lg m-2 p-2" style="cursor: default;">Perfil Activo: {{accountLabel}}</label>
      </div>
    </div>
    <!--end: Account bar -->
    <!--begin: User Bar -->
    <KTQuickUser></KTQuickUser>
    <!--end: User Bar -->
  </div>
</template>

<script>
import KTQuickUser from "@/view/layout/extras/offcanvas/QuickUser.vue";
import ApiService from "@/core/services/api.service";
export default {
  name: "KTTopbar",
  components: {
    KTQuickUser
  },
  data() {
    return {
      accountLabel: '',
    }
  },
  mounted() {
    this.getAccounts();
  },
  methods: {
    getAccounts() {
      ApiService.setHeader();
      ApiService.get("api/user/get-label-active-account")
        .then(({data}) => {
          if (data.code == 200){
            this.accountLabel = data.data.accountLabel;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  }
};
</script>
