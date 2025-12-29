<x-mail::message>
    # Application Status Update

    Hi {{ $vendor->user->name }},

    We have reviewed your vendor application for **{{ $vendor->store_name }}**.

    Unfortunately, we are unable to approve your application at this time.

    @if($reason)
        **Reason:**
        {{ $reason }}
    @endif

    If you have any questions or believe this is an error, please contact our support team.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>