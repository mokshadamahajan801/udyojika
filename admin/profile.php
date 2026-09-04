<?php
$page_title = "Admin Profile";

require_once __DIR__ . '/includes/header.php';

$success_msg = '';
$error_msg = '';

/* =========================
   CHANGE PROFILE PHOTO
========================= */

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'change_photo'
) {

    if (
        isset($_FILES['profile_photo'])
        && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK
    ) {

        $file = $_FILES['profile_photo'];

        $allowed_types = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!isset($allowed_types[$mime_type])) {

            $error_msg = "Only JPG, PNG and WEBP images are allowed.";

        } elseif ($file['size'] > 5 * 1024 * 1024) {

            $error_msg = "Image size must be less than 5 MB.";

        } else {

            $extension = $allowed_types[$mime_type];

            $filename =
                'admin_' .
                $current_user['id'] .
                '_' .
                time() .
                '.' .
                $extension;

            $upload_folder = __DIR__ . '/../uploads/admin/';

            $upload_path = $upload_folder . $filename;

            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {

                $avatar_path = '../uploads/admin/' . $filename;

                $stmt = $pdo->prepare("
                    UPDATE users
                    SET avatar = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $avatar_path,
                    $current_user['id']
                ]);

                $current_user = get_logged_in_user();

                $success_msg = "Profile photo updated successfully!";

            } else {

                $error_msg = "Unable to upload profile photo.";

            }
        }

    } else {

        $error_msg = "Please select a profile photo.";

    }
}


/* =========================
   UPDATE PROFILE
========================= */

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'edit_profile'
) {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '' || $email === '') {

        $error_msg = "Name and Email are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_msg = "Please enter a valid email address.";

    } else {

        try {

            $stmt = $pdo->prepare("
                UPDATE users
                SET name = ?, email = ?, phone = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $name,
                $email,
                $phone !== '' ? $phone : null,
                $current_user['id']
            ]);

            $current_user = get_logged_in_user();

            $success_msg = "Profile updated successfully!";

        } catch (PDOException $e) {

            $error_msg = "Unable to update profile. Please try again.";

        }
    }
}


/* =========================
   PROFILE DATA
========================= */

$avatar = !empty($current_user['avatar'])
    ? $current_user['avatar']
    : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop';

$name = $current_user['name'] ?? 'Admin';

$email = $current_user['email'] ?? 'Not provided';

$phone = !empty($current_user['phone'])
    ? $current_user['phone']
    : 'Not provided';

$role = $current_user['role'] ?? 'admin';

$status = $current_user['status'] ?? 'active';

$member_since = !empty($current_user['created_at'])
    ? date('d M Y', strtotime($current_user['created_at']))
    : 'Not available';

?>

<style>

/* =========================
   PROFILE LAYOUT
========================= */

.profile-wrapper {
    max-width: 950px;
    margin: 0 auto;
}

.profile-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}


/* =========================
   PROFILE TOP
========================= */

.profile-top {
    padding: 38px 30px;
    text-align: center;
    border-bottom: 1px solid #eeeeee;
}

.profile-photo {
    width: 125px;
    height: 125px;
    object-fit: cover;
    border-radius: 50%;
    border: 5px solid #ffffff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}


/* =========================
   CAMERA BUTTON
========================= */

.change-photo-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    width: 42px;
    height: 42px;

    margin-top: -18px;
    margin-left: 80px;

    position: relative;

    background: #ffffff;
    border: 2px solid #0d6efd;

    border-radius: 50%;

    color: #0d6efd;

    cursor: pointer;

    box-shadow: 0 3px 10px rgba(0,0,0,0.15);

    transition: 0.2s;
}

.change-photo-btn:hover {
    background: #0d6efd;
    color: #ffffff;
}


/* =========================
   PROFILE NAME
========================= */

.profile-name {
    margin-top: 18px;
    font-size: 28px;
    font-weight: 700;
    color: #222222;
}

.profile-role {
    margin-top: 5px;
    color: #777777;
    font-size: 15px;
}

.profile-status {
    margin-top: 12px;
}


/* =========================
   PROFILE BODY
========================= */

.profile-body {
    padding: 30px;
}

.section-title {
    font-size: 19px;
    font-weight: 700;
    margin-bottom: 22px;
}


/* =========================
   INFORMATION BOX
========================= */

.info-box {
    background: #f8f9fa;
    border: 1px solid #eeeeee;
    border-radius: 12px;
    padding: 18px;
    height: 100%;
}

.info-label {
    font-size: 13px;
    color: #777777;
    margin-bottom: 7px;
}

.info-value {
    font-size: 16px;
    font-weight: 600;
    color: #222222;
    word-break: break-word;
}


/* =========================
   ACTION BUTTONS
========================= */

.profile-actions {
    margin-top: 28px;
    padding-top: 22px;
    border-top: 1px solid #eeeeee;

    display: flex;
    justify-content: flex-end;

    gap: 10px;

    flex-wrap: wrap;
}


/* =========================
   EDIT FORM
========================= */

#editForm {
    display: none;
}

</style>


