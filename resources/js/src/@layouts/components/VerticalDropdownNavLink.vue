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
    class="nav-link link-father" 
    :class="{ 'on': dropdow }"
    @click="dropdow = !dropdow"
  >

    <a class=""  >
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i" class="v-icon notranslate v-theme--light v-icon--size-default nav-item-icon iconify iconify--mdi" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 5a3.5 3.5 0 0 0-3.5 3.5A3.5 3.5 0 0 0 12 12a3.5 3.5 0 0 0 3.5-3.5A3.5 3.5 0 0 0 12 5m0 2a1.5 1.5 0 0 1 1.5 1.5A1.5 1.5 0 0 1 12 10a1.5 1.5 0 0 1-1.5-1.5A1.5 1.5 0 0 1 12 7M5.5 8A2.5 2.5 0 0 0 3 10.5c0 .94.53 1.75 1.29 2.18c.36.2.77.32 1.21.32c.44 0 .85-.12 1.21-.32c.37-.21.68-.51.91-.87A5.42 5.42 0 0 1 6.5 8.5v-.28c-.3-.14-.64-.22-1-.22m13 0c-.36 0-.7.08-1 .22v.28c0 1.2-.39 2.36-1.12 3.31c.12.19.25.34.4.49a2.482 2.482 0 0 0 1.72.7c.44 0 .85-.12 1.21-.32c.76-.43 1.29-1.24 1.29-2.18A2.5 2.5 0 0 0 18.5 8M12 14c-2.34 0-7 1.17-7 3.5V19h14v-1.5c0-2.33-4.66-3.5-7-3.5m-7.29.55C2.78 14.78 0 15.76 0 17.5V19h3v-1.93c0-1.01.69-1.85 1.71-2.52m14.58 0c1.02.67 1.71 1.51 1.71 2.52V19h3v-1.5c0-1.74-2.78-2.72-4.71-2.95M12 16c1.53 0 3.24.5 4.23 1H7.77c.99-.5 2.7-1 4.23-1Z"></path></svg>
      <!-- 👉 Title -->
      <span class="nav-item-title" > {{item.title}} </span>
      <VIcon :icon=" dropdow ? 'bx-chevron-down' : 'bx-chevron-right'"  />  
    </a>
    <ul class="ms-4">
      <template v-for=" (drowpDownItem,index) in item.items" :key="index">
        <li
          class="nav-link link-son "
          :class="{ disabled: drowpDownItem.disable }" 
          @click="isSonActive()"
          
        >
          <Component
            :is="drowpDownItem.to ? 'RouterLink' : 'a'"
            :to="drowpDownItem.to"
            :href="drowpDownItem.href"
          >
            <VIcon
              :icon="drowpDownItem.icon"
              class="nav-item-icon"
            />
            <!-- 👉 Title -->
            <span class="nav-item-title">
              {{ drowpDownItem.title }}
            </span>
          </Component>
        </li>
      </template>
    </ul>

  </li>
</template>

<style lang="scss" scoped>
.layout-vertical-nav {
  .nav-link a {
    display: flex;
    align-items: center;
    cursor: pointer;
  }
  .link-father{
    max-height: 44px;
    height: 100%;
    overflow: hidden;
    transition: all .4s ease-in;
    &.on{
      max-height: 140px;
    }
  }
}
</style>
<script>
import { onBeforeRouteUpdate } from 'vue-router';
export default {
  data() {
    return {
      dropdow: false
    };
  },
  methods:{
    isSonActive(){
      setTimeout(() => {
        
        let elements = document.querySelectorAll('.link-son');
        let isActive = []
        elements.forEach(element => {
          if(element.querySelector('.router-link-active.router-link-exact-active')) isActive.push(element.querySelector('.router-link-active.router-link-exact-active'))
        });
  
        if(isActive.length > 0) this.dropdow = true
      }, 100);

    },
  },
  mounted(){
    this.isSonActive()
  },


};
</script>