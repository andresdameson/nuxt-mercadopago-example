<template>
  <section class="container">
    <div>
      <app-logo/>
      <h1 class="title">
        Example of implementation of MercadoPago API on NUXT
      </h1>
      <div class="links">
        <a
          href="collect-card-information"
          class="button--green"
        >
          Start!
        </a>
      </div>
    </div>
  </section>
</template>

<script>
import AppLogo from '~/components/AppLogo.vue'

export default {
  components: {
    AppLogo
  }
}
</script>

<style>
.container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.title {
  font-weight: 300;
  font-size: 42px;
  color: #526488;
  word-spacing: 5px;
  padding-bottom: 15px;
  max-width: 600px;
}

.links {
  padding-top: 15px;
}
</style>
