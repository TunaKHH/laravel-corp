@extends('layouts.app')

@section('title', '餐廳修改')

@section('main.body')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message . '/name:' . Session::get('image') }}</strong>
                </div>
                <img src="{{ secure_asset('storage/images/' . Session::get('image')) }}">
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
                <div class="col-md-12">
                    <form action="{{ route('restaurant.update', $restaurant->id) }}" method="post" class="form">
                        @csrf
                        @method('put')
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">餐廳修改</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>餐廳名稱</label>
                                    <input type="text" class="form-control" name="name" placeholder="餐廳名稱"
                                        value="{{ $restaurant->name }}" autofocus required>
                                </div>
                                <div class="form-group">
                                    <label>電話</label>
                                    <input type="text" class="form-control" name="phone" placeholder="電話"
                                        value="{{ $restaurant->phone }}">
                                </div>
                                <div class="form-group">
                                    <label>備註</label>
                                    <input type="text" class="form-control" name="remark"
                                        value="{{ $restaurant->remark }}" placeholder="這裏可留空">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-danger">修改</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">縮圖</th>
                                <th scope="col">全圖</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($restaurant->photos as $photo)
                                <tr>
                                    <td>
                                        <img src="{{ $photo->url }}" alt="" class="img-thumbnail img-size-64">

                                    </td>
                                    <td>
                                        <a href="{{ $photo->url }}" target="_blank">
                                            連結{{ $loop->index }}
                                        </a>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('restaurantPhoto.destroy', $photo->id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <span>沒有圖片</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>



        </div><!-- /.container-fluid -->
    </section>

@endsection
