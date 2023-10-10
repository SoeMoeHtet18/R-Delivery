import './bootstrap.js';
import 'devextreme/dist/css/dx.common.css';
import 'devextreme/dist/css/dx.light.css';

import {createApp} from 'vue';

//import pages
import CreateBulkOrder from './pages/orders/order_bulk_create/CreateBulkOrder.vue';
import Setting from './pages/setting/Setting.vue';
import ShopList from './pages/shop/ShopList.vue';
import UserList from './pages/user/UserList.vue';

//import components
import NavBar from './components/nav_bar/NavBar.vue';
import Dashboard from './components/Dashboard.vue';

const app = createApp({});

//Main pages

app.component('dashboard', Dashboard);
app.component('create-bulk-order', CreateBulkOrder);
app.component('setting', Setting);
app.component('shop-list', ShopList);
app.component('user-list', UserList);
// Components

app.component('nav-bar', NavBar);
app.mount('#app');
