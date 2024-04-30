<script setup >
import moment from 'moment-timezone';
import 'moment/locale/es';
moment.defineLocale('es-mx', {
  relativeTime: {
    future: 'en %s',
    past: 'Hace %s',
    s: 'unos segundos',
    ss: '%d segundos',
    m: 'un minuto',
    mm: '%d minutos',
    h: 'una hora',
    hh: '%d horas',
    d: 'un día',
    dd: '%d días',
    w: 'una semana',
    ww: '%d semanas',
    M: 'un mes',
    MM: '%d meses',
    y: 'un año',
    yy: '%d años',
  },
});
</script>
<template>
  <div class="chat-list__content">
    <div v-if="loadChats">
      <div class="pt-8" v-if="chats.length > 0" >
        <ul>
          <li class="mt-3 pb-3 position-relative" v-for="(chat, index) in chats" :key="index" >
            <div class="timeLeter">
              {{ caculateTime(chat) }}
            </div>
            <div class="chat-description pa-2" :class="{'active': activeChat && chat.id === activeChat.id }">
              <div class="rounded-circle pa-3 d-flex justify-center align-center bg-primary me-2" @click="selectedChat(chat.id)" >
                <VIcon icon="mingcute:user-2-fill" size="large" />
              </div>
              <div class="w-60" @click="selectedChat(chat.id)">
                <div class="text-secondary user-chat__name text-capitalize">
                  {{ is_admin == "true" ? chat.sender.name : chat.receipet.name }}
                </div> 
                <div class="user-chat__type mt-0 hide-text mt-1">
                  {{chat.title}}
                </div>
                <div class="user-chat_last_message font-italic mt-0 hide-text mt-1 " :class="{'font-weight-black': ifReadForMe(chat.messages[0]) && chat.messages[0].read == 0 }" v-if="chat.messages.length > 0">
                 {{  !ifReadForMe(chat.messages[0]) ? 'Tu:' : `${chat.messages[0].sender.name}:`}} {{chat.messages[0].message}}
                </div>
              </div>
              <div class="d-flex align-center justify-center ms-2">

                <div class="unReadMessage-acitve bg-error ms-2 " v-if="getUnreadMessage(chat) > 0" >
                  {{ getUnreadMessage(chat) }}
                </div>
                <div class="mt-3" v-if="is_admin=='true'" >
                  <v-btn size="small" icon="carbon:overflow-menu-vertical" variant="text" @click="selectedChat(chat.id, true)" />
                </div>
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
    <v-navigation-drawer
          v-model="drawer"
          location="bottom"
          temporary
          class="drawer-bottom"
          :class="`${drawer ? 'd-block' : 'd-none'}` "
          style=""
          v-if="activeChat"
        >
        <div class="text-center d-flex flex-column  h-100" style="position: relative;">
          <div :class="`drawer__close-button ${drawer ? 'active' : ''}`" >
            <v-col  class="pa-0 pe-4">
              <v-btn icon="mingcute:close-fill" class="bg-secondary shadow-button"   @click="drawer = false" ></v-btn>
            </v-col>
          </div>
          <h3 class="mt-4 mb-0">{{ activeChat.title }}</h3>
          <h4 class="mt-1">Por: {{ activeChat.sender.name }}</h4>
          <div class="d-flex  justify-center mx-5  align-center h-50 mt-4 " style="box-sizing:content-box;">
            <div class="d-flex flex-column justify-center align-center" >
              <v-btn size="large" class="d-block mx-8 shadow-button" color="primary" @click="showModal('viewRecipe')" icon="solar:inbox-archive-linear" />
              <h5 class="mt-1" >Cerrar Ticket</h5>
            </div>
            <div class="d-flex flex-column justify-center align-center"  v-if="activeChat.reference_id">
              <v-btn size="large" class="d-block mx-8 shadow-button" color="primary" @click="showModal('viewRecipe')" icon="solar:box-outline" />
              <h5 class="mt-1" >Ver Producto</h5>
            </div>
            <div class="d-flex flex-column justify-center align-center" >
              <v-btn size="large" class="d-block mx-8 shadow-button" color="error" @click="showModal('deleteRecipe')" icon="mi:delete" />
              <h5 class="mt-1" >Eliminar</h5>
            </div>
          </div>
        </div>
    </v-navigation-drawer>  
  </div>
