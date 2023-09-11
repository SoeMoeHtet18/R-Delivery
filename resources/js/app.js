import './bootstrap.js';
import {createApp} from 'vue';

//Main pages
import Dashboard from './components/Dashboard.vue';
import BulkOrderCreateField from './components/order/order_bulk_create/OrderCreateFieldComponents.vue';

const app = createApp({});
app.component('dashboard', Dashboard);
app.component('bulk-order-create-field', BulkOrderCreateField);
app.mount('#app');
