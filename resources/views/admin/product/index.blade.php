@extends('admin.layouts.app')
@section('content')
    <div class="category-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh sách món ăn </h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchProduct" placeholder="nhập để tìm kiếm..." class="form-control"
                    name="nameCategory">
                <button class="btn btn-primary" onclick="searchProduct()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProductModal"
                data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>Tạo mới món
                ăn</button>
        </div>
        <div class="mt-3">
            <div id="product_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('admin.product.modal_update')
@endsection
@section('web-script')
    <script>
        const urlDeleteProduct = "{{ route('admin.product.delete', ['id' => ':id']) }}";

        /**
         * tải danh sách bảng products
         */
        function searchProduct(page = 1) {
            $.ajax({
                url: '<?= route('admin.product.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchProduct').val(),
                }
            }).done(function(data) {
                $('#product_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchProduct();

            // Xóa danh mục
            $(document).on('click', '#btnDeleteProduct', function() {
                let productId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa món ăn này?', function() {
                    $.ajax({
                        url: urlDeleteProduct.replace(':id', productId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Đã xóa món ăn thành công");
                            searchProduct();
                        }
                    }).fail(function(xhr) {
                        if (xhr.status === 400 && xhr.responseJSON.errors) {
                            const errorMessages = xhr.responseJSON.errors;
                            for (let fieldName in errorMessages) {
                                notiError(errorMessages[fieldName][0]);
                            }
                        } else {
                            notiError();
                        }
                    })
                })
            })
        });
    </script>
@endsection
