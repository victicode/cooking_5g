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
    console.log(credentials)
    return new Promise(resolve => {
      console.log(ApiService.post("api/login",''))
      
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
  [REGISTER](context, credentials) {
    return new Promise(resolve => {
      ApiService.post("api/register", credentials)
        .then(({ data:dataRegister }) => {
          if(dataRegister.status === 'error'){
            resolve(dataRegister);
            return;
          }

          ApiService.post("api/login", credentials)
          .then(( { data } ) => {
            if(!data.access_token){
              throw data;
            }
            context.commit(SET_TOKEN, data.access_token);
            context.commit(SET_AUTH, dataRegister.user);
            context.commit(SET_HAS_ACCOUNT, dataRegister.user);
            context.commit(SET_IS_ADMIN, dataRegister.user);

            resolve(dataRegister);
          })
          .catch(( { data } ) => {
            context.commit(SET_ERROR, data.errors);
          });
        })
        .catch(({ data }) => {
          context.commit(SET_ERROR, data.errors);
        });
    });
  },
  [REDIRECT_REGISTER_GOOGLE](context,provider) {
    return new Promise(resolve => {
      ApiService.get("api/auth/"+provider+"/redirect")
        .then(({ data }) => {
          resolve(data);
        })
        .catch(({ data }) => {
          context.commit(SET_ERROR, data.errors);
        });
    });
  },
  [REGISTER_GOOGLE](context, payload) {
    return new Promise(resolve => {
      ApiService.get("api/auth/"+payload.provider+"/callback?code="+payload.code+'&&')
        .then(({ data:dataRegister }) => {
          if(dataRegister.status === 'error'){
            resolve(dataRegister);
            return;
          }
          const email = dataRegister.user.email
          const password = dataRegister.user.password;

          ApiService.post("api/login", { email, password })
          .then(( { data } ) => {
            if(!data.access_token){
              throw data;
            }
            context.commit(SET_TOKEN, data.access_token);
            context.commit(SET_AUTH, dataRegister.user);
            context.commit(SET_HAS_ACCOUNT, dataRegister.user);
            context.commit(SET_IS_ADMIN, dataRegister.user);

            resolve(dataRegister);
          })
        })
        .catch(({ data }) => {
          context.commit(SET_ERROR, data.errors);
        });
    });
  },
  [VALIDATE_EMAIL](_, payload) {
    const data = payload;
    return new Promise(resolve => {
        ApiService.setHeader();
        ApiService.put("api/validate_email/"+data .user, data )
        .then(({ data }) => {
          resolve(data);
          return;
        });
      
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
  [ALL_USERS](_) {

    return new Promise(resolve => {
      ApiService.post("api/user/all")
      .then(({ data }) => {
        resolve(data);
        return;
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
  [SET_HAS_ACCOUNT](state, user) {
    let timeInitSesion = Date.parse(new Date())
    window.localStorage.setItem("has_account", user.active_account_type == 0 || user.active_account_type == 1 ? true : false);
    window.localStorage.setItem("session_on",timeInitSesion)
  },
  [SET_IS_ADMIN](state, user) {
    window.localStorage.setItem("is_admin", user.roles.findIndex(role => role.name == 'admin') > -1 ? true : false);
  },
  [SET_AUTH](state, user) {
    state.isAuthenticated = true;
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
