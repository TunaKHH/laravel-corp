@extends('auth.layouts.app')

@section('main')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>SKW</b>CORP</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">邀請碼若不知道請呼叫鮪魚</p>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <h5><i class="icon fas fa-ban"></i>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </h5>
                        請重試
                    </div>
                @endif
                <form action="{{ url(URL::route('register.enter', [], false)) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>邀請碼</label>
                        {{-- 如果是debug模式就直接填入邀請碼 --}}
                        @if (env('APP_DEBUG') === true)
                            <input type="text" class="form-control" name="invitation_code" placeholder="請輸入邀請碼"
                                value="{{ env('INVITATION_CODE') }}" required>
                        @else
                            <input type="text" class="form-control" name="invitation_code" placeholder="請輸入邀請碼"
                                value="{{ old('invitation_code') }}" required>
                        @endif
                    </div>
                    <hr>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="名稱"
                            value="{{ old('name') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-signature"></span>
                            </div>
                        </div>
                    </div>

                    <small class="form-text text-muted">帳號必須介於6到30個字元</small>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="account" placeholder="帳號"
                            value="{{ old('account') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <small class="form-text text-muted">密碼必須介於8到255個字元</small>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="密碼" required>
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
