{{-- Ginagamit ang guest layout. --}}
<x-guest-layout>
    <h4 class="text-center mb-3">Reset Password</h4>

    {{-- POST papunta sa route na nagse-save ng bagong password. --}}
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Nakatagong token galing sa link sa email; pinatutunayan nito na ang user ang humiling ng reset. --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- EMAIL: nakalagay na galing sa link. --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- BAGONG PASSWORD --}}
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="form-control @error('password_confirmation') is-invalid @enderror">
            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-theme w-100">Reset Password</button>
    </form>
</x-guest-layout>