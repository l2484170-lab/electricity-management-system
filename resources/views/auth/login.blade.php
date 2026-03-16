<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة الكهرباء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { max-width: 420px; margin: auto; border-radius: 1rem; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">نظام إدارة الكهرباء</h3>
                    <p class="text-muted">سجّل الدخول إلى حسابك</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control form-control-lg" value="{{ old('username') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
