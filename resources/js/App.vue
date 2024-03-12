<template>
<!-- frontend -->
  <div v-if="!authUser.isAuthenticated">

    <div class="min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-white dark:bg-gray-700 text-black dark:text-white">
      <Header />
      <router-view />
      </div>
  </div>

<!-- backend -->
  <div v-else class="min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-white dark:bg-gray-700 text-black dark:text-white">
    <Topbar />
    <Sidebar />

    <div class="h-full ml-14 mt-14 mb-10 md:ml-64">
      <RouterView />
    </div>
  </div>


</template>

<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Header from '@/components/user/layouts/Header.vue';
import Homepage from '@/components/user/Homepage.vue';
import Sidebar from './components/admin/layouts/Sidebar.vue';
import Topbar from './components/admin/layouts/Topbar.vue';

const authUser = useAuthStore();

const frontend = ref(false);

</script>
