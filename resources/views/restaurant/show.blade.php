@extends('layouts.app')

@section('title','餐廳菜單')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('taskOrder.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="exampleDataList" class="form-label">餐點名稱</label>
                    <input class="form-control" list="datalistOptions" id="exampleDataList" name="name" placeholder="餐點名稱">
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
                    <input class="form-control" id="numList" value="1" name="qty">
                </div>
                <div class="col">
                    <label for="amount" class="form-label">金額</label>
                    <input class="form-control" type="number" list="numOptions" id="amount" value="0" name="amount" required>
                </div>

            </div>
{{--            <input type="hidden" name="restaurant_id" value="{{ $task->restaurant_id }}">--}}
            <input type="hidden" name="task_id" value="{{ $restaurant->id }}">
            <input type="hidden" name="user_id" value="1">
            <button type="submit" class="btn btn-primary">點餐</button>

        </form>
        <div class="row">
            @forelse($restaurant->photos as $photo)
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
                    <th scope="col">餐點</th>
                    <th scope="col">餐點單價</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($restaurant->restaurantMeals as $restaurantMeal)
                    <tr>
                        <td>{{ $restaurantMeal->name }}</td>
                        <td>{{ $restaurantMeal->price }}</td>
                        <td>
                            <form action="{{ route('restaurantMeal.destroy', $restaurantMeal->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger" disabled>刪除(外部關聯未處理好)</button>
                            </form>
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


