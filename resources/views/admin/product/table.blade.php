<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên món ăn</th>
            <th>Hình ảnh</th>
            <th>Giá</th>
            <th>So lung</th>
            <th>Danh mục</th>
            <th>Trạng thái</th>
            <th>Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>
                    <img src="{{ Storage::url($item->image) }}"
                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px;" alt="">
                </td>
                <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                <td>{{ $item->quantity }}</td>

                <td>{{ $item->categoryName }}</td>
                <td><button style="cursor: unset"
                        class="btn btn-{{ $item->status == 1 ? 'primary' : 'danger' }}">{{ $item->status == 1 ? 'ON' : 'OFF' }}</button>
                </td>
                <td>
                    <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                        data-bs-target="#updateProductModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        class="btn btn-success m-1 me-4"><i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh
                        sửa</button>
                    <button id="btnDeleteProduct" data-id="{{ $item->id }}" class="btn btn-danger"><i
                            class="fa-solid fa-trash-can me-2"></i>Xóa</button>
                </td>
            </tr>
        @endforeach
        @if (count($data) == 0)
            <td class="align-center" colspan="9" style="background-color: white; font-size : 20px;text-align:center">
                Không có dữ liệu để hiển thị
            </td>
        @endif
    </tbody>
</table>
<div class="row">
    <div class="table-footer" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('admin.product.paging') }}
    </div>
</div>
