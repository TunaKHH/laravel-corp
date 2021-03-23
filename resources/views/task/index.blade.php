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
                            <label>餐廳</label>
                            <p>
                                (之後改成若沒有就自動新增並跳出上傳圖片的視窗)
                            </p>
                            <div class="form-floating">
                                <select class="form-select" name="restaurant_id" id="restaurant_id" class="form-select" aria-label="Floating label select example" required>
                                    <option selected>請選擇</option>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                    @endforeach
                                </select>
                                <label for="restaurant_id">請選擇餐廳</label>
                            </div>
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
                    <th scope="col">操作</th>
                </tr>
                </thead>

                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>
                                <a href="{{ route('restaurant.show', $task->restaurant->id) }}">
                                    {{ $task->restaurant->name }}
                                </a>
                            </td>
                            <td>{{ $task->remark }}</td>
                            <td>{{ $task->created_at }}</td>
                            @switch($task->is_open)
                                @case(0)
                                <td class="bg-danger">
                                    已結束
                                </td>
                                @break

                                @case(1)
                                <td class="bg-success">
                                    開啟
                                </td>
                                @break

                                @case(2)
                                <td class="bg-warning">
                                    鎖定中
                                </td>
                                @break

                                @default
                                <td>
                                   未定義
                                </td>
                            @endswitch

                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    @if($task->can_order)
                                        <a href="{{ route('task.show', $task->id) }}" class="btn btn-primary">
                                            點餐
                                        </a>
                                    @endif

                                    {{--                                <button type="button" class="btn btn-success">點餐</button>--}}
                                    @switch($task->is_open)
                                        @case(1)
                                            <form method="post" action="{{ route('task.lock') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $task->id }}">
                                                <input type="submit" value="鎖定後結單" class="btn btn-warning">
                                            </form>
                                        @break
                                        @case(2)
                                            <form method="post" action="{{ route('task.unlock') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $task->id }}">
                                                <input type="submit" value="解除鎖定" class="btn btn-success">
                                            </form>
                                            <a href="{{ route('task.show', $task->id) }}" class="btn btn-dark">
                                                結單
                                            </a>
                                        @break
                                        @default
                                            未定義
                                    @endswitch
                                    @if($task->can_order)
                                        <form method="post" action="{{ route('task.destroy', $task->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="刪除" class="btn btn-danger">
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            沒有資料
                        </tr>
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


