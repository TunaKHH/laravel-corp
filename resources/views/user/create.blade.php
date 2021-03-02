@extends('layouts.app')
@section('title','增加使用者(我還沒寫功能)')
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
                        <h3 class="card-title">增加新成員請填寫以下表格 <small>(若不會請洽鮪魚)</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="quickForm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">姓名</label>
                                <input type="text" name="people_name" class="form-control" id="people_name" placeholder="請填入姓名" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">首次儲值金額</label>
                                <input type="number" name="people_deposit" class="form-control" id="people_deposit" placeholder="請填入首次儲值金額" required>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary">送出</button>
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


