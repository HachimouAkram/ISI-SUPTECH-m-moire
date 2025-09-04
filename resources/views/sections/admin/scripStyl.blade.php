<style>
    :root {
        --primary-color: red;
        --brand-color: #1d3c78;
        --brand-sub-color: goldenrod;
        --active-link-color: var(--primary-color);
    }

    [data-theme="blue"] {
        --primary-color: #4da6ff;
        --brand-color: #004aad;
        --brand-sub-color: #f0c94d;
        --active-link-color: var(--primary-color);
    }

    /* Application automatique */
    .text-primary { color: var(--primary-color) !important; }
    .btn-primary { background: var(--primary-color) !important; border-color: var(--primary-color) !important; }
    .bg-primary { background: var(--primary-color) !important; }

    /* Sidebar links */
    .navbar-nav .nav-link.active {
        color: var(--active-link-color) !important;
        font-weight: bold;
    }

    /* Dropdown active */
    .dropdown-item.active,
    .dropdown-item:active {
        color: var(--active-link-color) !important;
        background: transparent !important;
        font-weight: bold;
    }
</style>
