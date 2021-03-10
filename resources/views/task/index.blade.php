@extends('layouts.app')

@section('title','任務列表')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">

{{--            <button class="btn btn-success">跟團</button>--}}

        </div>
        <div class="row">
            <form action="{{ route('task.store') }}" method="post" class="form">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">開團標題</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>餐廳名稱</label>
                            <input type="text" class="form-control" name="restaurant_name" placeholder="備註">
                        </div>
                        <div class="form-group">
                            <label>備註</label>
                            <input type="text" class="form-control" name="remark" placeholder="這裏可留空">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">開團</button>
                    </div>
                </div>
            </form>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">餐廳名稱</th>
                    <th scope="col">備註</th>
                    <th scope="col">開單時間</th>
                    <th scope="col">任務狀態</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>還沒寫</td>
                        <td>{{ $task->remark }}</td>
                        <td>{{ $task->created_at }}</td>
                        <td class="{{ $task->is_open? 'bg-success':'bg-danger' }}">{{ $task->is_open ? '開啟':'關閉' }}</td>
                    </tr>
                @empty
                    <p>沒有資料</p>
                @endforelse
                </tbody>
                <tfoot>
                <tr>

                </tr>
                </tfoot>
            </table>
        </div>

    </div><!-- /.container-fluid -->
</section>

@endsection


