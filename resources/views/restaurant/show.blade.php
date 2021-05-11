@extends('layouts.app')

@section('title','餐廳菜單')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('restaurantMeal.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="exampleDataList" class="form-label">餐點名稱</label>
                    <input class="form-control" list="datalistOptions" id="exampleDataList" name="name" placeholder="餐點名稱">
                    <datalist id="datalistOptions">
                        <option value=" ">
                    </datalist>
                </div>
                <div class="col">
                    <label for="price" class="form-label">金額</label>
                    <input class="form-control" type="number" id="price" value="0" name="price" required>
                </div>
            </div>
            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

            <button type="submit" class="btn btn-primary">新增</button>

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
                                <button type="submit" class="btn btn-danger">刪除(外部關聯未處理好)</button>
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


