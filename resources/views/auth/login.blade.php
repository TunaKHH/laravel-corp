@extends('auth.layouts.app')

@section('main')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>SKW</b>CORP</a>
        </div>
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
                <form action="{{ route('login.enter') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="account" placeholder="帳號 or Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-blind"></span>
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
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    記住我
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">登入</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
            </div>
            <!-- /.login-card-body -->
            <div class="card-footer">
                <a href="{{ route('register') }}">
                    沒有帳號?註冊一個
                </a>
            </div>
        </div>
    </div>
    <!-- /.login-box -->
@endsection
