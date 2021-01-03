<template>
  <div class="container max-w-5xl mx-auto p-5">
    <div class="pb-6 clearfix" v-if="!server_error">
      <nuxt-link
        to="compose"
        class="btn hover:bg-red-700 float-left px-4 bg-red-600 rounded text-white shadow-md block py-3"
        >Compose mail</nuxt-link
      >
    </div>

    <div v-if="server_error && !loading" class="p-10 border-2 rounded border-red-700 text-2xl text-center text-red-800">
      Sorry, something's wrong with API server 😥
    </div>

    <div v-if="mails.total===0 && !server_error && !loading" class="p-10 border-2 rounded border-gray-700 text-2xl text-center text-gray-800">
      No Mail added yet 😋
    </div>

    <div class="flex px-2 text-gray-700 py-1 rounded mb-6 text-sm w-full" v-if="mails.total>0">
      <span class="flex-grow">
        <span class="text-gray-900">Page:</span> {{ mails.current_page }} / {{ mails.last_page }}
      </span>
      <span class="flex-grow text-right">
        <span class="text-gray-900">Total Mails:</span> {{ mails.total }}
      </span>
    </div>

    <div class="form block row md:flex" v-if="mails.total>0">
      <div class="flex-shrink">
        <label for="status">Status</label>
        <select v-model="search.status" name="" id="status">
          <option value="">All</option>
          <option value="posted">Posted</option>
          <option value="sent">Sent</option>
          <option value="failed">Failed</option>
        </select>
      </div>
      <div class="flex-grow">
        <label for="sender">Sender</label>
        <input
          v-model="search.from"
          class="w-full"
          type="text"
          name=""
          id="sender"
        />
      </div>
      <div class="flex-grow">
        <label for="recipient">Recipient</label>
        <input
          v-model="search.to"
          type="text"
          class="w-full"
          name=""
          id="recipient"
        />
      </div>
      <div class="flex-grow">
        <label for="subject">Subject</label>
        <input
          v-model="search.subject"
          type="text"
          name=""
          class="w-full"
          id="subject"
        />
      </div>
      <div class="items-end flex pt-3">
        <button class="btn search" @click="searchFilter">Get'em</button>
        <button class="btn clean" @click="clean">Clean</button>
      </div>
    </div>

    <div class="loader" v-if="loading">Loading</div>

    <table class="mail-list w-full" v-if="mails.data && !loading">
      <tbody>
        <tr
          v-for="(mail, key) in mails.data"
          :key="key"
          class="border-b py-2 focus:bg-yellow-100 hover:bg-blue-100 cursor-pointer"
          @click="$router.push(mail.id + '/')"
        >
            <td class="text-center block sm:table-cell py-3 px-4 w-full sm:w-1/12">
              <StatusBadge
                :status="mail.status"
              />
            </td>
            <td class="py-3 pl-3 block sm:table-cell w-full sm:w-5/12">
              <b>F:</b> {{ mail.from_name }}
              <span class="text-gray-500 text-xs"
                >&lt;{{ mail.from_email }}&gt;</span
              >
              <br />
              <b>T:</b> {{ mail.to_name }}
              <span class="text-gray-500 text-xs"
                >&lt;{{ mail.to_email }}&gt;</span
              >
            </td>
            <td class="p-3 block sm:table-cell w-full sm:w-6/12">
              {{ mail.subject }}
            </td>
        </tr>
      </tbody>
    </table>

    <Paginate
      v-if="mails.last_page > 1"
      v-model="mails.current_page"
      class="flex-wrap"
      :page-count="mails.last_page"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'"
    >
    </Paginate>
  </div>
</template>

<script>
import StatusBadge from '@/components/StatusBadge'
import Paginate from 'vuejs-paginate//src//components//Paginate.vue'

export default {
  name: "home",
  components: {
    Paginate,
    StatusBadge,
  },
  scrollToTop: true,
  data() {
    return {
      loading: true,
      server_error: false,
      mails: {
        last_page: 1,
        current_page: 1,
        total: 0,
      },
      search: {
        status: "",
      },
    };
  },
  computed: {
    params() {
      let params = {}
      for (const [key, value] of Object.entries(this.search)) {
        if (value.trim() !== "") params[key] = value.trim()
      }
      return { params }
    },
  },
  methods: {
    async changePage(pageNum) {
      this.loading = true
      this.mails = await this.$axios.$get(
        "/api/mail?page=" + pageNum,
        this.params
      )
      this.$router.push({ query: { ...this.$route.query, page: pageNum } })
      this.loading = false
    },
    async searchFilter() {
      this.loading = true
      try {
        this.mails = await this.$axios.$get("/api/mail", this.params, {
          headers: { "Content-Type": "application/json" },
        });
      } catch (error) {
        console.log(error.response)
      }
      this.$router.push({ query: { ...this.$route.query, page: 1 } })
      this.loading = false
    },
    async clean() {
      this.loading = true
      this.search = { status: "" }
      this.mails = await this.$axios.$get("/api/mail")
      this.loading = false
    },
  },
  async fetch() {
    let page = this.$route.query.page ?? 1
    try {
      this.mails = await this.$axios.$get("/api/mail?page=" + page)
      this.loading = false
    } catch (error) {
        this.loading = false
        this.server_error = true
        console.log(error.response)
    }
  },
  head() {
    let title = "miniSend";
    let description = "SEO Description";

    return {
      title: title,
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        { hid: "description", name: "description", content: description },
      ],
    };
  },
};
</script>

<style lang="postcss">
tr:nth-child(even) {
  @apply bg-gray-100;
}
tr:nth-child(odd) {
  @apply bg-white;
}
table.mail-list {
  @apply mb-6 shadow-lg border rounded overflow-hidden;
}
</style>
