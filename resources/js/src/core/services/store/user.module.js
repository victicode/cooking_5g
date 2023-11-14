import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_USER = "GET_USER";
// mutation types
export const SET_USER = "setUser";

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
                        console.log(data.data.user)
                        context.commit(SET_USER, data.data.user);
                        resolve(data.data);
                        
                    })
                    .catch(( { response } ) => {
                        console.log(response)
                        switch (response.status) {
                            case 400:
                                reject(response.data.error)
                                break;
                            default:
                                reject('Ocurrió un error desconocido al intentar guardar el usuario.');
                                break;
                        }
                    });
            }
        });
    },
};
const mutations = {
    [SET_USER](state, user) {
        state.account = user;
      },
}
export default {
    actions,
    getters,
    mutations,
    state
};
