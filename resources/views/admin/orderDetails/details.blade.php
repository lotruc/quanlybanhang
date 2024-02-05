@extends('admin.layouts.app')
@section('content')
    <div class="orderdetails-container container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Danh sách chi tiết đơn hàng</h2>
            <span id="orderId" class="d-none">{{ $id }}</span>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchOrderDetails" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="orderDeitals">
                <button class="btn btn-primary" onclick="searchOrderDetailsAdmin()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="">
                <a href="{{ route('admin.order.index') }}" class="btn btn-danger"><i
                        class="fa-solid fa-circle-arrow-left me-2"></i>Quay lại</a>
            </div>
        </div>
        <div class="mt-3">
            <div id="order_details_table">

            </div>
        </div>
    </div>
    @include('admin.order.modal_update_status')
@endsection
@section('web-script')
    <script>
        /**
         * Hàm tải chi tiết đơn hàng
         */
        function searchOrderDetailsAdmin(page = 1) {
            $.ajax({
                url: '<?= route('admin.order.searchDetails') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    orderId: $("#orderId").text(),
                    searchName: $('#txtSearchOrderDetails').val(),
                }
            }).done(function(data) {
                $('#order_details_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchOrderDetailsAdmin();
        });
    </script>
@endsection