</template>
<style lang="scss"  scoped>
.timeLeter{
  font-size: 0.9rem;
  text-align: end;
  position: absolute;
  right:5%;
  top: 1%;
}
.w-60{
  width: 60%!important;
}
.unReadMessage-acitve{
  width: 25px;
  height: 25px;
  border-radius: 5px;
  padding: 5px;
  font-size: 0.7rem;
  display: flex;
  justify-content: center;
  align-items: center;
}
.chat-list__content{
  width: 100%;
  height: fit-content;
  max-height: 88%;
  max-width: 100%;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  padding: 0px 10px;
  overflow-x: hidden;
  overflow-y: auto;
}
ul{
  list-style: none;
  overflow: hidden;
  & li > h3{
    color:#44474d!important;
    
  }
}
.chat-description{
  display: flex;
  align-items: center;
  transition: all 0.3s ease-in-out;
  border-radius: 10px;
  padding: 5px 0px;
  
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
  font-size: 0.9rem;
  font-weight: 600;
}
.user-chat_last_message{
  font-size: 0.9rem;
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
import notificationTone4 from "@/assets/audio/notification10.mp3";

export default {
  name:'UserChatListComponent',
  data: ()=>{
    return{
      chats:{},
      sound: new Audio(notificationTone4),
      loadChats: false,
      is_admin: window.localStorage.is_admin ,
      currentUserId: window.localStorage.user_unique_id,
      drawer:false,
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
    selectedChat(id, isDrawer = false){
      this.$store.dispatch(GET_CHAT_BY_ID, id).then((data)=>{
        this.$store.state.activeChatID = data.data;
        if (isDrawer){
          setTimeout(()=>{

            this.drawer = true
          },200)
          return
        }
        this.readChat(data.data)
        this.emitter.emit('displayLastMessagge')
        this.emitter.emit('mobileFunction')
        this.emitter.emit('getMessages', data.data.messages)  
        this.emitter.emit('updateUnread')  
      })
    },
    readChat(chat){
      for(let i = 0; i < this.chats.length; i++){
        if(this.chats[i].id == chat.id){
          this.chats[i] = chat;
        }
      }
      this.getUnreadMessages(this.chats)
    },
    getUnreadMessage($chat){
      const unReadMessage = $chat.messages_unread.filter((unReadMessage) => unReadMessage.sender_id != parseInt(window.localStorage.user_unique_id)).length;
      return unReadMessage
    },
    getUnreadMessages($chats){
      let unRead = 0
      $chats.forEach(chat => {
        unRead += chat.messages_unread.filter((unReadMessage) => unReadMessage.sender_id != parseInt(window.localStorage.user_unique_id)).length;
      });
      this.$store.state.unReadMessages = unRead
    },
    updateChat(){
      this.getUnreadMessages(this.chats)
      if(this.activeChat){
        // this.$store.state.chatMessages = this.chats.find((chat) => chat.id === this.activeChat.id).messages
        this.emitter.emit('displayLastMessagge')
        this.emitter.emit('getMessages', this.chats.find((chat) => chat.id === this.activeChat.id).messages)  
        return
      };
    },
    ifReadForMe(message){
      return !(message.sender_id == this.currentUserId) 
    },
    caculateTime(chat){
      let today = new moment();
      let chatCreateAt = moment(chat.created_at);
      let diff = Math.round(moment.duration(chatCreateAt.diff(today)).as('days'));

      if(chat.messages.length > 0 ){
        chatCreateAt = moment(chat.messages[0].created_at);
        diff = Math.round(moment.duration(chatCreateAt.diff(today)).as('days'));
      }
      if((diff*-1) > 0) return chatCreateAt.fromNow()
      return chatCreateAt.format('h:mm A')
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