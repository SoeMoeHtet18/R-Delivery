import './bootstrap.js';
import {createApp} from 'vue';
import Dashboard from './components/Dashboard.vue';
import CreateBulkOrder from './pages/orders/order_bulk_create/CreateBulkOrder.vue';

const app = createApp({});

//Main pages

app.component('dashboard', Dashboard);
app.component('create-bulk-order', CreateBulkOrder);

// Components


app.mount('#app');
