<template>
  <div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="hero-section text-white text-center py-5" style="background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);">
      <div class="container py-5">
        <h1 class="display-3 fw-bold mb-3">Bienvenue sur Ma Boutique</h1>
        <p class="lead mb-4">Découvrez nos produits de qualité aux meilleurs prix</p>
        <router-link to="/products" class="btn btn-light btn-lg px-5">
          Voir nos produits
        </router-link>
      </div>
    </div>

    <!-- Categories -->
    <div class="container py-5">
      <h2 class="text-center mb-5">Catégories Populaires</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card shadow h-100">
            <div class="card-body text-center py-4">
              <i class="bi bi-laptop display-1 text-primary mb-3"></i>
              <h5 class="card-title">Électronique</h5>
              <p class="card-text text-muted">Découvrez nos produits électroniques</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow h-100">
            <div class="card-body text-center py-4">
              <i class="bi bi-watch display-1 text-success mb-3"></i>
              <h5 class="card-title">Accessoires</h5>
              <p class="card-text text-muted">Complétez votre style</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow h-100">
            <div class="card-body text-center py-4">
              <i class="bi bi-house-door display-1 text-primary mb-3"></i>
              <h5 class="card-title">Maison</h5>
              <p class="card-text text-muted">Pour votre confort</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="bg-light py-5">
      <div class="container">
        <div class="row text-center g-4">
          <div class="col-md-3">
            <i class="bi bi-truck display-4 text-success mb-3"></i>
            <h6>Livraison Rapide</h6>
            <p class="text-muted small">Sous 48h</p>
          </div>
          <div class="col-md-3">
            <i class="bi bi-shield-check display-4 text-success mb-3"></i>
            <h6>Paiement Sécurisé</h6>
            <p class="text-muted small">100% sécurisé</p>
          </div>
          <div class="col-md-3">
            <i class="bi bi-arrow-repeat display-4 text-success mb-3"></i>
            <h6>Retours Gratuits</h6>
            <p class="text-muted small">30 jours</p>
          </div>
          <div class="col-md-3">
            <i class="bi bi-headset display-4 text-success mb-3"></i>
            <h6>Support 24/7</h6>
            <p class="text-muted small">À votre écoute</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
