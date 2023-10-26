<template>
    <div class="px-9 pt-2 pb-1 font-lato">
        <div id="filterContentContainer"
            ref="filterContentContainer"
        >
            <!-- page title -->
            <h1 class="page-title mb-7">SHOP</h1>
            <div class="flex mb-5">
                <!-- search box -->
                <SearchBox @search="getDataBySearch"/>
                <!-- search toggle -->
                <iconify-icon icon="prime:filter-fill" width="30" class="mx-3" @click="toggleSearch"></iconify-icon>
                <!-- create new shop btn -->
                <button class="bg-main text-white rounded-sm px-4 pb-0.5" @click="createShop">Create New Shop</button>
                <shop-create v-if="isShopCreate" 
                    @close="closeShopCreate"
                    @refreshData="getShopTableData"  
                ></shop-create>
            </div>
            <!-- filter container -->
            <div id="filter-container" v-if="isToggleSearch">
                <div class="flex">
                    <!-- township filter -->
                    <DxSelectBox
                        class="search-box ml-11"
                        :items="townshipList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="Township"
                        ref="township"
                        :onValueChanged=getDataByTownship
                        width="204px"
                    />
                    <!-- city filter -->
                    <DxSelectBox
                        class="search-box ml-9"
                        :items="cityList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="City"
                        ref="city"
                        :onValueChanged=getDataByCity
                        width="204px"
                    />
                    <!-- date range filter -->
                    <div class="flex items-center">
                        <h5 class="from-label">From</h5>
                        <DxDateBox
                            v-model="fromDate"
                            class="date-box"
                            width="204px"
                            type="date"
                        />
                        <h5 class="to-label">To</h5>
                        <DxDateBox
                            v-model="toDate"
                            class="date-box"
                            width="204px"
                            type="date"
                        />
                    </div>
                    <!-- <DxSelectBox
                        class="search-box ml-9"
                        :items="shopList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="Shop"
                        ref="shop"
                        :onValueChanged=getDataByShop
                    /> -->
                </div>
            </div>
        </div>
        <!-- table list view -->
        <div id="list-view-container">
            <!-- table -->
            <dx-data-grid
                :data-source="gridData"
                :columns="gridColumns"
                class="custom-data-grid"
                :columnAutoWidth="true"
                ref="myDataGrid"
                :style="{ height: isToggleSearch ? '62vh' : '78vh' }"
                @row-click="directToShopDetail"
            >
                <DxColumn
                    data-field="shopName"
                    caption="SHOP NAME"
                    cell-template="shopTemplate"
                />
                <template #shopTemplate="{ data }">
                    <a :href="`/shops/${data.data.id}`">{{data.data.shopName}}</a>
                </template>
                <DxColumn 
                    data-field="phoneNumber"
                    caption="PHONE NUMBER"
                />
                <DxColumn
                    data-field="township"
                    caption="TOWNSHIP"
                    cell-template="townshipTemplate"
                />
                <template #townshipTemplate="{ data }">
                    <a class="hover-text" :href="`/townships/${data.data.townshipId}`">{{data.data.township}}</a>
                </template>
                <DxColumn
                    data-field="city"
                    caption="CITY"
                    cell-template="cityTemplate"
                />
                <template #cityTemplate="{ data }">
                    <a class="hover-text" :href="`/cities/${data.data.cityId}`">{{data.data.city}}</a>
                </template>
                <DxColumn 
                    data-field="address"
                    caption="ADDRESS"
                />
                <DxColumn 
                    data-field="totalPickUp"
                    caption="THIS MONTH'S TOTAL&nbsp;PICKUP"
                />
                <DxColumn 
                    data-field="joinedDate"
                    caption="JOINED DATE"
                />
                <DxPaging :page-size="10"/>
                <DxPager
                    :visible="true"
                    :allowed-page-sizes="pageSize"
                    display-mode="compact"
                    :show-page-size-selector="true"
                    :show-info="true"
                    :show-navigation-buttons="true"
                />
                <DxScrolling
                    mode="standard"
                    :use-native="false"
                    :scroll-by-content="true"
                    :scroll-by-thumb="true"
                    show-scrollbar="never" />
            </dx-data-grid>
            <!-- paging -->
            <!-- <div class="flex justify-between items-center" :class="{ 'mt-5' : pageSize != 10}">
                <select id="pageSizeSelect" v-model="pageSize">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <div class="paging flex items-center">
                    <span class="mr-8">Page {{ pageIndex + 1 }} of {{ totalPages }} ({{ totalCount }} items)</span>
                    <button class="mr-4 text-base" @click="prevPage">&lt;</button>
                    <span class="current-page">{{ pageIndex + 1 }}</span> <span class="mx-1"> of </span> <span>{{ totalPages }}</span>
                    <button class="ml-4 text-base" @click="nextPage">&gt;</button>
                </div>
            </div> -->
        </div>
    </div>
