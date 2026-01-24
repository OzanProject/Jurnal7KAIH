<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input type="password" name="current_password" id="update_password_current_password" class="form-control" autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-danger" />
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input type="password" name="password" id="update_password_password" class="form-control" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-danger" />
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-danger" />
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success py-1 px-3 mb-0" role="alert">
                    {{ __('Saved.') }}
                </div>
            @endif
        </div>
    </form>
</section>
