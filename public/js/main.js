let cropper;
let scaleX = 1;
let scaleY = 1;

document.getElementById("image").addEventListener("change", function (e) {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = function (event) {
        const img = document.getElementById("previewImage");
        img.src = event.target.result;
        if (cropper) cropper.destroy();
        cropper = new Cropper(img, {
            aspectRatio: NaN,
            viewMode: 1,
        });
    };
    reader.readAsDataURL(file);
});

// rotate image
function rotateImage(deg) {
    if (cropper) {
        cropper.rotate(deg);
    }
}

// flip image
// vertical flip
function flipVertical() {
    if (cropper) {
        scaleY = -scaleY;
        cropper.scale(scaleX, scaleY);
    }
}

// horizontal flip
function flipHorizontal() {
    if (cropper) {
        scaleX = -scaleX;
        cropper.scale(scaleX, scaleY);
    }
}

// zoom in
function zoomIn() {
    if (cropper) {
        cropper.zoom(0.1);
    }
}

// zoom out
function zoomOut() {
    if (cropper) {
        cropper.zoom(-0.1);
    }
}

// center image
function centerImage() {
    const canvasData = cropper.getCanvasData();
    const containerData = cropper.getContainerData();

    const left = (containerData.width - canvasData.width) / 2;
    const top = (containerData.height - canvasData.height) / 2;

    cropper.setCanvasData({
        left: left,
        top: top,
        width: canvasData.width,
        height: canvasData.height,
    });
}

// reset image
function resetAll() {
    cropper.reset(); // Resets zoom, rotate, crop box
    scaleX = 1;
    scaleY = 1;
}

// rest to original
function resetToOriginal(originalUrl) {
    const img = document.getElementById("previewImage");
    img.src = originalUrl;
    if (cropper) cropper.destroy();
    cropper = new Cropper(img, {
        aspectRatio: NaN,
        viewMode: 1,
    });
}

// ✅ Reset back to original uploaded image (not just crop reset)
function resetToOriginal() {
    const img = document.getElementById("previewImage");
    const originalSrc = img.getAttribute("data-original");

    if (originalSrc) {
        const newSrc = originalSrc + "?t=" + new Date().getTime();

        if (cropper) cropper.destroy();

        img.onload = () => {
            cropper = new Cropper(img, {
                aspectRatio: NaN,
                viewMode: 1,
            });
            scaleX = 1;
            scaleY = 1;
        };

        img.src = newSrc;
    } else {
        alert("No original image found.");
    }
}

// upload image
document.getElementById("uploadForm").addEventListener("submit", function (e) {
    e.preventDefault();

    if (!cropper) {
        alert("Upload and crop an image first.");
        return;
    }

    cropper.getCroppedCanvas().toBlob(function (blob) {
        const formData = new FormData();
        const originalFile = document.getElementById("image").files[0];

        formData.append("image", originalFile);
        formData.append("cropped_image", blob, "cropped_" + originalFile.name);

        fetch("/upload", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector("input[name=_token]").value,
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                alert("Image uploaded successfully!");
                console.log(data);
            })
            .catch((error) => {
                console.error("Upload failed:", error);
                alert("Image upload failed.");
            });
    }, "image/jpeg");
});
