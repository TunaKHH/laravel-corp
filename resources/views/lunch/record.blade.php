@extends('layouts.app')

@section('title','金額紀錄')
@section('main.body')


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">姓名</th>
                        <th scope="col">金額</th>
                        <th scope="col">備註</th>
                        <th scope="col">操作時間</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)

                    <tr class="alert {{ $record->amount >= 0 ?'alert-success':'alert-danger'}}">
                        <td>{{ $record->user->name }}</td>
                        <td>{{ $record->amount }}</td>
                        <td>{{ $record->remark }}</td>
                        <td>{{ $record->created_at }}</td>
                    </tr>
                    @empty
                        <p>沒有資料</p>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection


