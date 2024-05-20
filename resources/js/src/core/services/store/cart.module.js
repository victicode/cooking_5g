import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_CART = "GET_CART";
export const ADD_TO_CART = "ADD_TO_CART";
export const SEE_ALL_NOTIFICATIONS_BY_TYPE = "SEE_ALL_NOTIFICATIONS_BY_TYPE"
export const SET_CART = "SET_CART";
export const DELETE_PRODUCT_CART = "DELETE_PRODUCT_CART";
export const UPDATE_QUANTITY_ITEMS = "UPDATE_QUANTITY_ITEMS";
export const DESTROY_CART = "DESTROY_CART"
let state = {
    cart: [],
};
const getters = {
    cart(state) {
      return state.cart;
    },
};

const actions = {
  [GET_CART](context,body){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/cart")
        .then(( { data } ) => {
            resolve(data);
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },
  [ADD_TO_CART](context,body){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/cart", body)
        .then(( { data } ) => {
            resolve(data);
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },
  [UPDATE_QUANTITY_ITEMS](context,body){

    console.log(body)
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/cart/item/quantity/"+body.id, body.data)
        .then(( { data } ) => {
            resolve(data);
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },
  [DELETE_PRODUCT_CART](context,id){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/cart/item/"+id)
        .then(( { data } ) => {
            resolve(data);
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },
  [DESTROY_CART](context,id){
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/cart/delete/"+id)
        .then(( { data } ) => {
            resolve(data);
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las ordenes');
        });
      }
    });
  },

};
const mutations = {
  [SET_CART](state, cart) {
      state.cart = cart;
    },
}
export default {
    actions,
};
