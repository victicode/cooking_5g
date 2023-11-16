import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_ORDERS = "GET_ORDERS";
export const GET_LAST_PENDING_ORDERS = "GET_LAST_PENDING_ORDERS" 
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
                        // console.log(response )
                        reject('Ocurrió un error desconocido al intentar obtener las ordenes');
                    });
            }
        });
    },
    [GET_ORDERS](context) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/order/get-last-pending")
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
};
export default {
    actions,
};
