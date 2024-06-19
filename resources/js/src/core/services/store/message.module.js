import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_MESSAGE = "GET_MESSAGE";
export const CREATE_MESSAGE = "CREATE_MESSAGE"
export const SEE_ALL_MESSAGE = "SEE_ALL_MESSAGE"


const actions = {
  [GET_MESSAGE](context){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/message/")
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
  [CREATE_MESSAGE](context,ProductId){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/products/notify-out-stock/"+ProductId)
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
  [SEE_ALL_MESSAGE](context, userId){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/message/see-all/"+userId)
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
  }
};
export default {
    actions,
};
