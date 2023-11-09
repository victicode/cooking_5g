import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const ACCOUNTS_GET_ALL = "accounts_get_all";
export const ACCOUNTS_GET_ALL_USER = "accounts_get_all_user";
export const ACCOUNTS_GET_ALL_USER_BY_SEARCH = "accounts_get_all_user_by_search";
export const SET_ACTIVE_ACCOUNT = 'accounts_set_active';

export const ACCOUNTS_PERSONAL_STORE = "accounts_personal_store";
export const ACCOUNTS_PERSONAL_GET_ALL = "accounts_personal_get_all";
export const ACCOUNTS_PERSONAL_DELETE = "accounts_personal_delete";
export const ACCOUNTS_PERSONAL_RESTORE = "accounts_personal_restore";
export const ACCOUNTS_PERSONAL_UPDATE = 'accounts_personal_update';
export const ACCOUNTS_PERSONAL_SET_PREFERENTIAL = 'accounts_personal_set_preferential';
export const ACCOUNTS_PERSONAL_DELETE_PREFERENTIAL = 'accounts_personal_delete_preferential';
export const ACCOUNTS_PERSONAL_SET_VALIDATE = 'accounts_personal_set_validate';
export const ACCOUNTS_PERSONAL_RESEND = 'accounts_personal_send_documents';
export const ACCOUNTS_GET_ALL_BY_USER = "accounts_personal_get_all_by_user"
export const ACCOUNTS_COMPANY_STORE = "accounts_company_store";
export const ACCOUNTS_COMPANY_GET_ALL = "accounts_company_get_all";
export const ACCOUNTS_COMPANY_DELETE = "accounts_company_delete";
export const ACCOUNTS_COMPANY_RESTORE = "accounts_company_restore";
export const ACCOUNTS_COMPANY_UPDATE = 'accounts_company_update';
export const ACCOUNTS_COMPANY_SET_PREFERENTIAL = 'accounts_company_set_preferential';
export const ACCOUNTS_COMPANY_DELETE_PREFERENTIAL = 'accounts_company_delete_preferential';
export const ACCOUNTS_COMPANY_SET_VALIDATE = 'accounts_company_set_validate';
export const ACCOUNTS_COMPANY_RESEND = 'accounts_company_send_documents';
export const GET_ACCOUNT_BY_ID_AND_TYPE = 'get_account_by_id_and_type'
export const SEND_EMAILS = 'send_email';
// mutation types
export const SET_ACCOUNT = "setUser";

let state = {
    account: [],
  };
