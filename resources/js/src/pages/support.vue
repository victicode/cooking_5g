<template>

  <VCard class="pa-0 ma-0">
    <VRow class="chat-areacomponent ma-0 pa-0 d-none d-md-flex" v-if="displayWidth > 780">
      <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__fadeInRight" 
          leave-active-class="animate__animated animate__fadeOutLeft"
        >
        <VCol cols="12"  md="4" class="pa-0 d-md-flex chat-list " >
          <userList />
        </VCol>
      </transition>
      <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__fadeInRight" 
          leave-active-class="animate__animated animate__fadeOutLeft"
        >
        <VCol class="d-md-flex pa-0 message-area"  cols="12" md="8">
          <messageChat />
        </VCol>
      </transition>
    </VRow>
    <VRow class="chat-areacomponent ma-0 pa-0 d-flex d-md-none" v-else>
      <VCol cols="12"  md="4" class="pa-0 d-md-flex chat-list " :class="{'borrao': deplay }">
        <userList />
      </VCol>

      <VCol class="d-md-flex pa-0 message-area d-flex"  cols="12" md="8" :class="{'activao': !deplay == false }">
        <messageChat />
      </VCol>
    </VRow>
  </VCard>
</template>
<script>
  import userList from '@/layouts/components/UserChatList.vue'
  import messageChat from '@/layouts/components/MessageChatArea.vue'

export default {
  components: {
    userList,
    messageChat,
  },
  data(){
    return{
      displayWidth: window.screen.width,
      deplay: false
    }
  },
  created(){
    this.emitter.emit('displayOverlayLoad', false)

    this.emitter.on("displayUserChatList", (status) => {
      this.deplay  = status
    })
    this.emitter.on("mobileFunction", (status) => {
      this.deplay  = true
    })
  },
}
</script>
<style lang="scss" >
 .animate__animated{
  animation-duration: 0.5s;
  }
.chat-areacomponent{
  background:  #ffffff25;
  width: 100%;
  height: 85vh;
  display: flex;
  border-radius: 10px;
}
.chat-list{
  height:100%;
  right: 0px;
  position: relative;
  transition: opacity ease-in 0.4s;
  opacity: 1;
  &.borrao{
    position: absolute;
    opacity: 0;
  }
}
.message-area {
  height:100%;
}
@media screen and (max-width: 780px){
  .chat-areacomponent{
    height: 76vh;
    overflow: hidden;
  }
  .message-area {
  height:100%;
  position: absolute;
  left: 500px;
  transform: scale(0.8);
  transition: all ease-in 0.4s;

  &.activao{
    position: relative;
    left: 0px;
    transform: scale(1);
  }
}
}
</style>