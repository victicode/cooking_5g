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
    [GET_USER](_, body='') {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user")
                    .then(( { data } ) => {
                        console.log(data)
                        // context.commit(SET_AUTH, data.data.user);
                        resolve(data);
                        
                    })
                    .catch(( { response } ) => {
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

export default {
    actions
};
