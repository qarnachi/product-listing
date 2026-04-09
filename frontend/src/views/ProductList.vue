<template>
  <div class="container py-5">
    <h1 class="text-center mb-5 text-primary">Produits</h1>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
      <p class="mt-3">Chargement des produits...</p>
    </div>

    <div v-else class="row g-4">
      <div
        v-for="product in products"
        :key="product.id"
        class="col-sm-6 col-md-4 col-lg-3"
      >
        <div class="card h-100 shadow">
          <!-- accent top -->
          <div class="card-accent"></div>
          <router-link :to="`/products/${product.id}`" class="text-decoration-none">
            <img
              :src="product.image"
              :alt="product.title"
              class="card-img-top p-3"
              style="height: 200px; object-fit: contain"
            />
          </router-link>

          <div class="card-body d-flex flex-column">
            <router-link :to="`/products/${product.id}`" class="text-decoration-none text-dark">
              <h5 class="card-title">{{ product.title }}</h5>
            </router-link>
            <p class="fw-bold text-primary mt-auto">{{ product.price }} €</p>
            <button
              @click="addToCart(product)"
              class="btn btn-danger w-100 mt-2"
            >
              <i class="bi bi-cart-plus"></i>
              Ajouter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div
      v-if="toast.visible"
      class="toast-container position-fixed bottom-0 end-0 p-3"
      style="z-index: 9999"
    >
      <div
        class="toast show align-items-center text-white"
        :class="toast.type === 'success' ? 'bg-success' : 'bg-danger'"
        role="alert"
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
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'
import { useCartStore } from '../stores/cartStore'

const API_URL = import.meta.env.VITE_API_URL
const router = useRouter()
const products = ref([])
const loading = ref(true)

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

const fetchProducts = async () => {
  if (!userStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    const res = await axios.get(`${API_URL}/api/products`, {
      headers: {
        Authorization: `Bearer ${userStore.token}`
      }
    })
    products.value = res.data
  } catch (e) {
    console.error('Erreur lors du chargement des produits', e)
    if (e.response?.status === 401) {
      userStore.logout()
      router.push('/login')
    }
  } finally {
    loading.value = false
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
  fetchProducts()
  cartStore.fetchCart()
})
</script>

<style scoped>
/* petit accent en haut de la carte: rouge->bleu */
.card .card-accent {
  height: 6px;
  border-top-left-radius: 0.25rem;
  border-top-right-radius: 0.25rem;
  background: linear-gradient(90deg, #dc3545 0%, #0d6efd 100%);
}
.card h5, .card .card-title {
  min-height: 3rem;
}
.text-primary { color: #0d6efd !important; }
.btn-danger { background-color: #dc3545; border-color: #dc3545; }
</style>
