<template>
  <div class="col-lg-3">
    <ul class="account-nav">
      <li><router-link :to="{ name: 'account' }" class="menu-link menu-link_us-s" :class="{ 'menu-link_active': $route.path === '/account' }">Dashboard</router-link>
      </li>
      <li><router-link :to="{ name: 'order' }" class="menu-link menu-link_us-s" :class="{ 'menu-link_active': $route.path === '/order' }">Orders</router-link></li>
      <li><a class="menu-link menu-link_us-s">Addresses</a></li>
      <li><a class="menu-link menu-link_us-s">Account Details</a></li>
      <li><a class="menu-link menu-link_us-s">Wishlist</a></li>
      <li><a class="menu-link menu-link_us-s">Logout</a></li>
    </ul>
  </div>
</template>