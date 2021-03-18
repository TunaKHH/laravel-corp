@extends('layouts.app')

@section('title','任務點餐')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('taskOrder.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="exampleDataList" class="form-label">餐點名稱</label>
                    <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="餐點名稱">
                    <datalist id="datalistOptions">
                        <option value="San Francisco">
                        <option value="New York">
                        <option value="Seattle">
                        <option value="Los Angeles">
                        <option value="Chicago">
                    </datalist>
                </div>
                <div class="col">
                    <label for="numList" class="form-label">數量</label>
                    <input class="form-control" id="numList" value="1">
                </div>
                <div class="col">
                    <label for="amount" class="form-label">金額</label>
                    <input class="form-control" type="number" list="numOptions" id="amount" value="" name="amount" required>
                </div>

            </div>
            <input type="hidden" name="restaurant_id" value="{{ $task->restaurant_id }}">
            <button type="submit" class="btn btn-primary">點餐</button>

        </form>
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
                    <th scope="col">餐點單價</th>
                    <th scope="col">餐點數量</th>
                    <th scope="col">餐點金額</th>
                    <th scope="col">點餐時間</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($task->taskOrder as $order)
                    <tr>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->restaurantMeal->name }}</td>
                        <td>{{ $order->restaurantMeal->price }}</td>
                        <td>{{ $order->qty }}</td>
                        <td>{{ $order->price }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                            <button class="btn btn-primary">確認</button>
                        </td>
                    </tr>
                @empty
                    <tr>沒有資料</tr>
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
        function 選擇菜單(e){
            console.log(e.src);
            document.getElementById('show_img').src = e.src;
        }

    </script>

@endpush


