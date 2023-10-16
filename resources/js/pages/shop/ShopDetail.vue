<template>
    <div class="px-9 py-2 font-lato">
        <!-- page title -->
        <h1 class="page-title mb-7">{{ shopInfo.name }}</h1>
        <div class="flex">
            <!-- search box -->
            <DxTextBox ref="search" class="search-btn" width="280px" height="34px" placeholder="Search" :buttons="[
                {
                    location: 'before',
                    name: 'searchButton',
                    options: searchButton,
                },
            ]">
            </DxTextBox>
            <!-- search toggle -->
            <iconify-icon icon="prime:filter-fill" width="30" class="mx-3" @click="toggleSearch"></iconify-icon>
        </div>
        <!-- filter container -->
        <div id="filter-container" v-if="isToggleSearch">
        </div>
        <!-- shop detail content -->
        <div id="shop-info-container" class="border-t border-grey mt-5 pt-5">
            <div class="flex justify-end">
                <!-- edit button -->
                <button v-if="!isEdit" @click="editShop"
                class="bg-main text-white w-70px h-8">Edit</button>
                <div>
                    <!-- cancel button -->
                    <button v-if="isEdit" @click="cancelEdit"
                        class="bg-white text-main w-70px h-8 border border-main">Cancel</button>
                    <!-- update/save button -->
                    <button v-if="isEdit" @click="updateShop"
                        class="bg-main text-white w-70px h-8 ml-2.5">Save</button>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 pb-7.5">
                <div class="flex flex-col">
                    <h5 class="info-label">Shop Name</h5>
                    <div v-if="!isEdit" 
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.name }}
                    </div>
                    <DxTextBox v-if="isEdit"
                        v-model="shopInfo.name"
                        height="37"
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Phone Number</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.phone_number }}
                    </div>
                    <DxTextBox v-if="isEdit"
                        v-model="shopInfo.phone_number"
                        mode="tel"
                        height="37"
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Township</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.township?.name }}
                    </div>
                    <DxSelectBox v-if="isEdit"
                        v-model="shopInfo.township.id"
                        :items="townshipList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="Township"
                        ref="township"
                        height="37"
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">City</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.township?.city?.name }}
                    </div>
                    <DxSelectBox v-if="isEdit"
                        v-model="shopInfo.township.city.id"
                        :items="cityList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="City"
                        ref="city"
                        height="37"
                        :onValueChanged=cityValueChange
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Address</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.address }}
                    </div>
                    <DxTextArea v-if="isEdit"
                        v-model="shopInfo.address"
                        :auto-resize-enabled="true"
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Joined Date</h5>
                    <div
                        id="joinedDate" class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ joinedDate }}
                    </div>
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">This Month's Total Pickups</h5>
                    <div
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.orders?.length != 0 ? this.formatWithNumberingSystem(shopInfo.orders?.length) : '0' }}
                    </div>
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Leftover Amount</h5>
                    <div
                         class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.payable_amount ? this.formatWithNumberingSystem(shopInfo.payable_amount) : 0 }} MMK
                    </div>
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Paid Amount</h5>
                    <div
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.paid_amount ? this.formatWithNumberingSystem(shopInfo.paid_amount) : 0 }} MMK
                    </div>
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Total Credit</h5>
                    <div
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ shopInfo.total_credit ? this.formatWithNumberingSystem(shopInfo.total_credit) : 0  }} MMK
                    </div>
                </div>
            </div>
        </div>
        <DxPopup v-if="isLoading" :drag-enabled="false">
            <div class="popup-content">
                <loader />
            </div>
        </DxPopup>
        <DxPopup v-if="isSuccess" :drag-enabled="false">
            <div class="popup-content">
                <success-pop-up />
            </div>
        </DxPopup>
        <DxTabs
            v-model:selected-index="selectedIndex"
        >
            <DxItem text="USERS"></DxItem>
            <DxItem text="ORDERS"></DxItem>
            <DxItem text="PICKUP"></DxItem>
            <DxItem text="EXCHANGE"></DxItem>
            <DxItem text="PAYMENT"></DxItem>
            <DxItem text="TRANSACTION"></DxItem>
            <DxItem text="CANCEL"></DxItem>
        </DxTabs>
        <div class="mt-4 shop-related-containers">
            <div v-if="selectedIndex === 0">
                <div class="flex justify-end my-4">
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div id="user-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="shopUsers"
                        :columns="gridUserColumns"
                        :customize-columns="customizeUserColumns"
                        class="custom-data-grid"
                        :columnAutoWidth="true"
                        ref="shopUserDataGrid"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn
                            data-field="userName"
                            caption="USER NAME"
                            cell-template="userNameTemplate"
                        />
                        <template #userNameTemplate="{ data }">
                            <a :href="`/shopusers/${data.data.id}`">{{data.data.userName}}</a>
                        </template>
                        <DxColumn 
                            data-field="phoneNumber"
                            caption="PHONE NUMBER"
                        ></DxColumn>
                        <DxColumn
                            data-field="shopName"
                            caption="SHOP NAME"
                            cell-template="shopTemplate"
                        ></DxColumn>
                        <template #shopTemplate="{ data }">
                            <a :href="`/shops/${data.data.shop_id}`">{{data.data.shopName}}</a>
                        </template>
                        <DxColumn 
                            data-field="email"
                            caption="EMAIL"
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
                </div>
            </div>
            <div v-if="selectedIndex === 1">
                <filter-sub-table 
                    @callApiFilter="filterShopOrder"
                    @download="downloadShopOrderPdf"
                ></filter-sub-table>
                <div class="grid grid-cols-4 gap-8 my-4">
                    <display-amount-box
                        title="ITEM AMOUNT"
                        :amount="financialAmounts.totalItemAmount"
                    ></display-amount-box>
                    <display-amount-box
                        title="MARKUP DELIVERY FEES"
                        :amount="financialAmounts.totalMarkUpDeliveryFees"
                    ></display-amount-box>
                    <display-amount-box
                        title="DELIVERY FEES"
                        :amount="financialAmounts.totalDeliveryFees"
                    ></display-amount-box>
                    <display-amount-box
                        title="PAY AMOUNT TO SHOP"
                        :amount="financialAmounts.totalAmountToPayShop"
                    ></display-amount-box>
                </div>
                <div id="order-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="orders"
                        :columns="gridOrderColumns"
                        :customize-columns="customizeOrderColumns"
                        class="custom-data-grid shop-order-data-grid"
                        ref="orderDataGrid"
                        :columnAutoWidth="true"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn
                            data-field="orderCode"
                            caption="ORDER ID"
                            cell-template="orderTemplate"
                        />
                        <template #orderTemplate="{ data }">
                            <a :href="`/orders/${data.data.id}`">{{data.data.orderCode}}</a>
                        </template>
                        <DxColumn 
                            data-field="customerPhoneNumber"
                            caption="CUSTOMER PHONE NUMBER"
                        ></DxColumn>
                        <DxColumn
                            data-field="customerAddress"
                            caption="CUSTOMER ADDRESS"
                        ></DxColumn>
                        <DxColumn 
                            data-field="customerName"
                            caption="CUSTOMER NAME"
                        ></DxColumn>
                        <DxColumn
                            data-field="shopName"
                            caption="SHOP NAME"
                            cell-template="orderShopNameTemplate"
                        />
                        <template #orderShopNameTemplate="{ data }">
                            <a :href="`/shops/${data.data.shopId}`">{{data.data.shopName}}</a>
                        </template>
                        <DxColumn
                            data-field="cityName"
                            caption="CITY"
                            cell-template="orderCityTemplate"
                        />
                        <template #orderCityTemplate="{ data }">
                            <a :href="`/cities/${data.data.cityId}`">{{data.data.cityName}}</a>
                        </template>
                        <DxColumn
                            data-field="townshipName"
                            caption="TOWNSHIP"
                            cell-template="orderTownshipTemplate"
                        />
                        <template #orderTownshipTemplate="{ data }">
                            <a :href="`/townships/${data.data.townshipId}`">{{data.data.townshipName}}</a>
                        </template>
                        <DxColumn
                            data-field="quantity"
                            caption="QUANTITY"
                        />
                        <DxColumn
                            data-field="totalAmount"
                            caption="TOTAL AMOUNT"
                        />
                        <DxColumn
                            data-field="deliveryFees"
                            caption="DELIVERY FEES"
                        />
                        <DxColumn
                            data-field="markupDeliveryFees"
                            caption="MARKUP DELIVERY FEES"
                        />
                        <DxColumn
                            data-field="extraCharges"
                            caption="EXTRA CHARGES"
                        />
                        <DxColumn
                            data-field="itemType"
                            caption="ITEM TYPE"
                        />
                        <DxColumn
                            data-field="paymentType"
                            caption="PAYMENT TYPE"
                        />
                        <DxColumn
                            data-field="paymentChannel"
                            caption="PAYMENT CHANNEL"
                        />
                        <DxColumn
                            data-field="collectionMethod"
                            caption="COLLECTION METHOD"
                        />
                        <DxColumn
                            data-field="scheduleDate"
                            caption="SCHEDULE DATE"
                        />
                        <DxColumn
                            data-field="type"
                            caption="TYPE"
                        />
                        <DxColumn
                            data-field="riderName"
                            caption="RIDER NAME"
                            cell-template="orderRiderTemplate"
                        />
                        <template #orderRiderTemplate="{ data }">
                            <a :href="`/riders/${data.data.riderId}`">{{data.data.riderName}}</a>
                        </template>
                        <DxColumn
                            data-field="remark"
                            caption="REMARK"
                        />
                        <DxColumn
                            data-field="origin"
                            caption="ORIGIN"
                        />
                        <DxColumn
                            data-field="status"
                            caption="STATUS"
                            cell-template="orderStatusTemplate"
                        />
                        <template #orderStatusTemplate="{ data }">
                            <span :class="getStatusColor(data.data.status)">{{ this.renderStatus(data.data.status) }}</span>
                        </template>
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
                </div>
            </div>
            <div v-if="selectedIndex === 2">
                <filter-sub-table
                    @callApiFilter="filterShopPickUp"
                    @download="downloadShopPickUpPdf"
                ></filter-sub-table>
                <div id="pickup-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="pickUps"
                        :columns="gridPickUpColumns"
                        :customize-columns="customizePickUpColumns"
                        class="custom-data-grid shop-pickup-data-grid"
                        ref="pickupDataGrid"
                        :columnAutoWidth="true"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn
                            data-field="pickUpCode"
                            caption="PICKUP ID"
                            cell-template="pickupIdTemplate"
                        />
                        <template #pickupIdTemplate="{ data }">
                            <a :href="`/collections/${data.data.id}`">{{data.data.pickUpCode}}</a>
                        </template>
                        <DxColumn
                            data-field="groupCode"
                            caption="GROUP ID"
                            cell-template="groupIdTemplate"
                        />
                        <template #groupIdTemplate="{ data }">
                            <a :href="`/collection-groups/${data.data.groupId}`">{{data.data.groupCode}}</a>
                        </template>
                        <DxColumn 
                            data-field="quantity"
                            caption="Quantity"
                        />
                        <DxColumn 
                            data-field="totalAmount"
                            caption="TOTAL AMOUNT"
                        />
                        <DxColumn 
                            data-field="paidAmountByRider"
                            caption="PAID AMOUNT BY RIDER"
                        />
                        <DxColumn 
                            data-field="leftoverAmount"
                            caption="LEFTOVER AMOUNT"
                        />
                        <DxColumn
                            data-field="riderName"
                            caption="RIDER NAME"
                            cell-template="riderNameTemplate"
                        />
                        <template #riderNameTemplate="{ data }">
                            <a :href="`/riders/${data.data.riderId}`">{{data.data.riderName}}</a>
                        </template>
                        <DxColumn
                            data-field="shopName"
                            caption="SHOP NAME"
                            cell-template="shopNameTemplate"
                        />
                        <template #shopNameTemplate="{ data }">
                            <a :href="`/shops/${data.data.shopId}`">{{data.data.shopName}}</a>
                        </template>
                         <DxColumn 
                            data-field="scheduledAt"
                            caption="SCHEDULED AT"
                        />
                        <DxColumn 
                            data-field="collectedAt"
                            caption="COLLECTED AT"
                        />
                        <DxColumn 
                            data-field="remark"
                            caption="REMARK"
                        />
                        <DxColumn
                            data-field="status"
                            caption="STATUS"
                            cell-template="statusTemplate"
                        />
                        <template #statusTemplate="{ data }">
                            <span :class="{'text-success': data.data.status == 'complete',
                                'text-warning': data.data.status != 'complete'}">
                                {{ this.renderPickUpStatus(data.data.status) }}
                            </span>
                        </template>
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
                </div>
            </div>
            <div v-if="selectedIndex === 3">
                <filter-sub-table
                    @callApiFilter="filterShopExchange"
                ></filter-sub-table>
                <div id="exchange-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="exchanges"
                        :columns="gridExchangeColumns"
                        :customize-columns="customizeExchangeColumns"
                        class="custom-data-grid shop-exchange-data-grid"
                        ref="exchangeDataGrid"
                        :columnAutoWidth="true"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn
                            data-field="exchangeCode"
                            caption="CODE"
                            cell-template="exchangeCodeTemplate"
                        />
                        <template #exchangeCodeTemplate="{ data }">
                            <a :href="`/customer-collections/${data.data.id}`">{{data.data.exchangeCode}}</a>
                        </template>
                        <DxColumn
                            data-field="groupCode"
                            caption="GROUP ID"
                            cell-template="groupIdTemplate"
                        />
                        <template #groupIdTemplate="{ data }">
                            <a :href="`/collection-groups/${data.data.groupId}`">{{data.data.groupCode}}</a>
                        </template>
                        <DxColumn
                            data-field="orderCode"
                            caption="ORDER ID"
                            cell-template="orderCodeTemplate"
                        />
                        <template #orderCodeTemplate="{ data }">
                            <a :href="`/orders/${data.data.orderId}`">{{data.data.orderCode }}</a>
                        </template>
                        <DxColumn
                            data-field="shopName"
                            caption="SHOP NAME"
                            cell-template="shopNameTemplate"
                        />
                        <template #shopNameTemplate="{ data }">
                            <a :href="`/shops/${data.data.shopId}`">{{data.data.shopName}}</a>
                        </template>
                        <DxColumn 
                            data-field="customerName"
                            caption="CUSTOMER NAME"
                        />
                        <DxColumn 
                            data-field="customerPhoneNumber"
                            caption="CUSTOMER PHONE NUMBER"
                        />
                        <DxColumn
                            data-field="cityName"
                            caption="CITY"
                            cell-template="orderCityTemplate"
                        />
                        <template #orderCityTemplate="{ data }">
                            <a :href="`/cities/${data.data.cityId}`">{{data.data.cityName}}</a>
                        </template>
                        <DxColumn
                            data-field="townshipName"
                            caption="TOWNSHIP"
                            cell-template="orderTownshipTemplate"
                        />
                        <template #orderTownshipTemplate="{ data }">
                            <a :href="`/townships/${data.data.townshipId}`">{{data.data.townshipName}}</a>
                        </template>
                        <DxColumn
                            data-field="customerAddress"
                            caption="CUSTOMER ADDRESS"
                        ></DxColumn>
                        <DxColumn 
                            data-field="paidAmountToCustomer"
                            caption="PAID AMOUNT TO CUSTOMER"
                        />
                        <DxColumn
                            data-field="riderName"
                            caption="RIDER"
                            cell-template="riderNameTemplate"
                        />
                        <template #riderNameTemplate="{ data }">
                            <a :href="`/riders/${data.data.riderId}`">{{data.data.riderName}}</a>
                        </template>
                        <DxColumn 
                            data-field="isWayPay"
                            caption="WAY FEES PAYABLE?"
                        />
                        <DxColumn 
                            data-field="items"
                            caption="ITEMS"
                        />
                        <DxColumn 
                            data-field="note"
                            caption="NOTE"
                        />
                        <DxColumn
                            data-field="status"
                            caption="STATUS"
                            cell-template="statusTemplate"
                        />
                        <template #statusTemplate="{ data }">
                            <span :class="{'text-success': data.data.status == 'complete',
                                'text-warning': data.data.status != 'complete'}">
                                {{ this.renderExchangeStatus(data.data.status) }}
                            </span>
                        </template>
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
                </div>
            </div>
            <div v-if="selectedIndex === 4">
                <filter-sub-table
                    @callApiFilter="filterShopPayment"
                ></filter-sub-table>
                <div id="payment-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="payments"
                        :columns="gridPaymentColumns"
                        :customize-columns="customizePaymentColumns"
                        class="custom-data-grid shop-payment-data-grid"
                        ref="paymentDataGrid"
                        :columnAutoWidth="true"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn 
                            data-field="date"
                            caption="DATE"
                        />
                        <DxColumn 
                            data-field="amount"
                            caption="AMOUNT"
                        />
                        <DxColumn 
                            data-field="type"
                            caption="TYPE"
                        />
                        <DxColumn 
                            data-field="note"
                            caption="NOTE"
                        />
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
                </div>
            </div>
            <div v-if="selectedIndex === 5">
                <filter-sub-table
                    @callApiFilter="filterShopTransaction"
                ></filter-sub-table>
                <div id="transaction-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="transactions"
                        :columns="gridTransactionColumns"
                        :customize-columns="customizeTransactionColumns"
                        class="custom-data-grid shop-transaction-data-grid"
                        ref="transactionDataGrid"
                        :columnAutoWidth="true"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn 
                            data-field="date"
                            caption="DATE"
                        />
                        <DxColumn 
                            data-field="amount"
                            caption="AMOUNT"
                        />
                        <DxColumn 
                            data-field="type"
                            caption="TYPE"
                        />
                        <DxColumn
                            data-field="paidByName"
                            caption="PAID BY"
                            cell-template="paidByNameTemplate"
                        />
                        <template #paidByNameTemplate="{ data }">
                            <a :href="`/users/${data.data.paidById}`">{{data.data.paidByName}}</a>
                        </template>
                        <DxColumn 
                            data-field="note"
                            caption="NOTE"
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
                            column-rendering-mode="virtual"
                            :use-native="false"
                            :scroll-by-content="true"
                            :scroll-by-thumb="true"
                            show-scrollbar="never" />
                    </dx-data-grid>
                </div>
            </div>
            <div v-if="selectedIndex === 6">
                <filter-sub-table
                    @callApiFilter="filterShopCanceledOrder"
                    @download="downloadShopCanceledOrderPdf"
                ></filter-sub-table>
                <div id="canceled-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="cancelOrders"
                        :columns="gridcancelOrderColumns"
                        :customize-columns="customizeOrderColumns"
                        class="custom-data-grid shop-order-data-grid"
                        ref="canceledOrderDataGrid"
                        :columnAutoWidth="true"
                        :style="{ height: '82vh' }"
                    >
                        <DxColumn 
                            data-field="index"
                            caption=""
                        />
                        <DxColumn
                            data-field="orderCode"
                            caption="ORDER ID"
                            cell-template="orderTemplate"
                        />
                        <template #orderTemplate="{ data }">
                            <a :href="`/orders/${data.data.id}`">{{data.data.orderCode}}</a>
                        </template>
                        <DxColumn 
                            data-field="customerPhoneNumber"
                            caption="CUSTOMER PHONE NUMBER"
                        ></DxColumn>
                        <DxColumn
                            data-field="customerAddress"
                            caption="CUSTOMER ADDRESS"
                        ></DxColumn>
                        <DxColumn 
                            data-field="customerName"
                            caption="CUSTOMER NAME"
                        ></DxColumn>
                        <DxColumn
                            data-field="shopName"
                            caption="SHOP NAME"
                            cell-template="orderShopNameTemplate"
                        />
                        <template #orderShopNameTemplate="{ data }">
                            <a :href="`/shops/${data.data.shopId}`">{{data.data.shopName}}</a>
                        </template>
                        <DxColumn
                            data-field="cityName"
                            caption="CITY"
                            cell-template="orderCityTemplate"
                        />
                        <template #orderCityTemplate="{ data }">
                            <a :href="`/cities/${data.data.cityId}`">{{data.data.cityName}}</a>
                        </template>
                        <DxColumn
                            data-field="townshipName"
                            caption="TOWNSHIP"
                            cell-template="orderTownshipTemplate"
                        />
                        <template #orderTownshipTemplate="{ data }">
                            <a :href="`/townships/${data.data.townshipId}`">{{data.data.townshipName}}</a>
                        </template>
                        <DxColumn
                            data-field="quantity"
                            caption="QUANTITY"
                        />
                        <DxColumn
                            data-field="totalAmount"
                            caption="TOTAL AMOUNT"
                        />
                        <DxColumn
                            data-field="deliveryFees"
                            caption="DELIVERY FEES"
                        />
                        <DxColumn
                            data-field="markupDeliveryFees"
                            caption="MARKUP DELIVERY FEES"
                        />
                        <DxColumn
                            data-field="extraCharges"
                            caption="EXTRA CHARGES"
                        />
                        <DxColumn
                            data-field="itemType"
                            caption="ITEM TYPE"
                        />
                        <DxColumn
                            data-field="paymentType"
                            caption="PAYMENT TYPE"
                        />
                        <DxColumn
                            data-field="paymentChannel"
                            caption="PAYMENT CHANNEL"
                        />
                        <DxColumn
                            data-field="collectionMethod"
                            caption="COLLECTION METHOD"
                        />
                        <DxColumn
                            data-field="scheduleDate"
                            caption="SCHEDULE DATE"
                        />
                        <DxColumn
                            data-field="type"
                            caption="TYPE"
                        />
                        <DxColumn
                            data-field="riderName"
                            caption="RIDER NAME"
                            cell-template="orderRiderTemplate"
                        />
                        <template #orderRiderTemplate="{ data }">
                            <a :href="`/riders/${data.data.riderId}`">{{data.data.riderName}}</a>
                        </template>
                        <DxColumn
                            data-field="remark"
                            caption="REMARK"
                        />
                        <DxColumn
                            data-field="origin"
                            caption="ORIGIN"
                        />
                        <DxColumn
                            data-field="status"
                            caption="STATUS"
                            cell-template="orderStatusTemplate"
                        />
                        <template #orderStatusTemplate="{ data }">
                            <span class="text-cancel">{{ this.renderStatus(data.data.status) }}</span>
                        </template>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { DxTextBox, DxButton as DxTextBoxButton } from 'devextreme-vue/text-box';
