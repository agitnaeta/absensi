{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" />
<x-backpack::menu-dropdown title="Absen" icon="la la-calendar">
    <x-backpack::menu-dropdown-item title="Scan" icon="la la-calendar" :link="route('presence.scan')" />
    <x-backpack::menu-dropdown-item title="Jadwal" icon="la la-calendar" :link="backpack_url('schedule')" />
    <x-backpack::menu-dropdown-item title="Setting Jadwal" icon="la la-calendar" :link="route('schedule.view.update')" />
    <x-backpack::menu-dropdown-item title="Kehadiran" icon="la la-calendar-check" :link="backpack_url('presence')" />
    <x-backpack::menu-dropdown-item title="Libur Nasional" icon="la la-sun" :link="backpack_url('national-holiday')" />
{{--    <x-backpack::menu-dropdown-item title="Hari Libur" icon="la la-moon" :link="backpack_url('schedule-day-off')" />--}}
{{--    <x-backpack::menu-dropdown-item title="Daftar Hari" icon="la la-sun" :link="backpack_url('day')" />--}}

</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Kasbon" icon="la la-money-bill">
    <x-backpack::menu-dropdown-item title="Rekap" icon="la la-money-bill" :link="route('loan.recap')" />
    <x-backpack::menu-dropdown-item title="Kasbon" icon="la la-money-bill" :link="backpack_url('loan')" />
    <x-backpack::menu-dropdown-item title="Pembayaran Kasbon" icon="la la-money-bill-alt" :link="backpack_url('loan-payment')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Gajian" icon="la la-money-check-alt">
    <x-backpack::menu-dropdown-item title="Gaji" icon="la la-money-check" :link="backpack_url('salary')" />
    <x-backpack::menu-dropdown-item title="Rekap Gaji" icon="la la-money-check" :link="backpack_url('salary-recap')" />
</x-backpack::menu-dropdown>


<x-backpack::menu-item title="Profile Perusahaan" icon="la la-building" :link="backpack_url('company-profile')" />

<x-backpack::menu-item title="Konfigurasi Akuntansi" icon="la la-cogs" :link="backpack_url('acc')" />
