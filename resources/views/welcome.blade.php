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
                    <div class="card">
                        <div class="card-header p-3">
                            <h4 class="text-center mb-4">Laravel Implement Cropper JS</h4>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*" />
                        </div>
                        <div class="card-body d-flex gap-3">
                            <div class="g-buttons d-flex flex-column gap-2 bg-dark p-2 rounded-2">
                                <button onClick="rotateImage(45)" class="btn btn-secondary">↻</button>
                                <button onClick="rotateImage(-45)" class="btn btn-secondary">↺</button>
                                <button onClick="flipVertical()" class="btn btn-secondary">⇅</button>
                                <button onClick="flipHorizontal()" class="btn btn-secondary">⇆</button>
                                <button onClick="zoomIn()" class="btn btn-success">+</button>
                                <button onClick="zoomOut()" class="btn btn-danger">-</button>
                                <button onClick="centerImage()" class="btn btn-light">Center</button>
                                <button onClick="resetAll()" class="btn btn-info">Reset</button>
                            </div>
                            <div class="image">
                                <img id="previewImage" />
                            </div>
                        </div>
                        <div class="card-footer">
                            <form id="uploadForm" method="POST" action="{{ route('upload') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">Save Cropped Image</button>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <table>
                    <thead>
                        <tr>
                            <th>s.n</th>
                            <th>Original Image</th>
                            <th>Crop Image</th>
                            <th>Passport Size Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $key => $image)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <img src="{{ asset($image->image) }}" alt="" width="200">
                                </td>
                                <td>
                                    <img src="{{ asset($image->crop_image) }}" alt="" width="200">
                                </td>
                                <td>
                                    <img src="{{ asset($image->passport_image) }}" alt="" width="200">
                                </td>
                                <td>
                                    <a href="{{ route('edit', $image->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('delete', $image->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
