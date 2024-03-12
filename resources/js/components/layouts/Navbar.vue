<template>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <div class="user-panel d-flex">
            <div class="image">
              <img :src="'/storage/users/' + authUser.user.image" class="img-circle elevation-2" alt="User Image">
            </div>

            <div class="info">{{ authUser?.user.name }}</div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <router-link to="/admin/profile" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> Profile
          </router-link>
          <div class="dropdown-divider"></div>
          <a href="javascript:void(0)" @click="logout" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { useAuthUserStore } from '@/store/authUserStore';
const authUser = useAuthUserStore();
import router from '@/router'

     const logout = () => {
      try {
        // const storedToken = localStorage.getItem('access_token');
        // if (!storedToken) {
        //   console.error('Access token not found.');
        //   return;
        // }

        const response = axios.post('/api/logout');

        // Perform any necessary cleanup and store management
        authUser.logout();
        // authUser.initializeStore(); // Re-initialize the store
        // localStorage.removeItem('access_token');

        // Redirect to the login page
        router.push({ name: "login" });

      } catch (error) {
        console.error('Logout error:', error);
      }
    }


</script>