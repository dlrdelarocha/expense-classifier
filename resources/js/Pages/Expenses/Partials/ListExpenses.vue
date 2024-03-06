<template>
  <section>
    <div class="bg-white p-8 rounded-md w-full">
      <div class="flex items-center justify-between pb-6">
        <div>
          <h2 class="text-gray-600 font-semibold">Expenses</h2>
          <span class="text-xs">All expenses</span>
        </div>
        <div class="flex items-center justify-between">
          <div class="lg:ml-40 ml-10 space-x-8">
            <button
              @click="openModal(true)"
              class="bg-indigo-600 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer"
            >
              New Expense
            </button>
          </div>
        </div>
      </div>
      <div>
        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
          <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
              <thead>
                <tr>
                  <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                  >
                    Description
                  </th>
                  <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                  >
                    Amount
                  </th>
                  <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                  >
                    Spent at
                  </th>
                  <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                  >
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody v-for="expense in expenses">
                <tr>
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <div class="flex items-center">
                      <div class="ml-3">
                        <p class="text-gray-900 whitespace-no-wrap">
                          {{ expense.name }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">$ {{ expense.amount }}</p>
                  </td>
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">
                      {{ expense.spent_at }}
                    </p>
                  </td>
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <button class="text-gray-900 whitespace-no-wrap" @click="deleteExpense(expense.id)">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <CreateExpenseModal
        @onCreated:expenseCreated="expenseCreated"
        @onCloseModal:closeModal="closeModal"
        :is-open="isModalOpen"
        :categories="categories"
      >
      </CreateExpenseModal>
    </div>
  </section>
</template>
<script setup>
import { ref, onMounted, defineProps } from 'vue'
import axios from 'axios'
import CreateExpenseModal from '@/Pages/Expenses/Partials/CreateExpenseModal.vue'

const isModalOpen = ref(false)
const expenses = ref([])
const categories = ref([])

const openModal = (value) => {
  isModalOpen.value = value
  getCategories()
}

const closeModal = (value) => {
  isModalOpen.value = value
}

const expenseCreated = (newExpense) => {
  expenses.value.unshift(newExpense)
  isModalOpen.value = false
}

onMounted(async () => {
  await getExpenses()
})

//todo include try catch
const getExpenses = async () => {
  const response = await axios.get('api/expenses')
  expenses.value = response.data.data
}

const getCategories = async () => {
  const response = await axios.get('api/categories')
  categories.value = response.data.data
}

const deleteExpense = async (id) => {
  await axios.delete(`api/expenses/${id}`)
  expenses.value = expenses.value.filter((c) => c.id !== id)
}
</script>
