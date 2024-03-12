<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">TechvBlogs</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link to="/" class="nav-link">Home </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link :to="{ name: 'about' }" class="nav-link">About Us </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link :to="{ name: 'contact' }" class="nav-link">Contact</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'company.index' }" class="nav-link">Company </router-link>
                        </li>
                    </ul>
                    <ul class="navbar-nav me-end" v-if="authUser.user.name === ''">
                        <li class="nav-item">
                            <router-link to="/login" class="btn btn-sm btn-primary">Login </router-link>
                        </li>
                    </ul>
                    <div class="d-flex" v-else>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ authUser.user.name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                                    <router-link class="dropdown-item" to="/dashboard">Dashboard</router-link>                               
                                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <main class="mt-3">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
import '../../../css/user.css';
import '../../../css/user.js';
import { useAuthUserStore } from '../../store/authUserStore'
export default {
    name: "default-layout",

    setup(){
       const authUser = useAuthUserStore();
       return{
        authUser
       }
    },
    methods: {
        async logout() {
            await axios.post('/api/logout').then(({ data }) => {
                this.authUser.logout();
                this.authUser.initializeStore();
                this.$router.push({ name: "login" });
            })
        }
    }
}
</script>