const getters = {
    currentAccount(state) {
      return state.account;
    },
};
const actions = {
    [ACCOUNTS_GET_ALL](context) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user/get-all-accounts",)
                .then(( { data } ) => {
                    

                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar todas los cuentas.');
                });
                ApiService.setHeader();
                ApiService.get("api/user")
                .then(({ data }) => {
                    context.commit(SET_ACCOUNT, data);

                })
                
            }
        });
    },
    [ACCOUNTS_GET_ALL_USER](_,) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user/get-all",)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar todas los cuentas.');
                });
            }
        });
    },
    [ACCOUNTS_GET_ALL_BY_USER](_,body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/user/get-all-accounts-by-user",body)
                .then(( { data } ) => {
                    
                    if(data.data.activeAccountType == 0){
                        data.data.personalAccounts.map((account)=>{
                            if(account.id == data.data.activeAccountId){
                                state.account = account;
                                state.account.id = account.id 
                            }
                        })
                    }else {
                        data.data.companyAccounts.map((account)=>{
                            if(account.id == data.data.activeAccountId){
                                state.account = account;
                                state.account.id = account.id 
                            }
                        })
                    }
                    state.account.type = data.data.activeAccountType;
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar todas los cuentas.');
                });
            }
        });
    },
    [ACCOUNTS_GET_ALL_USER_BY_SEARCH](_,body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user/get-all-by-search-adm"+body)
                .then(({ data }) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar todas los cuentas.');
                });
            }
        });
    },
    [GET_ACCOUNT_BY_ID_AND_TYPE](_, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/accounts/get_by_id_and_type/"+ body.type+'/'+ body.id )
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
                                reject('Ocurrió un error desconocido al intentar eliminar el preferencial la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [SET_ACTIVE_ACCOUNT](_, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/user/active/change", body)
                .then(( { data } ) => {
                    resolve(data);
                    
                })
                .catch(() => {
                    switch (response.status) {
                        case 404:
                            reject(response.data.error)
                            break;
                        default:
                            reject('Ocurrió un error desconocido al intentar cambiar la cuenta activa.');
                            break;
                    }
                });
            }
        });
    },
    [ACCOUNTS_PERSONAL_GET_ALL](_, query = '') {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/accounts/personal" + query)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar las cuentas personales.');
                });
            }
        });
    },
    [ACCOUNTS_PERSONAL_STORE](_, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/accounts/personal", body)
                    .then(( { data } ) => {
                        resolve(data);
                    })
                    .catch(( { response } ) => {
                        switch (response.status) {
                            case 400:
                                reject(response.data.error)
                                break;
                            case 500:
                                reject(response.data.error)
                                break;
                            default:
                                reject('Ocurrió un error desconocido al intentar guardar la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_DELETE](_, id) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.delete("api/accounts/personal/" + id)
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
                                reject('Ocurrió un error desconocido al intentar eliminar la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_RESTORE](_, id) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/accounts/personal/" + id + '/restore')
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
                                reject('Ocurrió un error desconocido al intentar restaurar la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_UPDATE](_, body) {
        let id = body.id;
        // delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/personal/" + id, body)
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
                            case 500:
                                reject(response.data.error)
                                break;
                            default:
                                reject('Ocurrió un error desconocido al intentar actualizar la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_SET_PREFERENTIAL](_, body) {
        let id = body.id;
        delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/personal/" + id + "/set/preferential", body)
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
                                reject('Ocurrió un error desconocido al intentar setear el preferencial la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_SET_VALIDATE](_, body) {
        let id = body.id;
        delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/personal/" + id + "/validate", body)
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
                                reject('Ocurrió un error desconocido al intentar cambiar el estatus.')
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_RESEND](_, body) {
        let id = body.id;
        delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/accounts/personal/" + id + "/sedDocument", body.data)
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
                                reject('Ocurrió un error desconocido al intentar cambiar el estatus.')
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_PERSONAL_DELETE_PREFERENTIAL](_, id) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/personal/" + id + "/delete/preferential")
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
                                reject('Ocurrió un error desconocido al intentar eliminar el preferencial la cuenta personal.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_GET_ALL](_, query = '') {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/accounts/company" + query)
                .then(( { data } ) => {
                    resolve(data);
                })
                .catch(() => {
                    reject('Ocurrió un error desconocido al intentar mostrar las cuentas empresariales.');
                });
            }
        });
    },
    [ACCOUNTS_COMPANY_STORE](_, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/accounts/company", body)
                    .then(( { data } ) => {
                        resolve(data);
                    })
                    .catch(( { response } ) => {
                        switch (response.status) {
                            case 400:
                                reject(response.data.error)
                                break;
                            case 500:
                                reject(response.data.error)
                                break;
                            default:
                                reject('Ocurrió un error desconocido al intentar guardar la cuenta empresarial.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_DELETE](_, id) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.delete("api/accounts/company/" + id)
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
                                reject('Ocurrió un error desconocido al intentar eliminar la cuenta empresarial.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_RESTORE](_, id) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/accounts/company/" + id + '/restore')
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
                                reject('Ocurrió un error desconocido al intentar restaurar la cuenta empresarial.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_UPDATE](_, body) {
        let id = body.id;
        // delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/company/" + id, body)
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
                            case 500:
                                reject(response.data.error)
                                break;
                            default:
                                reject('Ocurrió un error desconocido al intentar actualizar la cuenta empresarial.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_SET_PREFERENTIAL](_, body) {
        let id = body.id;
        delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/company/" + id + "/set/preferential", body)
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
                                reject('Ocurrió un error desconocido al intentar setear el preferencial la cuenta empresarial.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_SET_VALIDATE](_, body) {
        let id = body.id;
        delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/company/" + id + "/validate", body)
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
                                reject('Ocurrió un error desconocido al intentar cambiar el estatus.');
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_RESEND](_, body) {
        let id = body.id;
        delete body.id
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/accounts/company/" + id + "/sedDocument", body.data)
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
                                reject('Ocurrió un error desconocido al intentar cambiar el estatus.')
                                break;
                        }
                    });
            }
        });
    },
    [ACCOUNTS_COMPANY_DELETE_PREFERENTIAL](_, id) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.put("api/accounts/company/" + id + "/delete/preferential")
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
                                reject('Ocurrió un error desconocido al intentar eliminar el preferencial la cuenta empresarial.');
                                break;
                        }
                    });
            }
        });
    },
    [SEND_EMAILS](_,id = 49) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.get("api/user/pruebax/"+id)
                .then(( { data } ) => {
                    resolve(data);
                    
                })
                .catch(() => {
                    switch (response.status) {
                        case 404:
                            reject(response.data.error)
                            break;
                        default:
                            reject('Ocurrió un error desconocido al intentar cambiar la cuenta activa.');
                            break;
                    }
                });
            }
        });
    },
};
const mutations = {
    [SET_ACCOUNT](state, user) {
        state.account = user;
      },
}
export default {
    actions,
    getters,
    mutations,
    state
};