import { DxTabs, DxItem } from 'devextreme-vue/tabs';
import DxSelectBox from 'devextreme-vue/select-box';
import DxTextArea from 'devextreme-vue/text-area';
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxColumn } from 'devextreme-vue/data-grid';

const searchButton = {
    icon: '/images/icons/search.svg',
    type: 'default',
};

const customizeUserColumns = (columns) => {
    columns[0].maxWidth = 47;
};

const customizeOrderColumns = (columns) => {
    columns[0].width = 47;
    columns[1].width = 200;
    columns[2].width = 127;
    columns[3].minWidth = 154;
    columns[3].maxWidth = 254;
    columns[4].width = 127;
    columns[5].width = 127;
    columns[6].width = 127;
    columns[7].width = 127;
    columns[8].width = 127;
    columns[9].width = 154;
    columns[10].width = 154;
    columns[11].width = 154;
    columns[12].width = 154;
    columns[13].width = 154;
    columns[14].width = 154;
    columns[15].minWidth = 154;
    columns[15].maxWidth = 200;
    columns[16].width = 154;
    columns[17].width = 154;
    columns[18].width = 154;
    columns[19].width = 154;
    columns[20].width = 154;
    columns[21].width = 154;
    columns[22].width = 154;

    columns.forEach((column) => {
        column.wordWrap = true;
    });
};

