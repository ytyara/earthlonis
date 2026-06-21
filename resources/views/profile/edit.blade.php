<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0">{{ __('Profile') }}</h2>
    </x-slot>

    <div class="row g-4 mw-profile">
        <div class="col-12">
            <div class="card border p-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="col-12">
            <div class="card border p-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="col-12">
            <div class="card border p-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
