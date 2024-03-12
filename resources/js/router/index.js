import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("@/components/user/Homepage.vue"),
        meta: { guest: true },
    },
    {
        path: "/about",
        name: "about",
        component: () => import("@/components/user/About.vue"),
        meta: { guest: true },
    },
    {
        path: "/testDashboard",
        name: "test.dashboard",
        component: () => import("@/components/TestDashboard.vue"),
        meta: { guest: true },
    },


    {
        // path: "/homepage",
        // name: "master",
        // children: [
        //     {
        //         path: "/test",
        //         name: "admin.test",
        //         component: () => import("@/components/admin/pages/TestTheme.vue"),
        //         meta: { guest: true },
        //     },
        //     {
        //         component: () => import("@/components/user/Homepage.vue"),
        //         meta: { guest: true },
        //     },
        //     {
        //         name: "",
        //         path: "",
        //         component: () => import('@/components/user/layouts/Cards.vue'),
        //         meta: { guest: true }
        //     },
        // ],

        path:"/admin",
        children: [
            {
                path: "login",
                name: "login",
                component: () => import("@/components/admin/pages/Login.vue"),
                meta: { guest: true },
            },
            {
                path: "dashboard",
                name: "dashboard",
                component: () => import("@/components/admin/pages/Dashboard.vue"),
                meta: { auth: true },
            },
            {
                path: "users",
                name: "users",
                component: () => import("@/components/admin/pages/users/Users.vue"),
                meta: { auth: true },
            },
            {
                path: "user",
                name: "user.add",
                component: () => import("@/components/admin/pages/users/Manage.vue"),
                meta: { auth: true },
            },
            {
                path: "user/:id",
                name: "user.edit",
                component: () => import("@/components/admin/pages/users/Manage.vue"),
                meta: { auth: true },
            },

            {
                path: 'category',
                name: 'category',
                component: () => import('@/components/admin/pages/category/Category.vue'),
                meta: { auth: true }
            },
            {
                path: 'category',
                name: 'category.add',
                component: () => import('@/components/admin/pages/category/Manage.vue'),
                meta: { auth: true }
            },
            {
                path: 'category/:id',
                name: 'category.edit',
                component: () => import('@/components/admin/pages/category/Manage.vue'),
                meta: { auth: true }
            }

        ],




        // path: "/admin",
        // children: [
        //     {
        //         path: "login",
        //         component: () => import("@/components/admin/pages/Login.vue"),
        //         name: "login",
        //         meta: { guest: true },
        //     },
        //     {
        //         path: "",
        //         component: () => import('@/layout/AppLayout.vue'),
        //         children: [
        //             {
        //                 path: "",
        //                 component: () => import("@/pages/Dashboard.vue"),
        //                 name: "dashboard",
        //                 meta: { auth: true },
        //             },
        //             {
        //                 path: "users",
        //                 component: () => import("@/pages/users/Index.vue"),
        //                 name: "users",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "users/create",
        //                 component: () => import("@/pages/users/Manage.vue"),
        //                 name: "users.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "users/:id/edit",
        //                 component: () => import("@/pages/users/Manage.vue"),
        //                 name: "users.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "users/:id/view",
        //                 component: () => import("@/pages/users/View.vue"),
        //                 name: "users.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "professions",
        //                 component: () => import("@/pages/professions/Index.vue"),
        //                 name: "professions",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "professions/create",
        //                 component: () => import("@/pages/professions/Manage.vue"),
        //                 name: "professions.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "professions/:id/edit",
        //                 component: () => import("@/pages/professions/Manage.vue"),
        //                 name: "professions.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "professions/:id/view",
        //                 component: () => import("@/pages/professions/View.vue"),
        //                 name: "professions.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "disciplines",
        //                 component: () => import("@/pages/disciplines/Index.vue"),
        //                 name: "disciplines",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "disciplines/create",
        //                 component: () => import("@/pages/disciplines/Manage.vue"),
        //                 name: "disciplines.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "disciplines/:id/edit",
        //                 component: () => import("@/pages/disciplines/Manage.vue"),
        //                 name: "disciplines.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "disciplines/:id/view",
        //                 component: () => import("@/pages/disciplines/View.vue"),
        //                 name: "disciplines.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "sponsors",
        //                 component: () => import("@/pages/sponsors/Index.vue"),
        //                 name: "sponsors",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "sponsors/create",
        //                 component: () => import("@/pages/sponsors/Manage.vue"),
        //                 name: "sponsors.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "sponsors/:id/edit",
        //                 component: () => import("@/pages/sponsors/Manage.vue"),
        //                 name: "sponsors.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "sponsors/:id/view",
        //                 component: () => import("@/pages/sponsors/View.vue"),
        //                 name: "sponsors.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "configurations/languages",
        //                 component: () => import("@/pages/languages/Index.vue"),
        //                 name: "languages",
        //             },
        //             {
        //                 path: "participant-roles",
        //                 component: () => import("@/pages/participant_roles/Index.vue"),
        //                 name: "participant_roles",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "participant-roles/create",
        //                 component: () => import("@/pages/participant_roles/Manage.vue"),
        //                 name: "participant_roles.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "participant-roles/:id/edit",
        //                 component: () => import("@/pages/participant_roles/Manage.vue"),
        //                 name: "participant_roles.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "participant-roles/:id/view",
        //                 component: () => import("@/pages/participant_roles/View.vue"),
        //                 name: "participant_roles.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-types",
        //                 component: () => import("@/pages/payment_types/Index.vue"),
        //                 name: "payment_types",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-types/create",
        //                 component: () => import("@/pages/payment_types/Manage.vue"),
        //                 name: "payment_types.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-types/:id/edit",
        //                 component: () => import("@/pages/payment_types/Manage.vue"),
        //                 name: "payment_types.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-types/:id/view",
        //                 component: () => import("@/pages/payment_types/View.vue"),
        //                 name: "payment_types.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-codes",
        //                 component: () => import("@/pages/payment_codes/Index.vue"),
        //                 name: "payment_codes",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-codes/create",
        //                 component: () => import("@/pages/payment_codes/Manage.vue"),
        //                 name: "payment_codes.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-codes/:id/edit",
        //                 component: () => import("@/pages/payment_codes/Manage.vue"),
        //                 name: "payment_codes.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "payment-codes/:id/view",
        //                 component: () => import("@/pages/payment_codes/View.vue"),
        //                 name: "payment_codes.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "banks",
        //                 component: () => import("@/pages/banks/Index.vue"),
        //                 name: "banks",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "banks/create",
        //                 component: () => import("@/pages/banks/Manage.vue"),
        //                 name: "banks.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "banks/:id/edit",
        //                 component: () => import("@/pages/banks/Manage.vue"),
        //                 name: "banks.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "banks/:id/view",
        //                 component: () => import("@/pages/banks/View.vue"),
        //                 name: "banks.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "email-accounts",
        //                 component: () => import("@/pages/email_accounts/Index.vue"),
        //                 name: "email_accounts",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "email-accounts/create",
        //                 component: () => import("@/pages/email_accounts/Manage.vue"),
        //                 name: "email_accounts.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "email-accounts/:id/edit",
        //                 component: () => import("@/pages/email_accounts/Manage.vue"),
        //                 name: "email_accounts.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "email-accounts/:id/view",
        //                 component: () => import("@/pages/email_accounts/View.vue"),
        //                 name: "email_accounts.view",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "user-companions",
        //                 component: () => import("@/pages/user_companions/Index.vue"),
        //                 name: "user_companions",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "user-companions/create",
        //                 component: () => import("@/pages/user_companions/Manage.vue"),
        //                 name: "user_companions.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "user-companions/:id/edit",
        //                 component: () => import("@/pages/user_companions/Manage.vue"),
        //                 name: "user_companions.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "user-companions/:id/view",
        //                 component: () => import("@/pages/user_companions/View.vue"),
        //                 name: "user_companions.view",
        //                 meta: { auth: true},
        //             },

        //             { // event
        //                 path: "event-types",
        //                 component: () => import("@/pages/event_types/Index.vue"),
        //                 name: "event-types",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "event-types/create",
        //                 component: () => import("@/pages/event_types/Manage.vue"),
        //                 name: "event-types.create",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "event-types/:id/edit",
        //                 component: () => import("@/pages/event_types/Manage.vue"),
        //                 name: "event-types.edit",
        //                 meta: { auth: true},
        //             },
        //             {
        //                 path: "event-types/:id/view",
        //                 component: () => import("@/pages/event_types/View.vue"),
        //                 name: "event-types.view",
        //                 meta: { auth: true},
        //             },
        //         ],
        //     },
        //     {
        //         path: ":pathMatch(.*)*",
        //         component: () => import("@/pages/NotFound.vue"),
        //     },
        // ],
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, form, next) => {
    handleRouteMiddlwares(to, form, next);
});

const handleRouteMiddlwares = async (to, from, next) => {
    const authStore = useAuthStore();
    if (to.meta.auth && !authStore.isAuthenticated) {
        next({ name: "login" });
    }

    if (to.meta.auth && !authStore.user) {
       let response =  await authStore.getUserDetail();
         if (!response.success) {
            authStore.flushUser();
            next({ name: "login" });
         }
    }

    if (to.meta.guest && authStore.isAuthenticated) {
        if (authStore.user) {
            next({ name: "dashboard" });
        }
        let response =  await authStore.getUserDetail();
        if (response.success) {
            next({ name: "dashboard" });
        }else{
            authStore.flushUser();
        }
    }

    next();
}

export default router;
