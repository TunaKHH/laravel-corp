@extends('layouts.app')

@section('title','任務點餐')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
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
        @if( $task->can_order )
            <form action="{{ route('taskOrder.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="nameList" class="form-label">點餐人</label>
                            <input class="form-control" list="userListOptions" id="nameList" value="" name="user_name" required>
                            <datalist id="userListOptions">
                                @foreach( $users as $user)
                                    <option value="{{ $user->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col">
                            <label for="meal_name" class="form-label">餐點名稱</label>
                            <input class="form-control" list="datalistOptions" id="meal_name" name="meal_name" placeholder="餐點名稱" onchange="autoUpdatePrice(this)" required>
                            <datalist id="datalistOptions">
                                @foreach( $task->restaurant->restaurantMeals as $restaurantMeal)
                                    <option value="{{ $restaurantMeal->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col">
                            <label for="numList" class="form-label">數量</label>
                            <input class="form-control" id="numList" value="1" name="qty" required>
                        </div>
                        <div class="col">
                            <label for="price" class="form-label">金額</label>
                            <input class="form-control" list="priceListOptions" type="number" id="meal_price" value="0" name="meal_price" required>
                            <datalist id="priceListOptions">
                                @foreach( $task->restaurant->restaurantMeals as $restaurantMeal)
                                    <option class="{{ $restaurantMeal->name }}" value="{{ $restaurantMeal->price }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col">
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
            <div class="col">餐廳名稱{{ $task->restaurant->name }}</div>
            <div class="col">備註{{ $task->remark }}</div>
            <div class="col">建立時間{{ $task->created_at }}</div>
        </div>
        <div class="row">
            @forelse($task->restaurant->photos as $photo)
                <img src="{{ $photo->url }}" alt="" class="img-size-64" onclick="選擇菜單(this)">
            @empty
                此餐廳未上傳圖片
            @endforelse
        </div>
        <div class="row">
            <img src="" alt="" class="img-fluid" id="show_img">
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
                    <th scope="col">餐點金額</th>
                    <th scope="col">點餐時間</th>
                    <th scope="col">
                        操作
                        <span class="badge btn rounded-pill bg-success" onclick="operationalToggle()">切換</span>

                    </th>
                </tr>
                </thead>

                <tbody>
                @forelse ($task->taskOrder as $order)

                    <tr>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->meal_name }}</td>
                        <td>{{ $order->remark }}</td>
                        <td>{{ $order->meal_price }}</td>
                        <td>{{ $order->qty }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                            <div class="d-none operational_list">
                                <form method="post" action="{{ route('taskOrder.destroy', $order->id) }}" >
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">刪除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">沒有資料</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="row justify-content-center bg-orange">
            <h2>下方統整</h2>
        </div>

        <div class="row">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">餐點</th>
                    <th scope="col">餐點單價</th>
                    <th scope="col">餐點數量</th>
                    <th scope="col">餐點金額</th>
                    <th scope="col">備註</th>
                    <th scope="col">點餐時間</th>
                </tr>
                </thead>

                <tbody>
                    @forelse ($task->taskOrder as $order)
                        <tr>
                            <td>{{ $order->meal_name }}</td>
                            <td>{{ $order->meal_price }}</td>
                            <td>{{ $order->qty }}</td>
                            <td>{{ $order->total_price }}</td>
                            <td>{{ $order->remark }}</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">沒有資料</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mr-4">
            @if($task->is_open == 2)
                <button class="btn btn-success">確認並扣款</button>
            @endif
        </div>
    </div><!-- /.container-fluid -->
</section>

@endsection
@push('js')
    <script>
        function 選擇菜單(e){
            document.getElementById('show_img').src = e.src;
        }

        function autoUpdatePrice(e){
            let temp_price = $('.'+ e.value).val();
            $('#meal_price').val(temp_price);
        }

        function operationalToggle(){// 切換控制區顯示
            $('.operational_list').toggleClass('d-none')


        }



    </script>

@endpush


