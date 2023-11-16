
import { createStore } from "vuex";

import auth from "./auth.module";
import user from "./user.module"
import order from "./order.module"

const store = createStore({
  modules:{
    auth,
    user,
    order
  }
})

export default store
