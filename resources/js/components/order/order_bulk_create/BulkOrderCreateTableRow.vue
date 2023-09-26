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
            placeholder="Select"
            ref="shop"
            :onItemClick=shopValueChange
            :onEnterKey=shopValueChange
            :onFocusOut="validateAndUpdateShop"
            :style="isShopRequired && !rowData.shop ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div v-if="isShopRequired && !rowData.shop" class="error-message">
            Shop is required.
        </div>
    </td>
    <td>
        <DxTextBox
            v-model="rowData.customer_name"
            :validation-rules="customerNameValidationRules"
            ref="customerName"
            :onFocusOut="validateAndUpdateCustomerName"
            :style="isCustomerNameRequired && !rowData.customer_name ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div v-if="isCustomerNameRequired && !rowData.customer_name" class="error-message">
            Customer Name is required.
        </div>
    </td>
    <td>
        <DxTextBox
            v-model="rowData.customer_phone_number"
            :validation-rules="customerPhoneNumberValidationRules"
            ref="customerPhoneNumber"
            :onFocusOut="validateAndUpdateCustomerPhoneNumber"
            :style="isCustomerPhoneNumberRequired && !rowData.customer_phone_number ? 'margin-top : 20px' : '' "
            mode="tel"
            @input="addNewRow"
        />
        
        <div v-if="isCustomerPhoneNumberRequired && !rowData.customer_phone_number" class="error-message">
            Phone Number is required.
        </div>
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
            ref="city"
            :onFocusOut="validateAndUpdateCity"
            :style="isCityRequired && !rowData.city ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div v-if="isCityRequired && !rowData.city" class="error-message">
            City is required.
        </div>
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
            ref="township"
            :onFocusOut="validateAndUpdateTownship"
            :style="isTownshipRequired && !rowData.township ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div v-if="isTownshipRequired && !rowData.township" class="error-message">
            Township is required.
        </div>
    </td>
    <td>
        <DxTextArea
            v-model="rowData.address"
            :onFocusOut="updateAddress"
            @input="addNewRow"
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
            ref="rider"
            :onFocusOut="validateAndUpdateRider"
            @input="addNewRow"
        />
    </td>
    <td>
        <DxNumberBox v-if="!rowData.is_deli_free"
            v-model="rowData.item_amount"
            :min="0"
            :show-spin-buttons="true"
            :onFocusOut="validateAndUpdateItemAmount"
            :style="isItemAmountRequired && !rowData.item_amount && !rowData.is_deli_free ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div class="flex" v-if="rowData.is_deli_free">
            <div class="flex flex-col">
                <h5 class="label-amount">Actual Amount</h5>
                <DxNumberBox
                    v-model="rowData.item_amount"
                    :min="0"
                    class="amount-box"
                    @input="addNewRow"
                />
            </div>
            <div class="flex flex-col">
                <h5 class="label-amount">Total Amount</h5>
                <DxNumberBox
                    v-model="actualAmount"
                    :min="0"
                    class="amount-box"
                    @input="addNewRow"
                />
            </div>
        </div>
        <div v-if="isItemAmountRequired && !rowData.item_amount && !rowData.is_deli_free" class="error-message">
            Item Amount is required.
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
            :disabled="true"
            style="border: 1px solid #ddd;"
        />
        <template v-else>
            -
        </template>
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.markup_delivery_fees"
            :min="0"
            :onFocusOut="updateMarkupDeliveryFees"
            @input="addNewRow"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.extra_charges"
            :min="0"
            :onFocusOut="updateExtraCharges"
            @input="addNewRow"
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
            :style="isPaymentMethodRequired && !rowData.payment_method ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div v-if="isPaymentMethodRequired && !rowData.payment_method" class="error-message">
            Payment Method is required.
        </div>
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
            @input="addNewRow"
        />
    </td>
    <td>
        <DxNumberBox 
            v-model="rowData.quantity"
            :min="1"
            :show-spin-buttons="true"
            class="quantity-input-box"
            :onFocusOut="updateQuantity"
            @input="addNewRow"
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
            :style="isDeliveryTypeRequired && !rowData.delivery_type ? 'margin-top : 20px' : '' "
            @input="addNewRow"
        />
        <div v-if="isDeliveryTypeRequired && !rowData.delivery_type" class="error-message">
            Delivery Type is required.
        </div>
    </td>
    <td>
        <DxDateBox
            v-model="rowData.schedule_date"
            type="date"
            :onKeyDown="handleArrowKeys"
            :min="getInvalidDate()"
            :onFocusOut="updateScheduleDate"
            @input="addNewRow"
        />
    </td>
    <td>
        <DxTextArea
            v-model="rowData.remark"
            :onKeyDown="saveOrder"
            :onFocusOut="updateRemark"
            @input="addNewRow"
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
            isShopRequired : false,
            isCustomerNameRequired : false,
            isCustomerPhoneNumberRequired : false,
            isCityRequired : false,
            isTownshipRequired : false,
            isItemAmountRequired : false,
            isPaymentMethodRequired : false,
            isDeliveryTypeRequired : false,
        }
    },
    methods: {
        selectShopValue(event) {
            this.shopList.forEach((shop) => {
                const shopName = shop.name.toLowerCase();
                const valueForCheck = event.target.value.toLowerCase();
                if(shopName == valueForCheck) {
                    this.rowData.shop = shop.id;
                }
            })
        },
        selectCityValue(event) {
            this.cityList.forEach((city) => {
                const cityName = city.name.toLowerCase();
                const valueForCheck = event.target.value.toLowerCase();
                if(cityName == valueForCheck) {
                    this.rowData.city = city.id;
                }
            })
        },
        selectTownshipValue(event) {
            this.townshipList.forEach((township) => {
                const townshipName = township.name.toLowerCase();
                const valueForCheck = event.target.value.toLowerCase();
                if(townshipName == valueForCheck) {
                    this.rowData.township = township.id;
                }
            })
        },
        selectRiderValue(event) {
            this.riderList.forEach((rider) => {
                const riderName = rider.name.toLowerCase();
                const valueForCheck = event.target.value.toLowerCase();
                if(riderName == valueForCheck) {
                    this.rowData.rider = rider.id;
                }
            })
        },
        addNewRow() {
            if(this.isAddRow){
                this.isAddRow = false;
                this.$emit('addRow', {
                    shop: null,
                });
            }
        },
        handleShopChange(newValue) {
            console.log('i choose the same option.');
            console.log(newValue);
        },
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

                this.$emit('duplicateData', {
                    index: this.rowIndex,
                    city: this.rowData.city,
                    township: this.rowData.township,
                    rider: this.rowData.rider,
                    delivery_fees: this.rowData.delivery_fees,
                    item_type: this.rowData.item_type,
                    delivery_type: this.rowData.delivery_type,
                    schedule_date: this.rowData.schedule_date,
                });
            }
        },
        validateAndUpdateShop() {
            console.log(this.isShopRequired);
            if(this.rowData.shop == null) {
                this.isShopRequired = true;
            }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateCustomerName() {
            if(this.rowData.customerName == null) {
                this.isCustomerNameRequired = true;
            }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateCustomerPhoneNumber() {
            if(this.rowData.customerPhoneNumber == null) {
                this.isCustomerPhoneNumberRequired = true;
            }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateCity() {
            if(this.rowData.city == null) {
                this.isCityRequired = true;
            }
            const order_id = this.rowData.order_id;
            if(order_id) {
                this.updateOrder(order_id);
            }
        },
        validateAndUpdateTownship() {
            if(this.rowData.township == null) {
                this.isTownshipRequired = true;
            }
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
            if(this.rowData.item_amount == null){
                this.isItemAmountRequired = true;
            }
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
            if(this.rowData.payment_method == null) {
                this.isPaymentMethodRequired = true;
            }
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
            if(this.rowData.delivery_type == null){
                this.isDeliveryTypeRequired = true;
            }
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
                    this.rowData.rider = this.riderList[0].id;
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
        this.getItemTypeList();
        this.getDeliveryTypeList();
        this.$refs.shop.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.selectShopValue
        );
        this.$refs.city.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.selectCityValue
        );
        this.$refs.township.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.selectTownshipValue
        );
        this.$refs.rider.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.selectRiderValue
        );
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
.payment-method-input-box:focus {
    border-color: #116A5B;
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

.error-message {
    color: red;
    margin-top: 5px;
    text-align: start;
    font-size: 12px;
    width: max-content;
}

</style>