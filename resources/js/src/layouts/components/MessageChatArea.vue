<script setup >
import { SEND_NEW_MESSAGE } from '@/core/services/store/chat.module';
import moment from 'moment-timezone';
import 'moment/locale/es';
moment.updateLocale('es-mx', {
  relativeTime: {
    future: 'en %s',
    past: 'hace %s',
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
  }
});
</script>
<template>
  <div class="chat-message__content  " :class="{'nochat': !activeChat}">
    <div class="h-100 w-100 d-flex justify-center align-center text-center no-chat-active position-relative" v-if="!activeChat">
      <h2>COOKING 5G <br> Soporte</h2>
      <div class="h-100 w-100 d-flex justify-center align-center sup-overlay">

      </div>
    </div>
    <div class="h-100 w-100  position-relative d-flex flex-column justify-space-between pb-4" v-if="messagesChat" >
      <div class="messageArea__header d-flex align-center justify-space-between pe-5">
        <div class=" d-flex align-center">
          <div class="rounded-circle pa-3  justify-center align-center bg-white me-2 messageArea__header--icon d-none d-md-flex">
            <VIcon icon="mingcute:user-2-fill" size="large" color="primary" />
          </div>
          <div class=" pa-0  justify-center align-center  me-4 messageArea__header--icon d-flex d-md-none" @click="backMessagesList()">
            <VIcon icon="fluent-mdl2:skype-arrow"  color="white" />
          </div>
          <div>
            <div class="user-chat__name text-white ">
              {{ activeChat.sender.name }}
            </div> 
            <div class="user-chat__type text-white">
              {{activeChat.title}}
            </div>
          </div>
        </div>
        <div class="text-center">
          <h5 class="text-white user-chat__type">Estado:</h5>
          <v-chip color="success" variant="elevated">
           <span class="user-chat__type"> 
            ABIERTO
           </span>
          </v-chip>
        </div>
      </div>
      <div class="px-3 messagesArea" id="messagesArea" >
        <div class="d-flex message__text-content flex-column mb-1 " v-for="(messages, index) in messagesChat" :id="'message_index_'+index" :key="index" :style="'order:'+(messagesChat.length - index)">
          <div class="lx mt-2" :class="{'visit': messages.sender_id == activeUser.user.id }">
            <div class=" local-message elevation-24" :class="{'visited-message': messages.sender_id == activeUser.user.id }">
              {{ messages.message }}
            </div>
          </div>
          <div :class="{'text-end': messages.sender_id == activeUser.user.id}">
            <h6 class="ms-2" :class="{'me-2': messages.sender_id == activeUser.user.id}">
              {{ moment(messages.created_at).format('h:mm A')  }}
            </h6>
          </div>
        </div>
      </div>
      <div class="d-flex align-center newMessage__area w-100 py-0 px-md-5 px-2">
        <textarea @keyup="typingShow()" class="elevation-24" name="" id="" placeholder="Escribe un mensaje" v-model="newMessage" cols="1" rows="1"></textarea>
        <v-btn icon="iconoir:send" variant="tonal" color="primary" class="ms-2" @click="sendNewMessage()"/>
      </div>
    </div>
    <v-snackbar
      v-model="snackShow"
      :color="snackType"
      rounded="pill"
      :timeout="snacktimeOut"
      width="max-content"
      class="text-center"
    >
     <h4 class="text-white w-100 text-center">

       {{snackMessage}}
     </h4>
        <template
          v-slot:actions
        >
        <VBtn  color="white" class="text-white" @click="snackShow=false"> Cerrar</VBtn>
        </template>
    </v-snackbar>
  </div>
