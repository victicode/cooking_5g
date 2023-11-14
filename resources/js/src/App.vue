<script setup>
import { useTheme } from 'vuetify'
import { hexToRgb } from './@layouts/utils'

const { global } = useTheme()
</script>

<template>
  <VApp style="background: transparent;" :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
    <router-view v-slot="{Component}">
       <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__zoomIn" 
          leave-active-class="animate__animated animate__zoomOut"
        >
          <component :is="Component"/>
       </transition>
    </router-view>
  </VApp>
</template>
<style>
.animate__animated {/* referring directly to the animation's @keyframe declaration */
  animation-duration: 0.2s; /* don't forget to set a duration! */
}
</style>