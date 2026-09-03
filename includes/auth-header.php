<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php
        echo isset($page_title)
            ? htmlspecialchars($page_title) . ' | Udyojika'
            : 'Udyojika';
        ?>
    </title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Rozha+One&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Your existing CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .auth-header {
            background: #fffdf6;
            border-bottom: 1px solid #eee2d5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .auth-brand {
            text-decoration: none;
        }

        .auth-logo {
            width: 44px;
            height: 44px;
            background: #5b1f2a;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-brand-name {
            font-family: 'Playfair Display', serif;
            color: #5b1f2a;
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1;
        }

        .auth-tagline {
            font-size: 0.68rem;
            letter-spacing: 1px;
            color: #777;
            font-weight: 600;
        }

        .auth-home-btn {
            color: #5b1f2a;
            border: 1px solid #5b1f2a;
            border-radius: 8px;
            padding: 8px 16px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .auth-home-btn:hover {
            background: #5b1f2a;
            color: white;
        }
    </style>
</head>

<body class="bg-cream-50">

<header class="auth-header">
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center">

            <!-- LOGO -->
            <a href="index.php"
               class="auth-brand d-flex align-items-center gap-2">

                <div class="auth-logo">
                    <i class="fa-solid fa-spa text-warning"></i>
                </div>

                <div>
                    <span class="auth-brand-name d-block">
                        Udyojika
                    </span>

                    <small class="auth-tagline">
                        HOMEMADE WITH LOVE
                    </small>
                </div>

            </a>

            <!-- HOME BUTTON -->
            <a href="index.php" class="auth-home-btn">
                <i class="fa-solid fa-house me-1"></i>
                <span class="d-none d-sm-inline">Back to Home</span>
                <span class="d-inline d-sm-none">Home</span>
            </a>

        </div>

    </div>
</header>

<main>