const customizePickUpColumns = (columns) => {
    columns[0].width = 47;
    columns[1].minWidth = 154;
    columns[1].maxWidth = 200;
    columns[2].minWidth = 154;
    columns[2].maxWidth = 200;
    columns[3].width = 100;
    columns[4].width = 154;
    columns[5].width = 154;
    columns[6].width = 154;
    columns[7].width = 154;
    columns[8].width = 154;
    columns[9].width = 154;
    columns[10].width = 154;
    columns[11].width = 200;
    columns[12].width = 154;

    columns.forEach((column) => {
        column.wordWrap = true;
    });
};

const customizeExchangeColumns = (columns) => {
    columns[0].width = 47;
    columns[1].minWidth = 154;
    columns[1].maxWidth = 200;
    columns[2].minWidth = 154;
    columns[2].maxWidth = 200;
    columns[3].minWidth = 154;
    columns[3].maxWidth = 200;
    columns[4].width = 154;
    columns[5].width = 154;
    columns[6].width = 154;
    columns[7].width = 127;
    columns[8].width = 127;
    columns[9].width = 200;
    columns[10].width = 154;
    columns[11].width = 127;
    columns[12].width = 127;
    columns[13].minWidth = 154;
    columns[13].maxWidth = 200;
    columns[14].minWidth = 154;
    columns[14].maxWidth = 200;
    columns[15].width = 154;

    columns.forEach((column) => {
        column.wordWrap = true;
    });
};

