<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

/*
|--------------------------------------------------------------------------
| Check Seller Login
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$seller_id = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Variables
|--------------------------------------------------------------------------
*/

$success = '';
$error = '';

$business = [
    'business_logo'        => '',
    'business_name'        => '',
    'business_category'   => '',
    'short_bio'            => '',
    'business_description'=> '',
    'owner_name'           => '',
    'phone'                => '',
    'email'                => '',
    'address'              => '',
    'city'                 => '',
    'state'                => '',
    'pincode'              => '',
    'established_year'     => ''
];

/*
|--------------------------------------------------------------------------
| Save / Update Business Profile
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $business_name         = trim($_POST['business_name'] ?? '');
    $business_category    = trim($_POST['business_category'] ?? '');
    $short_bio             = trim($_POST['short_bio'] ?? '');
    $business_description  = trim($_POST['business_description'] ?? '');
    $owner_name            = trim($_POST['owner_name'] ?? '');
    $phone                 = trim($_POST['phone'] ?? '');
    $email                 = trim($_POST['email'] ?? '');
    $address               = trim($_POST['address'] ?? '');
    $city                  = trim($_POST['city'] ?? '');
    $state                 = trim($_POST['state'] ?? '');
    $pincode               = trim($_POST['pincode'] ?? '');
    $established_year      = trim($_POST['established_year'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($business_name === '') {
        $error = "Business name is required.";
    } elseif ($business_category === '') {
        $error = "Please select a business category.";
    } elseif ($short_bio === '') {
        $error = "Short bio is required.";
    } elseif ($owner_name === '') {
        $error = "Owner name is required.";
    } elseif ($phone === '') {
        $error = "Phone number is required.";
    } elseif ($email === '') {
        $error = "Email address is required.";
    }

    /*
    |--------------------------------------------------------------------------
    | Logo Upload
    |--------------------------------------------------------------------------
    */

    $logo_path = $business['business_logo'];

    if (
        empty($error) &&
        isset($_FILES['business_logo']) &&
        $_FILES['business_logo']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['business_logo']['error'] !== UPLOAD_ERR_OK) {
            $error = "There was a problem uploading the logo.";
        } else {

            $allowed_types = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            $file_type = mime_content_type($_FILES['business_logo']['tmp_name']);

            if (!in_array($file_type, $allowed_types, true)) {
                $error = "Only JPG, PNG and WEBP images are allowed.";
            } elseif ($_FILES['business_logo']['size'] > 2 * 1024 * 1024) {
                $error = "Logo size must be less than 2MB.";
            } else {

                $upload_dir = __DIR__ . '/../uploads/business/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $extension = strtolower(
                    pathinfo(
                        $_FILES['business_logo']['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $file_name = 'business_' . $seller_id . '_' . time() . '.' . $extension;

                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file(
                    $_FILES['business_logo']['tmp_name'],
                    $target_file
                )) {

                    $logo_path = 'uploads/business/' . $file_name;

                } else {
                    $error = "Unable to save the business logo.";
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Insert / Update
    |--------------------------------------------------------------------------
    */

    if (empty($error)) {

        try {

            // Check whether seller already has a business profile
            $check = $pdo->prepare("
                SELECT id
                FROM business
                WHERE seller_id = ?
                LIMIT 1
            ");

            $check->execute([$seller_id]);

            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {

                /*
                | UPDATE existing profile
                */

                $sql = "
                    UPDATE business
                    SET
                        business_logo = ?,
                        business_name = ?,
                        business_category = ?,
                        short_bio = ?,
                        business_description = ?,
                        owner_name = ?,
                        phone = ?,
                        email = ?,
                        address = ?,
                        city = ?,
                        state = ?,
                        pincode = ?,
                        established_year = ?
                    WHERE seller_id = ?
                ";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    $logo_path,
                    $business_name,
                    $business_category,
                    $short_bio,
                    $business_description,
                    $owner_name,
                    $phone,
                    $email,
                    $address,
                    $city,
                    $state,
                    $pincode,
                    $established_year !== '' ? $established_year : null,
                    $seller_id
                ]);

                $success = "Business profile updated successfully.";

            } else {

                /*
                | INSERT new profile
                */

                $sql = "
                    INSERT INTO business (
                        seller_id,
                        business_logo,
                        business_name,
                        business_category,
                        short_bio,
                        business_description,
                        owner_name,
                        phone,
                        email,
                        address,
                        city,
                        state,
                        pincode,
                        established_year
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    $seller_id,
                    $logo_path,
                    $business_name,
                    $business_category,
                    $short_bio,
                    $business_description,
                    $owner_name,
                    $phone,
                    $email,
                    $address,
                    $city,
                    $state,
                    $pincode,
                    $established_year !== '' ? $established_year : null
                ]);

                $success = "Business profile created successfully.";
            }

        } catch (PDOException $e) {

            $error = "Unable to save business profile. Please try again.";

            // For development only:
            // $error = $e->getMessage();
        }
    }
}

/*
|--------------------------------------------------------------------------
| Load Current Business Profile
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM business
    WHERE seller_id = ?
    LIMIT 1
");

$stmt->execute([$seller_id]);

$saved_business = $stmt->fetch(PDO::FETCH_ASSOC);

if ($saved_business) {
    $business = array_merge($business, $saved_business);
}


/*
|--------------------------------------------------------------------------
| Dashboard Header
|--------------------------------------------------------------------------
*/

$page_title = "Business Profile";
$page_header = "Business Profile";
$page_subheader = "Manage your business information and profile details.";

require_once __DIR__ . '/includes/header.php';

?>

<style>

.business-page {
    margin: 0 auto;
    padding: 25px;
}

.business-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px;
    margin-bottom: 22px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 5px;
}

