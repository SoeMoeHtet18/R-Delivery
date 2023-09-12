import './bootstrap.js';
import {createApp} from 'vue';
import Dashboard from './components/Dashboard.vue';
import CreateBulkOrder from './pages/orders/order_bulk_create/CreateBulkOrder.vue';
import BulkOrderCreateTable from './components/order/order_bulk_create/BulkOrderCreateTable.vue';

const app = createApp({});

//Main pages

app.component('dashboard', Dashboard);
app.component('create-bulk-order', CreateBulkOrder);

// Components

app.component('bulk-order-create-table', BulkOrderCreateTable);

app.mount('#app');
