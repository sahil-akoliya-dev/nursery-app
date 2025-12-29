<x-mail::message>
    # New Vendor Application

    A new vendor has applied for an account.

    **Applicant:** {{ $vendor->user->name }} ({{ $vendor->user->email }})
    **Store Name:** {{ $vendor->store_name }}
    **Date:** {{ $vendor->created_at->format('M d, Y H:i') }}

    <x-mail::button :url="url('/admin-dashboard.html#vendors')">
        Review Application
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>