<template>
  <!-- El modal -->
  <teleport to="body">
    <transition name="modal-fade">
      <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-25">
        <div class="bg-white rounded p-6 min-w-96">
          <button @click="closeModal" class="float-right">X</button>

          <h2 class="text-lg font-medium mb-4">Create new expense</h2>

          <form @submit.prevent="submitForm">
            <div>
              <InputLabel for="name" value="Name" />

              <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="expense.name"
                required
                autofocus
                autocomplete="name"
              />
            </div>

            <div class="mt-4">
              <InputLabel for="amount" value="Amount" />

              <input
                id="amount"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="expense.amount"
                ref="input"
              />
            </div>
            <div class="mt-4">
              <InputLabel for="date" value="Date" />
              <TextInput id="date" type="date" class="mt-1 block w-full" v-model="expense.spent_at" required />
            </div>

            <div class="mt-4">
              <DropdownOptions id="category" label="Category" :options="categories" v-model="expense.category_id" />
            </div>
            <div class="flex items-center justify-end mt-4">
              <button @click.prevent="closeModal" class="float-right">Cancel</button>
              <PrimaryButton class="ms-4" :class="{ 'opacity-25': processing }" :disabled="processing">
                Create
              </PrimaryButton>
            </div>
          </form>
          <InputError class="mt-2" :message="errors.message" />
        </div>
      </div>
    </transition>
  </teleport>
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref, defineProps } from 'vue'
import DropdownOptions from '@/Components/DropdownOptions.vue'
import axios from 'axios'
import InputError from '@/Components/InputError.vue'

const processing = ref(false)

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  categories: {
    type: Array,
    required: true
  }
})

const expense = ref({
  name: '',
  amount: '',
  spent_at: '',
  category_id: null
})

const errors = ref({ message: '' })

const emit = defineEmits(['onCloseModal:closeModal', 'onCreated:expenseCreated'])

const closeModal = () => {
  emit('onCloseModal:closeModal', false)
}

const resetExpense = () => {
  expense.value.name = ''
  expense.value.amount = ''
  expense.value.spent_at = ''
  expense.value.category_id = null
}

const submitForm = async () => {
  try {
    const response = await axios.post('/api/expenses', expense.value)
    emit('onCreated:expenseCreated', response.data.data)
    resetExpense()
  } catch (error) {
    errors.value.message = error.response.data.message
    setTimeout(() => {
      errors.value.message = ''
    }, 2000)
  }
}
</script>

<style>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
