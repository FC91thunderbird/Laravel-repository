// import { defineStore } from "pinia";
// import { Ref } from "vue";
// import {useToastr} from '@/toastr';

// const toastr = useToastr();

// export default addCart = defineStore('addToCart', ()=>{
//     const cart = ref({
//         id:'',
//         title:'',
//         category:'',
//         subcategory:'',
//         price:'',
//         quentity:''
//     });



// })


                                                            
// import { defineStore } from 'pinia'
// import Swal from 'sweetalert2'

// export const useShoppingStore = defineStore('shopping', {
//     state: () => {
//         return { 
//             cartItems : []
//         }
//     },
//     getters: {
//         countCartItems(){
//             return this.cartItems.length;
//         },
//         getCartItems(){
//             return this.cartItems;
//         }
//     },
//     actions: {
//         addToCart(item) {
//             let index = this.cartItems.findIndex(product => product.id === item.id);
//             if(index !== -1) {
//               this.products[index].quantity += 1;
//               Swal.fire({
//                 position: 'top-end',
//                 icon: 'success',
//                 title: 'Your item has been updated',
//                 showConfirmButton: false,
//                 timer: 1500
//               });
//             }else {
//               item.quantity = 1;
//               this.cartItems.push(item);
//               Swal.fire({
//                 position: 'top-end',
//                 icon: 'success',
//                 title: 'Your item has been saved',
//                 showConfirmButton: false,
//                 timer: 1500
//               });
//             }
//         },
//         incrementQ(item) {
//             let index = this.cartItems.findIndex(product => product.id === item.id);
//             if(index !== -1) {
//                 this.cartItems[index].quantity += 1;
//                 Swal.fire({
//                     position: 'top-end',
//                     icon: 'success',
//                     title: 'Your item has been updated',
//                     showConfirmButton: false,
//                     timer: 1500
//                 });
//             }
//         },
//         decrementQ(item) {
//             let index = this.cartItems.findIndex(product => product.id === item.id);
//             if(index !== -1) {
//                 this.cartItems[index].quantity -= 1;
//                 if(this.cartItems[index].quantity === 0){
//                     this.cartItems = this.cartItems.filter(product => product.id !== item.id);
//                 }
//                 Swal.fire({
//                     position: 'top-end',
//                     icon: 'success',
//                     title: 'Your item has been updated',
//                     showConfirmButton: false,
//                     timer: 1500
//                 });
//             }
//         },
//         removeFromCart(item) {
//             this.cartItems = this.cartItems.filter(product => product.id !== item.id);
//             Swal.fire({
//               position: 'top-end',
//               icon: 'success',
//               title: 'Your item has been removed',
//               showConfirmButton: false,
//               timer: 1500
//             });
//         }
        
//     },
// })
                                                            
                                                        
import { ref } from "vue";
import { defineStore } from "pinia";

export default defineStore('cart', () => {

    const items = ref([]);
    const totalItems = ref(0);
    const totalCost = ref(0);

    const addItem = (item) => {
        let targetItem = items.value.filter( currItem => currItem.id === item.id )[0];

        if(targetItem) targetItem.count += 1;
        else items.value = [...items.value, {...item, count: 1}];

        totalItems.value += 1;
        totalCost.value += item.price;
    };

    const removeItem = (item) => {
        let targetItem = items.value.filter( currItem => currItem.id === item.id )[0];

        if(targetItem.count === 1) items.value = items.value.filter( currItem => currItem.id !== item.id );
        else targetItem.count -= 1;

        totalItems.value -= 1;
        totalCost.value -= item.price;
    };

    return {
        items,
        addItem,
        removeItem,
        totalItems,
        totalCost
    };
});
