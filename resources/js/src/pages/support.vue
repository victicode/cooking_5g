<template>
  <div class="chat-areacomponent">
    <section id="chat-list">
      <userList />
    </section>
    <section id="message-area" >

    </section>
  </div>
</template>
<script>
  import userList from '@/layouts/components/UserChatList.vue'
export default {
  components: {
    userList,
  },
  created(){
    this.emitter.emit('displayOverlayLoad', false)
  },
}
</script>
<style lang="scss" >

.chat-areacomponent{
  background: #181818;
  width: 100%;
  height: 85vh;
  display: flex;
  border-radius: 10px;
}
#chat-list{
  width: 30%;
}
#message-area {
  width: 70%;
}
</style>