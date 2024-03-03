import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_NOTIFICATIONS = "GET_NOTIFICATIONS";
export const SEE_ALL_NOTIFICATIONS_BY_TYPE = "SEE_ALL_NOTIFICATIONS_BY_TYPE"


const actions = {
  [GET_NOTIFICATIONS](context,body){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/notification")
        .then(( { data } ) => {
            console.log(data)
            resolve(data);
            
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },
  [SEE_ALL_NOTIFICATIONS_BY_TYPE](context, type){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/notification/see-all/"+type)
        .then(( { data } ) => {
            console.log(data)
            resolve(data);
            
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  }
};
export default {
    actions,
};
