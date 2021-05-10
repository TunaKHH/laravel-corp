@extends('layouts.app')
@section('title','編輯使用者')
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
                    <div class="card-header">
                        <h3 class="card-title">修改 <small> (若不會請洽帥氣鮪魚)</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" action="{{ route('user.update', $user) }}">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="people_name">姓名</label>
                                <input type="text" name="name" class="form-control" id="people_name" placeholder="請填入姓名" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="people_line_id">line id</label>
                                <input type="text" name="line_id" class="form-control" id="people_line_id" placeholder="請填入line id" value="{{ $user->line_id }}">
                            </div>
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


