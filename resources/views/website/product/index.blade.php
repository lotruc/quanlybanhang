@extends('website.layouts.app')
@section('content')
    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control p-3" placeholder="keywords"
                            aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->


    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop</li>
        </ol>
    </div>
    <!-- Single Page Header End -->


    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Fresh fruits shop</h1>
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

            // // tìm kiếm sản phẩm theo danh mục
            // $('.category-tab').click(function() {
            //     let categoryId = $(this).data('id');
            //     $('.category-tab').removeClass('active');
            //     $(this).addClass('active');
            //     searchProductMenu(page = 1, categoryId = categoryId ?? null);

            // })
        })
    </script>
@endsection
