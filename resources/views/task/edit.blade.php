@extends('layouts.app')
@section('first_page')
    <a href="{{ route('task.index') }}">
        任務列表
    </a>
@endsection
@section('last_page')
    <a href="{{ route('task.show',$task->id) }}">{{ $task->restaurant->name }}</a>
@endsection
@section('title','修改餐點金額')

@section('main.body')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('task.update', $task->id) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="row">
                此表單會修改餐廳餐點金額及此任務金額
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">餐點名稱</th>
                        <th scope="col">餐點單價</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($task_totals as $task_total)
                            <tr>
                                <td>{{ $task_total->meal_name }}</td>
                                <td>
                                    <input class="form-control" type="number" name="meal_price[]" value="{{ $task_total->meal_price }}">
                                    <input type="hidden" name="meal_id[]" value="{{ $task_total->meal_id }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">沒有資料</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <button type="submit" class="btn btn-block btn-lg  btn-danger">修改金額</button>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>

@endsection
@push('js')
    <script>
        function 選擇菜單(e){
            document.getElementById('show_img').src = e.src;
        }

        function autoUpdatePrice(e){
            let temp_price = $('.'+ e.value).val();
            $('#meal_price').val(temp_price);
        }

        function operationalToggle(){// 切換控制區顯示
            $('.operational_list').toggleClass('d-none');
        }

        function editCancel(id){// 取消修改
            $('#order_' + id + ' td>input').prop('disabled',true);

            $('#btn_cancel_' + id).hide();
            $('#btn_edit_' + id).show();
            $('#btn_confirm_' + id).hide();


        }

        function editOpen(id){// 開啟修改
            $('#order_' + id + ' td>input').prop('disabled',false);

            $('#btn_confirm_' + id).show();
            $('#btn_cancel_' + id).show();
            $('#btn_edit_' + id).hide();



        }



    </script>

@endpush


