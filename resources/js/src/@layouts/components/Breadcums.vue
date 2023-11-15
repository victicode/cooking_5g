<script setup>

</script>

<template>

 <div class="my-4">
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
