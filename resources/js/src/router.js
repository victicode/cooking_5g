import Vue from "vue";
import Router from "vue-router";

import guest from './middlewares/guest'
import auth from './middlewares/auth'
import hasAccount from './middlewares/hasAccount'
import isAdmin from './middlewares/isAdmin'

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: "/",
      redirect: "/dashboard",
      component: () => import("@/view/layout/Layout"),
      children: [
        {
          path: "/dashboard",
          name: "dashboard",
          component: () => import("@/view/pages/dashboard/Dashboard.vue"),
          meta: {
            middleware: [
              auth,
              hasAccount
            ]
          },
        },
        {
          path: "/exchange-rate",
          name: "exchange-rate",
          component: () => import("@/view/pages/exchange_rate/ExchangeRate.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
        {
          path: "/to-actions",
          name: "to-actions",
          component: () => import("@/view/pages/actions/Layout.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
        {
          path: "/banks",
          name: "banks",
          component: () => import("@/view/pages/banks/Banks.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
        {
          path: "/bank-accounts",
          name: "bank-accounts",
          component: () => import("@/view/pages/bank_accounts/BankAccounts.vue"),
          meta: {
            middleware: [
                auth,
                hasAccount
            ]
          },
        },
        {
          path: "/personal/accounts",
          name: "accounts-personal",
          component: () => import("@/view/pages/accounts/Personal.vue"),
          meta: {
            middleware: [
                auth,
                hasAccount
            ]
          },
        },
        {
          path: "/company/accounts",
          name: "accounts-company",
          component: () => import("@/view/pages/accounts/Company.vue"),
          meta: {
            middleware: [
                auth,
                hasAccount
            ]
          },
        },
        {
          path: "/operations",
          name: "client-operations-new",
          component: () => import("@/view/pages/operations/client/NewOperation.vue"),
          meta: {
            middleware: [
              auth,
              hasAccount
            ]
          },
        },
        {
          path: "/my-operations",
          name: "client-my-operations",
          component: () => import("@/view/pages/operations/client/MyOperations.vue"),
          meta: {
            middleware: [
              auth,
              hasAccount
            ]
          },
        },
        // {
        //   path: "/operations/manual",
        //   name: "admin-operations-manual",
        //   component: () => import("@/view/pages/operations/admin/NewManualOperation.vue"),
        //   meta: {
        //     middleware: [
        //       auth,
        //       isAdmin,
        //       hasAccount
        //     ]
        //   },
        // },
        {
          path: "/all-operations",
          name: "admin-all-operations",
          component: () => import("@/view/pages/operations/admin/AllOperations.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
        {
          path: "/profiles",
          name: "profiles",
          component: () => import("@/view/pages/profiles/Profiles.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
        {
          path: "/profiles/:id",
          name: "profile-settings",
          component: () => import("@/view/pages/profiles/ProfileSetting.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
        {
          path: "/report-sbs",
          name: "report-sbs",
          component: () => import("@/view/pages/reports/sbs-report.vue"),
          meta: {
            middleware: [
              auth,
              isAdmin,
              hasAccount
            ]
          },
        },
      ]
    },
    
    // CREATE ACCOUNTS ROUTES
    {
      path: "/create-account",
      name: "create-account",
      
      component: () => import("@/view/pages/create_account/Layout.vue"),
    },
    {
      path: '/login',
      name: 'login',
      meta: {
        middleware: [
            guest,
        ]
      },
      component: () => import("@/view/pages/auth/login/Login.vue")
    },
    // RESET PASSWORD ROUTE
    {
      path: '/password/reset/:token',
      component: () => import("@/view/pages/auth/reset_password/ResetPassword.vue"),
      
    },
    // SOCIAL LOGIN
    {
      path: '/auth/:provider/callback',
      name: 'Social',
      meta: {
        middleware: [
            guest,
        ]
      },
      component: () => import("@/view/pages/auth/login/SocialLogin.vue")
    },
    {
      path: '/auth/email/verify/:id',
      name: 'email_verify',
      component: () => import("@/view/pages/auth/email_verify/EmailVerify.vue")
    },
    // 404 ROUTEs
    {
      path: "*",
      redirect: "/404",
    },
    // 404 ROUTEs
    {
      // the 404 route, when none of the above matches
      path: "/404",
      name: "404",
      component: () => import("@/view/pages/error/404.vue"),
    },
    {
      // the 403 route, no permissions
      path: "/403",
      name: "403",
      component: () => import("@/view/pages/error/403.vue"),
    }
  ]
});