</template>
 
<script>
import { DxTextBox, DxButton as DxTextBoxButton } from 'devextreme-vue/text-box';
import DxSelectBox from 'devextreme-vue/select-box';
import DxDateBox from 'devextreme-vue/date-box';
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxColumn } from 'devextreme-vue/data-grid';
import SearchBox from '../../components/general/SearchBox.vue';

export default {
    components: {
        DxTextBox,
        DxTextBoxButton,
        DxSelectBox,
        DxDateBox,
        DxDataGrid,
        DxPager,
        DxPaging,
        DxScrolling,
        DxColumn,
        SearchBox
    },
    data() {
        return {
            townshipList: [],
            cityList: [],
            shopList: [],
            isToggleSearch: false,
            gridData: [],
            gridColumns: [
                'SHOP NAME',
                'PHONE NUMBER',
                'TOWNSHIP',
                'CITY',
                'ADDRESS',
                "THIS MONTH'S TOTAL PICKUP",
                'JOINED DATE'
            ],
            pageSize: [10, 20, 50, 100],
            fromDate: null,
            toDate: null,
            isShopCreate: false,
            dataGridHeight: 'auto',
        };
    },
    methods: {
        directToShopDetail(e) {
            window.location.href = `/shops/${e.data.id}`;
        },
        // calculateDataGridHeight() {
        //     const filterContentContainer = this.$refs.filterContentContainer;
        //     console.log(filterContentContainer.clientHeight);
        //     if (filterContentContainer) {
        //         const screenHeight = window.innerHeight;
        //         const height = filterContentContainer.clientHeight;
        //         const blankHeight = height + 12 + (!this.isToggleSearch ? 20 : 0) + 1;
        //         this.dataGridHeight = (screenHeight - blankHeight) + 'px';
        //     }
        // },
        createShop() {
            this.isShopCreate = true;
        },
        closeShopCreate() {
            this.isShopCreate = false;
        },
        async getDataByDateRange(fromDate, toDate) {
            const fromattedFromDate = fromDate.toISOString();
            const fromattedToDate = toDate.toISOString();
            const response = await fetch(`/api/shops?from_date=${fromattedFromDate}&to_date=${fromattedToDate}`);
            this.fetchData(response);
        },
        // prevPage() {
        //     if (this.pageIndex > 0) {
        //         this.pageIndex--;
        //         this.getShopTableData();
        //     }
        // },
        // nextPage() {
        //     if (this.pageIndex < this.totalPages) {
        //         this.pageIndex++;
        //         this.getShopTableData();
        //     }
        // },
        // getTotalPageCount() {
        //     const totalCount = this.$refs['myDataGrid'].instance.pageCount();
        //     console.log(totalCount);
        //     this.totalPages = totalCount + 1;
        //     return totalCount;
        // },
        async getDataByTownship(event) {
            const response = await fetch(`/api/shops?township_id=${event.value}`);
            this.fetchData(response);
        },
        async getDataByCity(event) {
            const response = await fetch(`/api/shops?city_id=${event.value}`);
            this.fetchData(response);
        },
        async getDataByShop(event) {
            const response = await fetch(`/api/shops?shop_id=${event.value}`);
            this.fetchData(response);
        },
        async getDataBySearch(data) {
            const response = await fetch(`/api/shops?search=${data.input}`);
            this.fetchData(response);
        },
        formatDate(inputDate) {
            const date = new Date(inputDate);
            const day = date.getDate();
            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            return `${day} ${month}, ${year}`;
        },
        async getShopTableData() {
            const response = await fetch(`/api/shops`);
            this.fetchData(response);
        },
        async fetchData(response) {
            try {
                const data = await response.json();
                
                const formattedData = data.data.map((shop) => ({
                    'id': shop.id,
                    'shopName': shop.name,
                    'phoneNumber': shop.phone_number,
                    'townshipId' : shop.township ? shop.township.id : '',
                    'township': shop.township ? shop.township.name : '',
                    'cityId': shop.township?.city ? shop.township.city.id : '',
                    'city': shop.township?.city ? shop.township.city.name : '',
                    'address': shop.address,
                    "totalPickUp": shop.orders.length,
                    'joinedDate': this.formatDate(shop.created_at),
                }));

                this.gridData = formattedData;
                this.totalCount = data.data.length;
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        async getTownshipList() {
            await fetch(`/api/get-township-list`)
                .then((res) => res.json())
                .then((data) => {
                    this.townshipList = data.data;
                });
        },
        async getCityList() {
            await fetch('/api/get-city-list')
                .then((res) => res.json())
                .then((data) => {
                    this.cityList = data.data;
                });
        },
        async getShopList() {
            await fetch('/api/get-shop-list')
                .then((res) => res.json())
                .then((data) => {
                    this.shopList = data.data;
                });
        },
        toggleSearch() {
            this.isToggleSearch = !this.isToggleSearch;
        }
    },
    mounted() {
        this.getShopTableData();
        this.getTownshipList();
        this.getCityList();
        this.getShopList();
        // this.calculateDataGridHeight();
    },
    watch: {
        fromDate(newFromDate, oldFromDate) {
            this.getDataByDateRange(newFromDate, this.toDate);
        },
        toDate(newToDate, oldToDate) {
            this.getDataByDateRange(this.fromDate, newToDate);
        },
        // isToggleSearch() {
        //     this.calculateDataGridHeight();
        // }
    },
}
</script>
 
<style>

.page-title {
    font-size: 34px !important;
    font-style: normal;
    font-weight: 800 !important;
    line-height: normal;
    text-transform: uppercase;
}

#filter-container {
    padding: 35px 0;
    border-top: 1px solid #AAAAAA;
}

.search-box, .date-box {
    border-color: #116A5B !important;
}

.search-box .dx-dropdowneditor-icon {
    background-image: url('/images/icons/arrow_down.svg');
    background-color: #ffffff !important;
}

.search-box .dx-dropdowneditor-icon::before, .date-box .dx-dropdowneditor-icon::before {
    content: unset;
}

.search-box .dx-placeholder {
    color: #000000;
}

.from-label {
    margin-left: 36px;
    margin-right: 14px;
}

.to-label {
    margin: 0 12px;
}

#filter-container .from-label, #filter-container .to-label {
    font-size: 13px ;
    font-style: normal;
    font-weight: 400;
    line-height: 16px;
}

