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
                                <button onClick="resetToOriginal()" class="btn btn-warning">Original</button>
                            </div>
                            <div class="image">
                                <img id="previewImage" src="{{ asset($image->crop_image) }}"
                                    data-original="{{ asset($image->image) }}" />
                            </div>
                        </div>
                        <div class="card-footer">
                            <form id="updateForm" method="POST" action="{{ route('update') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="image_id" value="{{ $image->id }}">
                                <button type="submit" class="btn btn-primary w-100">Update Cropped Image</button>
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


        <script>
            // Initialize cropper on page load
            window.addEventListener("load", function() {
                const img = document.getElementById("previewImage");
                originalImageURL = img.src;
                cropper = new Cropper(img, {
                    aspectRatio: NaN,
                    viewMode: 1,
                });
            });

            document.getElementById("updateForm").addEventListener("submit", function(e) {
                e.preventDefault();

                if (!cropper) {
                    alert("Update and crop an image first.");
                    return;
                }

                // Get base64 data URL from cropper
                const croppedBase64 = cropper.getCroppedCanvas().toDataURL('image/jpeg');

                // Create FormData object and append necessary data
                const formData = new FormData();
                formData.append("cropped_image", croppedBase64);
                formData.append("image_id", this.querySelector("input[name='image_id']").value);
                formData.append("_token", this.querySelector("input[name='_token']").value);

                fetch(this.action, {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            // Optionally update the preview image src with new cropped image URL
                            document.getElementById("previewImage").src = data.cropped;
                        } else {
                            alert("Update failed: " + (data.message || "Unknown error"));
                        }
                    })
                    .catch(error => {
                        console.error("Update failed:", error);
                        alert("Image update failed.");
                    });
            });
        </script>
        {{-- main script --}}
        <script src="/js/main.js"></script>

    </body>

</html>
