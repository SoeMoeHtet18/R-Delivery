import './bootstrap.js';
import 'devextreme/dist/css/dx.common.css';
import 'devextreme/dist/css/dx.light.css';

import {createApp} from 'vue';

//import pages
import CreateBulkOrder from './pages/orders/order_bulk_create/CreateBulkOrder.vue';
import Setting from './pages/setting/Setting.vue';
import ShopList from './pages/shop/ShopList.vue';
import ShopCreate from './pages/shop/ShopCreate.vue';
import ShopDetail from './pages/shop/ShopDetail.vue';
import UserList from './pages/user/UserList.vue';
import CityList from './pages/city/CityList.vue';

//import components
import NavBar from './components/nav_bar/NavBar.vue';
import Dashboard from './components/Dashboard.vue';
import Loader from './components/general/Loader.vue';
import SuccessPopUp from './components/general/SuccessPopUp.vue';
import FilterSubTable from './components/shop/shop_detail/FilterSubTable.vue';
import DisplayAmountBox from './components/shop/shop_detail/DisplayAmountBox.vue';

const app = createApp({});

//Main pages

app.component('dashboard', Dashboard);
app.component('create-bulk-order', CreateBulkOrder);
app.component('setting', Setting);
app.component('shop-list', ShopList);
app.component('shop-create', ShopCreate);
app.component('shop-detail', ShopDetail);

app.component('user-list', UserList);
app.component('city-list', CityList);
// Components

app.component('nav-bar', NavBar);
app.component('loader', Loader);
app.component('success-pop-up', SuccessPopUp);
app.component('filter-sub-table', FilterSubTable);
app.component('display-amount-box', DisplayAmountBox);

app.mount('#app');
