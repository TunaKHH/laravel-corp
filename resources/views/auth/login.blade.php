@extends('auth.layouts.app')

@section('main')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Tuna</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">登入以操作，若不會請呼叫鮪魚</p>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <p>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </p>
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
                        <div class="col-12 d-flex justify-content-between">
                            <div>
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" name="remember">
                                    <label for="remember">
                                        記住我
                                    </label>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary ">登入</button>
                            </div>
                        </div>
                        {{-- 分隔線 --}}
                        <div class="col-12">
                            <hr>
                        </div>
                        {{-- 第三方登入 --}}
                        <div class="col-12">
                            <div class="social-auth-links text-center mb-3">
                                <a href="{{ route('google.auth') }}">
                                    {{-- 圖片連結  --}}
                                    <img class="w-50"
                                        src="{{ url('/images/google/2x/btn_google_signin_dark_normal_web@2x.png') }}"
                                        alt="" srcset="">
                                </a>
                            </div>
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
