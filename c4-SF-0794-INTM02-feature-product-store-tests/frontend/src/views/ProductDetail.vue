<template>
  <div class="container py-5">
    <div v-if="product" class="card mx-auto shadow product-card" style="max-width: 600px;">
      <img :src="product.image" class="card-img-top" alt="Image produit" />

      <div class="card-body">
        <h1 class="card-title h4 text-primary">{{ product.title }}</h1>
        <p class="card-text">{{ product.description }}</p>
        <p class="card-text fw-bold text-primary">{{ product.price }} €</p>
        <button
              @click="addToCart(product)"
              class="btn btn-danger w-100"
            >
              <i class="bi bi-cart-plus"></i>
              Ajouter au panier
        </button>
      </div>
    </div>

    <div v-else class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
      <p class="mt-3">Chargement du produit...</p>
    </div>
    <!-- Toast Bootstrap -->
    <div
      v-if="toast.visible"
      class="toast-container position-fixed bottom-0 end-0 p-3"
      style="z-index: 9999"
    >
      <div
        class="toast show align-items-center text-white"
        :class="toast.type === 'success' ? 'bg-success' : 'bg-danger'"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
      >
        <div class="d-flex">
          <div class="toast-body">{{ toast.message }}</div>
          <button
            type="button"
            class="btn-close btn-close-white me-2 m-auto"
            @click="toast.visible = false"
          ></button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'
import { useCartStore } from '../stores/cartStore'

const API_URL = import.meta.env.VITE_API_URL
const route = useRoute()
const router = useRouter()
const product = ref(null)
const userStore = useUserStore()
const cartStore = useCartStore()

const toast = ref({
  visible: false,
  message: '',
  type: 'success',
})

const showToast = (message, type = 'success') => {
  toast.value = {
    visible: true,
    message,
    type,
  }
  setTimeout(() => {
    toast.value.visible = false
  }, 3000)
}

const fetchProduct = async () => {
  if (!userStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    const res = await axios.get(`${API_URL}/api/products/${route.params.id}`, {
      headers: {
        Authorization: `Bearer ${userStore.token}`
      }
    })
    product.value = res.data
  } catch (e) {
    console.error('Erreur lors du chargement du produit', e)
    if (e.response?.status === 401) {
      userStore.logout()
      router.push('/login')
    }
  }
}

const addToCart = async (product) => {
  if (!userStore.isAuthenticated) {
    showToast('Vous devez être connecté.', 'danger')
    router.push('/login')
    return
  }

  try {
    await axios.post(`${API_URL}/api/cart/add`, {
      id: product.id,
      title: product.title,
      price: product.price,
      image: product.image,
    }, {
      headers: {
        Authorization: `Bearer ${userStore.token}`
      }
    })
    await cartStore.fetchCart()
    showToast('Produit ajouté au panier !', 'success')
  } catch {
    showToast('Erreur lors de l\'ajout au panier.', 'danger')
  }
}

onMounted(() => {
  if (!userStore.isAuthenticated) {
    router.push('/login')
    return
  }
  fetchProduct()
  cartStore.fetchCart()
})
</script>

<style scoped>
.product-card {
  border-top: 6px solid #0d6efd; /* bleu accent */
}
.text-primary { color: #0d6efd !important; }
.btn-danger { background-color: #dc3545; border-color: #dc3545; }
.toast.show.bg-success { background-color: #0d6efd !important; } /* toast succès bleu */
</style>
