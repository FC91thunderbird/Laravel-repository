<template>
    <section class="category-carousel bg-grey-f7f5ee">
        <div class="container">
            <div class="mb-3 mb-xl-5 pb-3 pt-1 pb-xl-5"></div>

            <div
                class="d-flex align-items-center justify-content-center justify-content-md-between flex-wrap mb-3 pb-xl-2 mb-xl-4 gap-4">
                <h2 class="section-title fw-normal">Shop By Category</h2>
                <a class="btn-link btn-link_md default-underline text-uppercase fw-medium" href="#">Shop All Categories</a>
            </div>

            <div id="category_carousel" class="position-relative">
                <div class="swiper-container js-swiper-slider" data-settings='{
              "autoplay": {
                "delay": 5000
              },
              
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 16
                },
                "768": {
                  "slidesPerView": 3,
                  "slidesPerGroup": 4,
                  "spaceBetween": 20
                },
                "992": {
                  "slidesPerView": 4,
                  "spaceBetween": 24
                },
                "1200": {
                  "slidesPerView": 5,
                  "slidesPerGroup": 4,
                  "spaceBetween": 28
                }
              }
            }'>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="category in categories" :key="category.id">
                            <img class=" mb-3" :src="categoryImage(category.image)" :height="220" :alt="category.name">
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium pb-0">{{ category.name }}</a>
                            </div>
                        </div>
                    </div><!-- /.swiper-wrapper -->
                </div><!-- /.swiper-container js-swiper-slider -->
            </div><!-- /.position-relative -->

            <div class="mt-3 mt-xl-5 pb-3 pt-1 pb-xl-5"></div>
        </div>
    </section>

    <section class="products-grid">
        <div class="container">
            <div
                class="d-flex align-items-center justify-content-center justify-content-md-between flex-wrap mb-3 pb-xl-2 mb-xl-4 gap-4">
                <h2 class="section-title fw-normal">Featured Products</h2>

                <ul class="nav nav-tabs justify-content-center" id="collections-1-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore active" id="collections-tab-1-trigger" data-bs-toggle="tab"
                            href="#collections-tab-1" role="tab" aria-controls="collections-tab-1" aria-selected="true">Best
                            Sellers</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore" id="collections-tab-2-trigger" data-bs-toggle="tab"
                            href="#collections-tab-2" role="tab" aria-controls="collections-tab-2" aria-selected="true">Most
                            Popular</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content pt-2" id="collections-2-tab-content">
                <div class="tab-pane fade show active" id="collections-tab-1" role="tabpanel"
                    aria-labelledby="collections-tab-1-trigger">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5">

                        <Product v-for="product in products" :key="product.id" :id="product.id" :title="product.title"
                            :category="product.category" :subcategory="product.subcategory" :price="product.price"
                            :image="product.image" />                       

                    </div>
                </div>


                <div class="tab-pane fade show" id="collections-tab-2" role="tabpanel"
                    aria-labelledby="collections-tab-2-trigger">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5">
                        <div class="product-card-wrapper mb-2">
                            <div class="product-card product-card_style9 border rounded-3 mb-3 mb-md-4 ">
                                <div class="position-relative pb-3">
                                    <div class="pc__img-wrapper pc__img-wrapper_wide3">
                                        <a href="">
                                            <img loading="lazy" src="" width="256" height="201"
                                                alt="Cropped Faux leather Jacket" class="pc__img">
                                        </a>
                                    </div>
                                    <div class="anim_appear-bottom position-absolute w-100 text-center">
                                        <button
                                            class="btn btn-round btn-hover-red border-0 text-uppercase me-2 js-add-cart js-open-aside"
                                            data-aside="cartDrawer" title="Add To Cart">
                                            <svg class="d-inline-block" width="14" height="14" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_cart" />
                                            </svg>
                                        </button>
                                        <button
                                            class="btn btn-round btn-hover-red border-0 text-uppercase me-2 js-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                                            <svg class="d-inline-block" width="18" height="18" viewBox="0 0 18 18"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_view" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-round btn-hover-red border-0 text-uppercase js-add-wishlist"
                                            title="Add To Wishlist">
                                            <svg width="14" height="14" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_heart" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="pc__info position-relative">
                                    <p class="pc__category">Fruits</p>
                                    <h6 class="pc__title"><a href="">Organic Strawberries</a></h6>
                                    <div class="product-card__review d-sm-flex align-items-center">
                                        <div class="reviews-group d-flex">
                                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.0172 0.313075L2.91869 2.64013L0.460942 3.0145C0.0201949 3.08129 -0.15644 3.64899 0.163185 3.97415L1.94131 5.78447L1.52075 8.34177C1.44505 8.80402 1.91103 9.15026 2.30131 8.93408L4.5 7.72661L6.69869 8.93408C7.08897 9.14851 7.55495 8.80402 7.47925 8.34177L7.05869 5.78447L8.83682 3.97415C9.15644 3.64899 8.97981 3.08129 8.53906 3.0145L6.08131 2.64013L4.9828 0.313075C4.78598 -0.101718 4.2157 -0.10699 4.0172 0.313075Z">
                                                </path>
                                            </svg>
                                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.0172 0.313075L2.91869 2.64013L0.460942 3.0145C0.0201949 3.08129 -0.15644 3.64899 0.163185 3.97415L1.94131 5.78447L1.52075 8.34177C1.44505 8.80402 1.91103 9.15026 2.30131 8.93408L4.5 7.72661L6.69869 8.93408C7.08897 9.14851 7.55495 8.80402 7.47925 8.34177L7.05869 5.78447L8.83682 3.97415C9.15644 3.64899 8.97981 3.08129 8.53906 3.0145L6.08131 2.64013L4.9828 0.313075C4.78598 -0.101718 4.2157 -0.10699 4.0172 0.313075Z">
                                                </path>
                                            </svg>
                                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.0172 0.313075L2.91869 2.64013L0.460942 3.0145C0.0201949 3.08129 -0.15644 3.64899 0.163185 3.97415L1.94131 5.78447L1.52075 8.34177C1.44505 8.80402 1.91103 9.15026 2.30131 8.93408L4.5 7.72661L6.69869 8.93408C7.08897 9.14851 7.55495 8.80402 7.47925 8.34177L7.05869 5.78447L8.83682 3.97415C9.15644 3.64899 8.97981 3.08129 8.53906 3.0145L6.08131 2.64013L4.9828 0.313075C4.78598 -0.101718 4.2157 -0.10699 4.0172 0.313075Z">
                                                </path>
                                            </svg>
                                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.0172 0.313075L2.91869 2.64013L0.460942 3.0145C0.0201949 3.08129 -0.15644 3.64899 0.163185 3.97415L1.94131 5.78447L1.52075 8.34177C1.44505 8.80402 1.91103 9.15026 2.30131 8.93408L4.5 7.72661L6.69869 8.93408C7.08897 9.14851 7.55495 8.80402 7.47925 8.34177L7.05869 5.78447L8.83682 3.97415C9.15644 3.64899 8.97981 3.08129 8.53906 3.0145L6.08131 2.64013L4.9828 0.313075C4.78598 -0.101718 4.2157 -0.10699 4.0172 0.313075Z">
                                                </path>
                                            </svg>
                                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.0172 0.313075L2.91869 2.64013L0.460942 3.0145C0.0201949 3.08129 -0.15644 3.64899 0.163185 3.97415L1.94131 5.78447L1.52075 8.34177C1.44505 8.80402 1.91103 9.15026 2.30131 8.93408L4.5 7.72661L6.69869 8.93408C7.08897 9.14851 7.55495 8.80402 7.47925 8.34177L7.05869 5.78447L8.83682 3.97415C9.15644 3.64899 8.97981 3.08129 8.53906 3.0145L6.08131 2.64013L4.9828 0.313075C4.78598 -0.101718 4.2157 -0.10699 4.0172 0.313075Z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="reviews-note text-lowercase text-secondary ms-sm-1">321,975</span>
                                    </div>
                                    <div class="product-card__price d-flex">
                                        <span class="money price fs-5">$79.99</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import Product from '../components/layouts/Product.vue';

// import {useShoppingStore} from '@/store/Cart'
import useCartStore from '@/store/Cart'

import { useToastr } from '@/toastr'

const cartStore = useCartStore();


// const store = useShoppingStore();
const toastr = useToastr();

const categories = ref([]);
const products = ref([]);

onMounted(() => {
    fetchCategory();
    fetchProducts();
});

const fetchCategory = async () => {
    axios.get('/api/user/category').then((res) => {
        categories.value = res.data.data;
    })
};

const fetchProducts = async () => {
    axios.get('/api/user/products').then((res) => {
        products.value = res.data.data;
    })
}

// const addToCart = async(productId) =>{
//     console.log(productId);
//     cartStore.additem(productId);
//     // store.addToCart(productId);
//     toastr.success('Item Added to cart');

// };

// const wishlist = async(productId) =>{
//     console.log(productId);
// }

const categoryImage = (image) => '/storage/category/' + image;


</script>