@extends('website.layouts.app')
@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Checkout</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white">Checkout</li>
        </ol>
    </div>
    <!-- Single Page Header End -->
    <!-- Checkout Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Chi tiết thanh toán</h1>
            <form id="form_order">
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Họ và tên<sup class="text-danger">*</sup></label>
                                    <input name="full_name" type="text" value="{{ Auth::user()->name ?? '' }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Số điện thoại<sup class="text-danger">*</sup></label>
                                    <input name="phone" type="number" value="{{ Auth::user()->phone ?? '' }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Địa chỉ giao hàng <sup class="text-danger">*</sup></label>
                            <textarea name="address" class="form-control" placeholder="Địa chỉ giao hàng"></textarea>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Mô tả</label>
                            <textarea name="message" class="form-control" spellcheck="false" cols="30" rows="5"
                                placeholder="ghi chú thêm về đơn hàng"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xl-5">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Products</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItemsInCheckout as $item)
                                        <tr>
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img src="{{ Storage::url($item->productImage) }}"
                                                        class="img-fluid rounded-circle" style="width: 90px; height: 90px;"
                                                        alt="">
                                                </div>
                                            </th>
                                            <td class="py-5">{{ $item->productName }}</td>
                                            <td class="py-5">{{ number_format($item->productPrice, 0, ',', '.') }}đ</td>
                                            <td class="py-5"> {{ $item->quantity }}</td>
                                            <td class="py-5">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between algin-items-center mb-4">
                            <p class="">Shipping</p>
                            <span class="btn btn-primary">Miễn phí vận chuyển</span>
                        </div>

                        <div class="d-flex justify-content-between algin-items-center">
                            <p class="">Tổng tiền thanh toán</p>
                            <p class="btn btn-primary"><span
                                    id="total_order">{{ number_format($totalCarts, 0, ',', '.') }}</span> đ</p>
                        </div>

                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <button id="btn-order" type="button"
                                class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Checkout Page End -->
@endsection
@section('web-script')
    <script>
        $(document).ready(function() {
            /**
             * Hàm tạo mới đơn hàng
             */
            function createOrder(data, btn, form) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('checkout.placeOrder') }}",
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                }).done(function(res) {
                    const data = res.data.original;
                    if (data.success) {
                        notiSuccess(data.success, 'center', function() {
                            window.location.href = "{{ route('home') }}";
                        });
                    } else {
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
                    btn.text('Đặt hàng');
                    btn.prop('disabled', false);
                })
            }


            // Click to order 
            $('#btn-order').click(function(e) {
                e.preventDefault();
                const btnOrder = $(this);
                const formCreateOrder = $('form#form_order');
                let formData = new FormData(formCreateOrder[0]);
                const totalOrderText = $("#total_order").text();
                const totalOrder = parseFloat(totalOrderText.replace(/\./g, ''));
                formData.append('total_order', totalOrder);
                showConfirmDialog('Bạn có chắc chắn muốn đặt đơn hàng này không?', function() {
                    btnOrder.text('Đang xử lý...');
                    btnOrder.prop('disabled', true);
                    createOrder(formData, btnOrder, formCreateOrder);
                });
            })
        })
    </script>
@endsection