</template>
<style lang="scss"  scoped>
.newMessage_text{
  background: rgba(71, 57, 38, 0.363)!important;
  border-radius: 7px;
}
.messagesArea{
  height: 80%;
  overflow-x: hidden;
  overflow-y: scroll;
  display: inline-flex;
  flex-direction: column;
}
textarea{
  width: 100%;
  height: max-content;
  background: rgb(207, 207, 207);
  border-radius: 20px;
  overflow: visible;
  outline: none;
  resize: none;
  padding: 13px;
  &::placeholder{
    color:#8592A3!important
  }
}
.messageArea__header{
  height: max-content;
  background: rgb(226, 101, 17);
  width: 100%;
  padding: 10px;
  &--icon{
    width: 50px;
    height: 50px;
  }
}
.newMessage__area{
  height: 40px;
  background: transparent;
}
.chat-message__content{
  width: 100%;
  height: 100%;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
  background: rgba(180, 149, 107, 0.199)!important;
  transition: all 0.2s ease-in;
  border: 0.5px solid rgb(226, 101, 17);
}
.nochat{
  background: rgba(238, 168, 76, 0.363)!important;
}
.no-chat-active{
  opacity: 0.2
}
.message__text-content{
  justify-content: start;
  width: 100%;
}
.message__text-content:has(> .visit){
  align-items: end;
  width: 100%;

}
.lx{
  max-width: fit-content;
  background: #696969;
  border-radius: 15px;
}
.visit{
  background: #cf6123;
  
}
.local-message{
  
  border-radius: 15px;
  width: fit-content;
  padding: 10px;
  color: white;
  max-width: 100%;
}
.visited-message{
  border-radius: 15px;
  width: fit-content;
  padding: 10px;
  color: white;
  max-width: 100%;

}
.sup-overlay{

  position: absolute;
  bottom: 0;
  top: 0;
  right: 0;
  left: 0;

}
.user-chat__name{
  font-size: 1.1rem;
}
.user-chat__type{
  font-size: .8rem;
  font-weight: 600;
}
@media screen and (max-width: 780px){
  
  .messagesArea{
    height: 75%;
  }
  .user-chat__name{
    font-size: 1rem;
  }
  .user-chat__type{
    font-size: .7rem;
    font-weight: 600;
  }
  .chat-message__content{
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }
  .newMessage__area{
    height: max-content;
  }
  .messageArea__header{
    height: max-content;
    &--icon{
      width: 40px;
      height: 40px;
    }
  }
  .local-message{
    font-size: 0.8rem;
  }
  .visited-message{
    font-size: 0.8rem;
  }
}
</style>
<script>
export default {
  name:'MessageChatAreaComponent',
  data:() => {
    return {
      newMessage:'',
      snackShow:false,
      snackMessage:'',
      snackType:'',
      snacktimeOut:2000,
    };
  },
  methods:{
    sendNewMessage(){
      if (this.newMessage && this.newMessage.trim() !== '') {
        
        const data = {
          id: this.activeChat.id,
          data:{
            message:this.newMessage
          }
        }
        this.$store.dispatch(SEND_NEW_MESSAGE, data)
        .then((data) => {
          this.newMessage = ''
        })
        return
      }
      this.newMessage = ''
      this.showSnackbar('error', 'No se permiten mensajes sin contenido')
    },
    showSnackbar(type, messagge){
      this.snackShow = true;
      this.snackType = type
      this.snackMessage = messagge
    },
    backMessagesList(){
      this.emitter.emit('displayUserChatList', false)
    },
    async typingShow(){
      this.axios.get('/api/typing/'+this.activeChat.id).then( async  (data) => {
        const datax = await data;
        // console.log(datax)
      })
    },
  },
  mounted(){
    // if(this.activeChat){
      setTimeout(() => {
        
        window.Echo.channel('chatMessages'+this.activeChat.id)
        .listen('RealTimeChatMessage',(e)=>{
          console.log(e)
          console.log('ESCRIBIENDO.....')
        })
      }, 10000);
    // }
  },
  created(){
    this.emitter.on("displayLastMessagge", () => {
      setTimeout(()=>{
        var objDiv = document.getElementById("messagesArea");
        objDiv.scrollTop = objDiv.scrollHeight;
      },100)
    })
  },
  computed: {
    messagesChat() {
      return this.$store.state.chatMessages
    },
    activeUser() {
      return this.$store.state.user
    },
    activeChat() {
      return this.$store.state.activeChatID
    },
  },
}
</script>