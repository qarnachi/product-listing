<template>
  <div class="container py-5">
    <h1 class="text-center mb-5">Mon Panier</h1>

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

    <div v-else>
      <div class="row">
        <div class="col-lg-8 mb-4">
          <div
            class="card shadow mb-3"
            v-for="item in cartStore.items"
            :key="item.id"
          >
            <div class="row g-0 align-items-center p-3">
              <div class="col-md-3">
                <img
                  :src="item.image"
                  :alt="item.title"
                  class="img-fluid rounded"
                  style="max-height: 120px; object-fit: contain"
                />
              </div>
              <div class="col-md-6">
                <div class="card-body">
                  <h5 class="card-title">{{ item.title }}</h5>
                  <p class="fw-bold text-primary">{{ item.price }} €</p>

                  <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0">Quantité :</label>
                    <input
                      type="number"
                      class="form-control"
                      style="width: 80px"
                      min="1"
                      v-model.number="item.quantity"
                      @change="updateQuantity(item)"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-3 text-end">
                <button
                  class="btn btn-danger"
                  @click="remove(item.productId)"
                >
                  <i class="bi bi-trash"></i>
                  Supprimer
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title mb-4">Résumé</h5>
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
              <button class="btn btn-success w-100">
                Valider la commande
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCartStore } from '../stores/cartStore'

const cartStore = useCartStore()

onMounted(() => {
  cartStore.fetchCart()
})

const remove = (id) => {
  cartStore.removeFromCart(id)
}

const updateQuantity = (item) => {
  if (item.quantity < 1) {
    item.quantity = 1
  }
  cartStore.updateQuantity(item.productId, item.quantity)
}
</script>
