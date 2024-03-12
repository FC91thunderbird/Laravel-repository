import { createWebHistory, createRouter } from 'vue-router'
// import store from '@/store'
import { useAuthUserStore } from '@/store/authUserStore'
import useCartData from '@/store/CartData';
import useCustomerStore from '@/store/Customer'


const routes = [
    {
        path: "/",
        name: "home",
        component: () => import('@/frontend/components/Default.vue'),
        meta: {
            middleware: "guest",
            title: `Home`
        },
        children: [
            {
                path: '/',
                name: "homepage",
                component: () => import('@/frontend/pages/Home.vue'),
                meta: {
                    middleware: "user",
                    title: `Homepage`
                }
            },
            {
                path:'/cart',
                name: 'cart',
                component: ()=> import('@/frontend/pages/Cart.vue'),
                meta:{
                    middleware: 'user',
                    title: 'Cart'
                }
            },
            { // Shop Page
                path:'/shop',
                name:'shop',
                component: () => import('@/frontend/pages/Shop.vue'),
                meta:{
                    middleware: 'user',
                    title: 'Shop Page'
                }
            },
            {
                path:'/checkout',
                name:'checkout',
                component: () => import('@/frontend/pages/Checkout.vue'),
                meta:{
                    middleware:'user',
                    title: 'Checkout Page'
                }
            },

            { // user login
                path: '/login',
                name: 'login',
                component: () => import('@/frontend/pages/Login.vue'),
                meta: {
                    middleware: 'user',
                    title: "User Login"
                }
            },
            { // Account
                path:'/account',
                name: 'account',
                component: () =>import('@/frontend/pages/account/Dashboard.vue'),
                meta: {
                    middleware: 'user',
                    title: 'Account Dashboard',
                }
            },
            { // Orders
                path: 'order',
                name: 'order',
                component: () => import('@/frontend/pages/account/Orders.vue'),
                meta:{
                    middleware: 'user',
                    title: 'Orders'
                }
            },

            {
                path: "/about",
                name: "about",
                component: () => import('@/components/pages/frontend/About.vue'),
                meta: {
                    middleware: "user",
                    title: 'About Us'
                },
            },
            {
                path: "/contact",
                name: "contact",
                component: () => import('@/components/pages/frontend/Contact.vue'),
                meta: {
                    middleware: "user",
                    title: "Contact Us"
                }
            },
            {
                path: '/company',
                name: 'company.index',
                component: () => import('@/components/company/Companies.vue')
            },

            {
                path: '/companies/create',
                name: 'companies.create',
                component: () => import('@/components/company/createCompany.vue')
            },
            {
                path: '/companies/:id/edit',
                name: 'companies.edit',
                component: () => import('@/components/company/editCompany.vue'),
                props: true
            },
        ]
    },


    {
        path: "/admin/login",
        name: "admin.login",
        component: () => import('@/components/pages/Login.vue'),
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        path: "/register",
        name: "register",
        component: () => import('@/components/pages/Register.vue'),
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/dashboard",
        name: 'dashboard',
        component: () => import('@/App.vue'),
        meta: {
            middleware: "auth",
            requiresAuth: true
        },
        children: [
            {
                name: "dashboard",
                path: '/dashboard',
                component: () => import('@/components/pages/Dashboard.vue'),
                meta: {
                    title: `Dashboard`,
                    requiresAuth: true
                }
            },

            { //category
                path: '/admin/category',
                name: 'admin.category',
                component: () => import('@/components/pages/backend/category/Category.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Category List',
                    requiresAuth: true
                }
            },
            {
                path: '/admin/addcategory',
                name: 'admin.addcategory',
                component: () => import('@/components/pages/backend/category/addCategory.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Add Category',
                    requiresAuth: true
                }
            },
            {
                path: '/admin/category/:id',
                name: 'admin.editCat',
                component: () => import('@/components/pages/backend/category/addCategory.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Edit Category',
                    requiresAuth: true
                }
            },

            { //subcategory
                path: '/admin/subcategory',
                name: 'admin.subcategory',
                component: () => import('@/components/pages/backend/subcategory/Subcategory.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Sub-Category List',
                    requiresAuth: true
                }
            },
            // {
            //     path: '/admin/editsubcategory/:id',
            //     name: 'admin.editSubcat',
            //     component: ()=> import('@/components/pages/backend/subcategory/Subcategory.vue'),
            //     meta:{
            //         middleware:'auth',
            //         title: 'Sub-Category List',
            //         requiresAuth: true 
            //     }
            // },

            { //Product
                path: '/admin/product',
                name: 'admin.product',
                component: () => import('@/components/pages/backend/product/Products.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Products List',
                    requiresAuth: true
                }
            },
            {
                path: '/admin/addproduct',
                name: 'admin.addProduct',
                component: () => import('@/components/pages/backend/product/addProduct.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Products List',
                    requiresAuth: true
                }
            },
            {
                path: '/admin/editproduct/:id',
                name: 'admin.editProduct',
                component: () => import('@/components/pages/backend/product/addProduct.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Products List',
                    requiresAuth: true
                }
            },


            { // users
                path: '/admin/users',
                name: 'admin.users',
                component: () => import('@/components/pages/backend/users/Users.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Users List',
                    requiresAuth: true
                }
            },
            {
                path: '/admin/addUpdate',
                name: 'admin.adduser',
                component: () => import('@/components/pages/backend/users/addUser.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Users Add or Update',
                    requiresAuth: true
                }
            },
            {
                path: '/admin/useredit/:id',
                name: 'admin.userEdit',
                component: () => import('@/components/pages/backend/users/addUser.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Users Add or Update',
                    requiresAuth: true
                }
            },

            {
                path: '/admin/setting',
                name: 'admin.setting',
                component: () => import('@/components/pages/backend/setting/Setting.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Site Settings',
                    requiresAuth: true
                }
            },

            {
                path: '/admin/profile',
                name: 'admin.profile',
                component: () => import('@/components/pages/backend/setting/Profile.vue'),
                meta: {
                    middleware: 'auth',
                    title: 'Admin Profile',
                    requiresAuth: true
                }
            },

            { // appointment
                path:'/admin/appointment',
                name: 'admin.appointment',
                component: () => import('@/components/pages/backend/appointment/Appointments.vue'),
                meta:{
                    middleware: 'auth',
                    title: 'Appointment',
                    requireAuth: true,
                }
            },
            {
                path:'/admin/add_appointment',
                name:'admin.addAppointment',
                component: ()=> import('@/components/pages/backend/appointment/addAppointment.vue'),
                meta:{
                    title: 'Add Appointment',
                    requireAuth: true,
                }
            },
            {
                path:'/admin/edit_appointment/:id',
                name: 'admin.editAppointment',
                component: () => import('@/components/pages/backend/appointment/addAppointment.vue'),
                meta: {
                    title: 'Edit Appointment',
                    requireAuth:true
                }
            },
        ]
    },

    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('@/components/layouts/NotFound.vue')
    }
]

const router = createRouter({
    mode: 'history',
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from) => {
    const cartData = useCartData();
    const customer = useCustomerStore();
    const authUserStore = useAuthUserStore();
    if(to.meta.middleware === 'user'){
        await Promise.all([
            cartData.fetchCartItem(),
            // customer.getCustomer(),
        ])
    }
    if(customer.isCustomer  && to.name ==='login' ){
        router.push({ name: 'homepage' })
    }
    // if (authUserStore.user.name === '' && to.name !== 'login') {
    //     await Promise.all([
    //         authUserStore.getAuthUser(),
    //     ]);
    // }
});


export default router