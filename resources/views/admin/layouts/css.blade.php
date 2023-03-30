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
        font-size: 1rem;
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
        border-left: 3px solid orange;
    }

    .page-sidebar ul li a:hover {
        background-color: #282D36 !important;
    }
</style>