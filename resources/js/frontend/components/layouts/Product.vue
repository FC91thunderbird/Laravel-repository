<template>
    <div class="product-card-wrapper mb-2">
        <div class="product-card product-card_style9 border rounded-3 mb-3 mb-md-4 ">
            <div class="position-relative pb-3">
                <div class="pc__img-wrapper pc__img-wrapper_wide3">
                    <a href="">
                        <img loading="lazy" :src='"/storage/product/" + image' :alt="title" :width="256" :height="201"
                            class="pc__img">
                    </a>
                </div>
                <div class="anim_appear-bottom position-absolute w-100 text-center">
                    <button @click="addcart(id)"
                        class="btn btn-round btn-hover-red border-0 text-uppercase me-2 js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="Add To Cart">
                        <i class="fa fa-shopping-cart"></i>
                    </button>
                    <button class="btn btn-round btn-hover-red border-0 text-uppercase me-2 js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button @click="wishlist(id)"
                        class="btn btn-round btn-hover-red border-0 text-uppercase js-add-wishlist" title="Add To Wishlist">
                        <i class="fa fa-heart"></i>
                    </button>
                </div>
            </div>

            <div class="pc__info position-relative">
                <h5 class=""><a href="">{{ title }}</a></h5>
                <p class="pc__category">Category: {{ category }}</p>
                <p class="pc__category">subcategory: {{ subcategory }}</p>
                <div class="product-card__price d-flex mb-1">
                    <span class="pc-label bg-info mr-1" v-for="col in color" :key="col" >{{col}}</span>
                </div>
                <div class="product-card__review d-sm-flex align-items-center">
                    <div class="reviews-group d-flex">
                        <i class="fa fa-cart"></i>
                    </div>
                </div>
                <div class="d-flex flex-wrap" > 
                    <a href="#" class="swatch-color" v-for="col in color" :key="col" :style="{ color: col,border: '1px solid #000' }"></a>
                </div>
                <div class="product-card__price d-flex" >
                    <del class="money">Rs. {{ buy_price }}</del>
                    <span class="money price fs-5">Rs. {{ price }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import useCartData from '@/store/CartData';

const cartData = useCartData();

const { id, title, category, subcategory, price, image, color,sizes, buy_price } = defineProps(['id', 'title', 'category', 'subcategory', 'price', 'image', 'color', 'sizes', 'buy_price']);
const emit = defineEmits();

const addcart = async (id) => {
    cartData.addCartItem(id);
}

const wishlist = async (id) => {
    alert('wishList', id)
}

// const fetchCartData = async() =>{
//     axios.get(`/api/user/cart`).then((res)=>{
//         if(res.data.success === true){
//             // toastr.success(res.data.message);
//         }
//     })
// }

// const addToCart = async(product) =>{
//     console.log('cart', product);
//     axios.post(`/api/user/cart/${product.id}`).then((res)=>{
//         if(res.data.success === true){
//             fetchCartData();
//             toastr.success(res.data.message);
//         }
//     }).catch((error)=>{
//         console.log('Cart: ', error);
//     })
// }

</script>

<style lang="scss" scoped></style>