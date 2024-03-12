<template>
    <section class="my-account container">
        <h2 class="page-title">Orders</h2>
        <div class="row">
            <UserSidebar />
            <div class="col-lg-9">
                <div class="page-content my-account__orders-list">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#2416</td>
                                <td>October 1, 2023</td>
                                <td>On hold</td>
                                <td>$1,200.65 for 3 items</td>
                                <td><button class="btn btn-primary">VIEW</button></td>
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import UserSidebar from './UserSidebar.vue';

</script>
