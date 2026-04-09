import { defineStore } from 'pinia'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL

export const useCartStore = defineStore('cartStore', {
  state: () => ({
    items: [],
    total: 0,
    discount: 0,
    totalAfterDiscount: 0,
    loading: false
  }),
  actions: {
    async fetchCart() {
      this.loading = true
      try {
        const res = await axios.get(`${API_URL}/api/cart`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        this.items = res.data.items
        this.total = res.data.total
        this.discount = res.data.discount
        this.totalAfterDiscount = res.data.total_after_discount
      } catch (err) {
        console.error('Erreur lors du chargement du panier', err)
      } finally {
        this.loading = false
      }
    },

    async addToCart(product) {
      try {
        await axios.post(`${API_URL}/api/cart/add`, product, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        await this.fetchCart()
      } catch (err) {
        console.error('Erreur lors de l’ajout au panier', err)
      }
    },

    async removeFromCart(productId) {
      try {
        await axios.post(`${API_URL}/api/cart/remove`, { id: productId }, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        await this.fetchCart()
      } catch (err) {
        console.error('Erreur lors de la suppression du produit', err)
      }
    },

    async updateQuantity(productId, quantity) {
      try {
        await axios.post(`${API_URL}/api/cart/update`, 
          { id: productId, quantity }, 
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          }
        )
        await this.fetchCart()
      } catch (err) {
        console.error('Erreur lors de la mise à jour de la quantité', err)
      }
    },
    async initCart() {
        this.items = []
        this.total = 0
        this.discount = 0
        this.totalAfterDiscount = 0
        this.loading = false
    }
  }
})
