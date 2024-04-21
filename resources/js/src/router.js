
import {createRouter, createWebHistory} from "vue-router";

import guest from './middlewares/guest'
import auth from './middlewares/auth'
import isAdmin from './middlewares/isAdmin'
import middlewarePipeline from './middlewares/middlewarePipeline'

// components
import defaultComponent from '@/layouts/default.vue';
import productsComponent from '@/pages/products.vue';
// import productsClientComponent from '@/pages/client/products.vue';
import ordersComponent from '@/pages/orders.vue';
import recipesComponent from '@/pages/recipes.vue';
import clientComponent from '@/pages/users/client.vue';
import chefComponent from '@/pages/users/chef.vue';
import accountSettings from  '@/pages/account-settings.vue'
// import recipeQrView from  '@/pages/recipe-qr-view.vue'


const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/dashboard' },
    {
      path: "/",
      name:"home",
      component: defaultComponent,
      children: [
        {
          name: "dashboard",
          path: 'dashboard',
          component: () => import('@/pages/dashboard.vue'),
          meta: {
            middleware: [
              auth
            ],
            title : 'Dashboard'
          },
        },
        {
          path: "/products",
          component: productsComponent,
          meta: {
            middleware: [
              auth,
              
            ],
            title : 'Productos'
          },
        },
        // {
        //   path: "/products_client",
        //   component: productsClientComponent,
        //   meta: {
        //     middleware: [
        //       auth,
        //     ],
        //     title : 'Productos'
        //   },
        // },
        // {
        //   path: "/products_2",
        //   component: products_2Component,
        //   meta: {
        //     middleware: [
        //       auth
        //     ],
        //     title : 'Productos'
        //   },
        // },

        {
          path: "/orders",
          component: ordersComponent,
          meta: {
            middleware: [
              auth,
            ],
            title : 'Ordenes'
          },
        },
        {
          path: "/user-list",
          component: clientComponent,
          meta: {
            middleware: [
              auth,
              isAdmin
            ],
            title : 'Listado de clientes'
          },
        },
        {
          path: "/chef-list",
          component: chefComponent,
          meta: {
            middleware: [
              auth,
              isAdmin
            ],
            title : 'Listado de chefs'
          },
        },
        {
          path: "/recipes",
          component: recipesComponent,
          meta: {
            middleware: [
              auth
            ],
            title : 'Recetas'
          },
        },
        {
          path: '/acount-settings',
          component: accountSettings,
          meta: {
            middleware: [
              auth
            ],
            title : 'Ajustes de perfil',
          },
        },
        {
          path: 'typography',
          component: () => import('@/pages/typography.vue'),
        },
        {
          path: 'icons',
          component: () => import('@/pages/icons.vue'),
        },
        {
          path: 'cards',
          component: () => import('@/pages/cards.vue'),
        },
        {
          path: 'tables',
          component: () => import('@/pages/tables.vue'),
        },
        {
          path: 'form-layouts',
          component: () => import('@/pages/form-layouts.vue'),
        },
      ]
    },
    {
      path: "/login",
      name: "Login",
      component: () => import('@/pages/logins.vue'),
      meta: {
        title: 'Bienvenido'
      },
    },
    {
      path: "/client/recipe/:id",
      name: "Receta",
      component: () => import('@/pages/recipe-qr-view.vue'),
      meta: {
        title: 'Receta - '
      },
    },
    {
      path: '/404',
      component: () => import('@/pages/[...all].vue'),
      meta: {
        title : 'OPPS! 404',
      },
    },
    {
      path: '/:pathMatch(.*)*',
      component: () => import('@/pages/[...all].vue'),
      meta: {
        title : 'OPPS! 404',
      },
    },
    // 404 ROUTEs
    // {
    //   path: "*",
    //   redirect: "/404",
    // },
    // // 404 ROUTEs
    // {
    //   // the 404 route, when none of the above matches
    //   path: "/404",
    //   name: "404",
    //   component: () => import('./pages/[...all].vue'),
    // },
    // {
    //   // the 403 route, no permissions
    //   path: "/403",
    //   name: "403",
    //   component: () => import("@/view/pages/error/403.vue"),
    // }
    
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
