import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import store from "@/store";
import { Mutations, Actions } from "@/store/enums/StoreEnums";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    redirect: "/dashboard",
    component: () => import("@/layout/Layout.vue"),
    children: [
      {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("@/views/Dashboard.vue")
      },
      {
        path: "/crafted/account",
        name: "account",
        component: () => import("@/views/account/Account.vue"),
        children: [
          {
            path: "overview",
            name: "account-overview",
            component: () => import("@/views/account/Overview.vue")
          },
          {
            path: "settings",
            name: "account-settings",
            component: () => import("@/views/account/Settings.vue")
          },
          {
            path: "password",
            name: "account-password",
            component: () => import("@/views/account/Password.vue")
          }
        ]
      },
      {
        path: "/admins/list",
        name: "admins-list",
        component: () => import("@/views/admins/list.vue")
      },
      {
        path: "/admins/create",
        name: "admins-create",
        component: () => import("@/views/admins/create.vue")
      },
      {
        path: "/admins/update/:id",
        name: "admin-update",
        props: true,
        component: () => import("@/views/admins/update.vue")
      },
      {
        path: "/admins/history/:id",
        name: "admin-history",
        props: true,
        component: () => import("@/views/admins/history.vue")
      },
      {
        path: "/users/list",
        name: "users-list",
        component: () => import("@/views/users/list.vue")
      },
      {
        path: "/users/create",
        name: "users-create",
        component: () => import("@/views/users/create.vue")
      },
      {
        path: "/users/update/:id",
        name: "users-update",
        props: true,
        component: () => import("@/views/users/update.vue")
      },
      {
        path: "/users/history/:id",
        name: "users-history",
        props: true,
        component: () => import("@/views/users/history.vue")
      },
      {
        path: "/phones/import",
        name: "phones-import",
        component: () => import("@/views/phones/import.vue")
      },
      {
        path: "/phones/list",
        name: "phones-list",
        component: () => import("@/views/phones/list.vue")
      },
      {
        path: "/phones/update/:id",
        name: "phones-update",
        props: true,
        component: () => import("@/views/phones/update.vue")
      },
      {
        path: "/tags/list",
        name: "tags-list",
        component: () => import("@/views/tags/list.vue")
      },
      {
        path: "/tags/create",
        name: "tags-create",
        component: () => import("@/views/tags/create.vue")
      },
      {
        path: "/tags/update/:id",
        name: "tags-update",
        props: true,
        component: () => import("@/views/tags/update.vue")
      },
      {
        path: "/report",
        name: "report",
        component: () => import("@/views/reports/report.vue")
      },
      {
        path: "/picking/list",
        name: "picking-list",
        component: () => import("@/views/picking/list.vue")
      },
    ]
  },
  {
    path: "/sign-in",
    name: "sign-in",
    component: () => import("@/views/auth/SignIn.vue")
  },
  {
    // the 404 route, when none of the above matches
    path: "/404",
    name: "404",
    component: () => import("@/views/error/Error404.vue")
  },
  {
    path: "/:pathMatch(.*)*",
    redirect: "/404"
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(() => {
  // reset config to initial state
  store.commit(Mutations.RESET_LAYOUT_CONFIG);

  store.dispatch(Actions.VERIFY_AUTH);

  // check if current user is authenticated
  // if (!store.getters.isUserAuthenticated) {
  //   router.push({ name: "sign-in" });
  // }

  // Scroll page to top on every route change
  setTimeout(() => {
    window.scrollTo(0, 0);
  }, 100);
});

export default router;
