<script setup>
  import DemoSimpleTableBasics from '@/views/pages/tables/DemoSimpleTableBasics.vue'
</script>

<template>
  <VRow>
  <VCol cols="12">
    <VCard title="Theme" class="pa-3">
      <VCardText>
        use <code>theme</code> prop to switch table to the dark theme.
      </VCardText>
      <DemoSimpleTableBasics />
    </VCard>
  </VCol>
</VRow>
</template>