import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";
// import newToken from "@/core/services/jwt.service";


// action types
export const GET_USER = "GET_USER";
// mutation types
export const SET_USER = "SET_USER";
export const GET_ALL_USER = "GET_ALL_USER";
export const CREATE_USER = "CREATE_USER";
export const GET_USER_BY_ID = "GET_USER_BY_ID";


const state = {
    user: {},
};

const getters = {
    currentUser(state) {
        return state.user;
    },
};
const actions = {
    [GET_USER](context) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user")
                    .then(( { data } ) => {

                        context.commit(SET_USER, data.data.user);
                        resolve(data.data);
 
                    })
                    .catch(( { response } ) => {
                        reject('Ocurrió un error desconocido al intentar guardar el usuario.');
                    });
            }
        });
    },
    [GET_ALL_USER](context) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user/all-client")
                    .then(( { data } ) => {

                        resolve(data.data);
 
                    })
                    .catch(( { response } ) => {
                        console.log(response)
                        reject('Ocurrió un error desconocido al intentar guardar el usuario.');
                    });
            }
        });
    },
    [GET_USER_BY_ID](context,userId){
        return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/user/get-by-id/"+userId)
            .then(( { data } ) => {
                resolve(data);
                
            })
            .catch(( { response } ) => {
                reject('Ocurrió un error desconocido al intentar obtener usuario');
            });
          }
        });
      },
    [CREATE_USER](context, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/user", body)
                    .then(( { data } ) => {
                        resolve(data.data);
 
                    })
                    .catch(( { response } ) => {
                        console.log(response)
                        reject('Ocurrió un error desconocido al intentar guardar el usuario.');
                    });
            }
        });
    },
};
const mutations = {
    [SET_USER](state, user) {
        state.user = user;
      },
}
export default {
    actions,
    getters,
    mutations,
    state
};
