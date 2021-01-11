<template>
  <div class="mx-auto container py-10 max-w-lg">
    <h1 class="text-2xl text-gray-800 font-bold text-center mb-10">
      <nuxt-link to="/">💌 miniSend</nuxt-link>
    </h1>

    <div class="form">
      <h1 class="text-lg font-bold mb-4">Sign-In</h1>

      <div class="w-full bg-yellow-200 text-yellow-900 p-4 mb-6 text-sm">
        You may Sign In as
        <a href="#" class="text-blue-600 underline" @click="setAdmin">Admin</a>
        /
        <a href="#" class="text-blue-600 underline" @click="setUser">User</a> or
        <nuxt-link to="/signup" class="text-blue-600 underline"
          >Sign Up</nuxt-link
        >
        for a new account
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

      <button
        @click="signin()"
        class="btn bg-green-500 hover:bg-green-600 text-white"
      >
        Sign In
      </button>
      <nuxt-link
        to="/signup"
        class="link text-blue-600 ml-4 px-2 hover:underline"
      >
        Sign Up
      </nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  layout: "empty",
  // middleware: 'auth',
  // auth: 'guest',
  data() {
    return {
      email: "",
      password: "",
      errors: {},
    };
  },
  methods: {
    async signin() {
      try {
        await this.$auth.loginWith("cookie", {
          data: {
            email: this.email,
            password: this.password,
          },
        });
      } catch (error) {
        this.errors = error.response.data.errors;
        // console.log(error.response.data.errors)
      }
    },

    async signup() {
      try {
        await this.$axios.get("/api/csrf-cookie");
        await this.$axios.post("/api/signup", {
          data: {
            email: this.email,
            password: this.password,
          },
        });
        // console.log(resp);
      } catch (error) {
        this.errors = error.response.data.errors;
        console.log(error.response.data);
      }
    },

    setAdmin() {
      this.email = "dmitry@ossipov.me";
      this.password = "123";
    },
    setUser() {
      this.email = "any@user.is";
      this.password = "here";
    },
    unsetForm() {
      this.email = "";
      this.password = "";
    },
  },
};
</script>
