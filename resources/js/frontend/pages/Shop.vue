<template>
  <section class="shop-main container d-flex">
    <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
      <div class="aside-header d-flex d-lg-none align-items-center">
        <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
        <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
      </div><!-- /.aside-header -->

      <div class="pt-4 pt-lg-0"></div>

      <div class="accordion" id="categories-list">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="accordion-heading-11">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
              Product Categories
              <i class="fa fa-array-up"></i>
            </button>
          </h5>
          <div id="accordion-filter-1" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-11" data-bs-parent="#categories-list">
            <div class="accordion-body px-0 pb-0 pt-3">
              <ul class="list list-inline mb-0">
                <li class="list-item" v-for="category in categories" :key="category.id">
                  <a @click="fetchProducts(category.id)" class="menu-link py-1" :class="activeRef === category.id ? 'swatch_active':''">{{ category.name }}</a>
                </li>
              </ul>
            </div>
          </div>
        </div><!-- /.accordion-item -->
      </div><!-- /.accordion-item -->


      <div class="accordion" id="color-filters">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="accordion-heading-1">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-2" aria-expanded="true" aria-controls="accordion-filter-2">
              Color
              <i class="fa fa-arraw-up"></i>
            </button>
          </h5>
          <div id="accordion-filter-2" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-1" data-bs-parent="#color-filters">
            <div class="accordion-body px-0 pb-0">
              <div class="d-flex flex-wrap">
                <a  class="swatch-color" v-for="colr in Colors" :key="colr" :style="{color: colr}" @click="fetchProducts(colr)" :class="activeRef===colr ? 'swatch_active':''"></a>
              </div>
            </div>
          </div>
        </div><!-- /.accordion-item -->
      </div><!-- /.accordion -->


      <div class="accordion" id="size-filters">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="accordion-heading-size">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-size" aria-expanded="true" aria-controls="accordion-filter-size">
              Sizes
              <i class="fa fa-arraw-up"></i>
            </button>
          </h5>
          <div id="accordion-filter-size" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-size" data-bs-parent="#size-filters">
            <div class="accordion-body px-0 pb-0">
              <div class="d-flex flex-wrap">
                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-2 mr-1" v-for="size in Sizes" :key="size" @click="fetchProducts(size)" :class="activeRef===size ? 'swatch_active': ''">{{ size}}</a>
              </div>
            </div>
          </div>
        </div><!-- /.accordion-item -->
      </div><!-- /.accordion -->


      <div class="accordion" id="price-filters">
        <div class="accordion-item mb-4">
          <h5 class="accordion-header mb-2" id="accordion-heading-price">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-price" aria-expanded="true" aria-controls="accordion-filter-price">
              Price
              <i class="fa fa-arrow"></i>
            </button>
          </h5>


          <div data-role="rangeslider">
            <label for="price-min">Price:</label>
            <input type="range" name="price-min" id="price-min" value="200" min="0" max="1000">
            <label for="price-max">Price:</label>
            <input type="range" name="price-max" id="price-max" value="800" min="0" max="1000">
          </div>


          <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
            <input class="price-range-slider" type="text" name="price_range" value="" data-slider-min="10"
              data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]" data-currency="$">
            <div class="price-range__info d-flex align-items-center mt-2">
              <div class="me-auto">
                <span class="text-secondary">Min Price: </span>
                <span class="price-range__min">$250</span>
              </div>
              <div>
                <span class="text-secondary">Max Price: </span>
                <span class="price-range__max">$450</span>
              </div>
            </div>
          </div>

        </div><!-- /.accordion-item -->
      </div><!-- /.accordion -->
    </div><!-- /.shop-sidebar -->

    <div class="shop-list flex-grow-1">
      <div class="d-flex justify-content-between mb-4 pb-md-2">
        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
          <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
          <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
          <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
        </div><!-- /.breadcrumb -->

        <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
          <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Sort Items"
            name="total-number">
            <option selected>Default Sorting</option>
            <option value="1">Featured</option>
          </select>

          <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>
        </div><!-- /.shop-acs -->
      </div><!-- /.d-flex justify-content-between -->

      <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">

        <Product v-for="product in products" :key="product.id" :id="product.id" :title="product.title"
          :category="product.category" :subcategory="product.subcategory" :price="product.price" :image="product.image" :color="product.color" :sizes="product.size" :buy_price="product.buy_price" />

      </div>

      <nav class="shop-pages d-flex justify-content-between mt-3" aria-label="Page navigation">
        <a href="#" class="btn-link d-inline-flex align-items-center">
          <svg class="me-1" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_prev_sm" />
          </svg>
          <span class="fw-medium">PREV</span>
        </a>
        <ul class="pagination mb-0">
          <li class="page-item"><a class="btn-link px-1 mx-2 btn-link_active" href="#">1</a></li>
          <li class="page-item"><a class="btn-link px-1 mx-2" href="#">2</a></li>
          <li class="page-item"><a class="btn-link px-1 mx-2" href="#">3</a></li>
          <li class="page-item"><a class="btn-link px-1 mx-2" href="#">4</a></li>
        </ul>
        <a href="#" class="btn-link d-inline-flex align-items-center">
          <span class="fw-medium me-1">NEXT</span>
          <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_next_sm" />
          </svg>
        </a>
      </nav>
    </div>
  </section><!-- /.shop-main container -->
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue'
import { useToastr } from '@/toastr'
import Product from '../components/layouts/Product.vue';

const products = ref([]);
// const filterProducts = ref([]);
const categories = ref([]);
const toastr = useToastr();
const activeRef = ref(false);

const Sizes = ref(['small', 'medium', 'large','xxl', 'xxxl']);
const Colors = ref(['red', 'blue', 'white', 'green', 'yellow']);

onMounted(() => {
  fetchProducts();
  fetchCategories();
});

const fetchProducts = async (categoryId = null) => {
  const url = categoryId ? `/api/user/products?category=${categoryId}` : '/api/user/products';

  axios.get(url).then((res) => {
    products.value = res.data.data;
    activeRef.value = categoryId;
  })
};

const fetchCategories = () => {
  axios.get(`/api/user/category`).then((res) => {
    categories.value = res.data.data;
  })
}

const fetchCartData = async () => {
  axios.get('/api/user/cart').then((res) => {
    console.log('CartData', res.data);
  })
}

const addToCart = async (product) => {
  console.log('cart', product);
  axios.post(`/api/user/cart/${product.id}`).then((res) => {
    if (res.data.success === true) {
      fetchCartData();
      toastr.success(res.data.message);
    }
  }).catch((error) => {
    console.log('Cart: ', error);
  })
}
</script>
