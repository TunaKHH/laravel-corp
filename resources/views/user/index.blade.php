@extends('layouts.app')

@section('title','管理使用者')
@section('main.body')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <table class="table">
                <thead>
                    <tr class="row">
                        <th scope="col" class="col-1"></th>
                        <th scope="col" class="col">姓名</th>
                        <th scope="col" class="col">line id</th>
                        <th scope="col" class="col">目前餘額</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="row">
                            <td class="col-1">
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                            </td>
                            <td class="col">{{ $user->name }}</td>
                            @if( isset($user->line_id) )
                                <td class="col">{{ $user->line_id }}</td>
                            @else
                                <td class="col text-warning">{{ '未設定' }}</td>
                            @endif
                            <td class="col {{ $user->deposit >= 0 ?'bg-success':'bg-danger'}}">{{ $user->deposit }}</td>
                        </tr>
                    @empty
                        <p>沒有資料</p>
                    @endforelse
                </tbody>
            </table>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

