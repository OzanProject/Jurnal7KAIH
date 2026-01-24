<x-backend-layout>
    <x-slot name="header">
        <h5 class="m-b-10">{{ __('Edit Profile') }}</h5>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            
            <div class="card stretch stretch-full border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">{{ __('Profile Information') }}</h5>
                        <p class="text-muted small">{{ __("Update your account's profile information and email address.") }}</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')

                    <hr class="my-4">

                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">{{ __('Update Password') }}</h5>
                        <p class="text-muted small">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                    </div>
                    @include('profile.partials.update-password-form')

                    <hr class="my-4">

                    <div class="mb-4">
                        <h5 class="fw-bold mb-1 text-danger">{{ __('Delete Account') }}</h5>
                        <p class="text-muted small">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-backend-layout>
