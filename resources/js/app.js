import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import { createRouter, createWebHistory } from 'vue-router';

// Pages
import WeatherDashboardPage from './pages/WeatherDasboardPage.vue';
import WeatherMonitorsPage from './pages/WeatherMonitorsPage.vue';

// Routes
const routes = [
  { path: '/', component: WeatherDashboardPage },
  { path: '/monitors', component: WeatherMonitorsPage },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const app = createApp(App);
app.use(router);
app.mount('#app');
