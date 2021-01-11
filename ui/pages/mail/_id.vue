<template>
  <div class="container max-w-5xl mx-auto p-5">
    <div class="pb-6 clearfix">
      <a href="#"
        @click="goBack"
        class="btn float-left px-4 hover:bg-gray-700 bg-gray-600 text-gray-100 rounded shadow-md block py-3"
        >&lt; Back</a
      >
    </div>

    <div class="loader" v-if="loading">Loading</div>

    <div class="form" v-if="mail && !loading">
      <div class="w-full mb-6">
        <StatusBadge
          class="float-left text-base mr-3"
          :status="mail.status"
        /> 📅 {{ mailDate }}
      </div>
      <div class="w-full">
        <b>F:</b> {{ mail.from_name }}
        <span class="text-gray-500 text-xs">&lt;{{ mail.from_email }}&gt;</span>
      </div>
      <div class="w-full mb-6">
        <b>T:</b> {{ mail.to_name }}
        <span class="text-gray-500 text-xs">&lt;{{ mail.to_email }}&gt;</span>
      </div>
      <div class="w-full mb-6">
        <h1 class="text-lg font-bold">{{ mail.subject }}</h1>
      </div>

      <b>HTML:</b>
      <div class="w-full mb-6 editor__content p-5 border rounded bg-white" v-html="mail.html">
      </div>

      <b>TEXT:</b>
      <div class="w-full mb-6 p-5 border rounded bg-white" v-html="brText">
      </div>

      <div v-if="mail.attachments.length > 0">
        <b>ATTACHMENTS:</b>
        <AttachmentsList
          :attachments="mail.attachments"
        />
      </div>

    </div>


  </div>
</template>

<script>
import StatusBadge from '@/components/StatusBadge'
import AttachmentsList from '@/components/AttachmentsList'

export default {
  data() {
    return {
      mail: {},
      loading: true,
    };
  },
  components: {
    StatusBadge,
    AttachmentsList
  },
  methods: {
    goBack() {
      // console.log(this.$router)
      this.$router.back()
      // $router.go(-1)
    }
  },
  async fetch() {
    this.mail = await this.$axios.$get("/api/mail?id=" + this.$route.params.id)
    this.mail.attachments = JSON.parse(this.mail.attachments)
    this.loading = false
  },
  computed: {
    mailDate: function () {
      let date = new Date(this.mail.updated_at)
      let months = ['January','February', 'March', 'April', 'May', 'January', 'July', 'August', 'September', 'October', 'November', 'December']
      // let days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']
      // let day = days[date.getDay()]
      let month = months[date.getMonth()]

      return  month + ' ' + date.getDate() + ', '+ date.getFullYear() + ' [' 
        + ("0" + date.getHours()).slice(-2) + ':' 
        + ("0" + date.getMinutes()).slice(-2) + ']'
    },
    brText: function() {
      return this.mail.text.replace(/(?:\r\n|\r|\n)/g, '<br />')
    }
  },
  head() {
    let title = this.mail.subject
    let description = "SEO Description"

    return {
      title: title,
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        { hid: "description", name: "description", content: description },
      ],
    }
  }
}

</script>
