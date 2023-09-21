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
            :validation-rules="shopValidationRules"
            ref="shop"
            :onFocusOut="validateAndUpdateShop"
        />
    </td>
    <td>
        <DxTextBox
            v-model="rowData.customer_name"
            :validation-rules="customerNameValidationRules"
            ref="customerName"
            :onFocusOut="validateAndUpdateCustomerName"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.customer_phone_number"
            :validation-rules="customerPhoneNumberValidationRules"
            ref="customerPhoneNumber"
            :onFocusOut="validateAndUpdateCustomerPhoneNumber"
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
            :onFocusOut="validateAndUpdateCity"
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
            :onFocusOut="validateAndUpdateTownship"
        />
    </td>
    <td>
        <DxTextArea
            v-model="rowData.address"
            :onFocusOut="updateAddress"
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
            :onFocusOut="validateAndUpdateRider"
        />
    </td>
    <td>
        <DxNumberBox v-if="!rowData.is_deli_free"
            v-model="rowData.item_amount"
            :min="0"
            :show-spin-buttons="true"
            :onFocusOut="validateAndUpdateItemAmount"
        />
        <div class="flex" v-if="rowData.is_deli_free">
            <div class="flex flex-col">
                <h5 class="label-amount">Actual Amount</h5>
                <DxNumberBox
                    v-model="rowData.item_amount"
                    :min="0"
                    class="amount-box"
                />
            </div>
            <div class="flex flex-col">
                <h5 class="label-amount">Total Amount</h5>
                <DxNumberBox
                    v-model="actualAmount"
                    :min="0"
                    class="amount-box"
                />
            </div>
        </div>
    </td>
    <td>
        <DxCheckBox
            v-model="rowData.is_deli_free"
        />
    </td>
    <td>
        <DxNumberBox v-if="!rowData.is_deli_free"
            v-model="rowData.delivery_fees"
            :min="0"
            :show-spin-buttons="true"
            :readOnly="true"
        />
        <template v-else>
            -
        </template>
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.markup_delivery_fees"
            :min="0"
            :show-spin-buttons="true"
            :onFocusOut="updateMarkupDeliveryFees"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.extra_charges"
            :min="0"
            :show-spin-buttons="true"
            :onFocusOut="updateExtraCharges"
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
            :onFocusOut="validateAndUpdatePaymentMethod"
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
            :onFocusOut="validateAndUpdateItemType"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.quantity"
            :min="1"
            :show-spin-buttons="true"
            class="quantity-input-box"
            :onFocusOut="updateQuantity"
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
            :onFocusOut="validateAndUpdateDeliveryType"
        />
    </td>
    <td>
        <DxDateBox
            v-model="rowData.schedule_date"
            type="date"
            :onKeyDown="handleArrowKeys"
            :min="getInvalidDate()"
            :onFocusOut="updateScheduleDate"
        />
    </td>
    <td>
        <DxTextArea
            v-model="rowData.remark"
            :onKeyDown="saveOrder"
            :onFocusOut="updateRemark"
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
            shopValidationRules: [
                {
                    type: 'required',
                    message: 'Shop is required',
                },
                // Add more validation rules as needed for the shop
            ],
            // Define validation rules for other fields as needed
            customerNameValidationRules: [
                {
                    type: 'required',
                    message: 'Customer name is required',
                },
                // Add more validation rules as needed for the customer name
            ],
            customerPhoneNumberValidationRules: [
                {
                    type: 'required',
                    message: 'Customer phone number is required',
                },
                // Add more validation rules as needed for the customer phone number
            ],
        }
    },
    methods: {
        updateOrder(order_id) {
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = this.rowData;

            fetch(`/api/orders/${order_id}`, {
                    method: "POST",
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                    }),
                    body: JSON.stringify({
                        data: formData,
                    }),
                })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    console.log(data);
                })
                .catch((error) => {
                    return error;
                });
        },
        saveOrder(event) {
            const keyEvent = event.event;
            if (keyEvent.which === 9 && !keyEvent.shiftKey) {
                const csrf = document.querySelector('meta[name="_token"]').content;
                let formData = this.rowData;
                
                fetch("/api/orders", {
                    method: "POST",
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                    }),
                    body: JSON.stringify({
                        data: formData,
                    }),
                })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    this.rowData.order_id = data.data;
                })
                .catch((error) => {
                    return error;
                });
            }
        },
        validateAndUpdateShop() {
            const selectBox = this.$refs.shop.$refs.input;
            // if (selectBox) {
            //     selectBox.validation.validate();
            // }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateCustomerName() {
            const textBox = this.$refs.customerName;
            // if (textBox) {
            //     textBox.instance.validate(); // Use .instance to access the DevExtreme component's methods
            // }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateCustomerPhoneNumber() {
            const numberBox = this.$refs.customer_phone_number.$refs.input;
            if (numberBox) {
                numberBox.validation.validate();
            }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateCity() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateTownship() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        updateAddress() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateRider() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateItemAmount() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        updateMarkupDeliveryFees() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        updateExtraCharges() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdatePaymentMethod() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateItemType() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        updateQuantity() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateDeliveryType() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        updateScheduleDate() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        updateRemark() {
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        getInvalidDate() {
            const date = new Date();
            date.setDate(date.getDate());
            return date.toISOString().split('T')[0];
        },
        handleArrowKeys(event) {
            const keyEvent = event.event;
            const currentDate = new Date(this.rowData.schedule_date); 

            if (keyEvent.which === 38) {
                currentDate.setDate(currentDate.getDate() + 1);
            } else if (keyEvent.which === 40) {
                currentDate.setDate(currentDate.getDate() - 1);
            }

            this.rowData.schedule_date = currentDate;
        },
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
    computed: {
        actualAmount() {
            if (this.rowData.is_deli_free) {
                return this.rowData.item_amount - this.rowData.delivery_fees;
            }
        },
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

.label-amount {
    text-align: start;
    color: #116A5B;
    font-family: 'Lato';
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    margin-bottom: 2px;
}

.amount-box {
    width: 100px !important;
}

.amount-box:first-of-type {
    margin-right: 5px;
}
</style>