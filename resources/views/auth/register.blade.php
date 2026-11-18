{{-- Ginagamit ang guest layout. --}}
<x-guest-layout>
    <h4 class="text-center mb-3">Register</h4>

    {{-- POST papunta sa register route ng Breeze. --}}
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- PANGALAN --}}
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- CONFIRM PASSWORD: dapat kapareho ng password sa itaas. --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="form-control @error('password_confirmation') is-invalid @enderror">
            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Link pabalik sa login at Register button. --}}
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="small">Already registered?</a>
            <button type="submit" class="btn btn-theme">Register</button>
        </div>
    </form>
</x-guest-layout>