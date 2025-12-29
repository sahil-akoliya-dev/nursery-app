<x-mail::message>
    # Application Approved

    Hi {{ $vendor->user->name }},

    Congratulations! Your vendor application for **{{ $vendor->store_name }}** has been approved.

    You can now log in and access your vendor dashboard to start managing your products and orders.

    <x-mail::button :url="url('/login.html')">
        Login to Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>