.date-box .dx-dropdowneditor-icon {
    background-image: url('/images/icons/calendar.svg');
    background-color: #ffffff !important;
}

#list-view-container {
    border-top: 1px solid #AAAAAA;
}
.custom-data-grid {
  overflow-x: auto;
}

.custom-data-grid .dx-datagrid-headers {
    border-bottom: none;
}

.custom-data-grid .dx-datagrid-headers .dx-datagrid-table .dx-header-row .dx-datagrid-action,
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td {
    vertical-align: middle;
    white-space: normal;
    text-align: center !important;
    border-left: none;
    border-right: none;
    border-bottom: 1px solid #D9D9D9;
    padding: 10px !important;
}

.custom-data-grid .dx-datagrid-headers .dx-datagrid-table .dx-header-row .dx-datagrid-action .dx-datagrid-text-content {
    white-space: normal !important;
    color: var(--black, #000);
    text-align: center;
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 18px; /* 150% */
    text-transform: capitalize;
}

.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td,
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td a {
    color: #000000 !important;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: 18px; /* 150% */
    text-transform: capitalize;
}

.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-freespace-row {
    display: block;
}

#pageSizeSelect {
    border: 1px solid #D9D9D9;
    border-radius: 7px;
    color: #D9D9D9;
    padding: 3px;
    font-size: 12px;
}

#pageSizeSelect:active, #pageSizeSelect:focus, #pageSizeSelect:focus-visible {
    border-color: #116A5B;
    color: #116A5B;
}

.dx-pager {
    padding-bottom: 0 !important;
}

.paging {
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    color: #AAAAAA;
}

.current-page {
    border: 1px solid #39CAB2;
    padding: 1px 8px;
    border-radius: 2px;
}

.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td .hover-text:hover {
    color: #39CAB2 !important;
}

.custom-data-grid .dx-datagrid-rowsview .dx-row.dx-data-row:hover {
    background-color: #D4EFEB;
}
</style>
 