.section-description {
    color: #777;
    font-size: 14px;
    margin-bottom: 25px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #7c3aed;
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 25px;
}

.logo-preview {
    width: 120px;
    height: 120px;
    border-radius: 14px;
    border: 2px dashed #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #f8f8f8;
}

.logo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.logo-placeholder {
    color: #999;
    font-size: 13px;
    text-align: center;
}

.logo-input {
    flex: 1;
}

.logo-input input {
    margin-top: 8px;
}

.alert {
    padding: 13px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: #eaf8ef;
    color: #18733c;
}

.alert-error {
    background: #fff0f0;
    color: #b42318;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 10px;
}

.btn {
    padding: 12px 22px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.btn-primary {
    background: #7c3aed;
    color: white;
}

.btn-primary:hover {
    background: #6d28d9;
}

.btn-secondary {
    background: #eee;
    color: #333;
}

@media (max-width: 700px) {

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }

    .logo-section {
        flex-direction: column;
        align-items: flex-start;
    }

    .business-page {
        padding: 15px;
    }
}

</style>


<div class="business-page">

    <?php if ($success): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>


    <?php if ($error): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>


    <form method="POST" enctype="multipart/form-data">


        <!-- =====================================================
             BUSINESS INFORMATION
        ====================================================== -->

        <div class="business-card">

            <div class="section-title">
                Business Information
            </div>

            <div class="section-description">
                Add the basic information about your business.
            </div>


            <!-- Logo -->

            <div class="logo-section">

                <div class="logo-preview">

                    <?php if (!empty($business['business_logo'])): ?>

    <img
        src="<?= '../' . htmlspecialchars($business['business_logo']) ?>"
        alt="Business Logo"
        style="width:100%; height:100%; object-fit:cover;"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
    >

    <div
        class="logo-placeholder"
        style="display:none;"
    >
        No Logo
    </div>

<?php else: ?>

    <div class="logo-placeholder">
        No Logo
    </div>

