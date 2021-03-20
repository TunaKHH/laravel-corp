@extends('layouts.app')

@section('title','餐廳列表')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message.'/name:'.Session::get('image') }}</strong>
            </div>
            <img src="{{ asset('storage/images/'.Session::get('image'))  }}">
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <form action="{{ route('restaurant.store') }}" method="post" class="form">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">餐廳名稱</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>餐廳名稱</label>
                            <input type="text" class="form-control" name="name" placeholder="餐廳名稱">
                        </div>
                        <div class="form-group">
                            <label>備註</label>
                            <input type="text" class="form-control" name="remark" placeholder="這裏可留空">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">建立新餐廳</button>
                    </div>
                </div>
            </form>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">餐廳名稱</th>
                    <th scope="col">備註</th>
                    <th scope="col">菜單圖片</th>
                    <th scope="col">菜單連結</th>
                    <th scope="col">建立時間</th>
                    <th scope="col">上傳菜單</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($restaurants as $restaurant)
                    <tr>
                        <td>{{ $restaurant->name }}</td>
                        <td>{{ $restaurant->remark }}</td>
                        <td>
                            @forelse ($restaurant->photos as $photo)
                                <a href="{{ $photo->url }}">連結{{ $loop->index }}</a>
                            @empty
                                <span>沒有圖片</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('restaurant.show', $restaurant->id) }}">連結</a>
                        </td>
                        <td>{{ $restaurant->created_at }}</td>
                        <td>
                            <form method="post" action="{{ route('uploadImage') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="image" class="form-control">
                                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                                    <input type="submit" class="btn btn-success" value="上傳">
                                </div>
                            </form>

                        </td>
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


