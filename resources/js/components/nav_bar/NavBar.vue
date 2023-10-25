<template>
    <aside class="w-1/6 bg-side-bar h-screen fixed pt-7.5 overflow-auto">
        <!-- main container -->
        <div class="menu-container">
            <h4 class="text-grey ml-8 main-menu">Main Menu</h4>
            <ul class="mt-5">
                <a href="/dashboard" class="flex items-center pl-7 h-11.25" :class="{ 'active': isActive('dashboard') }">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="ri:dashboard-line" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Dashboard</h5>
                    </li>
                </a>
                <a href="/shops" class="flex items-center pl-7 h-11.25" :class="{ 'active': isActive('shops')}">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="ph:house-line" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Shop</h5>
                    </li>
                </a>
                <a href="/shopusers" class="flex items-center pl-7 h-11.25" :class="{ 'active': isActive('shopusers')}">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="cil:people" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Shop User</h5>
                    </li>
                </a>
                <a href="/riders" class="flex items-center pl-7 h-11.25">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="ph:car" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Rider</h5>
                    </li>
                </a>
                <!-- collection dropdown -->
                <a href="#" class="flex items-center pl-7 h-11.25 relative" @click="toggleCollection">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="fluent:collections-20-regular" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Collections</h5>
                        <iconify-icon icon="ic:baseline-keyboard-arrow-down" width="20" class="absolute right-4"
                            :class="{ 'rotate-180': isCollectionOpen }"></iconify-icon>
                    </li>
                </a>
                <!-- dropdown items of collections -->
                <ul v-if="isCollectionOpen" class="dropdown-menu">
                    <a href="/collection-groups" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Pickup Group</h5>
                        </li>
                    </a>
                    <a href="/collections" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Pickup</h5>
                        </li>
                    </a>
                    <a href="/customer-collections" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Exchanges</h5>
                        </li>
                    </a>
                </ul>
                <a href="/orders" class="flex items-center pl-7 h-11.25">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="solar:document-text-bold" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Order</h5>
                    </li>
                </a>
                <!-- payment dropdown -->
                <a href="#" class="flex items-center pl-7 h-11.25 relative" @click="togglePayment">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="fluent:payment-48-regular" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Payments</h5>
                        <iconify-icon icon="ic:baseline-keyboard-arrow-down" width="20" class="absolute right-4"
                            :class="{ 'rotate-180': isPaymentOpen }"></iconify-icon>
                    </li>
                </a>
                <!-- dropdown items of payments -->
                <ul v-if="isPaymentOpen" class="dropdown-menu">
                    <a href="/rider-payments" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Rider</h5>
                        </li>
                    </a>
                    <a href="/transactions-for-shop" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">From Company</h5>
                        </li>
                    </a>
                    <a href="/shoppayments" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">From Shop</h5>
                        </li>
                    </a>
                </ul>
                <a href="/branches" class="flex items-center pl-7 h-11.25">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="fluent:branch-compare-24-regular" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Branch</h5>
                    </li>
                </a>
                <!-- admin tool dropdown -->
                <a href="#" class="flex items-center pl-7 h-11.25 relative" @click="toggleAdminTool">
                    <li class="flex items-center text-grey">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.33333 20C2.40741 20 1.62037 19.7083 0.972222 19.125C0.324074 18.5417 0 17.8333 0 17V14H3.33333V0H20V17C20 17.8333 19.6759 18.5417 19.0278 19.125C18.3796 19.7083 17.5926 20 16.6667 20H3.33333ZM16.6667 18C16.9815 18 17.2456 17.904 17.4589 17.712C17.6722 17.52 17.7785 17.2827 17.7778 17V2H5.55556V14H15.5556V17C15.5556 17.2833 15.6622 17.521 15.8756 17.713C16.0889 17.905 16.3526 18.0007 16.6667 18ZM6.66667 7V5H16.6667V7H6.66667ZM6.66667 10V8H16.6667V10H6.66667ZM3.33333 18H13.3333V16H2.22222V17C2.22222 17.2833 2.32889 17.521 2.54222 17.713C2.75556 17.905 3.01926 18.0007 3.33333 18ZM3.33333 18H2.22222H13.3333H3.33333Z"
                                fill="#AAAAAA" />
                        </svg>
                        <h5 class="menu-label ml-4">Admin Tools</h5>
                        <iconify-icon icon="ic:baseline-keyboard-arrow-down" width="20" class="absolute right-4"
                            :class="{ 'rotate-180': isAdminToolOpen }"></iconify-icon>
                    </li>
                </a>
                <!-- dropdown items of admin tools -->
                <ul v-if="isAdminToolOpen" class="dropdown-menu">
                    <a href="/users" class="flex items-center pl-14 h-11.25" :class="{ 'sub-active': isActive('users')}">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">User</h5>
                        </li>
                    </a>
                    <a href="/cities" class="flex items-center pl-14 h-11.25" :class="{ 'sub-active': isActive('cities')}">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">City</h5>
                        </li>
                    </a>
                    <a href="/gates" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Gate</h5>
                        </li>
                    </a>
                    <a href="/townships" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Township</h5>
                        </li>
                    </a>
                    <a href="/itemtypes" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Item Type</h5>
                        </li>
                    </a>
                    <a href="/delivery-types" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Delivery Type</h5>
                        </li>
                    </a>
                    <a href="/third-party-vendor" class="flex items-center pl-14 h-11.25">
                        <li class="flex items-center text-grey relative">
                            <h5 class="menu-label ml-4">Third Party Vendor </h5>
                        </li>
                    </a>
                </ul>
            </ul>
        </div>
        <!-- setting container -->
        <div class="setting-container pt-4 w-full"
            :class="{ 'absolute bottom-px': isPositionAbsolute }">
            <h4 class="text-grey ml-8 main-menu">Setting</h4>
            <ul class="dropdown-menu">
                <a href="/dashboard" class="flex items-center pl-7 h-11.25">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="uil:setting" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Setting</h5>
                    </li>
                </a>
                <a href="/dashboard" class="flex items-center pl-7 h-11.25">
                    <li class="flex items-center text-grey">
                        <iconify-icon icon="material-symbols:help" width="20"></iconify-icon>
                        <h5 class="menu-label ml-4">Help</h5>
                    </li>
                </a>
            </ul>
        </div>
    </aside>
