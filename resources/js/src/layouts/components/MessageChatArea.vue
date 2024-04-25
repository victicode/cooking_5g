<script setup >
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
  <div class="chat-message__content " :class="{'nochat': !activeChat}">
    <div class="h-100 w-100 d-flex justify-center align-center text-center no-chat-active position-relative" v-if="!activeChat">
      <h2>COOKING 5G <br> Soporte</h2>
      <div class="h-100 w-100 d-flex justify-center align-center sup-overlay">

      </div>
    </div>
    <div class="h-100 w-100  position-relative d-flex flex-column justify-space-between pb-4" v-if="messagesChat" >
      <div class="messageArea__header d-flex align-center justify-space-between pe-5">
        <div class=" d-flex align-center">
          <div class="rounded-circle pa-3 d-flex justify-center align-center bg-white me-2 messageArea__header--icon">
            <VIcon icon="mingcute:user-2-fill" size="large" color="primary" />
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
          <h5 class="text-white">Estado:</h5>
          <v-chip color="success" variant="elevated">
            ABIERTO
          </v-chip>
        </div>
      </div>
      <div class="pa-3 messagesArea">
        <div class="d-flex message__text-content" v-for="(messages, index) in messagesChat" :key="index">
          <div  :class="{'visit': messages.sender_id == 1 }">
            <div class="mt-2 local-message elevation-24" :class="{'visited-message': messages.sender_id == 1 }">
              {{ messages.message }}
            </div>
            <div :class="{'text-end': messages.sender_id == 1 }">
              <h6 class="ms-2" :class="{'me-2': messages.sender_id == 1 }">
                {{ moment(messages.created_at).format('h:mm A')  }}
              </h6>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex align-center newMessage__area w-100 pa-5">
        <textarea class="elevation-24" name="" id="" placeholder="Escribe un mensaje" cols="1" rows="1"></textarea>
        <v-btn icon="iconoir:send" variant="tonal" color="primary" class="ms-2"/>
      </div>
    </div>
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
}
.nochat{
  background: rgba(238, 168, 76, 0.363)!important;
}
.no-chat-active{
  opacity: 0.2
}
.message__text-content{
  justify-content: start;
}
.message__text-content:has(> .visit){
  justify-content: end;
}
.local-message{
  background: #696969;
  border-radius: 15px;
  width: max-content;
  padding: 10px;
  color: white;
}
.visited-message{
  background: #cf6123;
  border-radius: 15px;
  width: max-content;
  padding: 10px;
  color: white;
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
  .chat-list__content{
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
  }
}
</style>
<script>
export default {
  name:'MessageChatAreaComponent',
  methods:{

  },
  computed: {
    messagesChat() {
      return this.$store.state.chatMessages
    },
    activeChat() {
      return this.$store.state.activeChatID
    },
  },
}
</script>