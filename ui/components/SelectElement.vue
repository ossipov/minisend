<template>
  <div>
    <label :for="id" v-if="label">{{ label }}</label>
    <select :id="id" class="form-control" @change="change">
      <option
        v-for="(option, index) in options" :key="index"
        :value="option.id"
        :selected="selectedOption(option)"
      >{{ option.text }}</option>
    </select>
    <span class="text-sm text-red-700 p-2" v-if="error">{{
      error
    }}</span>
  </div>
</template>
<script>
export default {
  props: {
    options: Array,
    id: String,
    label: String,
    error:  {
      type: [ String, Boolean ],
    }
  },

  data() {
    return {
      selected: null
    }
  },

  methods: {
    selectedOption(option) {
      if (this.value) {
        return option.id === this.value.code
      }
      return false
    },
    change(e) {
      const selectedCode = e.target.value;
      const option = this.options.find((option) => {
        return selectedCode === option.id
      });
      this.$emit("input", option.id)
    }
  }
}
</script>
