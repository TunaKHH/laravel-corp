@extends('auth.layouts.app')

@section('main')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Tuna</b></a>
        </div>
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

                    @component('components.input-group')
                        @slot('type', 'text')
                        @slot('name', 'account')
                        @slot('placeholder', '帳號 or Email')
                        @slot('icon', 'fas fa-blind')
                    @endcomponent

                    @component('components.input-group')
                        @slot('type', 'password')
                        @slot('name', 'password')
                        @slot('placeholder', '密碼')
                        @slot('icon', 'fas fa-lock')
                    @endcomponent

                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <x-partials.remember-me-checkbox />
                            <button type="submit" class="btn btn-primary">登入</button>
                        </div>

                        <div class="col-12">
                            <hr>
                        </div>

                        <div class="col-12">
                            <x-partials.google-login />
                            <x-partials.line-login />
                        </div>
                    </div>
                </form>

                <div class="card-footer">
                    <a href="{{ route('register') }}">沒有帳號?註冊一個</a>
                </div>
            </div>
        </div>
    </div>
@endsection
