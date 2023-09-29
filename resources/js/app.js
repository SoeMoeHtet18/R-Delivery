import './bootstrap.js';
import 'devextreme/dist/css/dx.common.css';
import 'devextreme/dist/css/dx.light.css';
import {createApp} from 'vue';
import Dashboard from './components/Dashboard.vue';
import CreateBulkOrder from './pages/orders/order_bulk_create/CreateBulkOrder.vue';
import Setting from './pages/setting/Setting.vue';

const app = createApp({});

//Main pages

app.component('dashboard', Dashboard);
app.component('create-bulk-order', CreateBulkOrder);
app.component('setting', Setting);
// Components


app.mount('#app');
