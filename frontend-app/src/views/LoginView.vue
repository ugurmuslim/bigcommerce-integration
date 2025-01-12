<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
      <h1 class="text-2xl font-semibold text-center text-gray-700 mb-6">Login</h1>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
          <input
            v-model="email"
            type="text"
            id="email"
            placeholder="Enter your username"
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
          <input
            v-model="password"
            type="password"
            id="password"
            placeholder="Enter your password"
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <button
          type="submit"
          @click="login"
          class="w-full py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      email: 'optimum@example.com',
      password: 'password',
    };
  },
  methods: {
    async login() {
      try {
        console.log('Logging in...');
        console.log('email:', this.email);
        console.log('Username:', this.password);
        const response = await axios.post('http://localhost/api/login', {
          email: this.email,
          password: this.password,
        }, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        const {token} = response.data.data;
        console.log('Login successful:', token);
        localStorage.setItem('auth_token', token);
        this.$router.push({ name: 'categories' });
      } catch (error) {
        console.error('Login failed:', error);
      }
    },
  },
};
</script>
