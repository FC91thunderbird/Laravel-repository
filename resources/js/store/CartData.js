import { ref } from "vue";
import { defineStore } from "pinia";
import axios from "axios";
import { useToastr } from '@/toastr';

const toastr = useToastr();

export default defineStore('cartStore', () => {

    const cartItems = ref([]);
    const totalItems = ref(0);
    const totalCost = ref(0);


    const fetchCartItem = ()=>{
        axios.get(`/api/user/cart`).then((res)=>{
            cartItems.value = res.data.data.cartItems;
            // totalItems.value = res.data.data.products;
            totalCost.value = res.data.data.total;
        })
    }

    const addCartItem = (item) => {
        axios.post(`/api/user/cart/${item}`).then((res)=>{
            if(res.data.success === true){
                toastr.success(res.data.message);
                fetchCartItem();
            }
        }).catch((error)=>{
            console.log('Cart  : ', error);
        })
    };

    const increment = (item, quantity) => {
        axios.put(`/api/user/cart/${item}`, {quantity: quantity}).then((res)=>{
            if(res.data.success === true){
                toastr.success(res.data.message);
                fetchCartItem();
            }
        }).catch((error)=>{
            console.log('Cart: ', error);
        })
    };
     
    const decrement = (item, quantity) => {
        if(quantity >= 1){
            axios.put(`/api/user/cart/${item}`,{quantity: quantity}).then((res)=>{            
                if(res.data.success === true){
                    toastr.success(res.data.message);
                    fetchCartItem();
                }
            }).catch((error)=>{
                console.log('Cart: ', error);
            })
        }else{
            alert('not able to decress');
        }
    };

    const removeCart = (item) => {
        axios.delete(`/api/user/cart/${item.id}`).then((res)=>{            
            if(res.data.success === true){
                toastr.success(res.data.message);
                fetchCartItem();
            }
        }).catch((error)=>{
            console.log('Cart: ', error);
        })
    };

    return {
        cartItems,
        addCartItem,
        removeCart,
        fetchCartItem,
        increment,
        decrement,
        totalItems,
        totalCost
    };
});