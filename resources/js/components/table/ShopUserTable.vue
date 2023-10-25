<template>
    <dx-data-grid
        :data-source="shopUsers"
        :columns="columns"
        :customize-columns="customizeColumns"
        class="custom-data-grid"
        :columnAutoWidth="true"
        ref="shopUserDataGrid"
        :style="{ 'height': gridHeight }"
    >
        <DxColumn 
            data-field="index"
            :caption="columns.index"
        />
        <DxColumn
            data-field="userName"
            :caption="columns.userName"
            cell-template="userNameTemplate"
        />
        <template #userNameTemplate="{ data }">
            <a v-if="isDetail"
                @click.prevent="showDetail(data.data)"
            >{{data.data.userName}}</a>
            <a :href="`/shopusers/${data.data.id}`" v-else>{{data.data.userName}}</a>
        </template>
        <DxColumn 
            data-field="phoneNumber"
            :caption="columns.phoneNumber"
        ></DxColumn>
        <DxColumn
            data-field="shopName"
            :caption="columns.shopName"
            cell-template="shopTemplate"
        ></DxColumn>
        <template #shopTemplate="{ data }">
            <a :href="`/shops/${data.data.shopId}`">{{data.data.shopName}}</a>
        </template>
        <DxColumn 
            data-field="email"
            :caption="columns.email"
        ></DxColumn>
        <DxPaging :page-size="10" />
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
            column-rendering-mode="virtual"
            :use-native="false"
            :scroll-by-content="true"
            :scroll-by-thumb="true"
            show-scrollbar="never"
        />
    </dx-data-grid>
    <ShopUserDetail v-if="isShowDetail"
        :shopUser="shopUserDetail"
        @close="closeDetail"   
    ></ShopUserDetail>
</template>
<script>
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxColumn } from 'devextreme-vue/data-grid';
import ShopUserDetail from '../../pages/shop-user/ShopUserDetail.vue';

const customizeColumns = (columns) => {
    columns[0].maxWidth = 47;
};

export default {
    components: {
        DxDataGrid,
        DxPager,
        DxPaging,
        DxScrolling,
        DxColumn,
        ShopUserDetail
    },
    props: ['shopUsers', 'columns', 'gridHeight', 'isDetail'],
    data() {
        return {
            isShowDetail: false,
            customizeColumns: customizeColumns,
            shopUserDetail: {},
        }
    },
    methods: {
        showDetail(data) {
            this.isShowDetail = true;
            this.shopUserDetail = data;
        },
        closeDetail() {
            this.isShowDetail = false;
            this.shopUserDetail = {};
        }
    }
}
</script>