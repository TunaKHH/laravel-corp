@extends('layouts.app')

@section('title','扣款/儲值')

@section('main.body')



<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        @livewire('lunch')

        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection
@section('script')


    <script>
        function openSave(){
            $('.user-save').attr('disabled', false);// 開啟儲值
        }

        $(document).on('submit', 'form', function(e) { //TODO 將表單改為即時更新
            // e.preventDefault();
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
            }).then((result) => {
                if (result.isConfirmed) {

                    // Livewire.emit('submit')

                    Swal.fire('送出成功!', '', 'success')
                }
            })
        });

    </script>

@endsection


