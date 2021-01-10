<template>
  <div class="mx-auto container py-10 max-w-lg">
    <h1 class="text-2xl text-gray-800 font-bold text-center mb-10">
      <nuxt-link to="/">💌 miniSend</nuxt-link>
    </h1>

    <div
      v-if="sent"
      class="text-center border-green-400 rounded border-2 w-full px-16 py-24"
    >
      <SuccessCheckmark />
      <div class="text-green-700 text-3xl mb-6">
        Registration is complete 🥳
      </div>
      <nuxt-link
        to="/signin"
        class="btn hover:bg-red-700 px-4 bg-red-600 rounded text-white shadow-md py-3"
      >
        Sign In
      </nuxt-link>
    </div>

    <div v-else class="form">
      <h1 class="text-lg font-bold mb-4">Sign-Up</h1>

      <div class="mb-4 w-full">
        <label for="name" class="block">Name</label>
        <input
          v-model="name"
          class="w-full"
          type="text"
          name="name"
          id="name"
        />
        <span class="text-sm text-red-700 p-2" v-if="errors.name">{{
          errors.name[0]
        }}</span>
      </div>

      <div class="mb-4 w-full">
        <label for="email" class="block">Email</label>
        <input
          v-model="email"
          class="w-full"
          type="text"
          name="email"
          id="email"
        />
        <span class="text-sm text-red-700 p-2" v-if="errors.email">{{
          errors.email[0]
        }}</span>
      </div>

      <div class="mb-6 w-full">
        <label for="password" class="block">Password</label>
        <input
          type="password"
          name="password"
          class="w-full"
          id="password"
          v-model="password"
        />
        <span class="text-sm text-red-700 p-2" v-if="errors.password">{{
          errors.password[0]
        }}</span>
      </div>

      <div class="mb-6 w-full">
        <label for="password_confirmation" class="block"
          >Password Confirmation</label
        >
        <input
          type="password"
          name="password_confirmation"
          class="w-full"
          id="password_confirmation"
          v-model="password_confirmation"
        />
      </div>

      <button
        @click="signup()"
        class="btn bg-green-500 hover:bg-green-600 text-white"
      >
        Sign Up
      </button>

      <nuxt-link
        to="/signin"
        class="link text-blue-600 ml-4 px-2 hover:underline"
      >
        Sign In
      </nuxt-link>
    </div>
  </div>
</template>

<script>
import SuccessCheckmark from '@/components/SuccessCheckmark'

export default {
  layout: "empty",
  auth: "guest",
  components: {
    SuccessCheckmark
  },
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      errors: {},
      sent: false,
    };
  },
  methods: {
    async signup() {
      try {
        await this.$axios.get("/api/csrf-cookie");
        await this.$axios.post("/api/signup", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password,
        });
        this.sent = true;
      } catch (error) {
        this.errors = error.response.data.errors;
        console.log(error.response.data);
      }
    },
  },
};
</script>
