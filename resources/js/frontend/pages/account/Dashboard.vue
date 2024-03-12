<template>
<div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">My Account</h2>
      <div class="row">
        <UserSidebar />

        <div class="col-lg-9">
          <div class="page-content my-account__dashboard">
            <p>Hello <strong>{{ customerStore.user.email }}</strong> (not <strong>alitfn58?</strong> <a href="">Log out</a>)</p>
            <p>From your account dashboard you can view your <a class="unerline-link" href="account_orders.html">recent orders</a>, manage your <a class="unerline-link" href="account_edit_address.html">shipping and billing addresses</a>, and <a class="unerline-link" href="account_edit.html">edit your password and account details.</a></p>
          </div>
        </div>
      </div>
    </section>
</template>

<script setup>
import UserSidebar from './UserSidebar.vue';
import useCustomerStore from '@/store/Customer'

const customerStore = useCustomerStore();

</script>
