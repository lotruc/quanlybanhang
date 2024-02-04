<!-- Modal -->
<div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleProductModal">Tạo mới món ăn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_product">
                    <input type="hidden" name="productId" id="productId">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="productName" class="form-label">Tên món ăn<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productName" name="name">
                        </div>
                        <div class="col-md-4">
                            <label for="productPrice" class="form-label">Giá<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productPrice" name="price">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="productImage" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="productImage" name="image">
                    </div>
                    <div class="w-100 d-flex justify-content-center my-2" id="imageProductPreviewContainer">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Danh mục<span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" id="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productQuantity"><span class="text-danger">*</span>So
                                luong</label>
                            <input type="number" value="1" class="form-control" name="quantity"
                                id="productQuantity">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="productDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="productDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Trạng thái<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusProduct">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnSaveCategory" type="button" onclick="doSubmitProduct()" class="btn btn-primary">Lưu
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    #updateProductModal {
        --bs-modal-width: 800px !important;
    }
</style>
<script>
    /**
     * Submit form cateogry
     */
    function doSubmitProduct() {
        let formData = new FormData($('form#form_product')[0]);
        formData.append('statusProduct', $('#cbStatusProduct').is(':checked') ? 1 : 0);
        if ($('#productId').val() == '') {
            showConfirmDialog('Bạn có chắc chắn muốn tạo món ăn này không?', function() {
                createProduct(formData);
            });
        } else {
            showConfirmDialog('Bạn có chắc chắn muốn cập nhật món ăn này không?', function() {
                updateProduct(formData);
            });
        }
    }

    /**
     * Tạo mới món ăn
     */
    function createProduct(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.product.create') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Món ăn được tạo thành công');
                searchProduct();
                $('#updateProductModal').modal('toggle');
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

    }

    /**
     * Cập nhật sản phẩm
     */
    function updateProduct(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.product.update') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Món ăn được cập nhật thành công');
                searchProduct();
                $('#updateProductModal').modal('toggle');
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
    }

    $(document).ready(function() {
        // add/change image for product
        $('#productImage').on('change', function() {
            handleImageUpload(this, $('#imageProductPreview'));
        });
        // sự kiện hiển thị modal 
        $('#updateProductModal').on('shown.bs.modal', function(e) {
            const data = $(e.relatedTarget).data('item');
            let imagePreviewHtml = '';
            if (data) {
                imagePreviewHtml = `<img src="/storage/${data.image}" id="imageProductPreview" />`
                $("#productId").val(data.id);
                $("#productName").val(data.name);
                $("#productPrice").val(data.price);
                $("#productQuantity").val(data.quantity);
                $("#productDescription").val(data.description);
                $('#imageProductPreviewContainer').html(imagePreviewHtml);
                $('#cbStatusProduct').prop('checked', data.status == 1);
                $('#titleProductModal').html('Cập nhật danh mục');
            } else {
                imagePreviewHtml =
                    `<img src="{{ asset('img/default-img.png') }}" id="imageProductPreview" />`;
                $("#productId").val("");
                $("#productName").val('');
                $("#productPrice").val('');
                $("#productImage").val('');
                $("#productQuantity").val('');

                $("#productDescription").val('');
                $('#imageProductPreviewContainer').html(imagePreviewHtml);
                $('#cbStatusProduct').prop('checked', true);
                $('#titleProductModal').html('Tạo mới món ăn');
            }
        });
    })
</script>
