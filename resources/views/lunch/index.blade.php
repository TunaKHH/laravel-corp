@extends('layouts.app')

@section('title','扣款/儲值')

@section('main.body')



<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <div class="row">
            <form method="post">
                @csrf
                {{--    <form method="post" action="{{ route('lunch.store') }}" >--}}

                <button type="button" class="btn btn-success float-right" onclick="openSave()">開啟儲值</button>

                <table class="table">
                    <thead class="thead-dark">
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
                                <input type="hidden" value="{{ $user->id }}" name="user_id[]">
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
                    <tr>
                        <td colspan="5">
                            <button type="submit" class="btn btn-dark float-right">儲存</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </form>

        </div>
    </div><!-- /.container-fluid -->
</section>

@endsection
@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function openSave(){
            $('.user-save').attr('disabled', false);// 開啟儲值
        }

        $(document).on('submit', 'form', function(e) { //TODO 將表單改為即時更新
            e.preventDefault();
            $(window).keydown(function (event) {
                if (event.keyCode === 13) { // 防止按到enter送出
                    Swal.fire({
                        title: '請點擊下方確認按鈕!',
                        icon: 'error',
                        confirmButtonText: '好'
                    })
                    return false;
                }
            });

            Swal.fire({
                showCloseButton: true,
                showCancelButton: true,
                title: '確認要送出嗎!',
                icon: 'info',
                confirmButtonText: '確認'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('lunch.store') }}',
                        data:$(this).serialize(),
                        success: function(code) {
                            if(code && code === '200'){
                                Swal.fire('送出成功!', '', 'success')
                                    .then(()=>{
                                        window.location.href = "{{ route('record') }}";
                                    })
                            }else{
                                Swal.fire('送出失敗!', '', 'error')
                            }
                        }
                    });
                }
            })
        });

    </script>

@endsection


