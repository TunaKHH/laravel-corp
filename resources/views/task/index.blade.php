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
                            <label>備註</label>
                            <input type="text" class="form-control" name="remark" placeholder="備註">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">開團</button>
                    </div>
                </div>


            </form>
        </div>

    </div><!-- /.container-fluid -->
</section>

@endsection


