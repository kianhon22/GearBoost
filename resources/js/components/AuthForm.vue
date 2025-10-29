<template>
  <div class="min-h-screen flex items-center justify-center bg-purple-50 px-4 py-12">
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold bg-purple-700 bg-clip-text text-transparent mb-2">
          {{ isLogin ? 'Welcome Back!' : 'Join GearBoost' }}
        </h1>
        <p class="text-gray-600">
          {{ isLogin ? 'Sign in to continue shopping' : 'Create your account to get started' }}
        </p>
      </div>

      <div class="bg-white rounded-2xl shadow-2xl p-8">
        <div class="flex gap-2 mb-8 bg-gray-100 p-1 rounded-lg">
          <button
            @click="isLogin = true"
            :class="[
              'flex-1 py-3 rounded-lg font-semibold transition-all duration-200',
              isLogin 
                ? 'bg-purple-700 text-white shadow-md' 
                : 'text-gray-600 hover:text-gray-900'
            ]"
          >
            Login
          </button>
          <button
            @click="isLogin = false"
            :class="[
              'flex-1 py-3 rounded-lg font-semibold transition-all duration-200',
              !isLogin 
                ? 'bg-purple-700 text-white shadow-md' 
                : 'text-gray-600 hover:text-gray-900'
            ]"
          >
            Register
          </button>
        </div>

        <form @submit.prevent="handleSubmit">
          <div v-if="!isLogin" class="mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
            <input
              type="text"
              id="name"
              v-model="form.name"
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              placeholder="Enter your full name"
              required
            />
            <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name[0] }}</p>
          </div>

          <div class="mb-6">
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
            <input
              type="email"
              id="email"
              v-model="form.email"
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              placeholder="Enter your email"
              required
            />
            <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email[0] }}</p>
          </div>

          <div class="mb-6">
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input
              type="password"
              id="password"
              v-model="form.password"
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              placeholder="Enter your password"
              required
            />
            <p v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password[0] }}</p>
          </div>

          <div v-if="!isLogin" class="mb-6">
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
            <input
              type="password"
              id="password_confirmation"
              v-model="form.password_confirmation"
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              placeholder="Confirm your password"
              required
            />
          </div>

          <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-600">{{ errorMessage }}</p>
          </div>

          <loading-button
            :text="isLogin ? 'Sign In' : 'Create Account'"
            :loadingText="isLogin ? 'Signing In...' : 'Creating Account...'"
            :loading="loading"
            type="submit"
            variant="primary"
            class="w-full py-3"
          />
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            {{ isLogin ? "Don't have an account?" : "Already have an account?" }}
            <button
              @click="isLogin = !isLogin"
              class="text-purple-600 hover:text-purple-600 hover:cursor-pointer font-semibold ml-1"
            >
              {{ isLogin ? 'Register' : 'Login' }}
            </button>
          </p>
        </div>

        <div class="mt-4 text-center">
          <a href="/" class="text-sm text-purple-600 hover:text-gray-800">
            ← Continue Browse Products
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthForm',
  data() {
    return {
      isLogin: true,
      loading: false,
      errorMessage: '',
      errors: {},
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    };
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.errorMessage = '';
      this.errors = {};

      const endpoint = this.isLogin ? '/login' : '/register';
      const data = this.isLogin 
        ? { email: this.form.email, password: this.form.password }
        : this.form;

      try {
        const response = await axios.post(endpoint, data);

        if (response.data.success) {
          window.location.href = response.data.redirect || '/';
        } else {
          this.errorMessage = response.data.message || 'An error occurred';
        }
      } catch (error) {  
        this.errorMessage = 'An error occurred. Please try again.';
      } finally {
        this.loading = false;
      }
    }
  },
  watch: {
    isLogin() {
      this.errors = {};
      this.errorMessage = '';
    }
  }
};
</script>

