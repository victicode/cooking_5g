<template>
  <div class="chat-list__content">
    <div v-if="loadChats">
      <div class="pt-8" v-if="chats.length > 0" >
        <ul>
          <li class="mt-3 pb-3" v-for="(chat, index) in chats" :key="index" @click="selectedChat(chat.id)">
            <div class="chat-description pa-2" :class="{'active': activeChat && chat.id === activeChat.id , 'justify-start': chat.messages_unread_count == 0}">
              <div class="rounded-circle pa-3 d-flex justify-center align-center bg-primary " :class="{'me-2': chat.messages_unread_count == 0}">
                <VIcon icon="mingcute:user-2-fill" size="large" />
              </div>
              <div>
                <div class="text-secondary user-chat__name text-capitalize">
                  {{ is_admin == "true" ? chat.sender.name : chat.receipet.name }}
                </div> 
                <div class="user-chat__type">
                  {{chat.title}}
                </div>
              </div>
              <div class="unReadMessage-acitve bg-error " v-if="chat.messages_unread_count > 0">
                {{ chat.messages_unread_count }}
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div v-else class="text-center pa-5">
        <h3>
          No tienes ningún ticket/chat aperturado.
        </h3>
      </div>
    </div>
    <div v-else class="pt-8">
      <v-skeleton-loader type="avatar, paragraph" class="w-100"></v-skeleton-loader>
      <v-skeleton-loader type="avatar, paragraph" class="w-100"></v-skeleton-loader>
      <v-skeleton-loader type="avatar, paragraph" class="w-100"></v-skeleton-loader>
    </div>
  </div>
</template>
<style lang="scss"  scoped>
.unReadMessage-acitve{
  width: 22px;
  height: 22px;
  top: 5px;
  right: 40%;
  border-radius: 3px;
  padding: 5px;
  font-size: 0.7rem;
  display: flex;
  justify-content: center;
  align-items: center;
}
.chat-list__content{
  width: 100%;
  height: 100%;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  padding: 0px 10px;
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
  padding: 5px 0px;
  justify-content: space-between;
  
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
  .active{
    background: transparent!important;
  }
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
      loadChats: false,
      is_admin: window.localStorage.is_admin 
    }
  },
  methods: {
    getAllChat(){
      this.$store.dispatch(GET_CHAT).then((data)=>{
        this.chats = data.data;
        // console.log(this.chats)
        this.loadChats = true;
        this.updateChat()
      }).catch((error) => {
        console.log(error)
      })
    },
    selectedChat(id){
      this.$store.dispatch(GET_CHAT_BY_ID, id).then((data)=>{
        this.$store.state.activeChatID = data.data;
        this.readChat(data.data)

        this.emitter.emit('displayLastMessagge')
        this.emitter.emit('mobileFunction')
        this.emitter.emit('getMessages', data.data.messages)  
      })
    },
    readChat(chat){
      for(let i = 0; i < this.chats.length; i++){
            if(this.chats[i].id == chat.id){
                this.chats[i] = chat;
            }
        }
    },
    updateChat(){
      if(this.activeChat){
        // this.$store.state.chatMessages = this.chats.find((chat) => chat.id === this.activeChat.id).messages
        this.emitter.emit('displayLastMessagge')
        this.emitter.emit('getMessages', this.chats.find((chat) => chat.id === this.activeChat.id).messages)  
        return
      };
    }
  }, 
  mounted(){
    this.getAllChat();
    window.Echo.channel('chatMessages.'+ window.localStorage.user_unique_id)
    .listen('RealTimeChatMessage',(e)=>{
      this.getAllChat();
      this.sound.play()
    })
  },

  computed: {
    activeChat() {
      return this.$store.state.activeChatID
    }
  },
}
</script>