@php($user = $user ?? null)

<label>
    Name
    <input type="text" name="name" value="{{ old('name', $user?->name) }}" required>
</label>

<label>
    Email
    <input type="email" name="email" value="{{ old('email', $user?->email) }}" required>
</label>

<label>
    Password
    <input type="password" name="password" {{ $user ? '' : 'required' }}>
    @if ($user)
        <small style="font-weight:400;color:#6b7280">Leave blank to keep the current password.</small>
    @endif
</label>

<label>
    Confirm password
    <input type="password" name="password_confirmation" {{ $user ? '' : 'required' }}>
</label>

@unless ($user)
    <label class="checkbox">
        <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin'))>
        Admin access
    </label>
@endunless
