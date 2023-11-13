import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const LOGIN = "login";
export const LOGOUT = "logout";
export const REGISTER = "register"
export const REGISTER_GOOGLE = "register_google";
export const REDIRECT_REGISTER_GOOGLE = 'export const';
export const ALL_USERS = "allUser";
export const UPDATE_PASSWORD = "updatePassword";
export const RESET_PASSWORD = 'resetPassword';
export const RESET_PASSWORD_EMAIL = 'resetPasswordEmail';
export const VALIDATE_USER_EXIST = 'validate_user_exist';
export const VALIDATE_EMAIL = 'validate_email';
// mutation types
export const PURGE_AUTH = "logOut";
export const SET_TOKEN = "setToken";
export const SET_HAS_ACCOUNT = "setHasAccount";
export const SET_IS_ADMIN = "setIsAdmin";
export const SET_AUTH = "setUser";
export const SET_PASSWORD = "setPassword";
export const SET_ERROR = "setError";

const state = {
  errors: [],
  user: {},
  isAuthenticated: !!JwtService.getToken()
};

const getters = {
  currentUser(state) {
    return state.user;
  },
  isAuthenticated(state) {
    return state.isAuthenticated;
  }
};

const actions = {
  [LOGIN](context, credentials) {
    return new Promise(resolve => {
      ApiService.post("api/login", credentials)
        .then(({ data }) => {
          if(!data.data.access_token){
            throw data;
          }
          context.commit(SET_TOKEN, data.data.access_token);
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/user")
              .then(({ data }) => {
                context.commit(SET_AUTH, data.data.user);
                context.commit(SET_IS_ADMIN, data.data.user);
                state.user = data.data.user;
                
                resolve(data.data);
              })
              .catch(( response ) => {
                context.commit(SET_ERROR, response.data);
              });
          }
        })
        .catch(( { response } ) => {
          resolve(response.data.data)
        });
    });
  },
  [LOGOUT](context) {
    return new Promise(resolve => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/logout")
          .then(() => {
            context.commit(PURGE_AUTH);
            resolve();
          });
      }
    });
  },
  [RESET_PASSWORD_EMAIL](context, credentials) {
    return new Promise(resolve => {
      ApiService.post("api/password/reset/email", credentials)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( data ) => {
          // console.log(data);
          // context.commit(SET_ERROR, data.errors);
        });
    });
  },
  [RESET_PASSWORD](context, credentials) {
    return new Promise(resolve => {
      ApiService.post("api/password/reset", credentials)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( data ) => {
          // console.log(data);
          // context.commit(SET_ERROR, data.errors);
        });
    });
  },
  [VALIDATE_USER_EXIST](_,query) {

    return new Promise(resolve => {
      ApiService.get("api/user/validate_user_exist"+query)
      .then(({ data }) => {
        resolve(data);
        return;
      });
    });
  },
  [UPDATE_PASSWORD](_, payload) {
    const password = payload;

    return new Promise(resolve => {
      ApiService.put("api/user/pass_update", password)
      .then(({ data }) => {
        // console.log(data)
        resolve(data);
        return;
      });
    });
  }
};

const mutations = {
  [SET_ERROR](state, error) {
    state.errors = error;
  },
  [SET_TOKEN](state, token) {
    JwtService.saveToken(token);
  },
  [SET_IS_ADMIN](state, user) {
    window.localStorage.setItem("is_admin", user.rol_id === 1 ? true : false);
  },
  [SET_AUTH](state, user) {
    state.isAuthenticated = true;
    console.log(user)
    state.user = user;
    state.errors = {};
  },
  [SET_PASSWORD](state, password) {
    state.user.password = password;
  },
  [PURGE_AUTH](state) {
    state.isAuthenticated = false;
    state.user = {};
    state.errors = {};
    window.localStorage.removeItem("id_token");
    window.localStorage.removeItem("has_account");
    window.localStorage.removeItem("is_admin");
    window.localStorage.removeItem("session_on");
    JwtService.destroyToken();
  }
};

export default {
  state,
  actions,
  mutations,
  getters
};
