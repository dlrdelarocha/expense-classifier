<template>
  <template v-if="isAuthenticated">
    <authenticated-layout>
      <router-view></router-view>
    </authenticated-layout>
  </template>

  <template v-else>
    <guest-layout>
      <router-view></router-view>
    </guest-layout>
  </template>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { useStore } from 'vuex'

const store = useStore()
const isAuthenticated = store.getters['auth/isLoggedIn']

console.log('Asdasdsad', isAuthenticated)
</script>
