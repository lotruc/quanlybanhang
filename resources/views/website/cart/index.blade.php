@extends('website.layouts.app')
@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Giỏ hàng</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white">Giỏ hàng</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div id="tableCart"></div>
    </div>
    <!-- Cart Page End -->
@endsection
@section('web-script')
    <script>
        /**
         * hàm load bảng giỏ hàng
         */
        function searchCart() {
            $.ajax({
                url: "{{ route('cart.search') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data) {
                $('#tableCart').html(data);
            }).fail(function() {
                notiError();
            });
        }

        function updateCart(data) {
            $.ajax({
                type: "POST",
                url: "{{ route('cart.update') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
            }).done(function(res) {
                const data = res.data.original;
                if (data.success) {
                    notiSuccess(data.success, 'center');
                } else if (data.error) {
                    notiError(data.error);
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
            }).always(function() {
                searchCart();
            })
        }
        $(document).ready(function() {
            searchCart();

            // Hàm tăng số lượng sản phẩm khi ấn nút +
            $(document).on('click', '.btn-plus', function() {
                let inputQuantity = $(this).siblings(".qtyProductCart");
                let currentValue = parseInt(inputQuantity.val());
                $(this).prop('disabled', true);
                inputQuantity.prop('disabled', true);
                inputQuantity.val(currentValue + 1);
                const productId = inputQuantity.data('product-id');
                const newQuantity = parseInt(inputQuantity.val());
                const data = {
                    productId: productId,
                    quantity: newQuantity,
                }
                updateCart(data);
            })

            // Hàm giảm số lượng sản phẩm khi ấn nút -
            $(document).on('click', '.btn-minus', function() {
                let inputQuantity = $(this).siblings(".qtyProductCart");
                let currentValue = parseInt(inputQuantity.val());
                if (currentValue > 1) {
                    $(this).prop('disabled', true);
                    inputQuantity.prop('disabled', true);
                    inputQuantity.val(currentValue - 1);
                    const productId = inputQuantity.data('product-id');
                    const newQuantity = parseInt(inputQuantity.val());
                    const data = {
                        productId: productId,
                        quantity: newQuantity,
                    }
                    updateCart(data);
                } else {
                    notiError('Số lượng tối thiểu là 1');
                }

            });


            // Remove product from cart
            $(document).on('click', '.remove-product-from-cart', function() {
                const productId = $(this).data('product-id');
                $(this).prop('disabled', true);
                showConfirmDialog('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?', function() {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('cart.remove') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            productId: productId,
                        }
                    }).done(function(res) {
                        const data = res.data.original;
                        if (data.success) {
                            notiSuccess(data.success);
                            searchCart();
                            getTotalProductInCart();
                        } else if (data.error) {
                            notiError(data.error);
                        }

                    }).fail(function() {
                        notiError();
                    }).always(function() {
                        $(this).prop('disabled', false);
                    })
                });
            });
        })
    </script>
@endsection
