@extends('auth.layouts.app')

@section('main')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>SKW</b>CORP</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">邀請碼若不知道請呼叫帥氣鮪魚</p>
                @error('error')
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </h5>
                    請重試
                </div>
                @enderror
                <form action="{{ route('register.enter') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label>邀請碼</label>
                        <input type="text" class="form-control" name="invitation_code" placeholder="請輸入邀請碼">
                    </div>
                    <hr>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="本名">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-signature"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="account" placeholder="帳號">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="密碼">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col">
                            <button type="submit" class="btn btn-info btn-block">註冊</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
            </div>
            <!-- /.login-card-body -->
            <div class="card-footer">
                <a href="{{ route('login') }}">
                    已有帳號?立即登入
                </a>
            </div>
        </div>
    </div>
    <!-- /.login-box -->
@endsection
