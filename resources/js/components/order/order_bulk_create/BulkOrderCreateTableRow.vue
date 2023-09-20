<template>
    <td>
        <b>{{ rowIndex }}</b>
    </td>
    <td>
        <DxSelectBox
            :items="shopList"
            displayExpr="name"
            valueExpr="id"
            v-model="rowData.shop"
            :searchEnabled=true
            :onValueChanged=shopValueChange
            placeholder="Select"
        />
    </td>
    <td>
        <DxTextBox
            v-model="rowData.customer_name"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.customer_phone_number"
        />
    </td>
    <td>
        <DxSelectBox
            :items="cityList"
            displayExpr="name"
            valueExpr="id"
            v-model="rowData.city"
            :searchEnabled=true
            :onValueChanged=cityValueChange
            placeholder="Select"
        />
    </td>
    <td>
        <DxSelectBox
            :items="townshipList"
            displayExpr="name"
            valueExpr="id"
            v-model="rowData.township"
            :searchEnabled=true
            :onValueChanged=townshipValueChange
            placeholder="Select"
        />
    </td>
    <td>
        <DxTextArea
            v-model="rowData.address"
        />
    </td>
    <td>
        <DxSelectBox
            :items="riderList"
            displayExpr="name"
            valueExpr="id"
            v-model="rowData.rider"
            :searchEnabled=true
            placeholder="Select"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.item_amount"
            :min="0"
            :show-spin-buttons="true"
        />
    </td>
    <td>
        <DxCheckBox
            v-model="rowData.is_deli_free"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.delivery_fees"
            :min="0"
            :show-spin-buttons="true"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.markup_delivery_fees"
            :min="0"
            :show-spin-buttons="true"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.extra_charges"
            :min="0"
            :show-spin-buttons="true"
        />
    </td>
    <td>
        <DxCheckBox
            v-model="rowData.is_paylater"
        />
    </td>
    <td class="text-nowrap">
        <DxRadioGroup
            :items="paymentMethods"
            v-model="rowData.payment_method"
            layout="horizontal"
            class="payment-method-input-box"
        />
    </td>
    <td>
        <DxSelectBox
            :items="itemTypeList"
            displayExpr="name"
            valueExpr="id"
            v-model="rowData.item_type"
            :searchEnabled=true
            placeholder="Select"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.quantity"
            :min="1"
            :show-spin-buttons="true"
            class="quantity-input-box"
        />
    </td>
    <td>
        <DxSelectBox
            :items="deliveryTypeList"
            displayExpr="name"
            valueExpr="id"
            v-model="rowData.delivery_type"
            :searchEnabled=true
            placeholder="Select"
        />
    </td>
    <td>
        <DxDateBox
            v-model="rowData.schedule_date"
            type="date"
        />
    </td>
    <td>
        <DxTextArea
            v-model="rowData.remark"
        />
    </td>
</template>

<script>
import DxSelectBox from 'devextreme-vue/select-box';
import DxTextBox from 'devextreme-vue/text-box';
import DxNumberBox  from 'devextreme-vue/number-box';
import DxDateBox from 'devextreme-vue/date-box';
import DxTextArea from 'devextreme-vue/text-area';
import DxCheckBox from 'devextreme-vue/check-box';
import DxRadioGroup from 'devextreme-vue/radio-group';

export default {
    components: {
        DxSelectBox,
        DxTextBox,
        DxNumberBox,
        DxDateBox,
        DxTextArea,
        DxCheckBox,
        DxRadioGroup
    },
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
            paymentMethods: [
                { text: 'COD', value: 'cash_on_delivery' },
                { text: 'Item Prepaid', value: 'item_prepaid' },
                { text: 'All Prepaid', value: 'all_prepaid' },
            ],
            isAddRow : true,
        }
    },
    methods: {
        shopValueChange() {
            if(this.isAddRow){
                this.isAddRow = false;
                this.$emit('addRow', {
                    shop: this.rowData.shop,
                });
            }
        },

        cityValueChange() {
            this.getTownshipList();
        },
        townshipValueChange() {
            this.getRiderList();
            this.getDeliveryFeesByTownshipID();
        },
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
        async getDeliveryFeesByTownshipID() {
            await fetch(`/api/get-delivery-fees-by-township-id?township_id=${this.rowData.township}`)
                .then((res) => res.json())
                .then((data) => {
                    this.rowData.delivery_fees = data.data;
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
    padding: 5px 5px;
}

td .dx-texteditor {
    width: max-content;
}
td .dx-radiogroup {
    width: max-content;
}

.dx-dropdowneditor-icon {
    background-color: black !important;
}

.quantity-input-box {
    width: 85px !important;
}

.payment-method-input-box {
    width: 100%;
    height: auto;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 7px 21px;
}
</style>