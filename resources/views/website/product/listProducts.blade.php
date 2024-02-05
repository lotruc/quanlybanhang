<div class="row g-4 justify-content-center">
    @foreach ($data as $item)
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="rounded position-relative fruite-item">
                <div class="fruite-img ">
                    <a href="{{ route('website.product.details', $item->id) }}">
                        <img src="{{ Storage::url($item->image) }}" class="img-fluid w-100 rounded-top" alt="">

                    </a>
                </div>

                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                    {{ $item->categoryName }}
                </div>
                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                    <h4>{{ $item->name }}</h4>
                    <p>{{ $item->description }}</p>
                    <div class="d-flex justify-content-between flex-lg-wrap">
                        <p class="text-dark fs-5 fw-bold mb-0">{{ number_format($item->price, 0, ',', '.') }} VND
                        </p>
                        <button data-product-id="{{ $item->id }}"
                            class="btn btn-add-cart border border-secondary rounded-pill px-3 text-primary"><i
                                class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @if (count($data) == 0)
        <div class="d-flex justify-content-center mt-5 w-100">
            <h2>Không tìm thấy sản phẩm nào</h2>
        </div>
    @endif

</div>
<div class="col-12">
    <div class="pagination d-flex justify-content-center mt-5">
        {{ $data->links('website.product.paging') }}
    </div>
</div>
