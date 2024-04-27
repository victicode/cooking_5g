<template>

  <VCard class="pa-0 ma-0">
    {{ activeChat ? '' : ''}}
    <VRow class="chat-areacomponent ma-0 pa-0 d-none d-md-flex">
      <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__fadeInRight" 
          leave-active-class="animate__animated animate__fadeOutLeft"
        >
        <VCol id="chat-list" cols="12"  md="4" class="pa-0 d-md-flex " >
          <userList />
        </VCol>
      </transition>
      <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__fadeInRight" 
          leave-active-class="animate__animated animate__fadeOutLeft"
        >
        <VCol id="message-area" class="d-md-flex pa-0"  cols="12" md="8">
          <messageChat />
        </VCol>
      </transition>
    </VRow>
    <VRow class="chat-areacomponent ma-0 pa-0 d-flex d-md-none">
      <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__fadeInRight" 
          leave-active-class="animate__animated animate__fadeOutLeft"
        >
        <VCol id="chat-list" cols="12"  md="4" class="pa-0 d-md-flex " v-if="!deplay">
          <userList />
        </VCol>
      </transition>
      <transition 
          mode="out-in" 
          enter-active-class="animate__animated animate__fadeInRight" 
          leave-active-class="animate__animated animate__fadeOutLeft"
        >
        <VCol id="message-area" class="d-md-flex pa-0" v-if="!deplay == false" cols="12" md="8">
          <messageChat />
        </VCol>
      </transition>
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
      deplay: false
    }
  },
  created(){
    this.emitter.emit('displayOverlayLoad', false)
    this.activeChat;
    this.emitter.on("displayUserChatList", (status) => {
      this.deplay  = status
    })
  },
  computed: {
    activeChat() {
      this.deplay = this.$store.state.activeChatID ? true : false
      return this.$store.state.activeChatID
    }
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
#chat-list{
  height:100%;
}
#message-area {
  height:100%;
}
@media screen and (max-width: 780px){
  .chat-areacomponent{
    height: 76vh;
    overflow: hidden;
  }
}
</style>