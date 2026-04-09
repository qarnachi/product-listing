<template>
  <div class="container py-5">
    <h1 class="text-center mb-4 text-primary">
      <i class="bi bi-cart3"></i> Mon Panier
    </h1>

    <div v-if="cartStore.loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
      <p class="mt-3">Chargement du panier...</p>
    </div>

    <div v-else-if="cartStore.items.length === 0" class="text-center py-5">
      <i class="bi bi-cart-x display-1 text-muted"></i>
      <h3 class="mt-3 text-muted">Votre panier est vide</h3>
      <router-link to="/products" class="btn btn-primary mt-3">
        Voir les produits
      </router-link>
    </div>

    <div v-else class="row">
      <div class="col-lg-8 mb-4">
        <div class="card shadow">
          <div class="card-body">
            <div
              v-for="item in cartStore.items"
              :key="item.id"
              class="d-flex align-items-center border-bottom py-3"
            >
              <img
                :src="item.image"
                :alt="item.title"
                class="rounded"
                style="width: 80px; height: 80px; object-fit: contain"
              />
              <div class="flex-grow-1 ms-3">
                <h6 class="mb-1">{{ item.title }}</h6>
                <p class="text-muted mb-0">{{ item.price }} €</p>
              </div>
              <div class="d-flex align-items-center gap-2">
                <button
                  @click="cartStore.updateQuantity(item.id, item.quantity - 1)"
                  class="btn btn-sm btn-outline-secondary"
                  :disabled="item.quantity <= 1"
                >
                  -
                </button>
                <span class="fw-bold">{{ item.quantity }}</span>
                <button
                  @click="cartStore.updateQuantity(item.id, item.quantity + 1)"
                  class="btn btn-sm btn-outline-secondary"
                >
                  +
                </button>
                <button
                  @click="cartStore.removeFromCart(item.id)"
                  class="btn btn-sm btn-danger ms-2"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title mb-4 text-primary">Résumé</h5>
            <div class="d-flex justify-content-between mb-2">
              <span>Sous-total</span>
              <span>{{ cartStore.total.toFixed(2) }} €</span>
            </div>
            <div v-if="cartStore.discount > 0" class="d-flex justify-content-between mb-2 text-success">
              <span>Réduction</span>
              <span>-{{ cartStore.discount.toFixed(2) }} €</span>
            </div>
            <hr />
            <div class="d-flex justify-content-between mb-4">
              <strong>Total</strong>
              <strong class="text-primary">{{ cartStore.totalAfterDiscount.toFixed(2) }} €</strong>
            </div>
            <button class="btn btn-primary w-100 py-2">
              <i class="bi bi-credit-card"></i> Passer la commande
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import { useUserStore } from '@/stores/userStore'

const router = useRouter()
const cartStore = useCartStore()
const userStore = useUserStore()

onMounted(() => {
  if (!userStore.isAuthenticated) {
    router.push('/login')
    return
  }
  cartStore.fetchCart()
})
</script>

<style scoped>
.text-primary { color: #0d6efd !important; }
.btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
.btn-danger { background-color: #dc3545; border-color: #dc3545; }
.card .border-bottom { border-color: rgba(13,110,253,0.08) !important; }
</style>
