import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_RECIPES      = "GET_RECIPES";
export const GET_RECIPE_BY_ID = "GET_RECIPE_BY_ID";
export const STORE_RECIPE     = "STORE_RECIPE";
export const UPDATE_RECIPE    = "UPDATE_RECIPE";
export const DELETE_RECIPE    = "DELETE_RECIPE";


const actions = {
    [GET_RECIPES](context, query) {

      console.log(query)
        return new Promise((resolve, reject) => {
          if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.get("api/recipes?product_title="+query+"&")
            .then(( { data } ) => {
                // console.log(data)
                resolve(data);
                
            })
            .catch(( { response } ) => {
                console.log(response )
                reject('Ocurrió un error desconocido al intentar obtener las ordenes');
            });
          }
        });
    },
    [STORE_RECIPE](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/recipes", body)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response )
              reject('Ocurrió un error desconocido al intentar crear el RECIPEo');
          });
        }
      });
    },
    [UPDATE_RECIPE](context, body) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("api/recipes/"+body.id, body.data)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject('Ocurrió un error desconocido al intentar actualizar el RECIPEo');
          });
        }
      });
    },
    [DELETE_RECIPE](context, id) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.delete("api/recipes/"+ id)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              console.log(response)
              reject('Ocurrió un error desconocido al intentar borrar el RECIPEo');
          });
        }
      });
    },
    [GET_RECIPE_BY_ID](context,recipeId){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/recipes/get-by-id/"+recipeId)
          .then(( { data } ) => {
              // console.log(data)
              resolve(data);
              
          })
          .catch(( { response } ) => {
              // console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener las recetas');
          });
        }
      });
    },
};
export default {
    actions,
};
