<script setup lang="ts">
import { RouterLink, useRouter } from "vue-router";
</script>

<template>
  <header>
    <nav>
      <ul>
        <li v-for="route in useRouter().getRoutes()" :key="route.path">
          <router-link :to="route.path">{{ route.name }}</router-link>
        </li>
      </ul>
    </nav>
  </header>
</template>
