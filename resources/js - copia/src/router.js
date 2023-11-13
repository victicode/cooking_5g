
import {createRouter, createWebHistory} from "vue-router";

import guest from './middlewares/guest'
import auth from './middlewares/auth'
import hasAccount from './middlewares/hasAccount'
import isAdmin from './middlewares/isAdmin'
import middlewarePipeline from './middlewares/middlewarePipeline'


const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name:"home",
      redirect: "/dashboard",
      component: () => import("@/view/layout/Layout.vue"),
      children: [
        {
          path: "/dashboard",
          name: "dashboard",
          component: () => import("@/view/pages/dashboard/Dashboard.vue"),
          meta: {
            middleware: [
              auth
            ],
            title : 'Dashboard'
          },
        },
        {
          path: "/products",
          name: "products",
          component: () => import("@/view/pages/products/Products.vue"),
          meta: {
            middleware: [
              auth
            ],
            title : 'Productos'
          },
        },
      ]
    },
    {
      path: "/login",
      name: "Login",
      component: () => import("@/view/pages/auth/login/Login.vue"),
      meta: {
        middleware: [
          guest
        ]
      },
    },
    
    // // CREATE ACCOUNTS ROUTES
    // {
    //   path: "/create-account",
    //   name: "create-account",
      
    //   component: () => import("@/view/pages/create_account/Layout.vue"),
    // },
    // {
    //   path: '/login',
    //   name: 'login',
    //   meta: {
    //     middleware: [
    //         guest,
    //     ]
    //   },
    //   component: () => import("@/view/pages/auth/login/Login.vue")
    // },
    // // RESET PASSWORD ROUTE
    // {
    //   path: '/password/reset/:token',
    //   component: () => import("@/view/pages/auth/reset_password/ResetPassword.vue"),
      
    // },
    // // SOCIAL LOGIN
    // {
    //   path: '/auth/:provider/callback',
    //   name: 'Social',
    //   meta: {
    //     middleware: [
    //         guest,
    //     ]
    //   },
    //   component: () => import("@/view/pages/auth/login/SocialLogin.vue")
    // },
    // {
    //   path: '/auth/email/verify/:id',
    //   name: 'email_verify',
    //   component: () => import("@/view/pages/auth/email_verify/EmailVerify.vue")
    // },
    // // 404 ROUTEs
    // {
    //   path: "*",
    //   redirect: "/404",
    // },
    // // 404 ROUTEs
    // {
    //   // the 404 route, when none of the above matches
    //   path: "/404",
    //   name: "404",
    //   component: () => import("@/view/pages/error/404.vue"),
    // },
    // {
    //   // the 403 route, no permissions
    //   path: "/403",
    //   name: "403",
    //   component: () => import("@/view/pages/error/403.vue"),
    // }
  ]
});


export default router
