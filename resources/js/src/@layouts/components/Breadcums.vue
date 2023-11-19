<script setup>

</script>

<template>

 <div class="mb-4 mx-2 mb-md-0 ">
  <h3>
    APP / {{this.$router.currentRoute.value.meta.title}}
  </h3>     
</div>
</template>

<style lang="scss">
.layout-vertical-nav {
  .nav-link a {
    display: flex;
    align-items: center;
    cursor: pointer;
  }
}
</style>
