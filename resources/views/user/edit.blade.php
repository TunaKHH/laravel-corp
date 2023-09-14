@extends('layouts.app')
@section('title', '編輯使用者')
@section('main.body')


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
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
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-success"></i>成功</h4>
                                {{ $message }}
                            </div>
                        @endif
                        <div class="card-header">
                            <h3 class="card-title">基本資訊 <small> (若有問題請洽鮪魚)</small></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" action="{{ route('profile.edit') }}">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>帳號</label>
                                    <input type="text" class="form-control" value="{{ $user->account }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="people_name">名稱(識別用)</label>
                                    <input type="text" name="name" class="form-control" id="people_name"
                                        placeholder="請填入名稱" value="{{ $user->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="people_nickname">暱稱</label>
                                    <input type="text" name="nickname" class="form-control" id="people_nickname"
                                        placeholder="請填入暱稱" value="{{ $user->nickname }}">
                                </div>
                                <div class="form-group">
                                    <label for="people_line_id">Line Id(需在群組內和line bot對話：「取得我的lineId」)</label>
                                    <input type="text" name="line_id" class="form-control" id="people_line_id"
                                        placeholder="請填入Line Id" value="{{ $user->line_id }}">
                                </div>
                                <div class="form-group">
                                    <label for="people_email">E-mail</label>
                                    <input type="email" name="email" class="form-control" id="people_email"
                                        placeholder="請填入E-mail" value="{{ $user->email }}" autocomplete="off"
                                        @isset($user->email)
                                            disabled
                                        @endisset>
                                </div>
                                {{-- <div class="form-group">
                                    <label disabled>舊密碼</label>
                                    <input type="password" name="old-password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="people_password" disabled>新密碼</label>
                                    <input type="password" name="password" class="form-control" id="people_password">
                                </div>
                                <div class="form-group">
                                    <label for="people_password2">再次輸入新密碼</label>
                                    <input type="password" name="password2" class="form-control" id="people_password2">
                                </div> --}}
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">修改</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
