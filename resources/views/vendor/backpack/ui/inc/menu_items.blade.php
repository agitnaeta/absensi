{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" />
<x-backpack::menu-dropdown title="Absen" icon="la la-calendar">
    <x-backpack::menu-dropdown-item title="Schedules" icon="la la-calendar" :link="backpack_url('schedule')" />
    <x-backpack::menu-dropdown-item title="Presences" icon="la la-calendar-check" :link="backpack_url('presence')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Kasbon" icon="la la-money-bill">
    <x-backpack::menu-dropdown-item title="Loans" icon="la la-money-bill" :link="backpack_url('loan')" />
    <x-backpack::menu-dropdown-item title="Loan payments" icon="la la-money-bill-alt" :link="backpack_url('loan-payment')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Gajian" icon="la la-money-check-alt">
    <x-backpack::menu-dropdown-item title="Salaries" icon="la la-money-check" :link="backpack_url('salary')" />
    <x-backpack::menu-dropdown-item title="Salary recaps" icon="la la-money-check" :link="backpack_url('salary-recap')" />
</x-backpack::menu-dropdown>
