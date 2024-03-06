<template>
  <label class="block font-medium text-sm text-gray-700">
    <input
      v-if="!uploading"
      type="file"
      accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
      @change="handleFileChange"
    />
    <span v-if="uploading">Validating and Importing items...</span>
    <span v-if="message" class="text-green-600">
      <p>
        {{ message }}
      </p>
    </span>

    <span v-else-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
  <div class="bg-blue-100 p-4 mt-4 rounded">
    <p class="text-blue-500 text-xs">
      Note: Duplicate expenses will not be saved. An expense is considered duplicate when the name, amount, and date of
      the expense are identical.
    </p>
  </div>
</template>

<script setup>
import { ref, defineProps } from 'vue'
import axios from 'axios'

const file = ref(null)
const uploading = ref(false)
const message = ref('')
const errors = ref({ message: '' })

defineProps({
  value: {
    type: String
  }
})

const handleFileChange = async (event) => {
  const uploadedFile = event.target.files[0]

  if (uploadedFile) {
    const fileName = uploadedFile.name
    if (!isValidFile(fileName)) {
      message.value = 'Please select a valid file with .csv or .xlsx extension.'
      return
    }

    uploading.value = true

    const formData = new FormData()
    formData.append('file', uploadedFile)

    try {
      const response = await axios.post('api/expenses/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })

      file.value = uploadedFile
      message.value = response.data.message
    } catch (error) {
      errors.value.message = error.response.data.message
      setTimeout(() => {
        errors.value.message = ''
      }, 3000)
    }

    setTimeout(() => {
      event.target.value = null
      message.value = ''
    }, 5000)

    uploading.value = false
  }
}

const isValidFile = (fileName) => {
  const validExtensions = ['.csv', '.xlsx']
  const fileExtension = fileName.slice(fileName.lastIndexOf('.'))
  return validExtensions.includes(fileExtension)
}
</script>
