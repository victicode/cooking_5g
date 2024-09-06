import ApiService from "@/core/services/api.service";
import JwtService from "@/core/services/jwt.service";

// action types
export const GET_RECIPES      = "GET_RECIPES";
export const GET_RECIPE_BY_ID = "GET_RECIPE_BY_ID";
export const STORE_RECIPE     = "STORE_RECIPE";
export const UPDATE_RECIPE    = "UPDATE_RECIPE";
export const DELETE_RECIPE    = "DELETE_RECIPE";
export const GET_RECIPE_BY_SEARCH  ="GET_RECIPE_BY_SEARCH "
export const GET_ALLERGEN = "GET_ALLERGEN";

const actions = {
    [GET_RECIPES](context, query) {
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/recipes?page="+query.page+"&recipe_title="+query.search+"&")
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
          ApiService.post("api/recipes/delete/"+ id)
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

        ApiService.setHeader();
        ApiService.get("/api/recipes/get-by-id/"+recipeId)
        .then(( { data } ) => {
            console.log(data)
            resolve(data);
            
        })
        .catch(( { response } ) => {
            console.log(response )
            reject('Ocurrió un error desconocido al intentar obtener las recetas');
        });
        
      });
    },
    [GET_RECIPE_BY_SEARCH](context,search){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/recipes/get-by-search?title="+search+"&")
          .then(( { data } ) => {
              resolve(data);
              
          })
          .catch(( { response } ) => {
              // console.log(response )
              reject('Ocurrió un error desconocido al intentar obtener el producto');
          });
        }
      });
    },
    [GET_ALLERGEN](context,search){
      return new Promise((resolve, reject) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/allergen")
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
    }

    
};
export default {
    actions,
};
