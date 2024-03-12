import { defineStore } from "pinia";

export const useCountStore = defineStore("counter", {
  state: () => {
    return { 
      count: 0
     };
  },
  actions: {
    increment(value = 1) {
      this.count += value;
    },
    decrement(value = 1) {
      this.count -= value;
    },
    reset() {
      this.count = 0;
    }
  },
  getters: {
    doubleCount: (state) => {
      return state.count * 2;
    },
    squareCount: (state) => {
      return state.count ** 2;
    },
  },
});