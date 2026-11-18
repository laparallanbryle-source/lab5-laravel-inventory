{{-- Ginagamit ang guest layout. --}}
<x-guest-layout>
    <h4 class="text-center mb-3">Forgot Password</h4>
    {{-- Paliwanag para sa user. --}}
    <p class="text-muted small">Enter your email address and we will send you a password reset link.</p>

    {{-- Mensahe kapag naipadala na ang reset link. --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- POST papunta sa route na nagpapadala ng reset link. --}}
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Button na buong lapad. --}}
        <button type="submit" class="btn btn-theme w-100">Email Password Reset Link</button>
    </form>
</x-guest-layout>