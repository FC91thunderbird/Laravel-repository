<script>
import Navbar from '@/components/layouts/Navbar.vue';
import Sidebar from '@/components/layouts/Sidebar.vue'
import Footer from '@/components/layouts/Footer.vue'

import { useAuthUserStore } from '@/store/authUserStore'

export default {
    name: "App",
    components: { Navbar, Sidebar, Footer },

    setup() {
        const auth = useAuthUserStore();
        return {
            auth
        }
    },
}
</script>

<template>
    <div class="wrapper" v-if="auth.user.name !== ''">
        <Navbar />
        <Sidebar />
        <div class="content-wrapper">
            <div class="content pt-4">
                <div class="container-fluid">
                        <router-view />
                </div>
            </div>
        </div>
        <Footer />
    </div>

    <div v-else>
        <router-view />
    </div>
</template>