<script setup >
  import { GET_MESSAGE, SEE_ALL_MESSAGE } from "@/core/services/store/message.module";
  import { mapGetters } from "vuex";
  import notificationTone4 from "@/assets/audio/notification2.mp3";
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
    },
  });
  
</script>
<template>
  <div class="d-flex justify-space-around">
    <v-menu
      transition="slide-y-transition"
    >
      <template v-slot:activator="{ props }">
        <div class="relative position-relative">
          <IconBtn class="me-2"  v-bind="props" @click="messages_count = 0 ">
            <VIcon  size="x-large" icon="mi:message-alt" />
          </IconBtn>
          <div class="message__count animate__animated" v-if="messages_count > 0">
            <div class="w-100">
                {{ 
                  messages_count > 10 
                  ?'+100'
                  : messages_count
                }}
            </div>
          </div>
        </div>
      </template>

      <v-list v-if="messages.length > 0" class="message-list pb-5" style="">
        <v-list-item
          v-for="(item, i) in messages"
          :key="i"
          
        >
          <v-list-item-title 
            class="d-flex flex-column  pt-0" 
            :class="item.read == 0 ?'bg-unread-notify' :''"  
            style="border-bottom: 1px solid #cf6123; "
            @click="seeAllMessages(item.id)"
          >
            <Component
              :is="'RouterLink'"
              :to="'/support'"
              :href="'/support'"
            >
                <div class="w-100 text-right message-time_text text-secondary">
                  {{ moment(item.created_at).fromNow() }}
                </div>
                <div class="py-2 px-1 font-weight-bold text-black" style="text-wrap: wrap; " >
                  <div>
                    <a href="" >{{ item.sender ? item.sender.name : 'Un usuario'}} </a>
                    te ha notificado que: 
                    <a>{{ item.products.title }}</a> <span v-html="item.message"> </span>
                  </div>
                </div>
            </Component>
          </v-list-item-title>
        </v-list-item>
      </v-list>
      <div v-else class="text-center card">
        <VCard class="pa-5">
          <h3 >No hay mensajes</h3>
        </VCard>
      </div>
    </v-menu>

  </div>
</template>
<style lang="scss">
.message-time_text{
  margin-bottom: -10px;
  font-size: small;
}
.v-overlay__content:has(.message-list){
  max-width: 390px!important;

}
.message-list{
  max-height: 50vh;
  overflow-y: scroll!important;
  background:#efefef!important;
  box-shadow:0px 3px 15px 5px #7f7f7f7d!important;
  &::-webkit-scrollbar {
    width: 8px;     /* Tamaño del scroll en vertical */
    height: 8px;    /* Tamaño del scroll en horizontal */
    display: none;  /* Ocultar scroll */
  }
}
.bg-unread-notify{
  background: #f58a0026;
}
.text-black{
  color: #505050!important
}
.message__count{
  position: absolute;
  height: 32px;
  border-radius: 10px;
  background: #cf6123;
  color: white;
  display: flex;
  align-items: center;
  top: -12px;
  left: 41%;
  text-align: center;
  width: 32px;
  padding: 2px;
  animation-duration: 1s!important; /* don't forget to set a duration! */
}
</style>
<script>
  export default {
    data(){
      return{
        sound: new Audio(notificationTone4),
        messages:[],
        messages_count:0
      }
    },
    methods:{
      getMessages(){
        setTimeout(() => {
          this.$store
          .dispatch(GET_MESSAGE)
          .then((data)=> {
            this.messages = data.all ; 
            this.messages_count = data.new_count ; 
  
          })
        }, 1200);
      },
      seeAllMessages(type){
        this.$store
        .dispatch(SEE_ALL_MESSAGE, 1)
        .then((data)=> {
          setTimeout(() => {
            this.getMessages()
          }, 500);
        })
      },
    },
    mounted(){
      this.getMessages()
      window.Echo.channel('messageCooking')
      .listen('MessageCooking',(e)=>{
        if(this.getCurrentAccount.rol_id !== 3){

          this.getMessages()
          this.sound.play()
        }
      })
    },
    computed: {
      ...mapGetters(["currentUser"]),

      getCurrentAccount() {
        return this.currentUser;
      },
    },
  };
</script>