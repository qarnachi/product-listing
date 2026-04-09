<template>
  <div id="app" class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <header>
      <nav class="navbar navbar-expand-lg header-gradient shadow-sm">
        <div class="container">
          <router-link class="navbar-brand d-flex align-items-center gap-2" to="/">
            <i class="bi bi-shop"></i> Ma Boutique
          </router-link>

          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
              <li v-if="isAuthenticated" class="nav-item">
                <router-link class="nav-link d-flex align-items-center gap-1" to="/products">
                  <i class="bi bi-bag"></i> Produits
                </router-link>
              </li>
              <li v-if="isAuthenticated" class="nav-item position-relative">
                <router-link class="nav-link d-flex align-items-center gap-1" to="/cart">
                  <i class="bi bi-cart"></i> Panier
                  <span
                    v-if="cartItemCount > 0"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    :class="{ bounce: badgeBounce }"
                    style="font-size: 0.7rem;"
                  >
                    {{ cartItemCount }}
                  </span>
                </router-link>
              </li>
              <li v-if="!isAuthenticated" class="nav-item">
                <router-link class="nav-link d-flex align-items-center gap-1" to="/login">
                  <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </router-link>
              </li>
              <li v-else class="nav-item">
                <a href="#" @click.prevent="logout" class="nav-link d-flex align-items-center gap-1">
                  <i class="bi bi-box-arrow-right"></i> Se déconnecter
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <main class="flex-grow-1 d-flex justify-content-center align-items-center min-vh-100 bg-light">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-auto shadow-sm">
      <small>&copy; 2025 Ma Boutique - Tous droits réservés</small>
    </footer>
    <!-- Toast -->
      <div
        class="toast-container position-fixed bottom-0 end-0 p-3"
        style="z-index: 1055"
      >
        <div
          ref="toastEl"
          class="toast align-items-center text-bg-success border-0"
          role="alert"
          aria-live="assertive"
          aria-atomic="true"
        >
          <div class="d-flex">
            <div class="toast-body" ref="toastMessage">
              Produit ajouté au panier !
            </div>
            <button
              type="button"
              class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"
              aria-label="Close"
            ></button>
          </div>
        </div>
      </div>

  </div>
</template>


<script setup>
import { computed, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from './stores/cartStore'
import { useUserStore } from './stores/userStore'

const router = useRouter()
const cartStore = useCartStore()
const userStore = useUserStore()

const isAuthenticated = computed(() => userStore.isAuthenticated)

const cartItemCount = computed(() =>
  cartStore.items.reduce((sum, item) => sum + item.quantity, 0)
)

const badgeBounce = ref(false)
watch(cartItemCount, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    badgeBounce.value = true
    setTimeout(() => (badgeBounce.value = false), 500)
  }
})

const logout = () => {
  userStore.logout()
  cartStore.initCart()
  router.push('/login')
}
</script>


<style scoped>
.header-gradient {
  background: linear-gradient(90deg, #dc3545 0%, #0d6efd 100%);
}
.header-gradient .navbar-brand,
.header-gradient .nav-link {
  color: #fff !important;
}
.header-gradient .nav-link:hover {
  opacity: 0.9;
}
.header-gradient .badge.bg-danger {
  background: #fff !important;
  color: #dc3545 !important;
}

.bounce {
  animation: bounce 0.5s;
}

@keyframes bounce {
  0%   { transform: scale(1); }
  50%  { transform: scale(1.3); }
  100% { transform: scale(1); }
}
</style>
