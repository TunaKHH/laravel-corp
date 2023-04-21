@extends('auth.layouts.app')

@section('main')
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">登入以操作，若不會請呼叫鮪魚</p>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <h5><i class="icon fas fa-ban"></i> 帳號或密碼錯誤</h5>
                        請重新再試一次
                    </div>
                @endif
                登入liff
                <form class="form-signin">
                    <div class="text-center mb-4">
                        <a href="#"><img class="mb-4" src="/images/line/2x/32dp/btn_login_base.png"></a>
                    </div>
                </form>
                <!-- /.social-auth-links -->
            </div>
        </div>
    </div>
    <!-- /.login-box -->
@endsection
@section('script')
    <script>
        alert('1')
        liff.init({
                liffId: "1656273332-k1bVqanP" // Use own liffId
            })
            .then(() => {
                liff.getProfile()
                    .then(profile => {
                        const name = profile.displayName
                        const userId = profile.userId
                        alert(name)
                        alert(userId)

                    })
                    .catch((err) => {
                        console.log('error', err);
                    });

                // Start to use liff's api
            })
    </script>
@endsection
