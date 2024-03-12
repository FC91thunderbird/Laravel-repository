<template>
    <Navbar />
    <main>
        <router-view></router-view>
    </main>
    <Footer />
</template>

<script setup>
import '../../../css/user.css';
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';

import '../../../css/user.js';

</script>