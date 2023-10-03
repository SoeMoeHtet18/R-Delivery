<template>
    <div class="px-9 pt-2">
        <!-- dashboard -->
        <h1 class="page-title">Dashboard</h1>
        <!-- add line -->
        <hr class="mt-4 mb-6 border-main">
        <!-- ui start -->
        <div class="flex flex-wrap">
            <div class="flex-0 pr-2">
                <!-- user container -->
                <div class="bg-white border rounded-lg shadow-lg mb-6">
                    <div class="flex items-center px-4 py-3">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="#116A5B" />
                            <path
                                d="M7.25 11.4286C8.07734 11.4286 8.75 10.4676 8.75 9.28571C8.75 8.10379 8.07734 7.14286 7.25 7.14286C6.42266 7.14286 5.75 8.10379 5.75 9.28571C5.75 10.4676 6.42266 11.4286 7.25 11.4286ZM17.75 11.4286C18.5773 11.4286 19.25 10.4676 19.25 9.28571C19.25 8.10379 18.5773 7.14286 17.75 7.14286C16.9227 7.14286 16.25 8.10379 16.25 9.28571C16.25 10.4676 16.9227 11.4286 17.75 11.4286ZM18.5 12.5H17C16.5875 12.5 16.2148 12.7377 15.943 13.1228C16.8875 13.8627 17.5578 15.1987 17.7031 16.7857H19.25C19.6648 16.7857 20 16.3069 20 15.7143V14.6429C20 13.4609 19.3273 12.5 18.5 12.5ZM12.5 12.5C13.9508 12.5 15.125 10.8225 15.125 8.75C15.125 6.67746 13.9508 5 12.5 5C11.0492 5 9.875 6.67746 9.875 8.75C9.875 10.8225 11.0492 12.5 12.5 12.5ZM14.3 13.5714H14.1055C13.618 13.9062 13.0766 14.1071 12.5 14.1071C11.9234 14.1071 11.3844 13.9062 10.8945 13.5714H10.7C9.20937 13.5714 8 15.2991 8 17.4286V18.3929C8 19.2801 8.50391 20 9.125 20H15.875C16.4961 20 17 19.2801 17 18.3929V17.4286C17 15.2991 15.7906 13.5714 14.3 13.5714ZM9.05703 13.1228C8.78516 12.7377 8.4125 12.5 8 12.5H6.5C5.67266 12.5 5 13.4609 5 14.6429V15.7143C5 16.3069 5.33516 16.7857 5.75 16.7857H7.29453C7.44219 15.1987 8.1125 13.8627 9.05703 13.1228Z"
                                fill="white" />
                        </svg>
                        <h2 class="ml-2 text-lg font-semibold box-label">Users</h2>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-4 pb-4">
                        <!-- user box -->
                        <a href="/users" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">USERS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ user_count }}</h5>
                            </div>
                        </a>
                        <!-- shop user box -->
                        <a href="/shopusers" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">SHOP USERS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ shop_user_count }}</h5>
                            </div>
                        </a>
                        <!-- rider box -->
                        <a href="/riders" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">RIDERS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ rider_count }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- payment container -->
                <div class="bg-white border rounded-lg shadow-lg my-6">
                    <div class="flex items-center px-4 py-3">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="#116A5B" />
                            <path
                                d="M5 8.06818C5 7.25445 5.25399 6.47405 5.70608 5.89865C6.15818 5.32325 6.77135 5 7.41071 5H17.5893C18.2286 5 18.8418 5.32325 19.2939 5.89865C19.746 6.47405 20 7.25445 20 8.06818V16.9318C20 17.7456 19.746 18.526 19.2939 19.1013C18.8418 19.6767 18.2286 20 17.5893 20H7.41071C6.77135 20 6.15818 19.6767 5.70608 19.1013C5.25399 18.526 5 17.7456 5 16.9318V8.06818ZM7.41071 6.36364C7.05551 6.36364 6.71486 6.54322 6.4637 6.86289C6.21253 7.18255 6.07143 7.61611 6.07143 8.06818V9.09091H18.9286V8.06818C18.9286 7.61611 18.7875 7.18255 18.5363 6.86289C18.2851 6.54322 17.9445 6.36364 17.5893 6.36364H7.41071ZM6.07143 16.9318C6.07143 17.3839 6.21253 17.8174 6.4637 18.1371C6.71486 18.4568 7.05551 18.6364 7.41071 18.6364H17.5893C17.9445 18.6364 18.2851 18.4568 18.5363 18.1371C18.7875 17.8174 18.9286 17.3839 18.9286 16.9318V10.4545H6.07143V16.9318ZM15.1786 14.5455H16.7857C16.9278 14.5455 17.0641 14.6173 17.1645 14.7452C17.265 14.873 17.3214 15.0464 17.3214 15.2273C17.3214 15.4081 17.265 15.5815 17.1645 15.7094C17.0641 15.8373 16.9278 15.9091 16.7857 15.9091H15.1786C15.0365 15.9091 14.9002 15.8373 14.7998 15.7094C14.6993 15.5815 14.6429 15.4081 14.6429 15.2273C14.6429 15.0464 14.6993 14.873 14.7998 14.7452C14.9002 14.6173 15.0365 14.5455 15.1786 14.5455Z"
                                fill="white" />
                        </svg>
                        <h4 class="ml-2 text-lg font-semibold box-label">Payments</h4>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-4 pb-4">
                        <!-- payment from company -->
                        <a href="/transactions-for-shop" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-0.5 label">SHOP PAYMENT <br> FROM COMPANY</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ shop_payment_from_company_count }}</h5>
                            </div>
                        </a>
                        <!-- payment from shop -->
                        <a href="/shoppayments" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-0.5 label">SHOP PAYMENT <br> FROM SHOP</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ shop_payment_from_shop_count }}</h5>
                            </div>
                        </a>
                        <!-- rider payment -->
                        <a href="/rider-payments" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-13px label">RIDER PAYMENTS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ rider_payment_count }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- order container -->
                <div class="my-6 flex flex-wrap">
                    <div class="bg-white border rounded-lg shadow-lg w-35/10 p-4 mr-10">
                        <div class="flex items-center pb-3">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="icon">
                                    <circle id="Ellipse 1" cx="12.5" cy="12.5" r="12.5" fill="#116A5B"/>
                                    <path id="Vector" d="M14.1667 16.6667H8.33333V15H14.1667M16.6667 13.3333H8.33333V11.6667H16.6667M16.6667 10H8.33333V8.33333H16.6667M18.3333 5H6.66667C5.74167 5 5 5.74167 5 6.66667V18.3333C5 18.7754 5.17559 19.1993 5.48816 19.5118C5.80072 19.8244 6.22464 20 6.66667 20H18.3333C18.7754 20 19.1993 19.8244 19.5118 19.5118C19.8244 19.1993 20 18.7754 20 18.3333V6.66667C20 6.22464 19.8244 5.80072 19.5118 5.48816C19.1993 5.17559 18.7754 5 18.3333 5Z" fill="white"/>
                                </g>
                            </svg>
                            <h4 class="ml-2 text-lg font-semibold box-label">Orders</h4>
                        </div>
                        <!-- order box -->
                        <a href="/orders" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">ORDERS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ order_count }}</h5>
                            </div>
                        </a>
                    </div>
                    <!-- shop container -->
                    <div class="bg-white border rounded-lg shadow-lg w-35/10 p-4">
                        <div class="flex items-center pb-3">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="icon">
                                    <circle id="Ellipse 1" cx="12.5" cy="12.5" r="12.5" fill="#116A5B"/>
                                    <path id="Vector" d="M19.5 18.7999H18.5V11.8656C18.5 11.6995 18.4713 11.5352 18.4157 11.3831C18.3601 11.231 18.2788 11.0944 18.1769 10.982L13.1769 5.3205C13.1744 5.31794 13.1721 5.31519 13.17 5.31225C12.9859 5.11133 12.746 5 12.4972 5C12.2484 5 12.0085 5.11133 11.8244 5.31225L11.8175 5.3205L6.82312 10.982C6.72125 11.0944 6.63993 11.231 6.58431 11.3831C6.52869 11.5352 6.49998 11.6995 6.5 11.8656V18.7999H5.5C5.36739 18.7999 5.24021 18.8631 5.14645 18.9756C5.05268 19.0882 5 19.2408 5 19.3999C5 19.5591 5.05268 19.7117 5.14645 19.8242C5.24021 19.9368 5.36739 20 5.5 20H19.5C19.6326 20 19.7598 19.9368 19.8536 19.8242C19.9473 19.7117 20 19.5591 20 19.3999C20 19.2408 19.9473 19.0882 19.8536 18.9756C19.7598 18.8631 19.6326 18.7999 19.5 18.7999ZM7.5 11.8656L7.50688 11.8581L12.5 6.19883L17.4937 11.8566L17.5006 11.8641V18.7999H14.5V15.1996C14.5 14.8813 14.3946 14.5761 14.2071 14.351C14.0196 14.1259 13.7652 13.9995 13.5 13.9995H11.5C11.2348 13.9995 10.9804 14.1259 10.7929 14.351C10.6054 14.5761 10.5 14.8813 10.5 15.1996V18.7999H7.5V11.8656ZM13.5 18.7999H11.5V15.1996H13.5V18.7999Z" fill="white"/>
                                </g>
                            </svg>
                            <h4 class="ml-2 text-lg font-semibold box-label">Shops</h4>
                        </div>
                        <!-- shop box -->
                        <a href="/shops" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">SHOPS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ shop_count }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- collection container -->
            <div class="flex-0 pl-2">
                <div class="bg-white border rounded-lg shadow-lg mb-6">
                    <div class="flex items-center px-4 py-3">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="#116A5B" />
                            <path
                                d="M11.7991 9.50267L11.9371 9.49892H17.5624C18.1849 9.49887 18.7838 9.737 19.2364 10.1645C19.6889 10.5919 19.9608 11.1764 19.9963 11.7979L20 11.9367V17.5623C20 18.1849 19.7618 18.784 19.3342 19.2366C18.9066 19.6892 18.322 19.961 17.7004 19.9962L17.5624 20H11.9371C11.3144 20 10.7154 19.7618 10.2628 19.3342C9.81024 18.9065 9.53845 18.3219 9.5032 17.7003L9.49945 17.563V11.9374C9.49941 11.3148 9.73762 10.7157 10.1652 10.2631C10.5928 9.81048 11.1774 9.53867 11.7991 9.50342V9.50267ZM17.5624 10.624H11.9371C11.6077 10.624 11.2903 10.7479 11.048 10.9711C10.8057 11.1942 10.6561 11.5004 10.629 11.8287L10.6245 11.9367V17.5623C10.6245 17.8918 10.7485 18.2093 10.9718 18.4516C11.1951 18.694 11.5014 18.8435 11.8298 18.8704L11.9371 18.8749H17.5624C17.8919 18.8749 18.2094 18.7509 18.4517 18.5276C18.694 18.3043 18.8435 17.998 18.8704 17.6695L18.8749 17.5623V11.9367C18.8749 11.5885 18.7367 11.2547 18.4905 11.0085C18.2443 10.7623 17.9105 10.624 17.5624 10.624ZM14.7497 11.7499C14.8989 11.7499 15.042 11.8092 15.1475 11.9147C15.253 12.0202 15.3123 12.1633 15.3123 12.3125V14.1862H17.1874C17.3365 14.1862 17.4796 14.2454 17.5851 14.3509C17.6906 14.4564 17.7499 14.5995 17.7499 14.7487C17.7499 14.8979 17.6906 15.041 17.5851 15.1465C17.4796 15.252 17.3365 15.3113 17.1874 15.3113H15.3123V17.188C15.3123 17.3372 15.253 17.4803 15.1475 17.5857C15.042 17.6912 14.8989 17.7505 14.7497 17.7505C14.6005 17.7505 14.4574 17.6912 14.352 17.5857C14.2465 17.4803 14.1872 17.3372 14.1872 17.188V15.3113H12.3121C12.1629 15.3113 12.0198 15.252 11.9143 15.1465C11.8088 15.041 11.7496 14.8979 11.7496 14.7487C11.7496 14.5995 11.8088 14.4564 11.9143 14.3509C12.0198 14.2454 12.1629 14.1862 12.3121 14.1862H14.1872V12.3125C14.1872 12.1633 14.2465 12.0202 14.352 11.9147C14.4574 11.8092 14.6005 11.7499 14.7497 11.7499ZM15.1862 6.67413L15.2252 6.80689L15.745 8.74809H14.5802L14.1392 7.09792C14.0946 6.93133 14.0177 6.77514 13.9127 6.6383C13.8078 6.50145 13.6769 6.38663 13.5276 6.3004C13.3782 6.21416 13.2133 6.1582 13.0424 6.13571C12.8714 6.11322 12.6977 6.12464 12.5311 6.16933L7.09782 7.62598C6.78065 7.71104 6.50688 7.91189 6.33054 8.18892C6.15419 8.46594 6.08809 8.79901 6.14527 9.12238L6.16927 9.23339L7.62585 14.667C7.69417 14.9223 7.83797 15.151 8.03845 15.3233C8.23892 15.4955 8.48671 15.6032 8.74941 15.6323V16.7619C8.26175 16.7329 7.79405 16.558 7.40691 16.26C7.01976 15.9621 6.731 15.5547 6.57804 15.0907L6.53904 14.9587L5.08321 9.52442C4.92196 8.92314 4.99684 8.28295 5.29249 7.73511C5.58815 7.18728 6.08219 6.77332 6.6733 6.57812L6.8068 6.53912L12.2401 5.08322C12.8413 4.92195 13.4815 4.99684 14.0293 5.29251C14.5771 5.58818 14.9911 6.08299 15.1862 6.67413Z"
                                fill="white" />
                        </svg>
                        <h4 class="ml-2 text-lg font-semibold box-label">Collections</h4>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-4 pb-4">
                        <!-- pick up box -->
                        <a href="/collections" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">PICKUP</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ pick_up_count }}</h5>
                            </div>
                        </a>
                        <!-- exchange box -->
                        <a href="/customer-collections" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">EXCHANGES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ exchange_count }}</h5>
                            </div>
                        </a>
                        <!-- pick up group box -->
                        <a href="/collection-groups" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">PICKUP GROUPS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ pick_up_group_count }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- admin tool container -->
                <div class="bg-white border rounded-lg shadow-lg my-6">
                    <div class="flex items-center px-4 py-3">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="icon">
                                <circle id="Ellipse 1" cx="12.5" cy="12.5" r="12.5" fill="#116A5B"/>
                                <path id="Vector" d="M7.5 20C6.80556 20 6.21528 19.7812 5.72917 19.3438C5.24306 18.9062 5 18.375 5 17.75V15.5H7.5V5H20V17.75C20 18.375 19.7569 18.9062 19.2708 19.3438C18.7847 19.7812 18.1944 20 17.5 20H7.5ZM17.5 18.5C17.7361 18.5 17.9342 18.428 18.0942 18.284C18.2542 18.14 18.3339 17.962 18.3333 17.75V6.5H9.16667V15.5H16.6667V17.75C16.6667 17.9625 16.7467 18.1407 16.9067 18.2847C17.0667 18.4287 17.2644 18.5005 17.5 18.5ZM10 10.25V8.75H17.5V10.25H10ZM10 12.5V11H17.5V12.5H10ZM7.5 18.5H15V17H6.66667V17.75C6.66667 17.9625 6.74667 18.1407 6.90667 18.2847C7.06667 18.4287 7.26444 18.5005 7.5 18.5ZM7.5 18.5H6.66667H15H7.5Z" fill="white"/>
                            </g>
                        </svg>
                        <h4 class="ml-2 text-lg font-semibold box-label">ADMIN TOOLS</h4>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-4 pb-4">
                        <!-- city box -->
                        <a href="/cities" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">CITIES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ city_count }}</h5>
                            </div>
                        </a>
                        <!-- township box -->
                        <a href="/townships" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">TOWNSHIPS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ township_count }}</h5>
                            </div>
                        </a>
                        <!-- branch box -->
                        <a href="/branches" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">BRANCHES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ branch_count }}</h5>
                            </div>
                        </a>
                        <!-- gate box -->
                        <a href="/gates" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">GATES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ gate_count }}</h5>
                            </div>
                        </a>
                        <!-- third party vendor box -->
                        <a href="/third-party-vendor" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-px label">THIRD PARTY VENDOR </h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ third_party_vendor_count }}</h5>
                            </div>
                        </a>
                        <!-- item type box -->
                        <a href="/itemtypes" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">ITEM TYPES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ item_type_count }}</h5>
                            </div>
                        </a>
                        <!-- delivery type box -->
                        <a href="/delivery-types" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">DELIVERY TYPES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ delivery_type_count }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user_count', 'shop_user_count', 'rider_count', 'pick_up_count', 'exchange_count', 'pick_up_group_count',
        'shop_payment_from_company_count', 'shop_payment_from_shop_count', 'rider_payment_count', 'city_count',
        'township_count', 'branch_count', 'gate_count', 'third_party_vendor_count', 'item_type_count', 'delivery_type_count',
        'order_count', 'shop_count'
    ]
}
</script>
<style scoped>
.page-title {
    font-size: 34px;
    font-style: normal;
    font-weight: 800;
    line-height: normal;
    text-transform: uppercase;
    color: #000000;
}

.box-label {
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    color: #000000;
}

.link-text {
    color: black;
}

.label {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 22px; /* 137.5% */
    text-transform: uppercase;
}
.label-count {
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}
</style>