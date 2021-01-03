<template>
  <div class="container max-w-5xl mx-auto p-5">
    <div class="pb-6 clearfix">
      <nuxt-link
        to="/"
        class="btn float-left px-4 hover:bg-gray-700 bg-gray-600 text-gray-100 rounded shadow-md block py-3"
        >&lt; Back</nuxt-link
      >
    </div>

    <div v-if="sent" class="text-center border-green-400 rounded border-2 w-full px-16 py-24">
      <SuccessCheckmark />
      <div class=" text-green-700 text-3xl mb-6">
        We'll try to deliver your Message ASAP 🤞
      </div>
      <button @click="reload" class="btn hover:bg-red-700 px-4 bg-red-600 rounded text-white shadow-md  py-3">
        New Mail       
      </button>    
    </div>

    <div v-else class="form">
      <div class="block row md:flex mb-4 ">
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
          <label for="from_name">FROM:</label>
          <div class="flex">
            <div class="flex-grow items-end w-full md:w-1/2">
              <input
                v-model="mail.from_name"
                placeholder="Joe The Sender"
                class="w-full"
                type="text"
                name=""
                id="from_name"
              />
            </div>
            <div class="flex items-end w-full md:w-1/2 flex-wrap">
              <input
                v-model="mail.from_email"
                placeholder="joe@senders.we.are"
                class="flex-grow w-full"
                type="text"
                name=""
                id="from_email"
              />
              <span class="text-sm text-red-700 p-2" v-if="errors.from_email">{{
                errors.from_email[0]
              }}</span>
            </div>
          </div>
        </div>

        <div class="w-full md:w-1/2">
          <label for="to_name">TO:</label>
          <div class="flex">
            <div class="flex-grow w-1/2">
              <input
                v-model="mail.to_name"
                class="w-full"
                placeholder="Doe The Reciever"
                type="text"
                name=""
                id="to_name"
              />
            </div>
            <div class="flex-grow w-1/2 items-end mr-0">
              <input
                v-model="mail.to_email"
                class="w-full"
                placeholder="doe@receiver.i.am"
                type="text"
                name=""
                id="to_email"
              />
              <span class="text-sm text-red-700 p-2" v-if="errors.to_email">{{
                errors.to_email[0]
              }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="flex-grow mb-6">
        <label for="subject">SUBJECT:</label>
        <input
          v-model="mail.subject"
          placeholder="Excellent news! We got someone you've been looking for."
          class="w-full"
          type="text"
          name=""
          id="subject"
        />
        <span class="text-sm text-red-700 p-2" v-if="errors.subject">{{
          errors.subject[0]
        }}</span>
      </div>

      <client-only>
        <div class="mb-6">
          <Editor :content="mail.html" @content-update="setHtml" />
          <span class="text-sm text-red-700 p-2" v-if="errors.html">{{
            errors.html[0]
          }}</span>
        </div>
      </client-only>

      <div class="w-full mb-4">
        <label for="attachments">ATTACHMENTS:</label>
        <input id="attachments" class="w-full" type="file" @change="onFileChange" multiple />
        <span class="text-sm text-red-700 p-2" v-if="errors.attachments">{{
          errors.attachments[0]
        }}</span>
      </div>
      
      <div class="w-full mb-6">
        <AttachmentsList 
          v-if="mail.attachments.length > 0"
          :attachments="mail.attachments"
        />
      </div>

      <div class="clearfix">
        <button
          @click="send"
          class="btn hover:bg-red-700 float-left px-4 bg-red-600 rounded text-white shadow-md block py-3"
        >
          Send it!
        </button>
        <button
          @click="send50"
          class="btn hover:bg-gray-700 float-left px-4 bg-gray-600 rounded text-white shadow-md ml-6 block py-3"
        >
          Send ×50
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Editor from "@/components/Editor";
import AttachmentsList from '@/components/AttachmentsList'
import SuccessCheckmark from '@/components/SuccessCheckmark'

export default {
  components: {
    Editor,
    AttachmentsList,
    SuccessCheckmark,
  },
  data() {
    return {
      mail: {
        attachments: [],
        from_name: '',
      },
      sent: false,
      errors: {
        attachments: []
      },
    };
  },
  methods: {
    reload() {
      this.mail = { attachments: [] }
      this.sent = false
    },
    setHtml(content) {
      this.mail.html = content;
    },
    onFileChange(e) {
      this.mail.attachments = [...e.target.files]
    },
    async send50() {
      for(let i=0; i<50; i++) { 
        let request = await this.send().then(result => result)
        if (! request) break
      }
    },
    async send() {
      let form = new FormData();

      for (const [key, value] of Object.entries(this.mail)) {
        if (key === 'attachments') {
          for (const [k, file] of Object.entries(this.mail.attachments)) {
            form.append("attachments[]", file)
          }
        } else {
          form.append(key, value);
        }
      }

      try {
        let resp = await this.$axios.post("/api/mail", form, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
        this.sent = true
        this.errors = { attachments: [] }
        return resp
      } catch (error) {
        let attachments = []

        for (const [key, value] of Object.entries(error.response.data)) {
          if (key.includes('attachments.')) {
            attachments.push(value[0])
            delete error.response.data[key]
          } 
        }
        if (attachments.length > 0) {
          error.response.data.attachments = attachments
        }
        this.errors = error.response.data

        console.log(this.errors)
        return false
      }
    },
  },
  head() {
    let title = "Compose Mail";
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