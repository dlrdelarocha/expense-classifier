<template>
  <div title="Register" />

  <form @submit.prevent="submitForm">
    <div>
      <InputLabel for="name" value="Name" />

      <TextInput
        id="name"
        type="text"
        class="mt-1 block w-full"
        v-model="user.name"
        required
        autofocus
        autocomplete="name"
      />
    </div>

    <div class="mt-4">
      <InputLabel for="email" value="Email" />

      <TextInput id="email" type="email" class="mt-1 block w-full" v-model="user.email" required />
    </div>

    <div class="mt-4">
      <InputLabel for="password" value="Password" />

      <TextInput
        id="password"
        type="password"
        class="mt-1 block w-full"
        v-model="user.password"
        required
        autocomplete="new-password"
      />
    </div>

    <div class="mt-4">
      <InputLabel for="password_confirmation" value="Confirm Password" />

      <TextInput
        id="password_confirmation"
        type="password"
        class="mt-1 block w-full"
        v-model="user.password_confirmation"
        required
        autocomplete="new-password"
      />
    </div>
    <InputError class="mt-2" :message="errors.message" />
    <div class="flex items-center justify-end mt-4">
      <a
        href="/login"
        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Already registered?
      </a>

      <PrimaryButton class="ms-4" :class="{ 'opacity-25': processing }" :disabled="processing">
        Register
      </PrimaryButton>
    </div>
  </form>
</template>

<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const user = ref({
  name: '',
  password: '',
  email: '',
  password_confirmation: ''
})

const errors = ref({ message: '' })
const processing = ref(false)

const store = useStore()
const router = useRouter()

const submitForm = async () => {
  processing.value = true

  console.log({
    name: user.value.name,
    email: user.value.email,
    password: user.value.password,
    password_confirmation: user.value.password_confirmation
  })

  try {
    await store.dispatch('auth/register', {
      name: user.value.name,
      email: user.value.email,
      password: user.value.password,
      password_confirmation: user.value.password_confirmation
    })

    window.location.href = '/dashboard'
  } catch (error) {
    errors.value.message = error.response.data.message
    console.log(error)
    setTimeout(() => {
      errors.value.message = ''
    }, 2000)
  }
  processing.value = false
}
</script>
