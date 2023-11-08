import ApiService from "@/src/core/services/api.service";
import JwtService from "@/src/core/services/jwt.service";

// action types
export const CONFIG_STORE = "config_store";
export const DESACTIVATE_OPERATION = "desactivate_operation";
export const CONFIG_GET_ALL = "config_get_all";
export const GET_SYSTEM_CONFIGURATION_VALUE = "GET_SYSTEM_CONFIGURATION_VALUE";
export const CONFIG_GET_SET = "config_get_set";
export const CONFIG_DELETE = "config_delete";
export const CONFIG_RESTORE = "config_restore";
export const CONFIG_UPDATE = 'config_update';

const actions = {
  [CONFIG_GET_ALL](_, ) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/config")
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(() => {
          reject('Ocurrió un error desconocido al intentar mostrar los bancos.');
        });
      }
    });
  },
  [DESACTIVATE_OPERATION](_,) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/config/desactivate-operation" )
        .then(( { data } ) => {
          resolve(data);
        })
        .catch((err) => {
          reject(err);
        });
      }
    });
  },
  [GET_SYSTEM_CONFIGURATION_VALUE](_, body) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/config/get-config" , body)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch((err) => {
          reject(err);
        });
      }
    });
  },
  [CONFIG_STORE](_, body) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/banks", body)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( { response } ) => {
          switch (response.status) {
            case 400:
              reject(response.data.error)
              break;
            default:
              reject('Ocurrió un error desconocido al intentar guardar el banco.');
              break;
          }
        });
      }
    });
  },
  [CONFIG_DELETE](_, id) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.delete("api/banks/" + id)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( { response } ) => {
          switch (response.status) {
            case 400:
              reject(response.data.error)
              break;
            case 404:
              reject(response.data.error)
              break;
            default:
              reject('Ocurrió un error desconocido al intentar eliminar el banco.');
              break;
          }
        });
      }
    });
  },
  [CONFIG_RESTORE](_, id) {
    return new Promise((resolve, reject) => {
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.get("api/banks/" + id + '/restore')
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( { response } ) => {
          switch (response.status) {
            case 400:
              reject(response.data.error)
              break;
            case 404:
              reject(response.data.error)
              break;
            default:
              reject('Ocurrió un error desconocido al intentar restaurar el banco.');
              break;
          }
        });
      }
    });
  },
  [CONFIG_UPDATE](_, body) {
    return new Promise((resolve, reject) => {
      let id = body.get('bank_id');
      body.delete('bank_id')
      if (JwtService.getToken()) {
        ApiService.setHeader();
        ApiService.post("api/banks/" + id + "?_method=PUT", body)
        .then(( { data } ) => {
          resolve(data);
        })
        .catch(( { response } ) => {
          switch (response.status) {
            case 400:
              reject(response.data.error)
              break;
            case 404:
              reject(response.data.error)
              break;
            default:
              reject('Ocurrió un error desconocido al intentar actualizar el banco.');
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
