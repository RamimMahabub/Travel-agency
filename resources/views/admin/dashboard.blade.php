<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Internal Portal
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Welcome, {{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">Role: {{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Sales</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_sales'], 2) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Bookings</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['bookings']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Profit</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['profit'], 2) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Refunds</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['refunds'], 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h4 class="font-semibold text-gray-800">Operations</h4>
                    <ul class="mt-3 text-sm text-gray-600 space-y-2">
                        <li>Manage users/customers</li>
                        <li>Manage bookings and ticket issuance</li>
                        <li>Supplier/API monitoring</li>
                        <li>Coupon and markup management</li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h4 class="font-semibold text-gray-800">Finance</h4>
                    <ul class="mt-3 text-sm text-gray-600 space-y-2">
                        <li>Payment verification and failed payments</li>
                        <li>Refund approval and processing</li>
                        <li>Daily sales and profit reporting</li>
                        <li>Wallet/credit controls</li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h4 class="font-semibold text-gray-800">Security</h4>
                    <ul class="mt-3 text-sm text-gray-600 space-y-2">
                        <li>2FA/OTP for internal login</li>
                        <li>Role-based access and audit logs</li>
                        <li>Suspicious login monitoring</li>
                        <li>Device/session tracking</li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h4 class="font-semibold text-gray-800">Live Counters</h4>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-700">
                    <div>Pending Tickets: <strong>{{ number_format($stats['pending_tickets']) }}</strong></div>
                    <div>Failed Payments: <strong>{{ number_format($stats['failed_payments']) }}</strong></div>
                    <div>Customers: <strong>{{ number_format($stats['active_customers']) }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
