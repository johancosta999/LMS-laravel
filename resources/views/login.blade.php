<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #eef4ff, #f8fafc);
        }

        .login-card {
            width: min(100% - 2rem, 330px);
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 1rem 2.5rem rgba(30, 58, 138, .12);
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">
    <main class="card login-card p-4">
        <div class="text-center mb-4">
            <h1 class="h4 mb-2">Student sign in</h1>
            <p class="text-secondary mb-0">Use your registered email and password.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.check') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>
    </main>
</body>

</html>
