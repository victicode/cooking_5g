import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_PRODUCTS = "GET_PRODUCTS";
export const GET_CRITICAL_STOCK_PRODUCTS = "GET_CRITICAL_STOCK_PRODUCTS";

const actions = {
    [GET_PRODUCTS](context) {
        return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/products")
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
    [GET_CRITICAL_STOCK_PRODUCTS](context) {
        return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/products/get-critical-stock")
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
