@extends('board.layouts.app')

@section('title','金額紀錄')
@section('main.body')


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">姓名</th>
                        <th scope="col">目前餘額</th>
                        <th scope="col">扣款</th>
                        <th scope="col">儲值</th>
                        <th scope="col">備註</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)

                    <tr>
                        <td>{{ $user->name }}</td>
                        <td class="{{ $user->deposit >= 0 ?'bg-success':'bg-danger'}}">{{ $user->deposit }}</td>
                        <td>
                            <input class="form-control user-cost" type="number" value="0" name="user_cost[]">
                            <input type="hidden" value="20" name="user_id[]">
                        </td>
                        <td>
                            <input class="form-control user-save" type="number" value="0" name="user_save[]" data-user_id="20" disabled="">
                        </td>
                        <td>
                            <input class="form-control user-remark" type="text" name="user_remark[]" data-user_id="20" placeholder="此處備註">
                        </td>
                    </tr>
                    @empty
                        <p>沒有資料</p>
                    @endforelse
                </tbody>
                <tfoot>
                    <button type="submit" class="btn btn-info float-right">確認</button>
                </tfoot>
            </table>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection


