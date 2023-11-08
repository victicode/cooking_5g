import ApiService from "@/src/core/services/api.service";
import JwtService from "@/src/core/services/jwt.service";

// action types
export const OPERATIONS_GET_PAGINATED = "operations_get_all";
export const OPERATIONS_CHANGE_STATUS = "operations_change_status";
export const OPERATIONS_STORE = "operations_store";
export const OPERATIONS_GET_LAST_FIVE = "operations_get_last_five"
export const OPERATIONS_GET_LAST_FIVE_BY_USER ="operations_get_last_five_by_user"
export const GET_OPERATION_BY_ID ="get_operation_by_id"
const actions = {
    // [OPERATIONS_GET_PAGINATED](_, query = '') {
    //     return new Promise((resolve, reject) => {
    //         if (JwtService.getToken()) {
    //             ApiService.setHeader();
    //             ApiService.get("api/operations", query)
    //             .then(( { data } ) => {
    //                 resolve(data);
    //             })
    //             .catch(() => {
    //                 reject('Ocurrió un error desconocido al intentar mostrar las operaciones.');
    //             });
    //         }
    //     });
    // },
    [OPERATIONS_GET_LAST_FIVE](_, query = '') {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/operations/last", query)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar las operaciones.');
                });
            }
        });
    },
    [GET_OPERATION_BY_ID](_, id ) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/operations/"+ id)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar las operaciones.');
                });
            }
        });
    },
    [OPERATIONS_GET_LAST_FIVE_BY_USER](_, query = '') {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/operations/last_by/"+query.id, query.query)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar las operaciones.');
                });
            }
        });
    },
    [OPERATIONS_CHANGE_STATUS](_, data) {
        let id = data.id;
        delete data.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/operations/" + id + "/change-status", data)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    switch (response.status) {
                        case 400:
                            reject(response.data.error)
                            break;
                        case 404:
                            reject(response.data.error)
                            break;
                        default:
                            reject('Ocurrió un error desconocido al intentar actualizar el estado de la operación.');
                            break;
                    }
                });
            }
        });
    },
    [OPERATIONS_STORE](_, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/operations", body)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(( { response } ) => {
                    switch (response.status) {
                        case 400:
                            reject(response.data.error)
                            break;
                        default:
                            reject('Ocurrió un error desconocido al intentar guardar la operación.');
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
