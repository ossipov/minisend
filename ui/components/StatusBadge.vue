<template>
  <div
    class="text-xs pb-1 px-1 rounded clearfix"
    :class="statusColor()"
  >
    {{ status }}
  </div>
</template>
<script>
export default {
  props: {
    status: String
  },
  methods: {
    statusColor() {
      let color = "bg-green-600 text-green-200"
      if (this.status === "failed") color = "bg-red-600 text-red-200"
      if (this.status === "posted") color = "bg-yellow-600 text-yellow-200"
      return color
    }
  },
};
</script>
