@extends('website.layouts.app')
@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Về chúng tôi</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white">Về chúng tôi</li>
        </ol>
    </div>
    <!-- Phần 1: Giới thiệu -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('img/fruite-item-2.jpg') }}" alt="Hình ảnh Sứ Mệnh" class="img-fluid">

            </div>
            <div class="col-md-6">
                <h2>Về Chúng Tôi</h2>
                <p>Chào mừng bạn đến với trang web của chúng tôi! Chúng tôi đam mê mang đến cho bạn những sản phẩm đặc sản
                    tốt nhất từ Việt Nam.</p>
            </div>
        </div>
    </div>

    <!-- Phần 2: Sứ mệnh của chúng tôi -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h3>Sứ Mệnh Của Chúng Tôi</h3>
                <p>Sứ mệnh của chúng tôi là giới thiệu những hương vị và truyền thống đặc biệt của ẩm thực Việt Nam. Mỗi sản
                    phẩm chúng tôi cung cấp được lựa chọn cẩn thận để đại diện cho di sản văn hóa phong phú của Việt Nam.
                </p>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('img/fruite-item-2.jpg') }}" alt="Hình ảnh Sứ Mệnh" class="img-fluid">
            </div>
        </div>
    </div>

    <!-- Phần 3: Đội ngũ của chúng tôi -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3>Đội Ngũ Của Chúng Tôi</h3>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Thành viên 1 -->
            <div class="col-md-3">
                <img src="{{ asset('img/fruite-item-2.jpg') }}" alt="Hình ảnh Thành Viên 1" class="img-fluid">
                <h4 class="mt-2">Tên Thành Viên 1</h4>
                <p>Mô tả về thành viên 1. Chia sẻ về kinh nghiệm và đam mê của họ trong lĩnh vực.</p>
            </div>

            <!-- Thành viên 2 -->
            <div class="col-md-3">
                <img src="{{ asset('img/fruite-item-2.jpg') }}" alt="Hình ảnh Thành Viên 2" class="img-fluid">
                <h4 class="mt-2">Tên Thành Viên 2</h4>
                <p>Mô tả về thành viên 2. Chia sẻ về kinh nghiệm và đam mê của họ trong lĩnh vực.</p>
            </div>

            <!-- Thành viên 3 -->
            <div class="col-md-3">
                <img src="{{ asset('img/fruite-item-2.jpg') }}" alt="Hình ảnh Thành Viên 3" class="img-fluid">
                <h4 class="mt-2">Tên Thành Viên 3</h4>
                <p>Mô tả về thành viên 3. Chia sẻ về kinh nghiệm và đam mê của họ trong lĩnh vực.</p>
            </div>

            <!-- Thành viên 4 -->
            <div class="col-md-3">
                <img src="{{ asset('img/fruite-item-2.jpg') }}" alt="Hình ảnh Thành Viên 4" class="img-fluid">
                <h4 class="mt-2">Tên Thành Viên 4</h4>
                <p>Mô tả về thành viên 4. Chia sẻ về kinh nghiệm và đam mê của họ trong lĩnh vực.</p>
            </div>
        </div>
    </div>

    <!-- Phần 4: Liên hệ -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h3>Liên Hệ</h3>
                <p>Nếu bạn có bất kỳ câu hỏi hoặc phản hồi nào, đừng ngần ngại liên hệ với chúng tôi. Chúng tôi ở đây để hỗ
                    trợ bạn trên hành trình khám phá hương vị của Việt Nam.</p>
            </div>
            <div class="col-md-6">
                <address class="mt-3">
                    <strong>Tên Công Ty Của Bạn: Lo Thi Truc</strong><br>
                    123 Đường Lê Duẩn, Thành Phố Vinh - Nghệ An<br>
                    <abbr title="Điện thoại">ĐT:</abbr> (123) 456-7890
                </address>
            </div>
        </div>
    </div>
@endsection
