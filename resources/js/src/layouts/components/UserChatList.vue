<template>
  <div class="chat-list__content">
    <div class="pt-8" v-if="chats.length > 0" >
      <ul>
        <li class="mt-3 pb-3" v-for="(chat, index) in chats" :key="index" @click="selectedChat(chat.id)">
          <div class="chat-description" :class="{'active': activeChat && chat.id === activeChat.id}">
            <div class="rounded-circle pa-3 d-flex justify-center align-center bg-primary me-2">
              <VIcon icon="mingcute:user-2-fill" size="large" />
            </div>
            <div>
              <div class="text-secondary user-chat__name">
                {{ chat.sender.name }}
              </div> 
              <div class="user-chat__type">
                {{chat.title}}
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div v-else class="pt-8">
      <v-skeleton-loader type="avatar, paragraph" class="w-100"></v-skeleton-loader>
      <v-skeleton-loader type="avatar, paragraph" class="w-100"></v-skeleton-loader>
      <v-skeleton-loader type="avatar, paragraph" class="w-100"></v-skeleton-loader>

    </div>
  </div>
</template>
<style lang="scss"  scoped>
.chat-list__content{
  width: 100%;
  height: 100%;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  padding: 0px 20px;
}
ul{
  list-style: none;
  & li > h3{
    color:#44474d!important
  }
}
.chat-description{
  display: flex;
  align-items: center;
  transition: all 0.3s ease-in-out;
  border-radius: 10px;
  padding: 5px 10px;
  
  &:hover{
    background: #cf622350;
  }
  & img{
    margin-right: 15px;
    border-radius: 50%;
  }
}
.active{
  background: #cf622350!important;
}
.user-chat__name{
  font-size: 1.1rem;
}
.user-chat__type{
  font-size: 1rem;
  font-weight: 600;
}

@media screen and (max-width: 780px){
  .chat-list__content{
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>
<script>
import { GET_CHAT, GET_CHAT_BY_ID } from '@/core/services/store/chat.module';
import notificationTone4 from "@/assets/audio/notification2.mp3";

export default {
  name:'UserChatListComponent',
  data: ()=>{
    return{
      chats:{},
      sound: new Audio(notificationTone4),
    }
  },
  methods: {
    getAllChat(){
      this.$store.dispatch(GET_CHAT).then((data)=>{
        this.chats = data.data;
        this.updateChat()
      }).catch((error) => {
        console.log(error)
      })
    },
    selectedChat(id){
      this.$store.dispatch(GET_CHAT_BY_ID, id).then((data)=>{
        this.emitter.emit('displayLastMessagge')
        this.emitter.emit('mobileFunction')

        this.$store.state.activeChatID = data.data
        this.$store.state.chatMessages = data.data.messages
      })
    },
    updateChat(){
      if(this.activeChat){
        this.$store.state.chatMessages = this.chats.find((chat) => chat.id === this.activeChat.id).messages
        this.emitter.emit('displayLastMessagge')
        return
      };
    }
  }, 
  mounted(){
    this.getAllChat();
    window.Echo.channel('chatMessages')
    .listen('RealTimeChatMessage',(e)=>{
      this.getAllChat();
      // this.sound.play()
    })
  },
  computed: {
    activeChat() {
      return this.$store.state.activeChatID
    }
  },
}
</script>