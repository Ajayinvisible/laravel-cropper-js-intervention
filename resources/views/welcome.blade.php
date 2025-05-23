<!doctype html>
<html lang="en">

    <head>
        <title>Laravel Implemet Cropper JS</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- cdn cropper css --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
        <style>
            .card-body {
                height: 450px;
            }

            .card-body .image {
                width: 100%;
                height: 100%;
                overflow: hidden;
                background: #eaeaea;
            }

            .card-body .image img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
        </style>
    </head>

    <body>
        <section class="container mt-5">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header p-3">
                            <h4 class="text-center mb-4">Laravel Implemet Cropper JS</h4>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*" />
                        </div>
                        <div class="card-body d-flex gap-3">
                            <div class="g-buttons d-flex flex-column gap-2 bg-dark p-2 rounded-2">
                                <button onClick="rotateImage(45)" class="btn btn-secondary" title="rotate-left">
                                    <i class="fa-solid fa-rotate-left d-block"></i>
                                </button>
                                <button onClick="rotateImage(-45)" class="btn btn-secondary" title="rotate-right">
                                    <i class="fa-solid fa-rotate-right d-block"></i>
                                </button>
                                <button onClick="flipVertical()" class="btn btn-secondary" title="vertical-flip">
                                    <i class="fa-solid fa-up-down d-block"></i>
                                </button>
                                <button onClick="flipHorizontal()" class="btn btn-secondary" title="horizontal-flip">
                                    <i class="fa-solid fa-left-right d-block"></i>
                                </button>
                                <button onClick="zoomIn()" class="btn btn-success" title="Zoom-in">
                                    <i class="fa-solid fa-magnifying-glass-plus d-block"></i>
                                </button>
                                <button onClick="zoomOut()" class="btn btn-danger" title="zoom-out">
                                    <i class="fa-solid fa-magnifying-glass-minus d-block"></i>
                                </button>
                                <button onClick="centerImage()" class="btn btn-light" title="image-center">
                                    <i class="fa-solid fa-arrows-to-circle d-block"></i>
                                </button>
                                <button onClick="resetAll()" class="btn btn-info" title="reset-default">
                                    <i class="fa-solid fa-arrows-rotate d-block"></i>
                                </button>
                                {{-- edit only --}}
                                {{-- <button class="btn btn-warning"
                                    onClick="resetToOriginal('{{ asset($original_path) }}')">
                                    <i class="fa-solid fa-backward"></i> Reset to Original
                                </button> --}}
                                {{-- edit only --}}
                            </div>
                            <div class="image">
                                <img id="previewImage" />
                            </div>
                        </div>
                        <div class="card-footer">
                            <form id="uploadForm" method="POST" action="{{ route('upload') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Hidden original image input -->
                                <input type="hidden" name="image" id="originalImageNameInput">

                                <!-- Cropped image will be appended as Blob via JS before submission -->
                                <button type="submit" class="btn btn-primary w-100" title="crop-save">
                                    <i class="fa-solid fa-download d-block"></i>
                                    Save Cropped Image
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        {{-- bootstrap js --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
        {{-- cdn cropjs --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

        {{-- main script --}}
        <script src="/js/main.js"></script>
    </body>

</html>
