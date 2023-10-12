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
                </div>
            </div>
            <div v-if="selectedIndex === 1">
                <div class="flex justify-end my-4">
                    <div class="mr-3">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="calendar">
                                <rect id="Rectangle 72" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="calendar_2" d="M10.673 5C10.8587 5 11.0367 5.07375 11.168 5.20503C11.2993 5.3363 11.373 5.51435 11.373 5.7V7.009H18.89V5.709C18.89 5.52335 18.9637 5.3453 19.095 5.21403C19.2263 5.08275 19.4043 5.009 19.59 5.009C19.7757 5.009 19.9537 5.08275 20.085 5.21403C20.2162 5.3453 20.29 5.52335 20.29 5.709V7.009H23C23.5303 7.009 24.0388 7.21958 24.4139 7.59443C24.7889 7.96929 24.9997 8.47774 25 9.008V23.001C24.9997 23.5313 24.7889 24.0397 24.4139 24.4146C24.0388 24.7894 23.5303 25 23 25H7C6.46974 25 5.96118 24.7894 5.58614 24.4146C5.2111 24.0397 5.00027 23.5313 5 23.001V9.008C5.00027 8.47774 5.2111 7.96929 5.58614 7.59443C5.96118 7.21958 6.46974 7.009 7 7.009H9.973V5.699C9.97327 5.51352 10.0471 5.33573 10.1784 5.20467C10.3096 5.07361 10.4875 5 10.673 5ZM6.4 12.742V23.001C6.4 23.0798 6.41552 23.1578 6.44567 23.2306C6.47583 23.3034 6.52002 23.3696 6.57574 23.4253C6.63145 23.481 6.69759 23.5252 6.77039 23.5553C6.84319 23.5855 6.92121 23.601 7 23.601H23C23.0788 23.601 23.1568 23.5855 23.2296 23.5553C23.3024 23.5252 23.3686 23.481 23.4243 23.4253C23.48 23.3696 23.5242 23.3034 23.5543 23.2306C23.5845 23.1578 23.6 23.0798 23.6 23.001V12.756L6.4 12.742ZM11.667 19.619V21.285H10V19.619H11.667ZM15.833 19.619V21.285H14.167V19.619H15.833ZM20 19.619V21.285H18.333V19.619H20ZM11.667 15.642V17.308H10V15.642H11.667ZM15.833 15.642V17.308H14.167V15.642H15.833ZM20 15.642V17.308H18.333V15.642H20ZM9.973 8.408H7C6.92121 8.408 6.84319 8.42352 6.77039 8.45367C6.69759 8.48382 6.63145 8.52802 6.57574 8.58374C6.52002 8.63945 6.47583 8.70559 6.44567 8.77839C6.41552 8.85119 6.4 8.92921 6.4 9.008V11.343L23.6 11.357V9.008C23.6 8.92921 23.5845 8.85119 23.5543 8.77839C23.5242 8.70559 23.48 8.63945 23.4243 8.58374C23.3686 8.52802 23.3024 8.48382 23.2296 8.45367C23.1568 8.42352 23.0788 8.408 23 8.408H20.29V9.337C20.29 9.52265 20.2162 9.7007 20.085 9.83197C19.9537 9.96325 19.7757 10.037 19.59 10.037C19.4043 10.037 19.2263 9.96325 19.095 9.83197C18.9637 9.7007 18.89 9.52265 18.89 9.337V8.408H11.373V9.328C11.373 9.51365 11.2993 9.6917 11.168 9.82297C11.0367 9.95425 10.8587 10.028 10.673 10.028C10.4873 10.028 10.3093 9.95425 10.178 9.82297C10.0468 9.6917 9.973 9.51365 9.973 9.328V8.408Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-8 my-4">
                    <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                        <h5 class="my-4 label">ITEM AMOUNT</h5>
                        <hr class="border-main">
                        <h5 class="my-6 label-count">{{this.formatWithNumberingSystem(financialAmounts.totalItemAmount)}} MMK</h5>
                    </div>
                    <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                        <h5 class="my-4 label">MARKUP DELIVERY FEES</h5>
                        <hr class="border-main">
                        <h5 class="my-6 label-count">{{this.formatWithNumberingSystem(financialAmounts.totalMarkUpDeliveryFees)}} MMK</h5>
                    </div>
                    <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                        <h5 class="my-4 label">DELIVERY FEES</h5>
                        <hr class="border-main">
                        <h5 class="my-6 label-count">{{this.formatWithNumberingSystem(financialAmounts.totalDeliveryFees)}} MMK</h5>
                    </div>
                    <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                        <h5 class="my-4 label">PAY AMOUNT TO SHOP</h5>
                        <hr class="border-main">
                        <h5 class="my-6 label-count">{{this.formatWithNumberingSystem(financialAmounts.totalAmountToPayShop)}} MMK</h5>
                    </div>
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
            <div v-if="selectedIndex === 2">
                <div class="flex justify-end my-4">
                    <div class="mr-3">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="calendar">
                                <rect id="Rectangle 72" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="calendar_2" d="M10.673 5C10.8587 5 11.0367 5.07375 11.168 5.20503C11.2993 5.3363 11.373 5.51435 11.373 5.7V7.009H18.89V5.709C18.89 5.52335 18.9637 5.3453 19.095 5.21403C19.2263 5.08275 19.4043 5.009 19.59 5.009C19.7757 5.009 19.9537 5.08275 20.085 5.21403C20.2162 5.3453 20.29 5.52335 20.29 5.709V7.009H23C23.5303 7.009 24.0388 7.21958 24.4139 7.59443C24.7889 7.96929 24.9997 8.47774 25 9.008V23.001C24.9997 23.5313 24.7889 24.0397 24.4139 24.4146C24.0388 24.7894 23.5303 25 23 25H7C6.46974 25 5.96118 24.7894 5.58614 24.4146C5.2111 24.0397 5.00027 23.5313 5 23.001V9.008C5.00027 8.47774 5.2111 7.96929 5.58614 7.59443C5.96118 7.21958 6.46974 7.009 7 7.009H9.973V5.699C9.97327 5.51352 10.0471 5.33573 10.1784 5.20467C10.3096 5.07361 10.4875 5 10.673 5ZM6.4 12.742V23.001C6.4 23.0798 6.41552 23.1578 6.44567 23.2306C6.47583 23.3034 6.52002 23.3696 6.57574 23.4253C6.63145 23.481 6.69759 23.5252 6.77039 23.5553C6.84319 23.5855 6.92121 23.601 7 23.601H23C23.0788 23.601 23.1568 23.5855 23.2296 23.5553C23.3024 23.5252 23.3686 23.481 23.4243 23.4253C23.48 23.3696 23.5242 23.3034 23.5543 23.2306C23.5845 23.1578 23.6 23.0798 23.6 23.001V12.756L6.4 12.742ZM11.667 19.619V21.285H10V19.619H11.667ZM15.833 19.619V21.285H14.167V19.619H15.833ZM20 19.619V21.285H18.333V19.619H20ZM11.667 15.642V17.308H10V15.642H11.667ZM15.833 15.642V17.308H14.167V15.642H15.833ZM20 15.642V17.308H18.333V15.642H20ZM9.973 8.408H7C6.92121 8.408 6.84319 8.42352 6.77039 8.45367C6.69759 8.48382 6.63145 8.52802 6.57574 8.58374C6.52002 8.63945 6.47583 8.70559 6.44567 8.77839C6.41552 8.85119 6.4 8.92921 6.4 9.008V11.343L23.6 11.357V9.008C23.6 8.92921 23.5845 8.85119 23.5543 8.77839C23.5242 8.70559 23.48 8.63945 23.4243 8.58374C23.3686 8.52802 23.3024 8.48382 23.2296 8.45367C23.1568 8.42352 23.0788 8.408 23 8.408H20.29V9.337C20.29 9.52265 20.2162 9.7007 20.085 9.83197C19.9537 9.96325 19.7757 10.037 19.59 10.037C19.4043 10.037 19.2263 9.96325 19.095 9.83197C18.9637 9.7007 18.89 9.52265 18.89 9.337V8.408H11.373V9.328C11.373 9.51365 11.2993 9.6917 11.168 9.82297C11.0367 9.95425 10.8587 10.028 10.673 10.028C10.4873 10.028 10.3093 9.95425 10.178 9.82297C10.0468 9.6917 9.973 9.51365 9.973 9.328V8.408Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div id="pickup-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="pickUps"
                        :columns="gridPickUpColumns"
                        :customize-columns="customizePickUpColumns"
                        class="custom-data-grid shop-pickup-data-grid"
                        ref="pickupDataGrid"
                        :columnAutoWidth="true"
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
            <div v-if="selectedIndex === 3">
                <div class="flex justify-end my-4">
                    <div class="mr-3">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="calendar">
                                <rect id="Rectangle 72" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="calendar_2" d="M10.673 5C10.8587 5 11.0367 5.07375 11.168 5.20503C11.2993 5.3363 11.373 5.51435 11.373 5.7V7.009H18.89V5.709C18.89 5.52335 18.9637 5.3453 19.095 5.21403C19.2263 5.08275 19.4043 5.009 19.59 5.009C19.7757 5.009 19.9537 5.08275 20.085 5.21403C20.2162 5.3453 20.29 5.52335 20.29 5.709V7.009H23C23.5303 7.009 24.0388 7.21958 24.4139 7.59443C24.7889 7.96929 24.9997 8.47774 25 9.008V23.001C24.9997 23.5313 24.7889 24.0397 24.4139 24.4146C24.0388 24.7894 23.5303 25 23 25H7C6.46974 25 5.96118 24.7894 5.58614 24.4146C5.2111 24.0397 5.00027 23.5313 5 23.001V9.008C5.00027 8.47774 5.2111 7.96929 5.58614 7.59443C5.96118 7.21958 6.46974 7.009 7 7.009H9.973V5.699C9.97327 5.51352 10.0471 5.33573 10.1784 5.20467C10.3096 5.07361 10.4875 5 10.673 5ZM6.4 12.742V23.001C6.4 23.0798 6.41552 23.1578 6.44567 23.2306C6.47583 23.3034 6.52002 23.3696 6.57574 23.4253C6.63145 23.481 6.69759 23.5252 6.77039 23.5553C6.84319 23.5855 6.92121 23.601 7 23.601H23C23.0788 23.601 23.1568 23.5855 23.2296 23.5553C23.3024 23.5252 23.3686 23.481 23.4243 23.4253C23.48 23.3696 23.5242 23.3034 23.5543 23.2306C23.5845 23.1578 23.6 23.0798 23.6 23.001V12.756L6.4 12.742ZM11.667 19.619V21.285H10V19.619H11.667ZM15.833 19.619V21.285H14.167V19.619H15.833ZM20 19.619V21.285H18.333V19.619H20ZM11.667 15.642V17.308H10V15.642H11.667ZM15.833 15.642V17.308H14.167V15.642H15.833ZM20 15.642V17.308H18.333V15.642H20ZM9.973 8.408H7C6.92121 8.408 6.84319 8.42352 6.77039 8.45367C6.69759 8.48382 6.63145 8.52802 6.57574 8.58374C6.52002 8.63945 6.47583 8.70559 6.44567 8.77839C6.41552 8.85119 6.4 8.92921 6.4 9.008V11.343L23.6 11.357V9.008C23.6 8.92921 23.5845 8.85119 23.5543 8.77839C23.5242 8.70559 23.48 8.63945 23.4243 8.58374C23.3686 8.52802 23.3024 8.48382 23.2296 8.45367C23.1568 8.42352 23.0788 8.408 23 8.408H20.29V9.337C20.29 9.52265 20.2162 9.7007 20.085 9.83197C19.9537 9.96325 19.7757 10.037 19.59 10.037C19.4043 10.037 19.2263 9.96325 19.095 9.83197C18.9637 9.7007 18.89 9.52265 18.89 9.337V8.408H11.373V9.328C11.373 9.51365 11.2993 9.6917 11.168 9.82297C11.0367 9.95425 10.8587 10.028 10.673 10.028C10.4873 10.028 10.3093 9.95425 10.178 9.82297C10.0468 9.6917 9.973 9.51365 9.973 9.328V8.408Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div id="exchange-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="exchanges"
                        :columns="gridExchangeColumns"
                        :customize-columns="customizeExchangeColumns"
                        class="custom-data-grid shop-exchange-data-grid"
                        ref="exchangeDataGrid"
                        :columnAutoWidth="true"
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
            <div v-if="selectedIndex === 4">
                <div class="flex justify-end my-4">
                    <div class="mr-3">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="calendar">
                                <rect id="Rectangle 72" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="calendar_2" d="M10.673 5C10.8587 5 11.0367 5.07375 11.168 5.20503C11.2993 5.3363 11.373 5.51435 11.373 5.7V7.009H18.89V5.709C18.89 5.52335 18.9637 5.3453 19.095 5.21403C19.2263 5.08275 19.4043 5.009 19.59 5.009C19.7757 5.009 19.9537 5.08275 20.085 5.21403C20.2162 5.3453 20.29 5.52335 20.29 5.709V7.009H23C23.5303 7.009 24.0388 7.21958 24.4139 7.59443C24.7889 7.96929 24.9997 8.47774 25 9.008V23.001C24.9997 23.5313 24.7889 24.0397 24.4139 24.4146C24.0388 24.7894 23.5303 25 23 25H7C6.46974 25 5.96118 24.7894 5.58614 24.4146C5.2111 24.0397 5.00027 23.5313 5 23.001V9.008C5.00027 8.47774 5.2111 7.96929 5.58614 7.59443C5.96118 7.21958 6.46974 7.009 7 7.009H9.973V5.699C9.97327 5.51352 10.0471 5.33573 10.1784 5.20467C10.3096 5.07361 10.4875 5 10.673 5ZM6.4 12.742V23.001C6.4 23.0798 6.41552 23.1578 6.44567 23.2306C6.47583 23.3034 6.52002 23.3696 6.57574 23.4253C6.63145 23.481 6.69759 23.5252 6.77039 23.5553C6.84319 23.5855 6.92121 23.601 7 23.601H23C23.0788 23.601 23.1568 23.5855 23.2296 23.5553C23.3024 23.5252 23.3686 23.481 23.4243 23.4253C23.48 23.3696 23.5242 23.3034 23.5543 23.2306C23.5845 23.1578 23.6 23.0798 23.6 23.001V12.756L6.4 12.742ZM11.667 19.619V21.285H10V19.619H11.667ZM15.833 19.619V21.285H14.167V19.619H15.833ZM20 19.619V21.285H18.333V19.619H20ZM11.667 15.642V17.308H10V15.642H11.667ZM15.833 15.642V17.308H14.167V15.642H15.833ZM20 15.642V17.308H18.333V15.642H20ZM9.973 8.408H7C6.92121 8.408 6.84319 8.42352 6.77039 8.45367C6.69759 8.48382 6.63145 8.52802 6.57574 8.58374C6.52002 8.63945 6.47583 8.70559 6.44567 8.77839C6.41552 8.85119 6.4 8.92921 6.4 9.008V11.343L23.6 11.357V9.008C23.6 8.92921 23.5845 8.85119 23.5543 8.77839C23.5242 8.70559 23.48 8.63945 23.4243 8.58374C23.3686 8.52802 23.3024 8.48382 23.2296 8.45367C23.1568 8.42352 23.0788 8.408 23 8.408H20.29V9.337C20.29 9.52265 20.2162 9.7007 20.085 9.83197C19.9537 9.96325 19.7757 10.037 19.59 10.037C19.4043 10.037 19.2263 9.96325 19.095 9.83197C18.9637 9.7007 18.89 9.52265 18.89 9.337V8.408H11.373V9.328C11.373 9.51365 11.2993 9.6917 11.168 9.82297C11.0367 9.95425 10.8587 10.028 10.673 10.028C10.4873 10.028 10.3093 9.95425 10.178 9.82297C10.0468 9.6917 9.973 9.51365 9.973 9.328V8.408Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div id="payment-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="payments"
                        :columns="gridPaymentColumns"
                        :customize-columns="customizePaymentColumns"
                        class="custom-data-grid shop-payment-data-grid"
                        ref="paymentDataGrid"
                        :columnAutoWidth="true"
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
            <div v-if="selectedIndex === 5">
                <div class="flex justify-end my-4">
                    <div class="mr-3">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="calendar">
                                <rect id="Rectangle 72" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="calendar_2" d="M10.673 5C10.8587 5 11.0367 5.07375 11.168 5.20503C11.2993 5.3363 11.373 5.51435 11.373 5.7V7.009H18.89V5.709C18.89 5.52335 18.9637 5.3453 19.095 5.21403C19.2263 5.08275 19.4043 5.009 19.59 5.009C19.7757 5.009 19.9537 5.08275 20.085 5.21403C20.2162 5.3453 20.29 5.52335 20.29 5.709V7.009H23C23.5303 7.009 24.0388 7.21958 24.4139 7.59443C24.7889 7.96929 24.9997 8.47774 25 9.008V23.001C24.9997 23.5313 24.7889 24.0397 24.4139 24.4146C24.0388 24.7894 23.5303 25 23 25H7C6.46974 25 5.96118 24.7894 5.58614 24.4146C5.2111 24.0397 5.00027 23.5313 5 23.001V9.008C5.00027 8.47774 5.2111 7.96929 5.58614 7.59443C5.96118 7.21958 6.46974 7.009 7 7.009H9.973V5.699C9.97327 5.51352 10.0471 5.33573 10.1784 5.20467C10.3096 5.07361 10.4875 5 10.673 5ZM6.4 12.742V23.001C6.4 23.0798 6.41552 23.1578 6.44567 23.2306C6.47583 23.3034 6.52002 23.3696 6.57574 23.4253C6.63145 23.481 6.69759 23.5252 6.77039 23.5553C6.84319 23.5855 6.92121 23.601 7 23.601H23C23.0788 23.601 23.1568 23.5855 23.2296 23.5553C23.3024 23.5252 23.3686 23.481 23.4243 23.4253C23.48 23.3696 23.5242 23.3034 23.5543 23.2306C23.5845 23.1578 23.6 23.0798 23.6 23.001V12.756L6.4 12.742ZM11.667 19.619V21.285H10V19.619H11.667ZM15.833 19.619V21.285H14.167V19.619H15.833ZM20 19.619V21.285H18.333V19.619H20ZM11.667 15.642V17.308H10V15.642H11.667ZM15.833 15.642V17.308H14.167V15.642H15.833ZM20 15.642V17.308H18.333V15.642H20ZM9.973 8.408H7C6.92121 8.408 6.84319 8.42352 6.77039 8.45367C6.69759 8.48382 6.63145 8.52802 6.57574 8.58374C6.52002 8.63945 6.47583 8.70559 6.44567 8.77839C6.41552 8.85119 6.4 8.92921 6.4 9.008V11.343L23.6 11.357V9.008C23.6 8.92921 23.5845 8.85119 23.5543 8.77839C23.5242 8.70559 23.48 8.63945 23.4243 8.58374C23.3686 8.52802 23.3024 8.48382 23.2296 8.45367C23.1568 8.42352 23.0788 8.408 23 8.408H20.29V9.337C20.29 9.52265 20.2162 9.7007 20.085 9.83197C19.9537 9.96325 19.7757 10.037 19.59 10.037C19.4043 10.037 19.2263 9.96325 19.095 9.83197C18.9637 9.7007 18.89 9.52265 18.89 9.337V8.408H11.373V9.328C11.373 9.51365 11.2993 9.6917 11.168 9.82297C11.0367 9.95425 10.8587 10.028 10.673 10.028C10.4873 10.028 10.3093 9.95425 10.178 9.82297C10.0468 9.6917 9.973 9.51365 9.973 9.328V8.408Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div id="transaction-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="transactions"
                        :columns="gridTransactionColumns"
                        :customize-columns="customizeTransactionColumns"
                        class="custom-data-grid shop-transaction-data-grid"
                        ref="transactionDataGrid"
                        :columnAutoWidth="true"
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
                <div class="flex justify-end my-4">
                    <div class="mr-3">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="calendar">
                                <rect id="Rectangle 72" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="calendar_2" d="M10.673 5C10.8587 5 11.0367 5.07375 11.168 5.20503C11.2993 5.3363 11.373 5.51435 11.373 5.7V7.009H18.89V5.709C18.89 5.52335 18.9637 5.3453 19.095 5.21403C19.2263 5.08275 19.4043 5.009 19.59 5.009C19.7757 5.009 19.9537 5.08275 20.085 5.21403C20.2162 5.3453 20.29 5.52335 20.29 5.709V7.009H23C23.5303 7.009 24.0388 7.21958 24.4139 7.59443C24.7889 7.96929 24.9997 8.47774 25 9.008V23.001C24.9997 23.5313 24.7889 24.0397 24.4139 24.4146C24.0388 24.7894 23.5303 25 23 25H7C6.46974 25 5.96118 24.7894 5.58614 24.4146C5.2111 24.0397 5.00027 23.5313 5 23.001V9.008C5.00027 8.47774 5.2111 7.96929 5.58614 7.59443C5.96118 7.21958 6.46974 7.009 7 7.009H9.973V5.699C9.97327 5.51352 10.0471 5.33573 10.1784 5.20467C10.3096 5.07361 10.4875 5 10.673 5ZM6.4 12.742V23.001C6.4 23.0798 6.41552 23.1578 6.44567 23.2306C6.47583 23.3034 6.52002 23.3696 6.57574 23.4253C6.63145 23.481 6.69759 23.5252 6.77039 23.5553C6.84319 23.5855 6.92121 23.601 7 23.601H23C23.0788 23.601 23.1568 23.5855 23.2296 23.5553C23.3024 23.5252 23.3686 23.481 23.4243 23.4253C23.48 23.3696 23.5242 23.3034 23.5543 23.2306C23.5845 23.1578 23.6 23.0798 23.6 23.001V12.756L6.4 12.742ZM11.667 19.619V21.285H10V19.619H11.667ZM15.833 19.619V21.285H14.167V19.619H15.833ZM20 19.619V21.285H18.333V19.619H20ZM11.667 15.642V17.308H10V15.642H11.667ZM15.833 15.642V17.308H14.167V15.642H15.833ZM20 15.642V17.308H18.333V15.642H20ZM9.973 8.408H7C6.92121 8.408 6.84319 8.42352 6.77039 8.45367C6.69759 8.48382 6.63145 8.52802 6.57574 8.58374C6.52002 8.63945 6.47583 8.70559 6.44567 8.77839C6.41552 8.85119 6.4 8.92921 6.4 9.008V11.343L23.6 11.357V9.008C23.6 8.92921 23.5845 8.85119 23.5543 8.77839C23.5242 8.70559 23.48 8.63945 23.4243 8.58374C23.3686 8.52802 23.3024 8.48382 23.2296 8.45367C23.1568 8.42352 23.0788 8.408 23 8.408H20.29V9.337C20.29 9.52265 20.2162 9.7007 20.085 9.83197C19.9537 9.96325 19.7757 10.037 19.59 10.037C19.4043 10.037 19.2263 9.96325 19.095 9.83197C18.9637 9.7007 18.89 9.52265 18.89 9.337V8.408H11.373V9.328C11.373 9.51365 11.2993 9.6917 11.168 9.82297C11.0367 9.95425 10.8587 10.028 10.673 10.028C10.4873 10.028 10.3093 9.95425 10.178 9.82297C10.0468 9.6917 9.973 9.51365 9.973 9.328V8.408Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="download">
                                <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                                <path id="Vector" d="M25 17.5V23.3333C25 23.7754 24.8244 24.1993 24.5118 24.5118C24.1993 24.8244 23.7754 25 23.3333 25H6.66667C6.22464 25 5.80072 24.8244 5.48816 24.5118C5.17559 24.1993 5 23.7754 5 23.3333V17.5C5 17.279 5.0878 17.067 5.24408 16.9107C5.40036 16.7545 5.61232 16.6667 5.83333 16.6667C6.05435 16.6667 6.26631 16.7545 6.42259 16.9107C6.57887 17.067 6.66667 17.279 6.66667 17.5V23.3333H23.3333V17.5C23.3333 17.279 23.4211 17.067 23.5774 16.9107C23.7337 16.7545 23.9457 16.6667 24.1667 16.6667C24.3877 16.6667 24.5996 16.7545 24.7559 16.9107C24.9122 17.067 25 17.279 25 17.5ZM14.4104 18.0896C14.4878 18.1671 14.5797 18.2285 14.6809 18.2705C14.782 18.3124 14.8905 18.334 15 18.334C15.1095 18.334 15.218 18.3124 15.3191 18.2705C15.4203 18.2285 15.5122 18.1671 15.5896 18.0896L19.7563 13.9229C19.8337 13.8455 19.8951 13.7536 19.937 13.6524C19.9789 13.5513 20.0005 13.4428 20.0005 13.3333C20.0005 13.2238 19.9789 13.1154 19.937 13.0143C19.8951 12.9131 19.8337 12.8212 19.7563 12.7437C19.6788 12.6663 19.5869 12.6049 19.4857 12.563C19.3846 12.5211 19.2762 12.4995 19.1667 12.4995C19.0572 12.4995 18.9487 12.5211 18.8476 12.563C18.7464 12.6049 18.6545 12.6663 18.5771 12.7437L15.8333 15.4885V5.83333C15.8333 5.61232 15.7455 5.40036 15.5893 5.24408C15.433 5.0878 15.221 5 15 5C14.779 5 14.567 5.0878 14.4107 5.24408C14.2545 5.40036 14.1667 5.61232 14.1667 5.83333V15.4885L11.4229 12.7437C11.2665 12.5874 11.0545 12.4995 10.8333 12.4995C10.6122 12.4995 10.4001 12.5874 10.2437 12.7437C10.0874 12.9001 9.99954 13.1122 9.99954 13.3333C9.99954 13.5545 10.0874 13.7666 10.2437 13.9229L14.4104 18.0896Z" fill="white"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div id="canceled-container">
                <!-- table -->
                    <dx-data-grid
                        :data-source="cancelOrders"
                        :columns="gridcancelOrderColumns"
                        :customize-columns="customizeOrderColumns"
                        class="custom-data-grid shop-order-data-grid"
                        ref="canceledOrderDataGrid"
                        :columnAutoWidth="true"
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
        async getShopTransactions() {
            const response = await fetch(`/api/shops/${this.id}/transactions`);
            const data = await response.json();

            const formattedData = data.data.map((transaction, index) => ({
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
        async getShopPayments() {
            const response = await fetch(`/api/shops/${this.id}/payments`);
            const data = await response.json();

            const formattedData = data.data.map((payment, index) => ({
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
        async getShopExchanges() {
            const response = await fetch(`/api/shops/${this.id}/exchanges`);
            const data = await response.json();

            const formattedData = data.data.map((exchange, index) => this.formatExchange(exchange, index));

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
        async getShopPickUps() {
            const response = await fetch(`/api/shops/${this.id}/pick-ups`);
            const data = await response.json();

            const formattedData = data.data.map((pickUp, index) => this.formatPickUp(pickUp, index));
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
        async getShopOrders(status = null) {
            const apiUrl = `/api/shops/${this.id}/orders`;
            const url = status !== null ? `${apiUrl}?status=${status}` : apiUrl;
            const response = await fetch(url);
            const data = await response.json();

            const formattedData = data.data.map((order, index) => this.formatOrder(order, index));
            
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
                this.getShopOrders();
                this.getFinancialAmounts();
                break;
            case 2:
                this.getShopPickUps();
                break;
            case 3:
               this.getShopExchanges();
                break;
            case 4:
                this.getShopPayments();
                break;
            case 5:
                this.getShopTransactions();
                break;
            case 6:
                this.getShopOrders('cancel');
                break;
            default:
                this.getShopUsers();
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