<template>
  <ul
    class="rounded bg-white p-5 border border-gray-300 text-xs block overflow-x-auto whitespace-no-wrap"
  >
    <li class="ml-6 mb-2" v-for="(attachment, key) in attachments" :key="key">
      📄 {{ attachment.name }}
      <span class="text-gray-600"
        >({{ +(attachment.size / 1024).toFixed(3) }} Kb)</span
      >
    </li>
  </ul>
</template>
<script>
export default {
  props: {
    attachments: Array,
  },
};
</script>
