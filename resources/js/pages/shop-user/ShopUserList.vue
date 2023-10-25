<template>
       <div class="px-9 pt-2 pb-1 font-lato">
            <!-- page title -->
            <h1 class="page-title mb-7">SHOP USER</h1>
            <div class="flex mb-5">
                <!-- search box -->
                <SearchBox />
                <!-- create new shop user btn -->
                <button
                    class="bg-main text-white rounded-sm ml-5 px-4 pb-0.5" 
                    @click="newUser"
                >Create Shop User</button>
                <ShopUserCreate v-if="isCreate" 
                    @close="close"
                    @refreshData="getShopUsers"
                ></ShopUserCreate>
            </div>
            <ShopUserTable 
                :shopUsers="shopUsers"
                :columns="columns"
                gridHeight="79vh"
                :isDetail=true
            ></ShopUserTable>
       </div>
</template>
<script>
import SearchBox from '../../components/general/SearchBox.vue';
import ShopUserTable from '../../components/table/ShopUserTable.vue';
import ShopUserCreate from './ShopUserCreate.vue';

export default {
    components: {
        SearchBox,
        ShopUserTable,
        ShopUserCreate
    },
    data() {
        return {
            columns: {
                index: 'ID',
                userName: 'USER NAME',
                phoneNumber: 'PHONE NUMBER',
                shopName: 'SHOP',
                email: 'EMAIL'
            },
            shopUsers: [],
            isCreate: false,
        }
    },
    methods: {
        async getShopUsers() {
            const response = await fetch(`/api/shop-users`);
            const data = await response.json();

            const formattedData = data.data.map((shopUser, index) => ({
                'id': shopUser.id,
                'userName': shopUser.name,
                'phoneNumber': shopUser.phone_number,
                'shopId' : shopUser.shop ? shopUser.shop.id : '',
                'shopName': shopUser.shop ? shopUser.shop.name : '',
                'email': shopUser.email ?? '-',
                'index': index + 1,
            }));
            this.shopUsers = formattedData;
        },
        newUser() {
            this.isCreate = true;
        },
        close() {
            this.isCreate = false;
        }
    },
    mounted() {
        this.getShopUsers();
    }
}

</script>