@extends('layouts.app')

@section('title','任務列表')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <form action="{{ route('task.store') }}" method="post" class="form">
                @csrf
                <div class="col-3 card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">開團標題</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <h5><i class="icon fas fa-ban">失敗</i>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </h5>
                                </div>
                            @endif
                            <label>餐廳</label>
                            <div class="form-group">
                                <select class="form-control" name="restaurant_id" id="restaurant_id" aria-label="Floating label select example" required>
                                    <option selected></option>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>備註</label>
                            <input type="text" class="form-control" name="remark" placeholder="這裏可留空">
                        </div>
                    </div>
                    <div class="card-footer row">
                        <button type="submit" class="btn btn-primary col">開團</button>
                        <a href="{{ route('restaurant.index') }}" class="btn btn-link">沒有餐廳?點擊這裡新增</a>
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
                                    已結單
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
                                            <form method="post" action="{{ route('task.prefinish', $task->id) }}">
                                                @csrf
                                                @method('put')
                                                <input type="submit" value="結單" class="btn btn-dark">
                                            </form>
                                        @break
                                        @default
                                            <a href="{{ route('task.show', $task->id) }}" class="btn btn-primary">
                                                查看
                                            </a>
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
                            <td>
                                沒有資料
                            </td>
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

@push('js')
    <script>
        // Swal.fire({
        //     icon: 'error',
        //     title: 'Oops...',
        //     text: 'Something went wrong!',
        //     footer: '<a href>Why do I have this issue?</a>'
        // })

        // $('form').on('submit',(e)=>{
        //     e.preventDefault();
        // })
    </script>
@endpush


