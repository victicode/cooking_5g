import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_ORDERS       = "GET_ORDERS";
export const GET_LAST_ORDERS  = "GET_LAST_SEND_ORDERS";
export const GET_ORDER_BY_ID  = "GET_ORDER_BY_ID";
export const CREATE_ORDER     = "CREATE_ORDER";
export const CREATE_OUT_ORDER = "CREATE_OUT_ORDER";
export const CHANGE_STATUS    = "CHANGE_STATUS";

const actions = {
  [GET_ORDERS](context) {
      return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
              ApiService.setHeader();
              ApiService.get("api/order")
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
  [GET_LAST_ORDERS](context,type) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/order/get-last?type="+type+'&&')
        .then(( { data } ) => {
            // console.log(data)
            resolve(data);
            
        })
        .catch(( { response } ) => {
            // console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },
  [GET_ORDER_BY_ID](context,orderID){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/order/get-by-id/"+orderID)
        .then(( { data } ) => {
            resolve(data);
            
        })
        .catch(( { response } ) => {
            reject(response);
        });
      }
    });
  },
  [CREATE_ORDER](context, body){
    console.log(body)
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/order", body)
        .then(( { data } ) => {
          console.log(data)
          resolve(data);
            
        })
        .catch(( { response } ) => {
          console.log(response)
            reject(response);
        });
      }
    });
  },
  [CREATE_OUT_ORDER](context, body){
    console.log(body)
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/out_order", body)
        .then(( { data } ) => {
          console.log(data)
          resolve(data);
            
        })
        .catch(( { response } ) => {
          console.log(response.data.error)
            reject(response.data.error);
        });
      }
    });
  },
  [CHANGE_STATUS](context,body){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/order/change-status/"+body.id, body)
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
