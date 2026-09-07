<?php

$page_title = "Edit Product - Maker Portal";
$page_header = "Update Homemade Product";
$page_subheader = "Modify details, price, stock availability and product photo";

require_once __DIR__ . '/includes/header.php';


/* ---------------------------------
   GET CURRENT SELLER
--------------------------------- */

$current_user = get_logged_in_user();

if (empty($current_user['id'])) {
    die("Seller login session not found.");
}

$seller_stmt = $pdo->prepare("
    SELECT id
    FROM sellers
    WHERE user_id = ?
    AND status = 'active'
    LIMIT 1
");

$seller_stmt->execute([
    (int) $current_user['id']
]);

$seller = $seller_stmt->fetch(PDO::FETCH_ASSOC);

if (!$seller) {
    die("Seller profile not found.");
}

$seller_id = (int) $seller['id'];


/* ---------------------------------
   PRODUCT ID
--------------------------------- */

$product_id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($product_id <= 0) {
    die("Invalid product ID.");
}


/* ---------------------------------
   GET PRODUCT
--------------------------------- */

$product_stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    AND seller_id = ?
    LIMIT 1
");

$product_stmt->execute([
    $product_id,
    $seller_id
]);

$product = $product_stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found or you do not have permission to edit it.");
}


/* ---------------------------------
   GET CATEGORIES
--------------------------------- */

$categories = get_categories($pdo);

$success_msg = '';
$error_msg = '';


/* ---------------------------------
   UPDATE PRODUCT
--------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p_name = trim($_POST['name'] ?? '');
    $category_name = trim($_POST['category'] ?? '');
    $unit = trim($_POST['unit'] ?? '');

    $price = (float) ($_POST['price'] ?? 0);

    $original_price = !empty($_POST['original_price'])
        ? (float) $_POST['original_price']
        : null;

    $stock = (int) ($_POST['stock'] ?? 0);

    $description = trim($_POST['description'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $features = trim($_POST['features'] ?? '');


    if (
        empty($p_name) ||
        empty($category_name) ||
        empty($unit) ||
        $price <= 0 ||
        $stock < 0 ||
        empty($description)
    ) {

        $error_msg = "Please fill all required fields correctly.";

    } else {

        try {

            /* ---------------------------------
               GET CATEGORY
            --------------------------------- */

            $category_stmt = $pdo->prepare("
                SELECT id
                FROM categories
                WHERE name = ?
                LIMIT 1
            ");

            $category_stmt->execute([
                $category_name
            ]);

            $category = $category_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$category) {

                $error_msg = "Selected category not found.";

            } else {

                $category_id = (int) $category['id'];


                /* ---------------------------------
                   IMAGE UPLOAD
                --------------------------------- */

                $image_paths = [];

                if (
                    isset($_FILES['images']) &&
                    !empty($_FILES['images']['name'][0])
                ) {

                    $upload_dir = __DIR__ . '/../uploads/products/';

                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $allowed_types = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/webp' => 'webp'
                    ];

                    $file_count = count($_FILES['images']['name']);

                    for ($i = 0; $i < $file_count; $i++) {

                        if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                            continue;
                        }

                        $tmp_name = $_FILES['images']['tmp_name'][$i];

                        $file_size = $_FILES['images']['size'][$i];

                        /* Maximum 5 MB */
                        if ($file_size > 5 * 1024 * 1024) {
                            continue;
                        }

                        $finfo = finfo_open(FILEINFO_MIME_TYPE);

                        $mime_type = finfo_file(
                            $finfo,
                            $tmp_name
                        );

                        finfo_close($finfo);

                        if (!isset($allowed_types[$mime_type])) {
                            continue;
                        }

                        $extension = $allowed_types[$mime_type];

                        $new_filename =
                            'product_' .
                            $product_id .
                            '_' .
                            time() .
                            '_' .
                            $i .
                            '.' .
                            $extension;

                        $destination =
                            $upload_dir . $new_filename;

                        if (move_uploaded_file(
                            $tmp_name,
                            $destination
                        )) {

                            $image_paths[] =
                                'uploads/products/' . $new_filename;
                        }
                    }
                }


                /* ---------------------------------
                   IMAGE DATA
                --------------------------------- */

                if (!empty($image_paths)) {

                    $images_json = json_encode(
                        $image_paths,
                        JSON_UNESCAPED_SLASHES
                    );

                } else {

                    /* Keep old images */
                    $images_json = $product['images'];
                }


                /* ---------------------------------
                   UPDATE DATABASE
                --------------------------------- */

                $update_stmt = $pdo->prepare("
                    UPDATE products
                    SET
                        name = ?,
                        category_id = ?,
                        category_name = ?,
                        unit = ?,
                        price = ?,
                        original_price = ?,
                        stock_quantity = ?,
                        description = ?,
                        ingredients = ?,
                        features = ?,
                        images = ?
                    WHERE id = ?
                    AND seller_id = ?
                ");

                $update_stmt->execute([
                    $p_name,
                    $category_id,
                    $category_name,
                    $unit,
                    $price,
                    $original_price,
                    $stock,
                    $description,
                    $ingredients,
                    $features,
                    $images_json,
                    $product_id,
                    $seller_id
                ]);


                /* ---------------------------------
                   REFRESH PRODUCT DATA
                --------------------------------- */

                $product_stmt->execute([
                    $product_id,
                    $seller_id
                ]);

                $product = $product_stmt->fetch(PDO::FETCH_ASSOC);

                $success_msg =
                    "Product '{$p_name}' updated successfully!";
            }

        } catch (PDOException $e) {

            $error_msg =
                "Database Error: " . $e->getMessage();
        }
    }
}

