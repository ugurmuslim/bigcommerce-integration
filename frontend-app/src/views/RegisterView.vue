<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
      <h1 class="text-2xl font-semibold text-center text-gray-700 mb-6">Register</h1>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-600">Company Name</label>
          <input
            v-model="name"
            type="text"
            id="name"
            placeholder="Enter your Company Name"
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <div class="mb-4">
          <label for="username" class="block text-sm font-medium text-gray-600">Email</label>
          <input
            v-model="email"
            type="text"
            id="email"
            placeholder="Enter your email"
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
          class="w-full py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Register
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import {useToast} from 'vue-toast-notification';

const toast = useToast();

export default {
  data() {
    return {
      name: '',
      email: 'optimum7@example.com',
      password: 'password',
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post('http://localhost/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
        }, {
          headers: {
            'Content-Type': 'application/json',
          },
        });
        toast.success(`Successfully Registered. Congrats!. Please Login`, {
          duration: 3000,
          position: 'top-right',
        });
      } catch (error) {
        if (error.response) {
          toast.error(`Register failed: ${error.response.data.error || 'Unknown error'}`, {
            duration: 3000,
            position: 'top-right',
          });
        } else if (error.request) {
          toast.error('Register failed: No response from server', {
            duration: 3000,
            position: 'top-right',
          });
        } else {
          console.error('Error', error.message);
          toast.error(`Register failed: ${error.message}`, {
            duration: 3000,
            position: 'top-right',
          });
        }
      }
    }
  }
}
</script>
