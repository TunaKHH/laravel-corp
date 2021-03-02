@extends('layouts.app')

@section('title','餘額排行榜')
@section('main.body')


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <table class="table">
                <thead>
                    <tr class="row">
                        <th scope="col" class="col-4">姓名</th>
                        <th scope="col" class="col-8">目前餘額</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="row">
                            <td class="col-4">{{ $user->name }}</td>
                            <td class="col-8 {{ $user->deposit >= 0 ?'bg-success':'bg-danger'}}">{{ $user->deposit }}</td>
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

