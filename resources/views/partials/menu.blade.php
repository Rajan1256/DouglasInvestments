<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full m-3 mb-4" href="#">
            <!-- {{ trans('panel.site_title') }} -->
            <img src="{{ asset('images/logo.png') }}" alt="{{ trans('panel.site_title') }}" class="img-fluid" />
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
        <li
            class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.userManagement.title') }}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('permission_access')
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.permissions.index") }}"
                        class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.permission.title') }}
                    </a>
                </li>
                @endcan
                @can('role_access')
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.roles.index") }}"
                        class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                        </i>
                        {{ trans('cruds.role.title') }}
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan


        @can('user_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.users.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/users") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                </i>
                Clients
            </a>
        </li>
        @endcan

        @can('manager_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.managers.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/managers") || request()->is("admin/managers/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                </i>
                Portfolio Manager
            </a>
        </li>
        @endcan

        @can('admin_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.admins.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/admins") || request()->is("admin/admins/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                </i>
                Admin
            </a>
        </li>
        @endcan

        @can('test_mail_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.users.testmail") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/testmail") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                </i>
                Test Mail
            </a>
        </li>
        @endcan

        @can('test_mail_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.users.sendmailtoall") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/sendmailtoall") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                </i>
                Send Mail To Customer
            </a>
        </li>
        @endcan


        @can('not_sync_yet')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.users.not_sync_yet") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/not-sync-yet") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                </i>
                User Note Sync Yet
            </a>
        </li>
        @endcan


        @can('invest_company_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.invest-companies.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/invest-companies") || request()->is("admin/invest-companies/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                </i>
                {{ trans('cruds.investCompany.title') }}
            </a>
        </li>
        @endcan

        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
        @can('profile_password_edit')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}"
                href="{{ route('profile.password.edit') }}">
                <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                </i>
                {{ trans('global.change_password') }}
            </a>
        </li>
        @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link"
                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
