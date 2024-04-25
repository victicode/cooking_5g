<template>

  <VCard class="pa-0 ma-0">
    <VRow class="chat-areacomponent ma-0 pa-0">
      <VCol id="chat-list" cols="12"  md="4" class="pa-0">
        <userList />
      </VCol>
      <VCol id="message-area" class="d-none d-md-flex pa-0" cols="12" md="8">
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
  created(){
    this.emitter.emit('displayOverlayLoad', false)
  },
}
</script>
<style lang="scss" >

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
</style>