?>


<?php if (!empty($success_msg)): ?>

<div class="alert alert-success d-flex align-items-center gap-2 mb-4">

    <i class="fa-solid fa-circle-check fs-4"></i>

    <div>
        <?php echo htmlspecialchars($success_msg); ?>
    </div>

</div>

<?php endif; ?>


<?php if (!empty($error_msg)): ?>

<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">

    <i class="fa-solid fa-circle-exclamation fs-4"></i>

    <div>
        <?php echo htmlspecialchars($error_msg); ?>
    </div>

</div>

<?php endif; ?>


<div class="row g-4">

    <div class="col-lg-8">

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">

                    <i class="fa-solid fa-pen-to-square text-maroon-800"></i>

                    Edit Product #<?php echo $product['id']; ?>

                </h5>

            </div>


            <div class="p-4">

                <form
                    action="edit-product.php?id=<?php echo $product['id']; ?>"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $product['id']; ?>"
                    >


                    <div class="row g-3">


                        <!-- Product Name -->

                        <div class="col-md-12">

                            <label class="form-label small fw-bold">
                                Product Title *
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($product['name']); ?>"
                                required
                            >

                        </div>


                        <!-- Category -->

                        <div class="col-md-6">

                            <label class="form-label small fw-bold">
                                Category *
                            </label>

                            <select
                                name="category"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <?php foreach ($categories as $c): ?>

                                    <option
                                        value="<?php echo htmlspecialchars($c['name']); ?>"
                                        <?php
                                        echo (
                                            ($product['category_name'] ?? $product['category'] ?? '')
                                            === $c['name']
                                        )
                                            ? 'selected'
                                            : '';
                                        ?>
                                    >

                                        <?php echo htmlspecialchars($c['name']); ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- Unit -->

                        <div class="col-md-6">

                            <label class="form-label small fw-bold">
                                Packaging / Unit *
                            </label>

                            <input
                                type="text"
                                name="unit"
                                class="form-control"
                                value="<?php echo htmlspecialchars($product['unit'] ?? ''); ?>"
                                required
                            >

                        </div>


                        <!-- Price -->

                        <div class="col-md-4">

                            <label class="form-label small fw-bold">
                                Selling Price (₹) *
                            </label>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                value="<?php echo htmlspecialchars($product['price']); ?>"
                                min="1"
                                step="0.01"
                                required
                            >

                        </div>


                        <!-- Original Price -->

                        <div class="col-md-4">

                            <label class="form-label small fw-bold">
                                Original MRP (₹)
                            </label>

                            <input
                                type="number"
                                name="original_price"
                                class="form-control"
                                value="<?php echo htmlspecialchars($product['original_price'] ?? ''); ?>"
                                min="0"
                                step="0.01"
                            >

                        </div>


                        <!-- Stock -->

                        <div class="col-md-4">

                            <label class="form-label small fw-bold">
                                Available Stock *
                            </label>

                            <input
                                type="number"
                                name="stock"
                                class="form-control"
                                value="<?php echo htmlspecialchars($product['stock_quantity'] ?? 0); ?>"
                                min="0"
                                required
                            >

                        </div>


                        <!-- Description -->

                        <div class="col-12">

                            <label class="form-label small fw-bold">
                                Description *
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                                required
                            ><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>

                        </div>


                        <!-- Ingredients -->

                        <div class="col-12">

                            <label class="form-label small fw-bold">
                                Ingredients or Raw Materials
                            </label>

                            <input
                                type="text"
                                name="ingredients"
                                class="form-control"
                                value="<?php echo htmlspecialchars($product['ingredients'] ?? ''); ?>"
                                placeholder="e.g. Mango, Oil, Spices"
                            >

                        </div>


                        <!-- Features -->

                        <div class="col-12">

                            <label class="form-label small fw-bold">
                                Key Homemade Highlights
                            </label>

                            <textarea
                                name="features"
                                class="form-control"
                                rows="3"
                                placeholder="One feature per line"
                            ><?php echo htmlspecialchars($product['features'] ?? ''); ?></textarea>

                        </div>


                        <!-- Replace Image -->

                        <div class="col-12">

                            <label class="form-label small fw-bold">
                                Replace Product Photo
                            </label>

                            <input
                                type="file"
                                id="imageUploadInput"
                                name="images[]"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                                multiple
                            >

                            <small class="text-muted">
                                JPG, PNG or WEBP — Maximum 5 MB per image.
                            </small>

                            <div
                                id="imagePreviewContainer"
                                class="d-flex flex-wrap mt-3"
                            ></div>

                        </div>


                        <!-- Buttons -->

                        <div class="col-12 pt-3 border-top mt-4">

                            <button
                                type="submit"
                                class="btn btn-maroon px-4 fw-bold"
                            >

                                <i class="fa-solid fa-floppy-disk me-1"></i>

                                Save Updates

                            </button>


                            <a
                                href="products.php"
                                class="btn btn-light border ms-2"
                            >

                                Back to Catalog

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- Product Image Preview -->

    <div class="col-lg-4">

        <div class="dashboard-card mb-4">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">

                    <i class="fa-solid fa-image text-terracotta"></i>

                    Product Image

                </h5>

            </div>


            <div class="p-3 text-center">

                <?php

                $current_image = '';

                if (!empty($product['images'])) {

                    $decoded_images =
                        json_decode(
                            $product['images'],
                            true
                        );

                    if (
                        is_array($decoded_images) &&
                        !empty($decoded_images[0])
                    ) {

                        $current_image =
                            $decoded_images[0];
                    }
                }

                ?>


                <?php if (!empty($current_image)): ?>

                    <img
                        src="../<?php echo htmlspecialchars(ltrim($current_image, '/')); ?>"
                        class="img-fluid rounded-4 shadow-sm border mb-3"
                        style="width:100%; height:220px; object-fit:cover;"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                    >

                <?php else: ?>

                    <div
                        class="border rounded-4 d-flex align-items-center justify-content-center mb-3"
                        style="height:220px; background:#f8f8f8;"
                    >

                        <div class="text-muted">

                            <i class="fa-solid fa-image fa-3x mb-2"></i>

                            <div>No product image</div>

                        </div>

                    </div>

                <?php endif; ?>


                <label
                    for="imageUploadInput"
                    class="btn btn-sm btn-outline-secondary w-100"
                >

                    <i class="fa-solid fa-camera me-1"></i>

                    Replace Photo

                </label>

            </div>

        </div>

    </div>

</div>


<script>

document
    .getElementById('imageUploadInput')
    .addEventListener('change', function () {

        const container =
            document.getElementById('imagePreviewContainer');

        container.innerHTML = '';

        Array.from(this.files).forEach(function (file) {

            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'me-2 mb-2';

                wrapper.style.width =
                    '110px';

                wrapper.style.height =
                    '110px';

                wrapper.style.overflow =
                    'hidden';

                wrapper.style.borderRadius =
                    '10px';

                wrapper.style.border =
                    '1px solid #ddd';


                const img =
                    document.createElement('img');

                img.src =
                    e.target.result;

                img.style.width =
                    '100%';

                img.style.height =
                    '100%';

                img.style.objectFit =
                    'cover';


                wrapper.appendChild(img);

                container.appendChild(wrapper);

            };

            reader.readAsDataURL(file);

        });

    });

</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>