const customizePaymentColumns = (columns) => {
    columns[0].maxWidth = 47;
};

const customizeTransactionColumns = (columns) => {
    columns[0].maxWidth = 47;
};

export default {
    props: ['id'],
    components: {
        DxTextBox,
        DxTextBoxButton,
        DxTabs,
        DxItem,
        DxDataGrid,
        DxPager,
        DxPaging,
        DxScrolling,
        DxColumn,
        DxSelectBox,
        DxTextArea
    },
    data() {
        return {
            searchButton: searchButton,
            isToggleSearch: false,
            pageSize: [10, 20, 50, 100],
            shopInfo: {},
            townshipList: [],
            cityList: [],
            selectedIndex: 0,
            shopUserColumns: [
                'USER NAME',
                'PHONE NUMBER',
                'SHOP NAME',
                'EMAIL'
            ],
            shopUsers: [],
            orders: [],
            pickUps: [],
            exchanges: [],
            payments: [],
            transactions: [],
            cancelOrders: [],
            isEdit: false,
            customizeUserColumns: customizeUserColumns,
            customizeOrderColumns: customizeOrderColumns,
            customizePickUpColumns: customizePickUpColumns,
            customizeExchangeColumns: customizeExchangeColumns,
            customizePaymentColumns: customizePaymentColumns,
            customizeTransactionColumns: customizeTransactionColumns,
            financialAmounts: {},
            isLoading: false,
            isSuccess: false
        }
    },
    methods: {
        downloadShopCanceledOrderPdf(data) {
            this.downloadPdf(data, 'order', 'cancel');
        },
        downloadShopPickUpPdf(data) {
            this.downloadPdf(data, 'pick_up');
        },
        downloadShopOrderPdf(data) {
            this.downloadPdf(data, 'order');
        },
        downloadPdf(data, type, status = null) {
            // Create the download URL with query parameters
            const apiUrl = `/api/shops/${this.id}/download-pdf`;
             // Create a URLSearchParams object to handle query parameters
            const params = new URLSearchParams();
            
            params.append('type', type);
            if (status !== null) {
                params.append('status', status);
            }
            if (data.fromDate && data.toDate) {
                params.append('from_date', data.fromDate);
                params.append('to_date', data.toDate);
            }
            // Combine the API URL and query parameters
            const downloadUrl = apiUrl + (params.toString() ? `?${params.toString()}` : '');
            // Navigate to the download URL
            window.location.href = downloadUrl;
        },
        filterShopCanceledOrder(data) {
            this.getShopOrders('cancel', data.fromDate, data.toDate);
        },
        filterShopTransaction(data) {
            this.getShopTransactions(data.fromDate, data.toDate);
        },
        filterShopPayment(data) {
            this.getShopPayments(data.fromDate, data.toDate);
        },
        filterShopExchange(data) {
            this.getShopExchanges(data.fromDate, data.toDate);
        },
        filterShopPickUp(data) {
            this.getShopPickUps(data.fromDate, data.toDate);
        },
        filterShopOrder(data) {
            this.getShopOrders(null, data.fromDate, data.toDate);
        },
        formatDateInNumber(dateString) {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = date.getMonth();
            const year = date.getFullYear();
            return `${day}.${month}.${year}`;
        },
        formatDateWithLongText(dateString) {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            return `${day} ${month}, ${year}`;
        },
        formatWithNumberingSystem(number, decimal_place = 2) {
            if(number) {
                return parseFloat(number).toFixed(decimal_place).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            } else {
                return '';
            }
        },
        getStatusColor(status) {
            if (status === 'success') {
                return 'text-success';
            } else if (status === 'cancel' || status === 'cancel_request') {
                return 'text-cancel';
            } else {
                return 'text-warning';
            }
        },
        renderStatus(status) {
            if (status === 'success') {
                return 'Delivered';
            } else if (status === 'warehouse') {
                return 'In Warehouse';
            } else if (status === 'cancel') {
                return 'Canceled';
            } else if (status === 'cancel_request') {
                return 'Cancel Request';
            } else {
                return status;
            }
        },
        renderPaymentType(paymentType) {
            switch (paymentType) {
                case 'all_prepaid':
                    return 'All Prepaid';
                case 'item_prepaid':
                    return 'Item Prepaid';
                default:
                    return 'Cash On Delivery';
            }
        },
        renderPaymentChannel(paymentChannel) {
            switch (paymentChannel) {
                case 'shop_online_payment':
                    return 'Online Payment\n(Shop)';
                case 'company_online_payment':
                    return 'Online Payment\n(Company)';
                default:
                    return 'Cash';
            }
        },
        renderCollectionMethod(collectionMethod) {
            if(collectionMethod == 'pickup') {
                return 'Pick Up';
            } else {
                return 'Drop Off'
            }
        },
        async updateShop() {
            this.isLoading = true;
            const apiUrl = `/api/shops/${this.id}`;
            const postData = {
                'name': this.shopInfo.name,
                'township_id': this.shopInfo.township.id,
                'address': this.shopInfo.address,
                'phone_number': this.shopInfo.phone_number
            };

            const csrfToken = document.head.querySelector('meta[name="_token"]').content;

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(postData),
            });
            const encodedData = await response.json();
            
            this.shopInfo = encodedData.data;
            
            if(encodedData.status.toLowerCase() == 'success') {
                this.isEdit = false;
                this.isSuccess = true;
                this.isLoading = false;
                setTimeout(() => {
                    this.isSuccess = false;
                }, '2000');

            }
        },
        cancelEdit() {
            this.isEdit = false;
        },
        editShop() {
            this.isEdit = true;
            this.getCityList();
            this.getTownshipList();
        },
        cityValueChange() {
            this.getTownshipList('byCity');
        },
        async getTownshipList(type = null) {
            const apiUrl = `/api/get-township-list`;
            const url = type !== null ? `/api/get-township-list?city_id=${this.shopInfo.township.city.id}` : apiUrl;
            const response = await fetch(url);
            const data = await response.json();
            this.townshipList = data.data;
        },
        async getCityList() {
            await fetch('/api/get-city-list')
                .then((res) => res.json())
                .then((data) => {
                    this.cityList = data.data;
                });
        },
        async getFinancialAmounts() {
            const response = await fetch(`/api/shops/${this.id}/financial-amounts`);
            const data = await response.json();
            this.financialAmounts = data.data;
        },
        async getShopTransactions(fromDate = null, toDate = null) {
            // prepare API URL
            const apiUrl = `/api/shops/${this.id}/transactions`;

            // Create a URLSearchParams object to handle query parameters
            const params = new URLSearchParams();

            if (fromDate && toDate) {
                params.append('from_date', fromDate);
                params.append('to_date', toDate);
            }

            // Combine the API URL and query parameters
            const url = apiUrl + (params.toString() ? `?${params.toString()}` : '');
            // Call API
            const response = await fetch(url);
            // Catch Response
            const data = await response.json();
            // Retrieve Data With Destructing
            const { data: transactionsData } = data;

            const formattedData = transactionsData.map((transaction, index) => ({
                'id': transaction.id,
                'date': this.formatDateInNumber(transaction.created_at),
                'amount': this.formatWithNumberingSystem(transaction.amount) + ' MMK',
                'type': transaction.type == 'fully_payment' ? 'Fully Payment' : 'Loan Payment',
                'paidById': transaction.user ? transaction.user.id : '',
                'paidByName': transaction.user ? transaction.user.name : '-',
                'note': transaction.description ?? '-',
                'index': index + 1,
            }));
            this.transactions = formattedData;
        },
        async getShopPayments(fromDate = null, toDate = null) {
            // prepare API URL
            const apiUrl = `/api/shops/${this.id}/payments`;

            // Create a URLSearchParams object to handle query parameters
            const params = new URLSearchParams();

            if (fromDate && toDate) {
                params.append('from_date', fromDate);
                params.append('to_date', toDate);
            }

            // Combine the API URL and query parameters
            const url = apiUrl + (params.toString() ? `?${params.toString()}` : '');
            // Call API
            const response = await fetch(url);
            // Catch Response
            const data = await response.json();
            // Retrieve Data With Destructing
            const { data: paymentsData } = data;

            const formattedData = paymentsData.map((payment, index) => ({
                'id': payment.id,
                'date': this.formatDateInNumber(payment.created_at),
                'amount': this.formatWithNumberingSystem(payment.amount) + ' MMK',
                'type': payment.type == 'delivery_payment' ? "Delivery Payment" : 'Remaining Payment',
                'note': payment.description ?? '-',
                'index': index + 1,
            }));

            this.payments = formattedData;
        },
        renderExchangeStatus(status) {
            switch(status) {
                case 'complete':
                    return 'Completed';
                case 'picking-up':
                    return 'Picking Up';
                case 'in-warehouse':
                    return 'In Warehouse';
                default:
                    return status;
            }
        },
        formatExchange(exchange, index) {
            return {
                'id': exchange.id,
                'exchangeCode': exchange.customer_collection_code,
                'groupId': exchange.collection_group? exchange.collection_group.id : '',
                'groupCode': exchange.collection_group? exchange.collection_group.collection_group_code : '-',
                'orderId': exchange.order ? exchange.order.id : '',
                'orderCode': exchange.order ? exchange.order.order_code : '-',
                'shopId': exchange.shop ? exchange.shop.id : '',
                'shopName': exchange.shop ? exchange.shop.name : '-',
                'customerName': exchange.customer_name,
                'customerPhoneNumber': exchange.customer_phone_number,
                'cityId' : exchange.city ? exchange.city.id : '',
                'cityName': exchange.city ? exchange.city.name : '-',
                'townshipId' : exchange.township ? exchange.township.id : '',
                'townshipName': exchange.township ? exchange.township.name : '-',
                'customerAddress': exchange.address ?? '-',
                'paidAmountToCustomer': this.formatWithNumberingSystem(exchange.paid_amount) ?
                    this.formatWithNumberingSystem(exchange.paid_amount) + ' MMK' : '-',
                'riderId': exchange.rider ? exchange.rider.id : '',
                'riderName': exchange.rider ? exchange.rider.name : '-',
                'isWayPay': exchange.is_way_fees_payable == 0 ? 'No' : 'Yes',
                'items': exchange.items ?? '-',
                'note': exchange.note ?? '-',
                'status': this.renderExchangeStatus(exchange.status),
                'index': index + 1,
            };
        },
        async getShopExchanges(fromDate = null, toDate = null) {
            // prepare API URL
            const apiUrl = `/api/shops/${this.id}/exchanges`;

            // Create a URLSearchParams object to handle query parameters
            const params = new URLSearchParams();
            
            if (fromDate && toDate) {
                params.append('from_date', fromDate);
                params.append('to_date', toDate);
            }

            // Combine the API URL and query parameters
            const url = apiUrl + (params.toString() ? `?${params.toString()}` : '');
            // Call API
            const response = await fetch(url);
            // Catch Response
            const data = await response.json();
            // Retrieve Data With Destructing
            const { data: exchangesData } = data;

            const formattedData = exchangesData.map((exchange, index) => this.formatExchange(exchange, index));

            this.exchanges = formattedData;
        },
        renderPickUpStatus(status) {
            switch(status) {
                case 'complete':
                    return 'Collected';
                case 'picking-up':
                    return 'Picking Up';
                case 'in-warehouse':
                    return 'In Warehouse';
                default:
                    return status;
            }
        },
        formatPickUp(pickUp, index) {
            return {
                'id': pickUp.id,
                'pickUpCode': pickUp.collection_code,
                'groupId': pickUp.collection_group? pickUp.collection_group.id : '',
                'groupCode': pickUp.collection_group? pickUp.collection_group.collection_group_code : '-',
                'quantity': this.formatWithNumberingSystem(pickUp.total_quantity, 0) ?
                    this.formatWithNumberingSystem(pickUp.total_quantity, 0) : '-',
                'totalAmount': this.formatWithNumberingSystem(pickUp.total_amount) ?
                    this.formatWithNumberingSystem(pickUp.total_amount) + ' MMK' : '-',
                'paidAmountByRider': this.formatWithNumberingSystem(pickUp.paid_amount) ?
                    this.formatWithNumberingSystem(pickUp.paid_amount) + ' MMK' : '-',
                'leftoverAmount': pickUp.total_amount - pickUp.paid_amount !== 0 ? 
                    this.formatWithNumberingSystem(pickUp.total_amount - pickUp.paid_amount) + ' MMK' : '',
                'riderId': pickUp.rider ? pickUp.rider.id : '',
                'riderName': pickUp.rider ? pickUp.rider.name : '-',
                'shopId': pickUp.shop ? pickUp.shop.id : '',
                'shopName': pickUp.shop ? pickUp.shop.name : '-',
                'scheduledAt': pickUp.assigned_at ?? '-',
                'collectedAt': pickUp.collected_at ?? '-',
                'remark': pickUp.note ?? '-',
                'status': this.renderPickUpStatus(pickUp.status),
                'index': index + 1,
            };
        },
        async getShopPickUps(fromDate = null, toDate = null) {
            // prepare API URL
            const apiUrl = `/api/shops/${this.id}/pick-ups`;

            // Create a URLSearchParams object to handle query parameters
            const params = new URLSearchParams();
            
            if (fromDate && toDate) {
                params.append('from_date', fromDate);
                params.append('to_date', toDate);
            }

            // Combine the API URL and query parameters
            const url = apiUrl + (params.toString() ? `?${params.toString()}` : '');
            // Call API
            const response = await fetch(url);
            // Catch Response
            const data = await response.json();
            // Retrieve Data With Destructing
            const { data: pickUpsData } = data;

            const formattedData = pickUpsData.map((pickUp, index) => this.formatPickUp(pickUp, index));
            this.pickUps = formattedData;
        },
        sumValuesSeparatedByCommas(inputString) {
            return inputString?.includes(',')
                ? inputString.split(',').map(Number).reduce((total, currentValue) => total + currentValue, 0)
                : inputString;
        },
        formatOrder(order, index) {
            return {
                'id': order.id,
                'orderCode': order.order_code,
                'customerPhoneNumber': order.customer_phone_number,
                'customerAddress': order.full_address ?? '-',
                'customerName': order.customer_name,
                'shopId': order.shop ? order.shop.id : '',
                'shopName': order.shop ? order.shop.name : '-',
                'cityId': order.city ? order.city.id : '',
                'cityName': order.city ? order.city.name : '-',
                'townshipId': order.township ? order.township.id : '',
                'townshipName': order.township ? order.township.name : '-',
                'quantity': this.sumValuesSeparatedByCommas(order.quantity) ? 
                    this.formatWithNumberingSystem(this.sumValuesSeparatedByCommas(order.quantity), 0) : '-',
                'totalAmount': this.formatWithNumberingSystem(order.total_amount) + ' MMK',
                'deliveryFees': this.formatWithNumberingSystem(order.delivery_fees - order.discount) ?
                    this.formatWithNumberingSystem(order.delivery_fees - order.discount) + ' MMK' : '-',
                'markupDeliveryFees': this.formatWithNumberingSystem(order.markupDeliveryFees) ? 
                    this.formatWithNumberingSystem(order.markupDeliveryFees) + ' MMK' : '-',
                'extraCharges': this.formatWithNumberingSystem(order.extra_charges) ?
                    this.formatWithNumberingSystem(order.extra_charges) + ' MMK' : '-',
                'itemType': order.item_type ? order.item_type.name : '-',
                'paymentType': this.renderPaymentType(order.payment_method),
                'paymentChannel': this.renderPaymentChannel(order.payment_channel),
                'collectionMethod': this.renderCollectionMethod(order.collection_method),
                'scheduleDate': this.formatDateWithLongText(order.schedule_date),
                'type': order.delivery_type.name,
                'riderId': order.rider ? order.rider.id : '',
                'riderName': order.rider ? order.rider.name : '-',
                'remark': order.remark ?? '-',
                'origin': order.branch.name,
                'status': order.status,
                'index': index + 1
            };
        },
        async getShopOrders(status = null, fromDate = null, toDate = null) {
            // prepare API URL
            const apiUrl = `/api/shops/${this.id}/orders`;

            // Create a URLSearchParams object to handle query parameters
            const params = new URLSearchParams();
            if (status !== null) {
                params.append('status', status);
            }
            if (fromDate && toDate) {
                params.append('from_date', fromDate);
                params.append('to_date', toDate);
            }

            // Combine the API URL and query parameters
            const url = apiUrl + (params.toString() ? `?${params.toString()}` : '');
            // Call API
            const response = await fetch(url);
            // Catch Response
            const data = await response.json();
            // Retrieve Data With Destructing
            const { data: ordersData } = data;
            const formattedData = ordersData.map((order, index) => this.formatOrder(order, index));
            
            if(status != 'cancel') {
                this.orders = formattedData;
            } else {
                this.cancelOrders = formattedData;
            }
        },
        async getShopUsers() {
            const response = await fetch(`/api/shops/${this.id}/shop-users`);
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
        async getShopDetail() {
            const response = await fetch(`/api/shops/${this.id}`);
            const data = await response.json();
            this.shopInfo = data.data;
        }
    },
    computed: {
        joinedDate() {
            return this.formatDateWithLongText(this.shopInfo.created_at);
        },
    },
    mounted() {
        this.getShopDetail();
        this.getShopUsers();
    },
    watch: {
        selectedIndex(newValue, oldValue) {
            switch (newValue) {
            case 1:
                if(this.orders.length == 0) {
                    this.getShopOrders();
                    this.getFinancialAmounts();
                }
                break;
            case 2:
                if(this.pickUps.length == 0) {
                    this.getShopPickUps();
                }
                break;
            case 3:
                if(this.exchanges.length == 0) {
                    this.getShopExchanges();
                }
                break;
            case 4:
                if(this.payments.length == 0) {
                    this.getShopPayments();
                }
                break;
            case 5:
                if(this.transactions.length == 0) {
                    this.getShopTransactions();
                }
                break;
            case 6:
                if(this.cancelOrders.length == 0) {
                    this.getShopOrders('cancel');
                }
                break;
            default:
                if(this.shopUsers.length == 0) {
                    this.getShopUsers();
                }
            }
        },
    }
}
</script>

