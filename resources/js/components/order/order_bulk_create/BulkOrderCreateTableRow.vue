<template>
    <td>
        <b>{{ rowIndex }}</b>
    </td>
    <td>
        <select v-model="rowData.shop">
            <option v-for="(shop, index) in shopList" :key="index" :value="shop.id">{{ shop.name }}</option>
        </select>
    </td>
    <td>
        <input v-model="rowData.customer_name" type="text">
    </td>
    <td>
        <input v-model="rowData.customer_phone_number" type="number">
    </td>
    <td>
        <select v-model="rowData.city">
            <option v-for="(city, index) in cityList" :key="index" :value="city.id">{{ city.name }}</option>
        </select>
    </td>
    <td>
        <select v-model="rowData.township">
            <option v-for="(township, index) in townshipList" :key="index" :value="township.id">{{ township.name }}</option>
        </select>
    </td>
    <td>
        <input v-model="rowData.address" type="text">
    </td>
    <td>
        <select v-model="rowData.rider">
            <option v-for="(rider, index) in riderList" :key="index" :value="rider.id">{{ rider.name }}</option>
        </select>
    </td>
    <td>
        <input v-model="rowData.item_amount" type="number">
    </td>
    <td>
        <input v-model="rowData.is_deli_free" type="checkbox">
    </td>
    <td>
        <input v-model="rowData.delivery_fees" type="number">
    </td>
    <td>
        <input v-model="rowData.markup_delivery_fees" type="number">
    </td>
    <td>
        <input v-model="rowData.extra_charges" type="number">
    </td>
    <td>
        <input v-model="rowData.is_paylater" type="checkbox">
    </td>
    <td class="text-nowrap">
        <label>
            <input type="radio" v-model="rowData.payment_method" value="radio1"> COD
        </label>
        <label>
            <input type="radio" v-model="rowData.payment_method" value="radio2"> Item Prepaid
        </label>
        <label>
            <input type="radio" v-model="rowData.payment_method" value="radio2"> All Prepaid
        </label>
    </td>
    <td>
        <select v-model="rowData.item_type">
            <option v-for="(itemType, index) in itemTypeList" :key="index" :value="itemType.id">{{ itemType.name }}</option>
        </select>
    </td>
    <td>
        <input v-model="rowData.quantity" type="number">
    </td>
    <td>
        <select v-model="rowData.delivery_type">
            <option v-for="(rider, index) in riderList" :key="index" :value="rider.id">{{ rider.name }}</option>
        </select>
    </td>
    <td>
        <input v-model="rowData.schedule_date" type="date">
    </td>
    <td>
        <textarea v-model="rowData.remark"></textarea>
    </td>
</template>

<script>
export default {
    props: {
        rowData: Object,
        rowIndex: Number
    },
    data() {
        return {
            shopList: [],
            cityList: [],
            townshipList: [],
            riderList: [],
            itemTypeList: [],
            deliveryTypeList: [],
        }
    },
    methods: {
        async getShopList() {
            await fetch('/api/get-shop-list')
                .then((res) => res.json())
                .then((data) => {
                    this.shopList = data.data;
                });
        },
        async getCityList() {
            await fetch('/api/get-city-list')
                .then((res) => res.json())
                .then((data) => {
                    this.cityList = data.data;
                });
        },
        async getTownshipList() {
            await fetch(`/api/get-township-list?city_id=${this.rowData.city}`)
                .then((res) => res.json())
                .then((data) => {
                    this.townshipList = data.data;
                });
        },
        async getRiderList() {
            await fetch(`/api/get-rider-list?township_id=${this.rowData.township}`)
                .then((res) => res.json())
                .then((data) => {
                    this.riderList = data.data;
                });
        },
        async getItemTypeList() {
            await fetch(`/api/get-item-type-list`)
                .then((res) => res.json())
                .then((data) => {
                    this.itemTypeList = data.data;
                });
        },
        async getDeliveryTypeList() {
            await fetch(`/api/get-delivery-type-list`)
                .then((res) => res.json())
                .then((data) => {
                    this.deliveryTypeList = data.data;
                });
        },
    },
    mounted() {
        this.getShopList();
        this.getCityList();
        this.getTownshipList();
        this.getRiderList();
        this.getItemTypeList();
        this.getDeliveryTypeList();
    },
}
</script>
<style scoped>
td {
    padding: 0 5px;
}
</style>