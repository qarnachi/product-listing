<template>
  <div class="register">
    <h2>Créer un compte</h2>
    <form @submit.prevent="submitRegister">
      <div>
        <label for="username">Nom d'utilisateur :</label>
        <input
          id="username"
          v-model="username"
          type="text"
          placeholder="Entrez votre nom d'utilisateur"
          :class="{ invalid: username && !isUsernameValid }"
          required
        />
      </div>

      <div>
        <label for="email">Email :</label>
        <input
          id="email"
          v-model="email"
          type="email"
          placeholder="exemple@mail.com"
          :class="{ invalid: email && !isEmailValid }"
          required
        />
      </div>

      <div>
        <label for="password">Mot de passe :</label>
        <input
          id="password"
          v-model="password"
          type="password"
          placeholder="Entrez votre mot de passe"
          :class="{ invalid: password && !isPasswordValid }"
          required
        />
      </div>

      <div v-if="localError" class="error">{{ localError }}</div>
      <div v-if="error" class="error">{{ error }}</div>
      <div v-if="success" class="success">
        {{ success }} <br />
        <router-link to="/login">Aller à la page de connexion</router-link>
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Chargement...' : "S'inscrire" }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import { useCartStore } from '@/stores/cartStore'

const API_URL = import.meta.env.VITE_API_URL
const username = ref('')
const email = ref('')
const password = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)
const localError = ref('')

const router = useRouter()
const userStore = useUserStore()
const cartStore = useCartStore()

const isUsernameValid = computed(() => username.value.length >= 3)
const isPasswordValid = computed(() => password.value.length >= 6)
const isEmailValid = computed(() =>
  /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)
)

const submitRegister = async () => {
  error.value = ''
  success.value = ''
  localError.value = ''

  if (!isUsernameValid.value) {
    localError.value = "Le nom d’utilisateur doit contenir au moins 3 caractères."
    return
  }
  if (!isEmailValid.value) {
    localError.value = "Adresse e-mail invalide."
    return
  }
  if (!isPasswordValid.value) {
    localError.value = "Le mot de passe doit contenir au moins 6 caractères."
    return
  }

  loading.value = true
  try {
    const res = await axios.post(`${API_URL}/api/register`, {
      username: username.value,
      email: email.value,
      password: password.value,
    })
    userStore.setToken(res.data.token)
    await cartStore.fetchCart() // Charger le panier après inscription
    success.value = "Compte créé avec succès !"
    setTimeout(() => {
      router.push('/products')
    }, 2000)
  } catch (err) {
    error.value = err.response?.data?.error || "Erreur lors de la création du compte."
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register {
  max-width: 350px;
  margin: 2rem auto;
  padding: 1.5rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  background: #fff;
}
h2 {
  text-align: center;
  margin-bottom: 1rem;
}
form > div {
  margin-bottom: 1rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
}
input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
input.invalid {
  border-color: red;
}
.error {
  color: red;
  margin-top: 0.5rem;
}
.success {
  color: green;
  margin-top: 0.5rem;
}
button {
  width: 100%;
  padding: 0.5rem;
  background-color: #007bff;
  border: none;
  color: white;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
}
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
