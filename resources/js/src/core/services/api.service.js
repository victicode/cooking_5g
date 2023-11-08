import * as Vue from 'vue';
import axios from "axios";
import VueAxios from "vue-axios";
import JwtService from "@/src/core/services/jwt.service";

/**
 * Service to call HTTP request via Axios
 */
const ApiService = {
  init() {
    Vue.use(VueAxios, axios);
    if (process.env.MIX_ENVIROMENT == "production") {
      Vue.axios.defaults.baseURL = process.env.MIX_BASE_URL_PRODUCTION
    } else if (process.env.MIX_ENVIROMENT == "staging") {
      Vue.axios.defaults.baseURL = process.env.MIX_BASE_URL_STAGING
    } else {
      Vue.axios.defaults.baseURL = process.env.MIX_BASE_URL
    }
  },

  /**
   * Set the default HTTP request headers
   */
  setHeader() {
    Vue.axios.defaults.headers.common[
      "Authorization"
    ] = `Bearer ${JwtService.getToken()}`;
    Vue.axios.defaults.headers.common[
      "Accept"
    ] = `application/json`;
  },

  query(resource, params) {
    return Vue.axios.get(resource, params);
  },

  /**
   * Send the GET HTTP request
   * @param resource
   * @param slug
   * @returns {*}
   */
  get(resource, slug = "") {
    return Vue.axios.get(`${resource}/${slug}`);
  },

  /**
   * Set the POST HTTP request
   * @param resource
   * @param params
   * @returns {*}
   */
  post(resource, params) {
    return Vue.axios.post(`${resource}`, params);
  },

  /**
   * Send the UPDATE HTTP request
   * @param resource
   * @param slug
   * @param params
   * @returns {IDBRequest<IDBValidKey> | Promise<void>}
   */
  update(resource, slug, params) {
    return Vue.axios.put(`${resource}/${slug}`, params);
  },

  /**
   * Send the PUT HTTP request
   * @param resource
   * @param params
   * @returns {IDBRequest<IDBValidKey> | Promise<void>}
   */
  put(resource, params) {
    return Vue.axios.put(`${resource}`, params);
  },

  /**
   * Send the DELETE HTTP request
   * @param resource
   * @returns {*}
   */
  delete(resource) {
    return Vue.axios.delete(resource);
  }
};

export default ApiService;