<div class="container-fluid py-4">

    <div class="profile-wrapper">


        <!-- =========================
             PAGE HEADER
        ========================== -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">
                    Admin Profile
                </h2>

                <p class="text-muted mb-0">
                    Manage your account information
                </p>

            </div>


            <a
                href="index.php"
                class="btn btn-secondary"
            >

                <i class="fa-solid fa-arrow-left me-2"></i>

                Back to Dashboard

            </a>

        </div>


        <!-- =========================
             SUCCESS MESSAGE
        ========================== -->

        <?php if (!empty($success_msg)): ?>

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check me-2"></i>

                <?php echo htmlspecialchars($success_msg); ?>

            </div>

        <?php endif; ?>


        <!-- =========================
             ERROR MESSAGE
        ========================== -->

        <?php if (!empty($error_msg)): ?>

            <div class="alert alert-danger">

                <i class="fa-solid fa-circle-exclamation me-2"></i>

                <?php echo htmlspecialchars($error_msg); ?>

            </div>

        <?php endif; ?>


        <!-- =========================
             PROFILE CARD
        ========================== -->

        <div class="profile-card">


            <!-- =========================
                 PROFILE HEADER
            ========================== -->

            <div class="profile-top">


                <!-- PROFILE PHOTO -->

                <img
                    src="<?php echo htmlspecialchars($avatar); ?>"
                    class="profile-photo"
                    alt="Admin Profile"
                >


                <!-- CAMERA UPLOAD -->

                <form
                    method="POST"
                    enctype="multipart/form-data"
                    id="photoForm"
                >

                    <input
                        type="hidden"
                        name="action"
                        value="change_photo"
                    >


                    <input
                        type="file"
                        name="profile_photo"
                        id="profilePhotoInput"
                        accept="image/jpeg,image/png,image/webp"
                        style="display:none;"
                        onchange="document.getElementById('photoForm').submit();"
                    >


                    <label
                        for="profilePhotoInput"
                        class="change-photo-btn"
                        title="Change Profile Photo"
                    >

                        <i class="fa-solid fa-camera"></i>

                    </label>

                </form>


                <!-- PROFILE NAME -->

                <div class="profile-name">

                    <?php echo htmlspecialchars($name); ?>

                </div>


                <!-- ROLE -->

                <div class="profile-role">

                    <i class="fa-solid fa-shield-halved me-1"></i>

                    <?php echo htmlspecialchars(ucfirst($role)); ?>

                    Account

                </div>


                <!-- STATUS -->

                <div class="profile-status">

                    <?php if ($status === 'active'): ?>

                        <span class="badge bg-success px-3 py-2">

                            <i class="fa-solid fa-circle-check me-1"></i>

                            Active

                        </span>

                    <?php else: ?>

                        <span class="badge bg-secondary px-3 py-2">

                            <?php echo htmlspecialchars(ucfirst($status)); ?>

                        </span>

                    <?php endif; ?>

                </div>

            </div>


            <!-- =========================
                 ACCOUNT INFORMATION
            ========================== -->

            <div
                class="profile-body"
                id="profileInfo"
            >


                <div class="section-title">

                    <i class="fa-solid fa-user me-2"></i>

                    Account Information

                </div>


                <div class="row g-3">


                    <!-- NAME -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Full Name
                            </div>

                            <div class="info-value">

                                <?php echo htmlspecialchars($name); ?>

                            </div>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Email Address
                            </div>

                            <div class="info-value">

                                <?php echo htmlspecialchars($email); ?>

                            </div>

                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Mobile Number
                            </div>

                            <div class="info-value">

                                <?php echo htmlspecialchars($phone); ?>

                            </div>

                        </div>

                    </div>


                    <!-- ROLE -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Role
                            </div>

                            <div class="info-value">

                                <?php echo htmlspecialchars(ucfirst($role)); ?>

                            </div>

                        </div>

                    </div>


                    <!-- STATUS -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Account Status
                            </div>

                            <div class="info-value">

                                <?php echo htmlspecialchars(ucfirst($status)); ?>

                            </div>

                        </div>

                    </div>


                    <!-- MEMBER SINCE -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Member Since
                            </div>

                            <div class="info-value">

                                <?php echo htmlspecialchars($member_since); ?>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =========================
                     ACTION BUTTONS
                ========================== -->

                <div class="profile-actions">


                    <!-- CHANGE PASSWORD -->

                    <a
                        href="change-password.php"
                        class="btn btn-outline-danger"
                    >

                        <i class="fa-solid fa-key me-2"></i>

                        Change Password

                    </a>


                    <!-- EDIT PROFILE -->

                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="showEdit()"
                    >

                        <i class="fa-solid fa-pen me-2"></i>

                        Edit Profile

                    </button>

                </div>

            </div>


            <!-- =========================
                 EDIT PROFILE FORM
            ========================== -->

            <div
                class="profile-body"
                id="editForm"
            >


                <div class="section-title">

                    <i class="fa-solid fa-user-pen me-2"></i>

                    Edit Profile

                </div>


                <form method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="edit_profile"
                    >


                    <!-- NAME -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?php echo htmlspecialchars($name); ?>"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >

                    </div>


                    <!-- PHONE -->

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Mobile Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>"
                        >

                    </div>


                    <!-- SAVE -->

                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fa-solid fa-floppy-disk me-2"></i>

                        Save Changes

                    </button>


                    <!-- CANCEL -->

                    <button
                        type="button"
                        class="btn btn-secondary ms-2"
                        onclick="hideEdit()"
                    >

                        Cancel

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script>

function showEdit() {

    document.getElementById('profileInfo').style.display = 'none';

    document.getElementById('editForm').style.display = 'block';

}


function hideEdit() {

    document.getElementById('editForm').style.display = 'none';

    document.getElementById('profileInfo').style.display = 'block';

}

</script>