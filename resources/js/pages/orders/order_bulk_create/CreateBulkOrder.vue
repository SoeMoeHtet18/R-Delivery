<template>
    <div class="py-8 px-6 sm:p-4 md:py-4 md:px-6">
        <div class="flex justify-between">
            <div class="flex items-center">
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M40 16.0202H4.77963L20.1959 28.5654L18.4329 30L0 15L18.4329 0L20.1959 1.43464L4.77963 13.9798H40V16.0202Z"
                        fill="black" />
                </svg>
                <h1 class="bulk-order-title font-lato text-start">CREATE ORDERS</h1>
            </div>
            <div class="flex items-center">
                <DxButton :width="100" text="Cancel" styling-mode="outlined"
                    style="margin-right: 10px; border-color: #116a5b; color: #116a5b;" />
                <DxButton :width="100" text="Save" styling-mode="contained" style="background-color: #116a5b; color: #ffff;"
                    @click="saveBulkOrder()" />
            </div>
        </div>

        <!-- table starts -->
        <div class="overflow-hidden overflow-x-auto" id="createBulkOrderTable">
            <table class="table-auto">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SHOP</th>
                        <th>CUSTOMER NAME</th>
                        <th>CUSTOMER PHONE NUMBER</th>
                        <th>CITY</th>
                        <th>TOWNSHIP</th>
                        <th>ADDRESS</th>
                        <th>RIDER</th>
                        <th>ITEM AMOUNT</th>
                        <th>IS DELI FREE ?</th>
                        <th>DELIVERY FEES</th>
                        <th>MARKUP DELIVERY FEES</th>
                        <th>EXTRA CHARGES</th>
                        <th>IS PAYLATER ?</th>
                        <th>PAYMENT METHOD</th>
                        <th>ITEM TYPE</th>
                        <th>QUANTITY</th>
                        <th>DELIVERY TYPE</th>
                        <th>SCHEDULE DATE</th>
                        <th>REMARK</th>
                    </tr>
                </thead>
                <tbody class="bulk-order-input-box-container">
                    <tr v-for="(data, index) in tableData" :key="index">
                        <bulk-order-create-table-row :rowData="data" :rowIndex="index + 1" @addRow="addNewRow">
                        </bulk-order-create-table-row>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import BulkOrderCreateTableRow from './../../../components/order/order_bulk_create/BulkOrderCreateTableRow.vue';
import DxButton from 'devextreme-vue/button';

export default {
    components: {
        BulkOrderCreateTableRow,
        DxButton
    },
    data() {
        const scheduledDate = new Date();
        const today = scheduledDate.setDate(scheduledDate.getDate());
        const tomorrow = scheduledDate.setDate(scheduledDate.getDate() + 1);

        return {
            tableData: [
                {
                    shop: null,
                    customer_name: null,
                    customer_phone_number: null,
                    city: null,
                    township: null,
                    address: null,
                    rider: null,
                    item_amount: null,
                    is_deli_free: false,
                    delivery_fees: null,
                    markup_delivery_fees: null,
                    extra_charges: null,
                    is_paylater: false,
                    payment_method: null,
                    item_type: null,
                    quantity: null,
                    delivery_type: null,
                    schedule_date: tomorrow,
                    remark: null
                },
                // Add more initial data rows as needed
            ],
            date_for_scheduled: tomorrow
        };
    },
    methods: {
        addNewRow(data) {
            // Add a new row to the tableData array
            this.tableData.push({
                shop: data.shop,
                customer_name: null,
                customer_phone_number: null,
                city: null,
                township: null,
                address: null,
                rider: null,
                item_amount: null,
                is_deli_free: false,
                delivery_fees: null,
                markup_delivery_fees: null,
                extra_charges: null,
                is_paylater: false,
                payment_method: null,
                item_type: null,
                quantity: null,
                delivery_type: null,
                schedule_date: this.date_for_scheduled,
                remark: null
            });
        },

        saveBulkOrder() {
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = this.tableData;
            console.log(formData);
            fetch("/api/save-bulk-order", {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                }),
                body: JSON.stringify({
                    bulkOrder: formData,
                }),
            })
                .then((response) => {
                    return response.json();
                })
                .then(function (data) {
                    console.log(data);
                    window.location = `/orders`;
                })
                .catch((error) => {
                    return error;
                });
        }
    },
};
</script>

<style lang="scss" scoped>
#createBulkOrderTable::-webkit-scrollbar {
    width: 0;
    height: 0;
}

#createBulkOrderTable table {
    border-collapse: separate;
    border-spacing: 0 10px;
    /* Adjust the second value to control the margin between rows */
}

#createBulkOrderTable {
    thead th {
        text-align: center;
        background-color: #116a5b;
        color: white;
        text-wrap: nowrap;
        font-size: 12px;
        font-weight: 700;
        line-height: 18px;
        padding: 10px 5px;
    }

    thead th:first-child {
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    thead th:last-child {
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    tr {
        margin-bottom: 10px;
    }

    tbody tr {
        background-color: #d4efeb;
        text-align: center;
    }
}

.bulk-order-title {
    font-weight: 800 !important;
    font-size: 34px;
    margin-left: 10px;
}
</style>
