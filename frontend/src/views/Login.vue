<template>
  <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-5">
        <div class="card shadow border-0">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <i class="bi bi-person-circle display-1 text-primary"></i>
              <h3 class="mt-3">Connexion</h3>
            </div>

            <form @submit.prevent="login">
              <div class="mb-3">
                <label class="form-label">Nom d'utilisateur</label>
                <input
                  v-model="username"
                  type="text"
                  class="form-control"
                  placeholder="votre_username"
                  required
                />
              </div>

              <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input
                  v-model="password"
                  type="password"
                  class="form-control"
                  placeholder="••••••••"
                  required
                />
              </div>

              <div v-if="error" class="alert alert-danger">
                {{ error }}
              </div>

              <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
              </button>
            </form>

            <div class="text-center mt-3">
              <router-link to="/register" class="text-decoration-none">
                Pas encore de compte ? <span class="text-danger">S'inscrire</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'
import { useCartStore } from '@/stores/cartStore'

const API_URL = import.meta.env.VITE_API_URL
const router = useRouter()
const userStore = useUserStore()
const cartStore = useCartStore()

const username = ref('')
const password = ref('')
const error = ref('')

const login = async () => {
  error.value = ''
  try {
    const res = await axios.post(`${API_URL}/api/login`, {
      username: username.value,
      password: password.value
    })
    userStore.setToken(res.data.token)
    await cartStore.fetchCart() // Charger le panier après connexion
    router.push('/products')
  } catch (e) {
    error.value = 'Nom d\'utilisateur ou mot de passe incorrect'
  }
}
</script>
