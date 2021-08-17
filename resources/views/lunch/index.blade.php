@extends('layouts.app')

@section('title','扣款/儲值')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </h5>
            </div>
        @endif
        單個欄位上限$900,000
        <div class="row">
            <form method="post" class="form-save" action="{{ route('lunch.store') }}">
                @csrf
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
                        <td></td>
                        <td>{{ $users->sum('deposit') }}</td>
                        <td id="sum_deduction">0</td>
                        <td id="sum_save">0</td>
                        <td>
                            <button type="button" class="btn btn-dark float-right save-confirm">儲存</button>
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
        var sum_last = 0;

        function openSave() {
            $('.user-save').prop('disabled', false);
        }


        function totalDedcutionAmount() { // 加總扣款金額
            let sum = 0;
            let user_costs = $('.user-cost');
            for (let i = 0; i < user_costs.length; i++) {
                const user_cost = user_costs[i];
                sum += parseInt(user_cost.value);
            }
            return sum;
        }

        function totalSaveAmount() { // 加總儲值金額
            let sum = 0;
            let user_saves = $('.user-save');
            for (let i = 0; i < user_saves.length; i++) {
                const user_save = user_saves[i];
                sum += parseInt(user_save.value);
            }
            return sum;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('keyup', function(e) {
            $('#sum_deduction').text(totalDedcutionAmount());
            $('#sum_save').text(totalSaveAmount());
        });



        $('.save-confirm').click(function(e){ //TODO 將表單改為即時更新
            var form =  $(this).closest("form");
            e.preventDefault();

            Swal.fire({
                showCloseButton: true,
                showCancelButton: true,
                title: '確認要送出嗎!',
                icon: 'info',
                confirmButtonText: '確認'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });

    </script>

@endsection


