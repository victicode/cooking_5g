import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const USERS_STORE = "users_store";

const actions = {
    [USERS_STORE](_, body) {
        return new Promise((resolve, reject) => {
            if (JwtService.getToken()) {
                ApiService.setHeader();
                ApiService.post("api/user", body)
                    .then(( { data } ) => {
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