<?php endif; ?>

                </div>


                <div class="logo-input">

                    <label>
                        Business Logo
                    </label>

                    <input
                        type="file"
                        name="business_logo"
                        accept="image/png,image/jpeg,image/webp"
                    >

                    <small>
                        JPG, PNG or WEBP. Maximum 2MB.
                    </small>

                </div>

            </div>

            <br>


            <div class="form-grid">

                <div class="form-group">

                    <label>
                        Business Name *
                    </label>

                    <input
                        type="text"
                        name="business_name"
                        value="<?= htmlspecialchars($business['business_name']) ?>"
                        placeholder="Enter business name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Business Category *
                    </label>

                    <select name="business_category" required>

                        <option value="">
                            Select Category
                        </option>

                        <?php

                        $categories = [
                            'Homemade Food',
                            'Pickles & Spices',
                            'Snacks',
                            'Bakery',
                            'Handicrafts',
                            'Clothing',
                            'Jewellery',
                            'Beauty & Personal Care',
                            'Home Decor',
                            'Other'
                        ];

                        foreach ($categories as $category):

                        ?>

                            <option
                                value="<?= htmlspecialchars($category) ?>"
                                <?= $business['business_category'] === $category ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($category) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="form-group full">

                    <label>
                        Short Bio *
                    </label>

                    <input
                        type="text"
                        name="short_bio"
                        maxlength="300"
                        value="<?= htmlspecialchars($business['short_bio']) ?>"
                        placeholder="Example: Authentic homemade pickles and snacks made with love."
                        required
                    >

                    <small>
                        This will be displayed on your business cards.
                    </small>

                </div>


                <div class="form-group full">

                    <label>
                        About Your Business
                    </label>

                    <textarea
                        name="business_description"
                        placeholder="Tell customers about your business, products and story..."
                    ><?= htmlspecialchars($business['business_description']) ?></textarea>

                </div>

            </div>

        </div>


        <!-- =====================================================
             OWNER & CONTACT
        ====================================================== -->

        <div class="business-card">

            <div class="section-title">
                Owner & Contact
            </div>

            <div class="section-description">
                Provide contact information for your business.
            </div>


            <div class="form-grid">

                <div class="form-group">

                    <label>
                        Owner Name *
                    </label>

                    <input
                        type="text"
                        name="owner_name"
                        value="<?= htmlspecialchars($business['owner_name']) ?>"
                        placeholder="Enter owner name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Phone Number *
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="<?= htmlspecialchars($business['phone']) ?>"
                        placeholder="Enter phone number"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label>
                        Email Address *
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($business['email']) ?>"
                        placeholder="Enter email address"
                        required
                    >

                </div>

            </div>

        </div>


        <!-- =====================================================
             BUSINESS LOCATION
        ====================================================== -->

        <div class="business-card">

            <div class="section-title">
                Business Location
            </div>

            <div class="section-description">
                Add your business location details.
            </div>


            <div class="form-grid">

                <div class="form-group full">

                    <label>
                        Address
                    </label>

                    <textarea
                        name="address"
                        placeholder="Enter complete business address"
                    ><?= htmlspecialchars($business['address']) ?></textarea>

                </div>


                <div class="form-group">

                    <label>
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="<?= htmlspecialchars($business['city']) ?>"
                        placeholder="Enter city"
                    >

                </div>


                <div class="form-group">

                    <label>
                        State
                    </label>

                    <input
                        type="text"
                        name="state"
                        value="<?= htmlspecialchars($business['state']) ?>"
                        placeholder="Enter state"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Pincode
                    </label>

                    <input
                        type="text"
                        name="pincode"
                        maxlength="10"
                        value="<?= htmlspecialchars($business['pincode']) ?>"
                        placeholder="Enter pincode"
                    >

                </div>

            </div>

        </div>


        <!-- =====================================================
             BUSINESS DETAILS
        ====================================================== -->

        <div class="business-card">

            <div class="section-title">
                Business Details
            </div>

            <div class="section-description">
                Additional information about your business.
            </div>


            <div class="form-grid">

                <div class="form-group">

                    <label>
                        Established Year
                    </label>

                    <input
                        type="number"
                        name="established_year"
                        min="1900"
                        max="<?= date('Y') ?>"
                        value="<?= htmlspecialchars($business['established_year']) ?>"
                        placeholder="Example: 2022"
                    >

                </div>

            </div>

        </div>


        <!-- =====================================================
             ACTION BUTTONS
        ====================================================== -->

        <div class="form-actions">

            <button
                type="reset"
                class="btn btn-secondary"
            >
                Reset
            </button>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Business Profile
            </button>

        </div>

    </form>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>