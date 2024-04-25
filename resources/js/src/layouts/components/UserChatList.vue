<template>
  <div class="chat-list__content">
    <div class="pt-8">
      <ul>
        <li class="mt-3">
          <div class="chat-description">
            <img src="https://i.pravatar.cc/100" width="60" alt="">
            <div>
              <div class="text-white user-chat__name">
                Manuel
              </div> 
            </div>
          </div>
        </li>
        <li class="mt-3">
          <div class="chat-description">
            <img src="https://i.pravatar.cc/300" width="60" alt="">
            <div class="chat-">
              <div class="text-white user-chat__name">
                Betania
              </div> 
            </div>
          </div>
        </li>
        <li class="mt-3">
          <div class="chat-description">
            <img src="https://i.pravatar.cc/200" width="60" alt="">
            <div>
              <div class="text-white user-chat__name">
                Froilan
              </div> 
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<style lang="scss"  scoped>
.chat-list__content{
  width: 100%;
  height: 100%;
  background: #222222;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  padding: 0px 20px;
}
ul{
  list-style: none;
  & li > h3{
    color:white!important
  }
}
.chat-description{
  display: flex;
  align-items: center;
  transition: all 0.5s ease-in-out;
  border-radius: 10px;
  padding: 5px 10px;
  &:hover{
    background: rgb(211, 29, 235);
  }
  & img{
    margin-right: 15px;
    border-radius: 50%;
  }
}
.user-chat__name{
  font-size: 20px;
}
</style>
<script>
export default {
  name:'UserChatListComponent',
}
</script>