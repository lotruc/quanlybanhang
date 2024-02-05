@if (count($cartItems) > 0)
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <th scope="row">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($item->productImage) }}"
                                        class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;"
                                        alt="">
                                </div>
                            </th>
                            <td>
                                <p class="mb-0 mt-4">{{ $item->productName }}</p>
                            </td>
                            <td>
                                <p class="mb-0 mt-4">{{ number_format($item->productPrice, 0, ',', '.') }}đ</p>
                            </td>
                            <td>
                                @if ($item->cartQuantity > $item->productQuantity)
                                    <span class="btn btn-danger">Hết hàng</span>
                                @else
                                    <div class="input-group quantity mt-4" style="width: 100px;">
                                        <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="text" data-product-id="{{ $item->productId }}"
                                            class="form-control qtyProductCart form-control-sm text-center border-0"
                                            value="{{ $item->cartQuantity }}">
                                        <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <p class="mb-0 mt-4">{{ number_format($item->total, 0, ',', '.') }}đ</p>
                            </td>
                            <td>
                                <button data-product-id="{{ $item->productId }}"
                                    class="btn remove-product-from-cart btn-md rounded-circle bg-light border mt-4">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal:</h5>
                            <p class="mb-0">{{ number_format($totalCarts, 0, ',', '.') }}đ</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 me-4">Shipping</h5>
                            <div class="">
                                <p class="mb-0">0đ</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                        <p class="mb-0 pe-4">{{ number_format($totalCarts, 0, ',', '.') }}đ</p>
                    </div>
                    <a href="{{ route('checkout.index') }}"
                        class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4">Tiến
                        hành thanh toán</a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="container d-flex align-items-center justify-content-center flex-column" style="min-height: 70vh;">
        <h3>Giỏ hàng của bạn đang trống!</h3>
        <div class="continue__btn mt-3">
            <a href="{{ route('website.product.index') }}">Đi mua sắm nào</a>
        </div>
    </div>
@endif
