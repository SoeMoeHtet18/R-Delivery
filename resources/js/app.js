import './bootstrap.js';
import {createApp} from 'vue';

//Main pages
import Dashboard from './components/Dashboard.vue';
import CreateBulkOrder from './pages/orders/order_bulk_create/CreateBulkOrder.vue';

const app = createApp({});
app.component('dashboard', Dashboard);
app.component('create-bulk-order', CreateBulkOrder);
app.mount('#app');
