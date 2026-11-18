{{-- Ginagamit ang guest layout (purple na background at card). --}}
<x-guest-layout>
    <h4 class="text-center mb-3">Log in</h4>

    {{-- Mensahe galing sa session (hal. pagkatapos mag-reset ng password). --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- POST papunta sa login route ng Breeze. --}}
    <form method="POST" action="{{ route('login') }}">
        {{-- CSRF token; kung wala ito, error 419. --}}
        @csrf

        {{-- EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            {{-- old('email') = ibinabalik ang tinype kapag nagkamali. is-invalid = pulang balangkas kapag may error. --}}
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror">
            {{-- Mensahe ng error sa ilalim ng field. --}}
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- REMEMBER ME: pananatilihin ang login kahit isara ang browser. --}}
        <div class="form-check mb-3">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label">Remember me</label>
        </div>

        {{-- Forgot password link sa kaliwa, Log in button sa kanan. --}}
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">Forgot your password?</a>
            @endif
            <button type="submit" class="btn btn-theme">Log in</button>
        </div>
    </form>

    {{-- Link papunta sa register page. --}}
    @if (Route::has('register'))
        <p class="text-center small mt-3 mb-0">No account yet? <a href="{{ route('register') }}">Register</a></p>
    @endif
</x-guest-layout>