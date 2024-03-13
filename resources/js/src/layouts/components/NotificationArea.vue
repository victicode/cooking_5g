<script setup >
  import * as bootstrap from 'bootstrap'
  import { GET_NOTIFICATIONS, SEE_ALL_NOTIFICATIONS_BY_TYPE } from "@/core/services/store/notification.module";
  import { mapGetters } from "vuex";
  import notificationTone4 from "@/assets/audio/notification4.mp3";
  import moment from 'moment';
  import 'moment/locale/es';
  moment.defineLocale('es-mx', {
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

          <IconBtn class="me-2" v-bind="props" @click="this.notifiactions_count=0">
            <VIcon icon="bx-bell" />
          </IconBtn>
          <div class="notification__count animate__animated" v-if="notifiactions_count > 0">
            <div class="w-100">
                {{ 
                  notifiactions_count > 10 
                  ?'+10'
                  : notifiactions_count
                }}
            </div>
          </div>
        </div>
      </template>

      <v-list v-if="notifications.length > 0" class="notification-list pb-5" style="">
        <v-list-item
          v-for="(item, i) in notifications"
          :key="i"
          
        >
          <v-list-item-title 
            class="d-flex flex-column  pt-0" 
            :class="item.read == 0 ?'bg-unread-notify' :''"  
            
            style="border-bottom: 1px solid #cf6123; "
            @click="seeAllNotificationByType(item.type)"
          >
            <Component
              :is="'RouterLink'"
              :to="item.type == 1 ? '/orders': item.type == 2 ? '/products' : '/orders'"
              :href="item.type == 1 ? '/orders': item.type == 2 ? '/products ' : '/orders'"
            >
                <div class="w-100 text-right notification-time_text text-secondary">
                  {{ moment(item.created_at).startOf('hour').fromNow()  }}
                </div>
                <div class="py-2 px-1 font-weight-bold text-black" style="text-wrap: wrap; " v-html="item.message">

                </div>
            </Component>
          </v-list-item-title>
        </v-list-item>
      </v-list>
      <div v-else class="text-center card">
        <VCard class="pa-5">
          <h3 >No hay notificaiones</h3>
        </VCard>
      </div>
    </v-menu>

  </div>
</template>
<style lang="scss">
.notification-time_text{
  margin-bottom: -10px;
  font-size: small;
}
.v-overlay__content:has(.notification-list){
  max-width: 390px!important;

}
.notification-list{
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
.notification__count{
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
        notifications:[],
        notifiactions_count:0
      }
    },
    methods:{
      getNotifications(){
        this.$store
        .dispatch(GET_NOTIFICATIONS)
        .then((data)=> {
          this.notifications = data.all ; 
          this.notifiactions_count = data.new_count ; 

        })
      },
      seeAllNotificationByType(type){

        this.$store
        .dispatch(SEE_ALL_NOTIFICATIONS_BY_TYPE,type )
        .then((data)=> {
          setTimeout(() => {
            this.getNotifications()
          }, 500);

        })
      
      }
    },
    mounted(){
      this.getNotifications()
      Notification.requestPermission().then(function (result) {
        console.log(result);
      });
      window.Echo.channel('notificationCooking')
      .listen('NotificationCooking',(e)=>{
        this.sound.play()
        this.getNotifications()
        document.querySelector('.notification__count').classList.toggle('animate__bounce')
        setTimeout(() => {
          document.querySelector('.notification__count').classList.toggle('animate__bounce')
          
        }, 2100);
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