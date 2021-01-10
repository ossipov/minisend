<template>
  <div class="flex flex-col">
    <section class="header bg-gray-300">
      <div class="mx-auto py-10 container px-6 max-w-5xl flex">
        <h1 class="text-2xl text-gray-800 font-bold">
          <nuxt-link to="/">💌 miniSend</nuxt-link>
        </h1>
        <div class="text-right flex-grow">
          <a href="http://127.0.0.1:8025" target="_blank"  rel="noreferrer" class="btn bg-gray-600 hover:bg-gray-700 text-gray-100 text-sm">MailHog</a>
          <button class="btn bg-gray-800 hover:bg-gray-900 text-gray-100 text-sm" v-if="$auth.loggedIn" @click="logout">Log Out</button>
        </div>
      </div>
    </section>
    <nuxt class="h-full mb-auto" />
  </div>
</template>
<script>
export default {
  methods: {
    async logout() {
      await this.$auth.logout()
      this.$router.go('/logout')
    }
  },
}
</script>