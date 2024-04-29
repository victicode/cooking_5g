<script setup>
const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})
</script>

<template>
  <li
    class="nav-link link-father position-relative"
    :class="{ disabled: item.disable }"
  >
    <Component
      :is="item.to ? 'RouterLink' : 'a'"
      :to="item.to"
      :href="item.href"
    >
      <VIcon
        :icon="item.icon"
        class="nav-item-icon"
      />
      <!-- 👉 Title -->
      <span class="nav-item-title">
        {{ item.title }}
      </span>
    </Component>
    <div v-if="item.title == 'Soporte' && $store.state.unReadMessages > 0" class="unReadMessage-acitve bg-error animate__pulse " >
      {{$store.state.unReadMessages}}
    </div>

  </li>
</template>

<style lang="scss" scoped>
.layout-vertical-nav {
  .nav-link a {
    display: flex;
    align-items: center;
    cursor: pointer;
  }
}
.unReadMessage-acitve{
  position: absolute;
  width: 22px;
  height: 22px;
  top: 5px;
  right: 40%;
  border-radius: 3px;
  padding: 5px;
  top: calc(50% - 11px);
  font-size: 0.7rem;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
