<template>
    <section class="shop-checkout container">
        <div class="shopping-cart">
            <div class="cart-table__wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
<!-- {{ cartStore.totalItems }} -->
                    <tbody v-if="Object.keys(cartStore.cartItems).length > 0">
                        <tr v-for="cart in cartStore.cartItems" :key="cart.id">
                            <td>
                                <div class="shopping-cart__product-item">
                                    <img loading="lazy" :src='"/storage/product/"+ cart.image' :width="120" :height="120" :alt="cart.title">
                                </div>
                            </td>
                            <td>
                                <div class="shopping-cart__product-item__detail">
                                    <h4>{{ cart.title }}</h4>
                                    <ul class="shopping-cart__product-item__options">
                                        <li>Category: {{ cart.category }}</li>
                                        <li>SubCategory: {{ cart.subcategory }}</li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <span class="shopping-cart__product-price">Rs. {{ cart.price }} X {{ cart.quantity }}</span>
                            </td>
                            <td>
                                <div class="qty-control position-relative">
                                    <input type="number" :value="cart.quantity" min="1" class="qty-control__number text-center">
                                    <div class="qty-control__reduce" @click="cartStore.decrement(cart.id,--cart.quantity)" :disabled="cart.quantity === 1">-</div>
                                    <div class="qty-control__increase" @click="cartStore.increment(cart.id, ++cart.quantity)">+</div>
                                </div><!-- .qty-control -->
                            </td>
                            <td>
                                <span class="shopping-cart__subtotal"> {{ cart.price * cart.quantity }} </span>
                            </td>
                            <td>
                                <a @click="cartStore.removeCart(cart)" class="remove-cart">
                                    <i class="fa fa-times"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6">Cart Empty</td>
                        </tr>
                    </tbody>
                </table>
                <div class="cart-table-footer">
                    <form action="" class="position-relative bg-body">
                        <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code">
                        <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                            value="APPLY COUPON">
                    </form>
                    <button class="btn btn-light">UPDATE CART</button>
                </div>
            </div>
            <div class="shopping-cart__totals-wrapper">
                <div class="sticky-content">
                    <div class="shopping-cart__totals">
                        <h3>Cart Totals</h3>
                        <table class="cart-totals">
                            <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>Rs. {{ cartStore.totalCost }}</td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input form-check-input_fill" type="checkbox" value=""
                                                id="free_shipping">
                                            <label class="form-check-label" for="free_shipping">Free shipping</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>Rs. {{ cartStore.totalCost }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mobile_fixed-btn_wrapper">
                        <div class="button-wrapper container">
                            <router-link :to="{ name: 'checkout'}" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import useCartStore from '@/store/CartData'

const cartStore = useCartStore();

</script>