<style>
#filter-container {
    padding: 35px 0;
    border-top: 1px solid #AAAAAA;
}

#shop-info-container .dx-textbox {
    margin-top: 4px;
}

#shop-info-container .dx-texteditor.dx-state-focused.dx-editor-outlined,
#shop-info-container .dx-texteditor.dx-state-hover {
    border-color: #116A5B;
}

.info-box {
    margin-top: 5px;
    padding: 8px 12px;
}

.dx-tabs {
    display: block;
    width: 100%;
    background-color: #FFFFFF;
    border-bottom: 1px solid #116A5B;
    box-shadow: unset;
}

.dx-tab {
    width: 100px;
    padding: 3px 9px !important;
    background-color: #FFFFFF !important;
    border-top: 1px solid #AAAAAA;
    border-right: 1px solid #AAAAAA;
}

.dx-tab:first-of-type {
    border-left: 1px solid #AAAAAA;
}

.dx-tabs.dx-state-focused .dx-tab.dx-state-focused, 
.dx-tab.dx-tab-selected {
    border: 1px solid #116A5B;
    color: #FFFFFF !important;
    background-color: #116A5B !important;
    box-shadow: unset !important;
}

.dx-tab .dx-tab-text {
    color: #AAAAAA !important;
}

.dx-tab.dx-tab-selected .dx-tab-text {
    color: #FFFFFF !important;
}

.shop-related-containers .custom-data-grid .dx-datagrid-headers .dx-datagrid-table .dx-header-row .dx-datagrid-action {
    height: 46px;
    vertical-align: middle;
    white-space: normal;
}

.label {
    color: #000000;
    font-size: 16px I !important;
    font-style: normal;
    font-weight: 400 !important;
    line-height: 22px;
    text-transform: uppercase;
}

.label-count {
    color: #000000;
    font-size: 20px !important;
    font-style: normal;
    font-weight: 700 !important;
    line-height: normal;
}
</style>