</template>
<script>
export default {
    data() {
        return {
            isCollectionOpen: false,
            isCollectionTrigger: false,
            isPaymentOpen: false,
            isAdminToolOpen: false,
            isSettingOverflow: false,
        };
    },
    methods: {
        isActive(routeName) {
            // Get the current window location
            const currentLocation = window.location.pathname;
            // Check if the current location matches the provided route
            return currentLocation.includes(routeName);
        },
        toggleCollection() {
            this.isCollectionOpen = !this.isCollectionOpen;
        },
        togglePayment() {
            this.isPaymentOpen = !this.isPaymentOpen;
        },
        toggleAdminTool() {
            this.isAdminToolOpen = !this.isAdminToolOpen;
        },
    },
    computed: {
        isPositionAbsolute() {
            return this.isCollectionOpen || this.isPaymentOpen || this.isAdminToolOpen ? false : true;
        },
    },
};
</script>
<style scoped>
aside::-webkit-scrollbar {
    display: none;
}

.main-menu {
    font-size: 10px !important;
}

.menu-label {
    font-size: 14px !important;
}

.sub-active,
.active {
    color: #ffffff;
    background-color: #07384D;
    border-left: 6px solid #116A5B;
}

.active {
    padding-left: 22px !important;
}
.sub-active {
    padding-left: 50px !important;
}

.active>* {
    color: #ffffff;
}

.rotate-180 {
    transform: rotate(180deg);
    transition: transform 0.5s ease;
}

.dropdown-menu>a {
    border-bottom: 1px solid #07384D;
}
</style>