
import { createStore } from "vuex";

import auth from "./auth.module";
import user from "./user.module"
import order from "./order.module"
import product from "./product.module"
import recipe from "./recipe.module"
import notification from "./notification.module"
import cart from "./cart.module"
import message from "./message.module"
import chat from "./chat.module"


const store = createStore({
  modules:{
    auth,
    order,
    product,
    recipe,
    user,
    notification,
    cart,
    message,
    chat, 
  }
})

export default store
