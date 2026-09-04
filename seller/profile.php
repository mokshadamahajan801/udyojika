<?php

$page_title = "Seller Profile - Udyोजिका";
$page_header = "Seller Profile";
$page_subheader = "Manage your personal information and account details";

require_once __DIR__ . '/includes/header.php';

$success_msg = '';
$error_msg = '';

/*
|--------------------------------------------------------------------------
| Seller Profile Update
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = (int) ($current_user['id'] ?? 0);

    if ($user_id <= 0) {

        $error_msg = "Invalid seller account.";

    } else {

        try {

            /*
            |--------------------------------------------------------------------------
            | Get Personal Information
            |--------------------------------------------------------------------------
            */

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $gender = trim($_POST['gender'] ?? '');
            $date_of_birth = trim($_POST['date_of_birth'] ?? '');
            $address = trim($_POST['address'] ?? '');


            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            if ($name === '') {

                throw new Exception("Full name is required.");

            }

            if ($email === '') {

                throw new Exception("Email address is required.");

            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                throw new Exception("Please enter a valid email address.");

            }

            if ($phone === '') {

                throw new Exception("Mobile number is required.");

            }


            /*
            |--------------------------------------------------------------------------
            | Profile Photo Upload
            |--------------------------------------------------------------------------
            */

            if (
                isset($_FILES['avatar']) &&
                $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE
            ) {

                if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {

                    throw new Exception("Unable to upload profile photo.");

                }


                $file = $_FILES['avatar'];


                /*
                |--------------------------------------------------------------------------
                | File Type
                |--------------------------------------------------------------------------
                */

                $allowed_types = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                $file_info = finfo_open(FILEINFO_MIME_TYPE);

                $mime_type = finfo_file(
                    $file_info,
                    $file['tmp_name']
                );

                finfo_close($file_info);


                if (!in_array($mime_type, $allowed_types, true)) {

                    throw new Exception(
                        "Only JPG, PNG and WEBP images are allowed."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | File Size
                |--------------------------------------------------------------------------
                */

                if ($file['size'] > 2 * 1024 * 1024) {

                    throw new Exception(
                        "Profile photo must be less than 2 MB."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Upload Folder
                |--------------------------------------------------------------------------
                */

                $upload_dir = __DIR__ . '/uploads/profile/';


                if (!is_dir($upload_dir)) {

                    if (!mkdir($upload_dir, 0755, true)) {

                        throw new Exception(
                            "Unable to create profile upload folder."
                        );

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Extension
                |--------------------------------------------------------------------------
                */

                $extension_map = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp'
                ];

                $extension = $extension_map[$mime_type];


                /*
                |--------------------------------------------------------------------------
                | New File Name
                |--------------------------------------------------------------------------
                */

                $new_file_name =
                    'seller_' .
                    $user_id .
                    '_' .
                    time() .
                    '.' .
                    $extension;


                $target_file =
                    $upload_dir .
                    $new_file_name;


                /*
                |--------------------------------------------------------------------------
                | Move File
                |--------------------------------------------------------------------------
                */

                if (
                    !move_uploaded_file(
                        $file['tmp_name'],
                        $target_file
                    )
                ) {

                    throw new Exception(
                        "Unable to save profile photo."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Database Avatar Path
                |--------------------------------------------------------------------------
                */

                $avatar_path =
                    'uploads/profile/' .
                    $new_file_name;


                /*
                |--------------------------------------------------------------------------
                | Delete Old Photo
                |--------------------------------------------------------------------------
                */

                if (!empty($current_user['avatar'])) {

                    $old_avatar =
                        __DIR__ .
                        '/' .
                        ltrim(
                            $current_user['avatar'],
                            '/'
                        );


                    if (
                        file_exists($old_avatar) &&
                        is_file($old_avatar)
                    ) {

                        @unlink($old_avatar);

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Update Avatar
                |--------------------------------------------------------------------------
                */

                $stmt = $pdo->prepare("
                    UPDATE users
                    SET avatar = ?
                    WHERE id = ?
                    AND role = 'seller'
                ");

                $stmt->execute([
                    $avatar_path,
                    $user_id
                ]);

                if ($stmt->rowCount() === 0) {
    throw new Exception("Profile photo database मध्ये save झाली नाही.");
}

            }


            /*
            |--------------------------------------------------------------------------
            | Update Personal Information
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                UPDATE users
                SET
                    name = ?,
                    email = ?,
                    phone = ?,
                    gender = ?,
                    date_of_birth = ?,
                    address = ?
                WHERE id = ?
                AND role = 'seller'
            ");


            $stmt->execute([
                $name,
                $email,
                $phone,
                $gender !== '' ? $gender : null,
                $date_of_birth !== '' ? $date_of_birth : null,
                $address !== '' ? $address : null,
                $user_id
            ]);


            /*
            |--------------------------------------------------------------------------
            | Refresh User Data
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                SELECT *
                FROM users
                WHERE id = ?
                LIMIT 1
            ");

            $stmt->execute([
                $user_id
            ]);

            $updated_user =
                $stmt->fetch(PDO::FETCH_ASSOC);


            if ($updated_user) {

                $current_user = $updated_user;

                $_SESSION['user'] = $updated_user;

            }


            $success_msg =
                "Your profile has been updated successfully!";


        } catch (Exception $e) {

            $error_msg =
                $e->getMessage();

            error_log(
                "Seller Profile Error: " .
                $e->getMessage()
            );

        }

    }

}

?>

<style>
    /* =========================================================
   PROFESSIONAL CENTERED PROFILE PHOTO
========================================================= */

.seller-profile-photo-section {
    width: 100%;
    display: flex;
    justify-content: center;
    text-align: center;

    padding: 10px 10px 25px;
}


/* Main Photo Area */

.seller-photo-main {
    display: flex;
    flex-direction: column;
    align-items: center;
}


/* Circle Wrapper */

.seller-photo-circle-wrapper {
    position: relative;

    width: 150px;
    height: 150px;

    margin-bottom: 16px;
}


/* Profile Image */

.seller-profile-photo {
    width: 150px;
    height: 150px;

    display: block;

    object-fit: cover;

    border-radius: 50%;

    border: 6px solid #ffffff;

    background: #f8f1f3;

    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.14),
        0 0 0 1px rgba(123, 23, 49, 0.12);

    transition: all 0.25s ease;
}


/* Image Hover */

.seller-profile-photo:hover {
    transform: scale(1.02);

    box-shadow:
        0 12px 30px rgba(0, 0, 0, 0.18),
        0 0 0 2px rgba(123, 23, 49, 0.12);
}


/* Placeholder */

.seller-profile-placeholder {
    width: 150px;
    height: 150px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    border: 6px solid #ffffff;

    background:
        linear-gradient(
            135deg,
            #f8eeee,
            #eee0e4
        );

    color: #8a5964;

    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.14),
        0 0 0 1px rgba(123, 23, 49, 0.12);
}


.seller-profile-placeholder i {
    font-size: 55px;
}


/* =========================================================
   CAMERA BUTTON
========================================================= */

.seller-camera-button {
    position: absolute;

    right: 1px;
    bottom: 4px;

    width: 45px;
    height: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #7b1731;

    color: #ffffff;

    border: 4px solid #ffffff;

    cursor: pointer;

    box-shadow:
        0 5px 15px rgba(0, 0, 0, 0.22);

    transition:
        transform 0.2s ease,
        background 0.2s ease,
        box-shadow 0.2s ease;
}


/* Camera Hover */

.seller-camera-button:hover {
    background: #5f1026;

    transform: scale(1.10);

    box-shadow:
        0 7px 18px rgba(0, 0, 0, 0.28);
}


/* Camera Icon */

.seller-camera-button i {
    font-size: 16px;
}


/* =========================================================
   PHOTO TEXT
========================================================= */

.seller-photo-heading {
    margin: 0;

    color: #3d1f26;

    font-size: 17px;

    font-weight: 700;
}


.seller-photo-description {
    margin: 5px 0 4px;

    max-width: 350px;

    color: #817478;

    font-size: 12px;

    line-height: 1.5;
}


.seller-photo-help {
    color: #a09598;

    font-size: 11px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 575px) {

    .seller-photo-circle-wrapper {
        width: 130px;
        height: 130px;
    }


    .seller-profile-photo,
    .seller-profile-placeholder {
        width: 130px;
        height: 130px;
    }


    .seller-profile-placeholder i {
        font-size: 45px;
    }


    .seller-camera-button {
        width: 42px;
        height: 42px;

        right: 0;
        bottom: 2px;
    }

}

/* =========================================================
   SELLER PROFILE - PROFESSIONAL ICONS
========================================================= */

.seller-input-icon {
    color: #7b1731 !important;
    font-size: 15px;
}

.seller-card-header i {
    color: #7b1731 !important;
}

.seller-account-icon {
    color: #7b1731 !important;
}

.seller-account-label i {
    color: #7b1731 !important;
}


/* =========================================================
   SELLER ACCOUNT - GREEN ACTIVE STATUS
========================================================= */

.seller-status-box {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 14px 16px;

    margin-bottom: 18px;

    background: #eaf8ef;

    border: 1px solid #b9e6c7;

    border-radius: 12px;

    color: #176b35;
}

.seller-status-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #d5f2df;

    border-radius: 50%;

    color: #198754 !important;

    flex-shrink: 0;
}

.seller-status-icon i {
    color: #198754 !important;
    font-size: 17px;
}

.seller-status-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.seller-status-content strong {
    color: #176b35;
    font-size: 14px;
    font-weight: 700;
}

.seller-status-content span {
    color: #198754;
    font-size: 13px;
    font-weight: 600;
}

.seller-status-dot {
    width: 8px;
    height: 8px;

    display: inline-block;

    margin-right: 5px;

    background: #198754;

    border-radius: 50%;
}


/* =========================================================
   SELLER ACCOUNT ITEMS
========================================================= */

.seller-account-item {
    display: flex;
    align-items: center;

    gap: 12px;

    padding: 13px 0;

    border-bottom: 1px solid #eee5e7;
}

.seller-account-item:last-child {
    border-bottom: none;
}

.seller-account-item > i,
.seller-account-icon {
    width: 35px;
    height: 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #f8eef1;

    border-radius: 9px;

    color: #7b1731 !important;

    flex-shrink: 0;
}

.seller-account-label {
    color: #817478;
    font-size: 12px;
    margin-bottom: 2px;
}

.seller-account-value {
    color: #2f2528;
    font-size: 14px;
    font-weight: 600;
}

.seller-role-badge {
    display: inline-block;

    padding: 5px 11px;

    background: #f8eef1;

    color: #7b1731;

    border-radius: 20px;

    font-size: 12px;

    font-weight: 700;
}


/* =========================================================
   SECURITY MESSAGE
========================================================= */

.seller-account-footer {
    margin-top: 16px;

    padding: 12px 14px;

    background: #f0faf3;

    border: 1px solid #d3eedb;

    border-radius: 10px;

    color: #287544;

    font-size: 12px;
}

.seller-account-footer i {
    color: #198754 !important;
    margin-right: 6px;
}


/* =========================================================
   FORM ICONS
========================================================= */

.seller-input-wrapper i,
.seller-input-icon,
.seller-textarea-wrapper i {
    color: #7b1731 !important;
}

.seller-input-wrapper:hover i,
.seller-input-wrapper:focus-within i {
    color: #5f1026 !important;
}


/* =========================================================
   PHOTO CAMERA
========================================================= */

.seller-camera-button {
    background: #7b1731 !important;
    color: #ffffff !important;
}

.seller-camera-button i {
    color: #ffffff !important;
}

/* =========================================================
   PERSONAL INFORMATION - ICON + LABEL IN ONE LINE
========================================================= */

.seller-input-wrapper {
    position: relative;
}

.seller-input-wrapper .seller-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);

    color: #7b1731 !important;
    z-index: 2;
}

.seller-input-wrapper .seller-profile-input,
.seller-input-wrapper .seller-profile-select {
    padding-left: 40px;
}


/* =========================================================
   SELLER ACCOUNT - ICON + CONTENT IN ONE LINE
========================================================= */

.seller-account-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.seller-account-item .seller-account-icon {
    flex: 0 0 36px;

    width: 36px;
    height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #f8eef1;
    color: #7b1731 !important;

    border-radius: 9px;
}

.seller-account-item .seller-account-content {
    display: flex;
    align-items: center;
    gap: 8px;

    flex: 1;
}

.seller-account-item .seller-account-label {
    margin: 0;

    min-width: 105px;

    color: #817478;
    font-size: 13px;
    font-weight: 600;
}

.seller-account-item .seller-account-value {
    margin: 0;

    color: #2f2528;
    font-size: 14px;
    font-weight: 600;
}


/* =========================================================
   ACTIVE STATUS - ONE LINE
========================================================= */

.seller-status-box {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 13px 15px;

    background: #eaf8ef;
    border: 1px solid #b9e6c7;
    border-radius: 12px;

    color: #176b35;
}

.seller-status-content {
    display: flex;
    align-items: center;
    gap: 6px;
}

.seller-status-content strong {
    color: #176b35;
    font-size: 14px;
}

.seller-status-content span {
    color: #198754;
    font-size: 14px;
    font-weight: 700;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 575px) {

    .seller-account-item .seller-account-content {
        gap: 5px;
    }

    .seller-account-item .seller-account-label {
        min-width: 85px;
    }

}

/* =========================================================
   SELLER ACCOUNT - PERFECT HORIZONTAL ALIGNMENT
========================================================= */

.seller-account-item {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 12px 0;
    border-bottom: 1px solid #eee5e7;
}

.seller-account-icon {
    width: 36px !important;
    height: 36px !important;

    min-width: 36px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    background: #f8eef1 !important;
    color: #7b1731 !important;

    border-radius: 9px;
}

.seller-account-icon i {
    color: #7b1731 !important;
    font-size: 14px;
}

.seller-account-content {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;

    flex: 1;
}

.seller-account-label {
    margin: 0 !important;

    min-width: 105px;

    color: #817478 !important;

    font-size: 13px;
    font-weight: 600;
}

.seller-account-value {
    margin: 0 !important;

    color: #2f2528 !important;

    font-size: 14px;
    font-weight: 700;
}

.seller-role-badge {
    margin: 0 !important;
}


/* =========================================================
   ACTIVE STATUS - GREEN
========================================================= */

.seller-status-box {
    display: flex !important;
    align-items: center !important;

    gap: 12px;

    padding: 13px 15px;

    background: #eaf8ef !important;

    border: 1px solid #b9e6c7 !important;

    border-radius: 12px;

    color: #176b35 !important;
}

.seller-status-icon {
    width: 38px !important;
    height: 38px !important;

    min-width: 38px;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    background: #d5f2df !important;

    color: #198754 !important;

    border-radius: 50%;
}

.seller-status-icon i {
    color: #198754 !important;
}

.seller-status-content {
    display: flex !important;
    align-items: center !important;

    gap: 6px !important;
}

.seller-status-content strong {
    color: #176b35 !important;
}

.seller-status-content span {
    color: #198754 !important;
    font-weight: 700;
}

.seller-status-dot {
    margin-left: auto;
    width: 9px;
    height: 9px;

    background: #198754 !important;

    border-radius: 50%;
}



</style>

<!-- =========================================================
     PROFILE PAGE
========================================================== -->

<div class="seller-profile-page">


    <!-- =====================================================
         ALERTS
    ====================================================== -->

    <?php if (!empty($success_msg)): ?>

        <div class="alert alert-success seller-profile-alert d-flex align-items-center gap-3 mb-4">

            <div class="seller-alert-icon success">

                <i class="fa-solid fa-check"></i>

            </div>

            <div>

                <strong class="d-block">
                    Success
                </strong>

                <span>
                    <?php echo htmlspecialchars($success_msg); ?>
                </span>

            </div>

        </div>

    <?php endif; ?>


    <?php if (!empty($error_msg)): ?>

        <div class="alert alert-danger seller-profile-alert d-flex align-items-center gap-3 mb-4">

            <div class="seller-alert-icon danger">

                <i class="fa-solid fa-exclamation"></i>

            </div>

            <div>

                <strong class="d-block">
                    Something went wrong
                </strong>

                <span>
                    <?php echo htmlspecialchars($error_msg); ?>
                </span>

            </div>

        </div>

    <?php endif; ?>



    <div class="row g-4">


        <!-- =================================================
             PERSONAL INFORMATION
        ================================================== -->

        <div class="col-lg-8">

            <div class="dashboard-card seller-profile-card">


                <!-- Card Header -->

                <div class="dashboard-card-header seller-card-header">

                    <div>

                        <h5 class="dashboard-card-title mb-1">

                            <i class="fa-solid fa-user-gear text-maroon-800"></i>

                            Personal Information

                        </h5>

                        <p class="seller-card-subtitle mb-0">
                            Keep your personal details up to date
                        </p>

                    </div>

                </div>



                <div class="p-4">


                    <form
                        action="profile.php"
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <div class="row g-3">


                            <!-- =========================================================
     PROFILE PHOTO - CENTERED
========================================================== -->

<div class="col-12">

    <div class="seller-profile-photo-section">

        <!-- Profile Photo -->

        <div class="seller-photo-main">

            <div class="seller-photo-circle-wrapper">

                <?php if (!empty($current_user['avatar'])): ?>

<img
    id="profilePreview"
    src="<?php echo htmlspecialchars(
        './' . ltrim($current_user['avatar'], '/')
    ); ?>"
    alt="Seller Profile Photo"
    class="seller-profile-photo"
>

<?php else: ?>

                    <div
                        id="profilePlaceholder"
                        class="seller-profile-placeholder"
                    >
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <img
                        id="profilePreview"
                        src=""
                        alt="Profile Preview"
                        class="seller-profile-photo d-none"
                    >

                <?php endif; ?>


                <!-- Camera Icon -->

                <label
                    for="avatar"
                    class="seller-camera-button"
                    title="Edit Profile Photo"
                >

                    <i class="fa-solid fa-camera"></i>

                </label>

            </div>


            <!-- Hidden Upload -->

            <input
                type="file"
                name="avatar"
                id="avatar"
                class="d-none"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >


            <h6 class="seller-photo-heading">
                Profile Photo
            </h6>

            <p class="seller-photo-description">
                Click the camera icon to change your profile photo.
            </p>

            <small class="seller-photo-help">
                JPG, PNG or WEBP • Maximum 2 MB
            </small>

        </div>

    </div>

</div>



                            <!-- =================================================
                                 FULL NAME
                            ================================================== -->

                            <div class="col-md-6">

                                <label class="form-label small fw-bold">

                                    Full Name
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="seller-input-wrapper">

                                    <i class="fa-solid fa-user seller-input-icon"></i>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control seller-profile-input"
                                        value="<?php echo htmlspecialchars($current_user['name'] ?? ''); ?>"
                                        placeholder="Enter your full name"
                                        required
                                    >

                                </div>

                            </div>



                            <!-- =================================================
                                 EMAIL
                            ================================================== -->

                            <div class="col-md-6">

                                <label class="form-label small fw-bold">

                                    Email Address
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="seller-input-wrapper">

                                    <i class="fa-solid fa-envelope seller-input-icon"></i>

                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control seller-profile-input"
                                        value="<?php echo htmlspecialchars($current_user['email'] ?? ''); ?>"
                                        placeholder="Enter your email"
                                        required
                                    >

                                </div>

                            </div>



                            <!-- =================================================
                                 MOBILE
                            ================================================== -->

                            <div class="col-md-6">

                                <label class="form-label small fw-bold">

                                    Mobile Number
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="seller-input-wrapper">

                                    <i class="fa-solid fa-phone seller-input-icon"></i>

                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control seller-profile-input"
                                        value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>"
                                        placeholder="Enter mobile number"
                                        required
                                    >

                                </div>

                            </div>



                            <!-- =================================================
                                 GENDER
                            ================================================== -->

                            <div class="col-md-6">

                                <label class="form-label small fw-bold">

                                    Gender

                                </label>

                                <div class="seller-input-wrapper">

                                    <i class="fa-solid fa-venus-mars seller-input-icon"></i>

                                    <select
                                        name="gender"
                                        class="form-control seller-profile-input seller-profile-select"
                                    >

                                        <option value="">
                                            Select Gender
                                        </option>

                                        <option
                                            value="Female"
                                            <?php
                                            echo (
                                                ($current_user['gender'] ?? '') === 'Female'
                                            )
                                            ? 'selected'
                                            : '';
                                            ?>
                                        >
                                            Female
                                        </option>

                                        <option
                                            value="Male"
                                            <?php
                                            echo (
                                                ($current_user['gender'] ?? '') === 'Male'
                                            )
                                            ? 'selected'
                                            : '';
                                            ?>
                                        >
                                            Male
                                        </option>

                                        <option
                                            value="Other"
                                            <?php
                                            echo (
                                                ($current_user['gender'] ?? '') === 'Other'
                                            )
                                            ? 'selected'
                                            : '';
                                            ?>
                                        >
                                            Other
                                        </option>

                                    </select>

                                </div>

                            </div>



                            <!-- =================================================
                                 DATE OF BIRTH
                            ================================================== -->

                            <div class="col-md-6">

                                <label class="form-label small fw-bold">

                                    Date of Birth

                                </label>

                                <div class="seller-input-wrapper">

                                    <i class="fa-solid fa-calendar-days seller-input-icon"></i>

                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        class="form-control seller-profile-input"
                                        value="<?php echo htmlspecialchars($current_user['date_of_birth'] ?? ''); ?>"
                                    >

                                </div>

                            </div>



                            <!-- =================================================
                                 ADDRESS
                            ================================================== -->

                            <div class="col-md-6">

                                <label class="form-label small fw-bold">

                                    Personal Address

                                </label>

                                <div class="seller-input-wrapper seller-textarea-wrapper">

                                    <i class="fa-solid fa-location-dot seller-input-icon"></i>

                                    <textarea
                                        name="address"
                                        class="form-control seller-profile-input seller-profile-textarea"
                                        rows="2"
                                        placeholder="Enter your personal address"
                                    ><?php echo htmlspecialchars($current_user['address'] ?? ''); ?></textarea>

                                </div>

                            </div>



                            <!-- =================================================
                                 SAVE BUTTON
                            ================================================== -->

                            <div class="col-12">

                                <div class="seller-save-area">

                                    <button
                                        type="submit"
                                        class="btn btn-maroon seller-save-btn"
                                    >

                                        <i class="fa-solid fa-floppy-disk me-2"></i>

                                        Save Changes

                                    </button>

                                </div>

                            </div>


                        </div>

                    </form>

                </div>

            </div>

        </div>



        <!-- =================================================
             SELLER ACCOUNT
        ================================================== -->

        <div class="col-lg-4">

            <div class="dashboard-card seller-account-card">


                <!-- Header -->

                <div class="dashboard-card-header seller-card-header">

                    <div>

                        <h5 class="dashboard-card-title mb-1">

                            <i class="fa-solid fa-user-check text-success"></i>

                            Seller Account

                        </h5>

                        <p class="seller-card-subtitle mb-0">
                            Your account information
                        </p>

                    </div>

                </div>



                <div class="p-3">


                    <!-- =================================================
                         ACTIVE STATUS
                    ================================================== -->

                    <div class="seller-status-box">

                        <div class="seller-status-icon">

                            <i class="fa-solid fa-check"></i>

                        </div>


                        <div class="seller-status-content">

                            <strong>
                                Account Status
                            </strong>

                            <span>

                                <?php
                                echo htmlspecialchars(
                                    ucfirst(
                                        $current_user['status'] ?? 'Active'
                                    )
                                );
                                ?>

                            </span>

                        </div>


                        <div class="seller-status-dot"></div>

                    </div>



                    <!-- =================================================
                         NAME
                    ================================================== -->

                    <div class="seller-account-item">

    <div class="seller-account-icon">
        <i class="fa-solid fa-user"></i>
    </div>

    <div class="seller-account-content">

        <span class="seller-account-label">
            Name
        </span>

        <strong class="seller-account-value">
            <?php
            echo htmlspecialchars(
                $current_user['name'] ?? 'N/A'
            );
            ?>
        </strong>

    </div>

</div>



                    <!-- =================================================
                         ACCOUNT ROLE
                    ================================================== -->

                    <div class="seller-account-item">

    <div class="seller-account-icon">
        <i class="fa-solid fa-user-tag"></i>
    </div>

    <div class="seller-account-content">

        <span class="seller-account-label">
            Account Role
        </span>

        <span class="seller-role-badge">
            <?php
            echo htmlspecialchars(
                ucfirst(
                    $current_user['role'] ?? 'Seller'
                )
            );
            ?>
        </span>

    </div>

</div>


                    <!-- Small Bottom Info -->

                    <div class="seller-account-footer">

                        <i class="fa-solid fa-shield-halved"></i>

                        <span>
                            Your seller account is secure.
                        </span>

                    </div>


                </div>

            </div>

        </div>


    </div>

</div>



<!-- =========================================================
     PROFILE PHOTO PREVIEW
========================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const avatarInput =
        document.getElementById('avatar');

    const profilePreview =
        document.getElementById('profilePreview');

    const profilePlaceholder =
        document.getElementById('profilePlaceholder');


    if (!avatarInput) {
        return;
    }


    avatarInput.addEventListener('change', function () {

        const file = this.files[0];


        if (!file) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Allowed File Types
        |--------------------------------------------------------------------------
        */

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        if (!allowedTypes.includes(file.type)) {

            alert(
                'Please select JPG, PNG or WEBP image.'
            );

            this.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Size
        |--------------------------------------------------------------------------
        */

        if (file.size > 2 * 1024 * 1024) {

            alert(
                'Profile photo must be less than 2 MB.'
            );

            this.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        const reader =
            new FileReader();


        reader.onload = function (event) {

            if (profilePreview) {

                profilePreview.src =
                    event.target.result;

                profilePreview.classList.remove(
                    'd-none'
                );

            }


            if (profilePlaceholder) {

                profilePlaceholder.classList.add(
                    'd-none'
                );

            }

        };


        reader.readAsDataURL(file);

    });

});

</script>



<?php require_once __DIR__ . '/includes/footer.php'; ?>