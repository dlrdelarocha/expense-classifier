<template>
  <div title="Log in" />
  <form @submit.prevent="submitForm">
    <div>
      <InputLabel for="email" value="Email" />

      <TextInput
        id="email"
        type="email"
        class="mt-1 block w-full"
        v-model="user.email"
        required
        autofocus
        autocomplete="username"
      />
    </div>

    <div class="mt-4">
      <InputLabel for="password" value="Password" />

      <TextInput
        id="password"
        type="password"
        class="mt-1 block w-full"
        v-model="user.password"
        required
        autocomplete="current-password"
      />
    </div>
    <InputError class="mt-2" :message="errors.message" />
    <div class="block mt-4">
      <label class="flex items-center">
        <Checkbox name="remember" v-model:checked="user.remember" />
        <span class="ms-2 text-sm text-gray-600">Remember me</span>
      </label>
    </div>

    <div class="flex items-center justify-end mt-4">
      <a
        href="/register"
        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Register
      </a>

      <PrimaryButton class="ms-4" :class="{ 'opacity-25': processing }" :disabled="processing"> Log in </PrimaryButton>
    </div>
  </form>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const user = ref({
  password: '',
  email: '',
  remember: false
})

const errors = ref({ message: '' })
const processing = ref(false)

const store = useStore()
const router = useRouter()

const submitForm = async () => {
  processing.value = true

  try {
    await store.dispatch('auth/login', {
      email: user.value.email,
      password: user.value.password
    })

    window.location.href = '/dashboard' //avoid render guest layout
  } catch (error) {
    errors.value.message = error.response.data.message
    setTimeout(() => {
      errors.value.message = ''
    }, 2000)
  }
  processing.value = false
}
</script>
