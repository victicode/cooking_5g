import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_CHAT          = "GET_CHAT";
export const STORE_CHAT        = "STORE_CHAT";
export const DELETE_CHAT       = "DELETE_CHAT";
export const UPDATE_CHAT       = "UPDATE_CHAT";
export const SEND_NEW_MESSAGE  = "SEND_NEW_MESSAGE";
export const GET_CHAT_BY_ID    = "GET_CHAT_BY_ID";
export const SET_ACTIVE_CHAT   = "SET_ACTIVE_CHAT";
export const GET_ALL_UNREAD_MESSAGES = "GET_ALL_UNREAD_MESSAGES"
export const CHANGE_STATUS = "CHANGE_STATUS"
const state = {
  activeChat: {
    id:'',
  },
  unReadMessages:0,
};
const actions = {
    [GET_CHAT](context, query) {
        return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/support?chat_id="+query.query+"&show="+query.show+"&onlyClosed="+query.onlyClosed+"&")
            .then(( { data } ) => {
                // console.log(data)
                resolve(data);
                
            })
            .catch(( { response } ) => {
                console.log(response )
                reject('Ocurrió un error desconocido al intentar obtener las ordenes');
            });
          }
        });
    },
    [STORE_CHAT](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/support", body)
          .then(( { data } ) => {
              resolve(data);
          })
          .catch(( { response } ) => {
              console.log(response )
              reject('Ocurrió un error desconocido al intentar crear el RECIPEo');
          });
        }
      });
    },
    [CHANGE_STATUS](context, body){
      return new Promise ((resolve, reject) => {
        if(JwtService.getToken()){
          ApiService.setHeader();
          ApiService.post("api/support/"+body.id, body.data)
          .then(( { data } ) => {
            console.log(data)
            resolve(data);
            
        })
        .catch(( { response } ) => {
            console.log(response)
            reject('Ocurrió un error desconocido al intentar actualizar el RECIPEo');
        });
        }
      })
    },
    [UPDATE_CHAT](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/recipes/"+body.id, body.data)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject('Ocurrió un error desconocido al intentar actualizar el RECIPEo');
          });
        }
      });
    },
    [DELETE_CHAT](context, id) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.delete("api/recipes/"+ id)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject('Ocurrió un error desconocido al intentar borrar el RECIPEo');
          });
        }
      });
    },
    [SEND_NEW_MESSAGE](context,data){
      return new Promise((resolve, reject) => {
        ApiService.setHeader();
        ApiService.post("/api/support/"+data.id+"/newMessage", data.data)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las recetas');
        });
        
      });
    },
    [GET_CHAT_BY_ID](context,id){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/support/"+id)
          .then(( { data } ) => {
            resolve(data);
          })
          .catch(( { response } ) => {
              // console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      });
    },
    [GET_ALL_UNREAD_MESSAGES](context){
      return new Promise((resolve, reject) => {
        if(JwtService.getToken()){
          ApiService.setHeader();
          ApiService.post("api/support/get-unread-messages", {id:'id'})
          .then( ({data}) =>{
            resolve(data)
          }).catch(( { response } ) => {
              // console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      })
    },
};

export default {
  state,  
  actions,
};
