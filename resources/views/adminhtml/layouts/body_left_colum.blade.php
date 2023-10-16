<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href={{ asset('assets/adminhtml/index.html') }}>
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">UI Elements</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href={{ asset('assets/adminhtml/#ui-basic') }}
                aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">UI Elements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href={{ route('category_admin_list') }}>Categories</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href={{ route('admin_paper_list') }}>Papers</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href={{ route('admin_writer_list') }}>Writers</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href={{ url('adminhtml/file/manager') }}>file manager</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin_rule_list') }}">rules</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('admin_permission_list') }}">permisstion</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin_user_list') }}">admin user</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('admin_config_create') }}">configuration</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/ui-features/buttons.html') }}>Buttons</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/ui-features/dropdowns.html') }}>Dropdowns</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/ui-features/typography.html') }}>Typography</a>
                    </li>

                </ul>
            </div>
        </li>
        <li class="nav-item nav-category">Forms and Datas</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href={{ asset('assets/adminhtml/#form-elements') }}
                aria-expanded="false" aria-controls="form-elements">
                <i class="menu-icon mdi mdi-card-text-outline"></i>
                <span class="menu-title">Form elements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/forms/basic_elements.html') }}>Basic
                            Elements</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href={{ asset('assets/adminhtml/#charts') }}
                aria-expanded="false" aria-controls="charts">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">Charts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/charts/chartjs.html') }}>ChartJs</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href={{ asset('assets/adminhtml/#tables') }}
                aria-expanded="false" aria-controls="tables">
                <i class="menu-icon mdi mdi-table"></i>
                <span class="menu-title">Tables</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/tables/basic-table.html') }}>Basic table</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href={{ asset('assets/adminhtml/#icons') }}
                aria-expanded="false" aria-controls="icons">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Icons</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/icons/mdi.html') }}>Mdi icons</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item nav-category">pages</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href={{ asset('assets/adminhtml/#auth') }}
                aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href={{ asset('assets/adminhtml/pages/samples/login.html') }}> Login </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item nav-category">help</li>
        <li class="nav-item">
            <a class="nav-link"
                href={{ asset('assets/adminhtml/http://bootstrapdash.com/demo/star-admin2-free/docs/documentation.html') }}>
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Documentation</span>
            </a>
        </li>
    </ul>
</nav>
