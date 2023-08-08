<style>
    .page-header-fixed .navbar-fixed-top {
        position: fixed;
    }
    .navbar-header {
        margin: 0 10px;
    }
    .navbar-header .navbar-brand {
        color: #BBC5BB;
    }
    .card-container{
        box-shadow: 0 1px 15px 0 rgba(69, 65, 78, 0.21) !important;
    }
    .card-header-title{
        margin-bottom: 30px;
    }
    .page-sidebar-wrapper .page-sidebar {
        display: block;
        
    }
    .page-sidebar-menu li .title{
        font-size: 16px;
    }
    .create-button { 
        height: 30px;
        margin-bottom: 10px;
        margin-right: 10px;
    }
    .current-user {
        display: inline-block;
        font-size: 16px;
        color: #C5C5C5;
    }
    .datatable th{
        white-space: nowrap;;
    }
    .datatable td {
        white-space: nowrap
    }

    .action-form-card .card-header-title {
        font-size: 26px;
    }
    .action-form-card .action-form h4{
        font-size: 14px;
        margin-top: 0.6em;
    }
    .detail-card .card-header-title {
        font-size: 26px;
        margin-bottom: 0;
    }

    .detail-card .detail-infos h4 {
        font-size: 16px;
        padding-top: 3px;
    }
    .customize-collapse {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .customize-collapse li {
        display: block;
        margin: 0;
        padding: 0;
        border-top: 1px solid #484848;
    }
    .customize-collapse li.active :hover {
        background-color: #282D36 !important;
    }
    .customize-collapse a {
        text-decoration: none;
        color: #d9d9d9;
        display: block;
        position: relative;
        margin: 0;
        border: 0px;
        padding: 10px 15px;
        text-decoration: none;
        font-weight: 300;
    }

    .customize-collapse li a .title {
        font-size: 14px;
        margin-left: 15px;
    }

    .arrow {
        display: inline-block;
        width: 0;
        height: 0;
        position: absolute;
        right: 30px;
        bottom: 46%;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        border-color: #fff transparent transparent transparent;
        transition-duration: 0.5s;
    }
    .arrow.up {
        transform: rotate(180deg);
        transition-duration: 0.5s;
    }
    .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active.open > a, .page-sidebar .page-sidebar-menu > ul li.active > a, .page-sidebar .page-sidebar-menu > li.active.open > a {
        background: #8c8c85;
        border-top-color: transparent;
        color: #ffffff;
        border-left: 3px solid #6ee0c9;
    }

    .page-sidebar ul li a:hover {
        background-color: #282D36 !important;
    }
    .filter-box{
        display: flex;
    }
    /* Select2 Container */
    .select2-container .select2-selection--single
    {
        height: 37px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b
    {
        margin-top: 0px;
        top: 15px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered
    {
        margin-top: -1px;
        font-size: 15px;
    }

    .select2-container--default .select2-search--inline .select2-search__field
    {
        height: 100%;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display
    {
        display: block;
        height: 1.7em;
    }
    /* Select2 Container End */

    /* Mulit-Select  DropDown */
    .select2-container--default .select2-selection--multiple .select2-selection__choice
    {
        background-color: #64c5b1;
        margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple 
    {
        height: 37px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        top: 1.5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: #64c5b1;
        top: 1.5px;
    }
    /* Mulit-Select DropDown End */

    /* Input box  */
    .form-control
    {
        height: 37px;
        font-size: 15px;
    }
    /* Input box  End */
    /* Sub-Titel & Page Content */
    .page-content {
        position: relative;
        background: #f3f4f3;
        margin: 0;
        z-index: 22;
    }
    .page-content::before{
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        height: 160px;
        z-index: -1;
        background: #64c5b1;
        content: '';
        border-radius: 0;
        left: 0;
    }
    .f_s_30 {
        font-size: 30px;
    }
    .f_w_700 {
        font-weight: 700;
    }
    .page-title-box {
        margin-bottom: 25px;
    }
    .page-sub-title-box {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .page-sub-title-box .page-sub-title {
        font-size: 14px;
        font-weight: 500;
        color: #ffffff;
    }
    .page-sub-title-box .page-sub-title::before {
        content: ">";
        float: left;
        padding: 0 0.5rem;
    }
    .page-sub-title-box .page-sub-title:first-child::before {
        content: none;
    }
    .content-card {
        border-radius: 10px !important;
    }
    .topbar-brand {
        padding-left: 8px;
    }
    /* Sub-Titel & Page Content End */
    .card{
        margin: 1rem 0rem !important;
    }

    /* Buttons */
    .create-btn {
    --bs-btn-color: #fff;
    --bs-btn-bg: #64c5b1;
    --bs-btn-border-color: #64c5b1;
    --bs-btn-hover-color: #64c5b1;
    --bs-btn-hover-bg: ghostwhite;
    --bs-btn-hover-border-color: #64c5b1;
    --bs-btn-focus-shadow-rgb: 60,153,110;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #64c5b1;
    --bs-btn-active-border-color: #13653f;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #64c5b1;
    --bs-btn-disabled-border-color: #64c5b1;
    }

    .card-toolbar{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }

    .datatable {
        width: 100% !important;
    }

    .dropdown-menu li input {
        color: black;
    }

    .dropdown-menu li a {
        color: black;
    }
</style>