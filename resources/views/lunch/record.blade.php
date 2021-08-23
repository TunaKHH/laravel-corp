@extends('layouts.app')

@section('title','金額紀錄')

@section('css')

    <link rel="stylesheet" type="text/css" href=" {{ url('DataTables/datatables.min.css') }}"/>
    <style>
        #DataTables_Table_0_wrapper {
            width: 100%;
        }
        #DataTables_Table_0_wrapper .alert-danger{
            color: black;
            background-color: #f8d7da;
        }

        #DataTables_Table_0_wrapper .alert-success{
            color: black;
            background-color: #d4edda;
        }

    </style>
@endsection


@section('main.body')


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">姓名</th>
                        <th scope="col">金額</th>
                        <th scope="col">備註</th>
                        <th scope="col">操作人</th>
                        <th scope="col">操作時間</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)

                    <tr class="alert {{ $record->amount >= 0 ?'alert-success':'alert-danger'}}">
                        <td>{{ $record->user->name }}</td>
                        <td>{{ $record->amount }}</td>
                        <td>{{ $record->remark }}</td>
                        <td>{{ $record->user->name }}</td>
                        <td>{{ $record->created_at }}</td>
                    </tr>
                    @empty
                        <p>沒有資料</p>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('script')
    <script src="{{ url('DataTables/datatables.min.js') }}"></script>

    <script>
        $(document).ready( function () {
            $('.table').DataTable({
                // responsive: true,

                "order": [[ 3, "desc" ]],
                "lengthMenu": [ [50, 100, -1], [ 50, 100, "All"]]

            });
        } );


    </script>
@endsection

