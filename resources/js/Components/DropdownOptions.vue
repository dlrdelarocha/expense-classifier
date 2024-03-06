<template>
  <div class="mt-4">
    <InputLabel :value="label" :for="id" />
    <select :id="id" :name="id" class="mt-1 block w-full" v-model="selectedValue" required>
      <option value="" disabled selected>{{ placeholder }}</option>
      <option v-for="option in options" :key="option.id" :value="option.id">
        {{ option.name }}
      </option>
    </select>
  </div>
</template>

<script>
import InputLabel from '@/Components/InputLabel.vue'

export default {
  components: {
    InputLabel
  },
  props: {
    id: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    options: {
      type: Array,
      required: true
    },
    placeholder: {
      type: String,
      default: 'Select an option'
    },
    modelValue: {
      type: [String, Number],
      default: null
    }
  },
  data() {
    return {
      selectedValue: this.modelValue
    }
  },
  watch: {
    modelValue(newValue) {
      this.selectedValue = newValue
    },
    selectedValue(newValue) {
      this.$emit('update:modelValue', newValue)
    }
  }
}
</script>
