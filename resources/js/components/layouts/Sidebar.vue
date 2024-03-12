<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <img :src="'/storage/logo/' + authUser?.setting.logo" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ authUser?.setting.sitename }}</span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img :src="'/storage/users/' + authUser?.user.image" class="img-circle elevation-2"
                        :alt="authUser?.user.name">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ authUser?.user.email }}</a>
                </div>
            </div>



            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <router-link to="/dashboard" class="nav-link" :class="{ 'active': $route.path === '/dashboard' }">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p> Dashboard </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/category" class="nav-link"
                            :class="{ 'active': $route.path === '/admin/category' }">
                            <i class="nav-icon fas fa-th"></i>
                            <p> Category </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/subcategory" class="nav-link"
                            :class="{ 'active': $route.path === '/admin/subcategory' }">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Subcategory</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/product" class="nav-link"
                            :class="{ 'active': $route.path === '/admin/product' }">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Products</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/appointment" class="nav-link"
                            :class="{ 'active': $route.path === '/admin/appointment' }">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Appointment</p>
                        </router-link>
                    </li>


                    <li class="nav-item">
                        <router-link to="/admin/users" class="nav-link"
                            :class="{ 'active': $route.path === '/admin/users' }">
                            <i class="nav-icon fas fa-users"></i>
                            <p> Users </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/setting" class="nav-link"
                            :class="{ 'active': $route.path === '/admin/setting' }">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Site Setting</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p> Setting<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/admin/setting" class="nav-link"
                                    :class="{ 'active': $route.path === '/admin/setting' }">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Site Setting</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Inactive Page</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>
</template>


<script setup>
import { useAuthUserStore } from '@/store/authUserStore';
const authUser = useAuthUserStore();
</script>