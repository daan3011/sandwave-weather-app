import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import { createRouter, createWebHistory } from 'vue-router';

// Pages
import WeatherDashboardPage from './pages/WeatherDasboardPage.vue';
import WeatherMonitorsPage from './pages/WeatherMonitorsPage.vue';
import WeatherMonitorReadingsPage from './pages/WeatherMonitorReadingsPage.vue';

// Routes
const routes = [
  {
    path: '/',
    component: WeatherDashboardPage
  },
  {
    path: '/weather-monitors',
    component: WeatherMonitorsPage
  },
  {
    path: "/weather-monitor/:id",
    name: "WeatherMonitorReadings",
    component: WeatherMonitorReadingsPage,
    props: true,
  },
  { path: '/:pathMatch(.*)*',
    redirect: '/'
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const app = createApp(App);
app.use(router);
app.mount('#app');
