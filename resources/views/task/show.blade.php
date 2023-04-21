@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ secure_asset('css/task.css') }}">
@endsection

@section('title')
    @if (empty($task->restaurant->phone))
        任務點餐-{{ $task->restaurant->name }} (無電話)
    @else
        任務點餐-{{ $task->restaurant->name }} ({{ $task->restaurant->phone }})
    @endif
@endsection
@section('first_page')
    <a href="{{ route('task.index') }}">
        任務列表
    </a>
@endsection

@section('main.body')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban">失敗</i>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </h5>
                </div>
            @endif

            @if ($message = Session::get('no_user'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i>失敗</h4>
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-success"></i>成功</h4>
                    {{ $message }}
                </div>
            @endif

            <form id="msform">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active" id="progress_step1"><strong>開放點餐</strong></li>
                    @if ($task->step >= 2)
                        <li class="active" id="progress_step2"><strong>鎖單/打電話訂餐中</strong></li>
                    @else
                        <li id="progress_step2"><strong>鎖單/打電話訂餐中</strong></li>
                    @endif
                    @if ($task->step >= 3)
                        <li class="active" id="progress_step3"><strong>餐點送達，金額無誤</strong></li>
                    @else
                        <li id="progress_step3"><strong>餐點送達，金額無誤</strong></li>
                    @endif
                    @if ($task->step >= 4)
                        <li class="active" id="progress_step4"><strong>結單/自動扣款</strong></li>
                    @else
                        <li id="progress_step4"><strong>結單/自動扣款</strong></li>
                    @endif


                </ul>
            </form>

            @if ($task->can_order)
                <form action="{{ route('taskOrder.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm col-12">
                            <label for="nameList" class="form-label">點餐人</label>
                            <input class="form-control" list="userListOptions" id="nameList"
                                value="{{ Auth::user()->name }}" name="user_name" required>
                            <datalist id="userListOptions">
                                @foreach ($users as $user)
                                    <option value="{{ $user->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-sm col-12">
                            <label for="meal_name" class="form-label">餐點名稱</label>
                            <input class="form-control" list="datalistOptions" id="meal_name" name="meal_name"
                                placeholder="餐點名稱" onchange="autoUpdatePrice(this)" autocomplete="off" required>
                            <datalist id="datalistOptions">
                                @foreach ($task->restaurant->restaurantMeals as $restaurantMeal)
                                    <option value="{{ $restaurantMeal->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-sm col-12">
                            <label for="numList" class="form-label">數量</label>
                            <input class="form-control" id="numList" value="1" name="qty" type="number"
                                required>
                        </div>
                        <div class="col-sm col-12">
                            <label for="price" class="form-label">金額</label>
                            <input class="form-control" list="priceListOptions" type="number" id="meal_price"
                                value="0" name="meal_price" required>
                            <datalist id="priceListOptions">
                                @foreach ($task->restaurant->restaurantMeals as $restaurantMeal)
                                    <option class="{{ $restaurantMeal->name }}" value="{{ $restaurantMeal->price }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-sm col-12">
                            <label for="remark" class="form-label">備註</label>
                            <input class="form-control" type="text" id="remark" name="remark" placeholder="(選填)我是備註">
                        </div>

                    </div>
                    <input type="hidden" name="restaurant_id" value="{{ $task->restaurant_id }}">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="user_id" value="1">
                    <button type="submit" class="btn btn-primary">點餐</button>
                </form>
            @else
                <div class="alert alert-danger" role="alert">
                    此任務目前不開放點餐
                </div>
            @endif
            <div class="row">
                @forelse($task->restaurant->photos as $photo)
                    {{--                <img src="{{ $photo->url }}" alt="" class="img-size-64" data-bs-toggle="collapse" data-bs-target="#collapseExample" onclick="選擇菜單(this)"> --}}
                    <img src="{{ $photo->url }}" alt="" class="img-size-64" onclick="選擇菜單(this)"
                        data-bs-toggle="collapse" data-bs-target="#collapseMenu">
                    {{--                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"> --}}
                    {{--                    Button with data-bs-target --}}
                    {{--                </button> --}}

                @empty
                    此餐廳未上傳圖片
                @endforelse
            </div>
            <div class="collapse" id="collapseMenu">
                <div class="card card-body" id="collapse_content">
                    圖片載入中
                </div>
            </div>
            {{--        <div class="row"> --}}
            {{--            <img src="" alt="" class="img-fluid" id="show_img"> --}}
            {{--        </div> --}}

            <div class="row justify-content-center bg-primary">
                <h2 class="text-center">訂餐明細</h2>
            </div>
            <div class="row">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">點餐人</th>
                            <th scope="col">餐點</th>
                            <th scope="col">備註</th>
                            <th scope="col">餐點單價</th>
                            <th scope="col">餐點數量</th>
                            <th scope="col">金額計算</th>
                            <th scope="col">點餐時間</th>
                            <th scope="col">
                                @if ($task->is_open !== 0)
                                    操作
                                    <span class="badge btn rounded-pill bg-success"
                                        onclick="operationalToggle()">切換</span>
                                @endif
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($task->taskOrder as $order)
                            <tr id="order_{{ $loop->index }}">
                                <form action="{{ route('taskOrder.update', $order) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <td>
                                        <input type="text" class="form-control" value="{{ $order->user->name }}"
                                            name="user_name" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $order->meal_name }}"
                                            name="meal_name" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $order->remark }}"
                                            name="remark" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $order->meal_price }}"
                                            name="meal_price" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $order->qty }}"
                                            name="qty" disabled>
                                    </td>
                                    <td>{{ $order->total_price }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <div class="d-none operational_list btn-group">
                                            <button type="button" class="btn btn-primary btn-edit"
                                                id="btn_edit_{{ $loop->index }}"
                                                onclick="editOpen({{ $loop->index }})"><i
                                                    class="fas fa-edit"></i></button>
                                            <form method="post" action="{{ route('taskOrder.update', $order->id) }}">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-primary" style="display: none;"
                                                    id="btn_confirm_{{ $loop->index }}"><i
                                                        class="fas fa-check"></i></button>
                                            </form>

                                            <form class="form-destroy" method="post"
                                                action="{{ route('taskOrder.destroy', $order->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </form>
                                            <button type="button" class="btn btn-secondary btn-cancel"
                                                style="display: none;" id="btn_cancel_{{ $loop->index }}"
                                                onclick="editCancel({{ $loop->index }})"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center;">沒有資料</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center bg-orange">
                <h2 class="text-center">下方統整</h2>
            </div>

            <div class="row">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">餐點</th>
                            <th scope="col">備註</th>
                            <th scope="col">餐點單價</th>
                            <th scope="col">餐點數量</th>
                            <th scope="col">金額計算</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($task_totals as $task_total)
                            <tr>
                                <td>{{ $task_total->meal_name }}</td>
                                <td>{{ $task_total->remark }}</td>
                                <td>{{ $task_total->meal_price }}</td>
                                <td>{{ $task_total->qty_sum }}</td>
                                <td>{{ $task_total->qty_sum * $task_total->meal_price }}</td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">沒有資料</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="4"></td>
                            <td class="text-bold text-danger">總金額：{{ $sum_money }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
            @if ($task->is_open != 0)
                <a href="{{ route('task.edit', $task->id) }}">餐廳金額有誤?點此統一修改</a>
            @endif

            <div>
                @if ($task->step == 3)
                    <form method="post" action="{{ route('task.finish', $task->id) }}" class="form-confirm row">
                        @csrf
                        @method('post')
                        <button type="submit" class="btn btn-block btn-lg  btn-success">結單並自動扣款</button>
                    </form>
                    {{--                TODO 自動扣款功能 --}}
                @endif
            </div>
        </div><!-- /.container-fluid -->
    </section>

@endsection
@push('js')
    <script>
        var collapseMenu = document.getElementById('collapseMenu')
        var collapseContent = document.getElementById('collapse_content')

        collapseMenu.addEventListener('hidden.bs.collapse', function() {
            collapseContent.innerHTML = '';
        })

        function 選擇菜單(e) {
            collapseContent.innerHTML = `<img src="${e.src}" alt="" class="img-fluid" id="show_img">`;
        }

        function autoUpdatePrice(e) {
            let temp_price = $('.' + e.value).val();
            $('#meal_price').val(temp_price);
        }

        function operationalToggle() { // 切換控制區顯示
            $('.operational_list').toggleClass('d-none');
        }

        function editCancel(id) { // 取消修改
            $('#order_' + id + ' td>input').prop('disabled', true);

            $('#btn_cancel_' + id).hide();
            $('#btn_edit_' + id).show();
            $('#btn_confirm_' + id).hide();
        }

        function editOpen(id) { // 開啟修改
            $('#order_' + id + ' td>input').prop('disabled', false);

            $('#btn_confirm_' + id).show();
            $('#btn_cancel_' + id).show();
            $('#btn_edit_' + id).hide();
        }
    </script>
@endpush
