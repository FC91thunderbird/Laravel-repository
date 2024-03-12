<template>
    <div class="mb-2 pb-4"></div>
    <section class="shop-checkout container">
        <div v-if="!customer?.user?.isCustomer">
            <button class="btn btn-primary btn-checkout"> Login</button>
            <div class="mb-2"></div>
            OR
        </div>
        <form name="checkout-form" @submit.prevent="chekout">
            <div class="checkout-form">
                <div class="billing-info__wrapper">
                    <h4>BILLING DETAILS</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" v-model="form.first_name"
                                    placeholder="First Name">
                                <label for="checkout_first_name">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" v-model="form.last_name"
                                    placeholder="Last Name">
                                <label for="checkout_last_name">Last Name</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="search-field my-3">
                                <div class="form-label-fixed hover-container">
                                    <label class="form-label">Country / Region*</label>
                                    <select class="form-select" v-model="form.country">
                                        <option value="">Select Country</option>
                                        <option value="India">India</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Canada">Canada</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mt-3 mb-3">
                                <input type="text" class="form-control" v-model="form.address"
                                    placeholder="Street Address *">
                                <label for="checkout_company_name">Street Address *</label>
                            </div>
                            <div class="form-floating mt-3 mb-3">
                                <input type="text" class="form-control" v-model="form.address2">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" v-model="form.city" placeholder="Town / City *">
                                <label for="checkout_city">Town / City *</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" v-model="form.zip"
                                    placeholder="Postcode / ZIP *">
                                <label for="checkout_zipcode">Postcode / ZIP *</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" v-model="form.phone" placeholder="Phone *">
                                <label for="checkout_phone">Phone *</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                                <input type="email" class="form-control" v-model="form.email" placeholder="Your Mail *">
                                <label for="checkout_email">Your Mail *</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkout__totals-wrapper">
                    <div class="sticky-content">
                        <div class="checkout__totals">
                            <h3>Your Order</h3>
                            <table class="checkout-cart-items">
                                <thead>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <th>SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody v-if="cartData.cartItems.length > 0">
                                    <tr v-for="cart in cartData.cartItems" :key="cart.id">
                                        <td>{{ cart.title }} x {{ cart.quantity }}</td>
                                        <td>Rs. {{ cart.price }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td>Cart Empty</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="checkout-totals">
                                <tbody>
                                    <tr>
                                        <th>SUBTOTAL</th>
                                        <td>{{ cartData.totalCost }}</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL</th>
                                        <td> Rs. {{ cartData.totalCost }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="checkout__payment-methods">
                            <div class="form-check">
                                <input class="form-check-input form-check-input_fill" type="radio"
                                    v-model="form.payment_method" id="checkout_payment_method_3">
                                <label class="form-check-label" for="checkout_payment_method_3">
                                    Cash on delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-check-input_fill" type="radio"
                                    v-model="form.payment_method" id="checkout_payment_method_4">
                                <label class="form-check-label" for="checkout_payment_method_4">
                                    Paypal
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-checkout" type="submit">PLACE ORDER</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <div class="mb-4"></div>


    <!-- <stripe-checkout ref="checkoutRef" mode="payment" :pk="publishableKey" 
    :line-items="lineItems" :success-url="successURL" :cancel-url="cancelURL" 
    /> -->
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import useCartStore from '@/store/CartData'
import useCustomerStore from '@/store/Customer';

const cartData = useCartStore();
const customer = useCustomerStore();


const form = ref({
    first_name: '',
    last_name: '',
    country: '',
    address: '',
    address2: '',
    city: '',
    zip: '',
    phone: '',
    email: '',
    payment_method: ''
});

onMounted(() => {
    fetchCustomerDetails();
});

const fetchCustomerDetails = async () => {
    form.value = customer.user;
}


const chekout = async () => {
    try {

        const response = await axios.post('/api/user/checkout', form.value);
        const stripeUrl = response.data.url;
        const sessionId = response.data.session_id;
        

        // const successUrl = await axios.get(`/api/user/checkout/success?session_id=${sessionId}`);

        // console.log('res', successUrl);

         if (response.data.stripeUrl) {
                window.location.href = response.data.stripeUrl;
            }else{
                // router.push({name: 'orderSuccess' })
            }

        if (error) {
            console.error(error.message);
        }
    } catch (error) {
        console.error(error);
    }
}

</script>
