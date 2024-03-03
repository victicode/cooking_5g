
import { createStore } from "vuex";

import auth from "./auth.module";
import user from "./user.module"
import order from "./order.module"
import product from "./product.module"
import recipe from "./recipe.module"
import notification from "./notification.module"

const store = createStore({
  modules:{
    auth,
    order,
    product,
    recipe,
    user,
    notification
  }
})

export default store
