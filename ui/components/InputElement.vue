<template>
  <div :class="{ 'error' : error }">
    <label :for="id" v-if="label">{{ label }}</label>
    <label :for="id" v-else class="invisible">&nbsp;</label>
    <input
      :id="id"
      :placeholder="placeholder"
      :value="value" 
      :type="type" 
      class="w-full"
      @input="$emit('input', $event.target.value)"
    />
    <span class="text-sm text-red-700 p-2" v-if="error">{{
      error[0]
    }}</span>
  </div>
</template>
<script>
export default {
  props: {
    id: String,
    value: String,
    placeholder: {
      type: String,
      default: null,
    },
    label: String,
    error: {
      type: [Boolean, Array],
      default: false
    },
    type: {
      type: String,
      default: 'text',
      validator(value) {
        return ['text', 'email', 'password'].includes(value);
      }
    },
  },
}
</script>