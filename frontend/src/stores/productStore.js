import { defineStore } from 'pinia'
import axios from 'axios'

export const useProductStore = defineStore('productStore', {
  state: () => ({
    products: [],
    loading: false,
    error: null
  }),
  actions: {
    async fetchProducts() {
      this.loading = true
      this.error = null
      try {
        const res = await axios.get('http://localhost:8000/api/products')
        this.products = res.data
      } catch (err) {
        this.error = 'Erreur lors du chargement des produits.'
        console.error(err)
      } finally {
        this.loading = false
      }
    }
  }
})
