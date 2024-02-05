@extends('website.layouts.app')
@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white">Shop</li>
        </ol>
    </div>
    <!-- Single Page Header End -->


    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Danh sách sản phẩm</h1>
            <div class="row g-4">
                <div class="col-lg-12" id="listProducts">

                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Fruits Shop End-->
@endsection

@section('web-script')
    <script>
        // hàm thêm sản phẩm vào giỏ hàng
        function addToCart(productId, quantity, btn) {
            $.ajax({
                    type: "POST",
                    url: "{{ route('cart.add') }}",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        productId: productId,
                        quantity: quantity,
                    },
                })
                .done(function(res) {
                    console.log(res);
                    // const quantityAvailable = res.data.original.quantityAvailable;
                    const data = res.data.original;
                    if (data.success) {
                        notiSuccess(data.success, "center");
                        // $("#product-available").text(quantityAvailable);
                        getTotalProductInCart();
                    } else if (data.error) {
                        notiError(data.error);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "/login";
                    } else if (xhr.status === 400 && xhr.responseJSON.errors) {
                        const errorMessages = xhr.responseJSON.errors;
                        for (let fieldName in errorMessages) {
                            notiError(errorMessages[fieldName][0]);
                        }
                    } else {
                        notiError();
                    }
                })
                .always(function() {
                    btn.prop("disabled", false);
                });
        }

        /**
         * tải danh sách products
         */
        function searchProductMenu(page = 1, categoryId = null) {
            $.ajax({
                url: '<?= route('website.product.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    categoryId: categoryId,
                    paginate: 6,
                    status: 1,
                }
            }).done(function(data) {
                $('#listProducts').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchProductMenu();

            $(document).on('click', '.btn-add-cart', function() {
                $(this).prop('disabled', true);
                const productId = $(this).data('product-id');
                addToCart(productId, 1, $(this));
                searchProductMenu();
            });

        })
    </script>
@endsection
