import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_PRODUCTS = "GET_PRODUCTS";
export const GET_CRITICAL_STOCK_PRODUCTS = "GET_CRITICAL_STOCK_PRODUCTS";
export const STORE_PRODUCT = "STORE_PRODUCT";
export const UPDATE_PRODUCT = "UPDATE_PRODUCT"
export const DELETE_PRODUCT = "DELETE_PRODUCT"
export const ADD_STOCK = "ADD_STOCK"
export const GET_PRODUCT_BY_ID = "GET_PRODUCT_BY_ID";
export const GET_PRODUCT_BY_SEARCH = "GET_PRODUCT_BY_SEARCH";
export const GET_LAST_LOTE = "GET_LAST_LOTE";
export const GET_LOTE_OF_PRODUCT = "GET_LOTE_OF_PRODUCT";
export const DELETE_LOTE_OF_PRODUCT = "DELETE_LOTE_OF_PRODUCT";
export const UPDATE_LOTE = 'UPDATE_LOTE';
const actions = {
    [GET_PRODUCTS](context, query) {
        return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/products?page="+query.page+"&product_title="+query.search+"&")
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
                console.log(response )
                reject('Ocurrió un error desconocido al intentar obtener las ordenes');
            });
          }
        });
    },
    
    [STORE_PRODUCT](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/products", body)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response )
              reject('Ocurrió un error desconocido al intentar crear el producto');
          });
        }
      });
    },
    [UPDATE_PRODUCT](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/products/"+body.id, body.data)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject('Ocurrió un error desconocido al intentar actualizar el producto');
          });
        }
      });
    },
    [ADD_STOCK](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/products/add-stock/"+body.id, body.data)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject(response);
          });
        }
      });
    },
    [GET_LAST_LOTE](context, id) {
      // console.log(id)
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/products/get-last-lote/"+id)
          .then(( { data } ) => {
            // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject(response);
          });
        }
      });
    },
    [DELETE_PRODUCT](context, data) {
      console.log(data)
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/products/delete/"+ data.id, data)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject('Ocurrió un error desconocido al intentar borrar el producto');
          });
        }
      });
    },
    [GET_PRODUCT_BY_ID](context,productID){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/products/get-by-id/"+productID)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              // console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      });
    },
    [GET_LOTE_OF_PRODUCT](context,loteID){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/lotes/get-by-id/"+loteID)
          .then(( { data } ) => {
            resolve(data);
          })
          .catch(( { response } ) => {
              console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      });
    },
    [DELETE_LOTE_OF_PRODUCT](context,data){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/lotes/delete/"+data.id, data)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      });
    },
    [GET_PRODUCT_BY_SEARCH](context,search){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/products/get-by-search?title="+search+"&")
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
    [UPDATE_LOTE](context, data){
      return new Promise((resolve, reject) => {
        if(JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/lotes/update/"+ data.id, data.data )
          .then(( { data } ) => {
            resolve(data);
          })
          .catch(( { response } ) => {
              console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      })
    }
};
export default {
    actions,
};
