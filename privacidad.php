<?php
// Privacy Policy Page
session_start();
require_once 'layouts/header.php';
?>

<nav aria-label="breadcrumb" class="bg-white shadow-sm">
    <ol class="breadcrumb container py-2 mb-0">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Privacy Policy</li>
    </ol>
</nav>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 bg-white p-5 shadow-sm rounded">
            <h1 class="fw-bold mb-4">Privacy Policy</h1>
            <p class="text-muted">Last updated: January 2026</p>

            <hr class="my-4">

            <section class="mb-5">
                <h3 class="h5 fw-bold">1. Data We Collect</h3>
                <p>To provide our hospitality services, we collect the following personal information from our clients:</p>
                <ul>
                    <li>Full Name and Email address.</li>
                    <li>Official Identity Document (ID/Passport).</li>
                    <li>Contact details including phone number and physical address.</li>
                    <li>Payment information processed securely via Stripe.</li>
                </ul>
            </section>

            <section class="mb-5">
                <h3 class="h5 fw-bold">2. How We Use Your Data</h3>
                <p>The information collected is strictly used for:</p>
                <ul>
                    <li>Managing your room reservations and stay.</li>
                    <li>Legal compliance with local hospitality regulations regarding guest registration.</li>
                    <li>Processing secure payments and generating invoices.</li>
                </ul>
            </section>

            <section class="mb-5">
                <h3 class="h5 fw-bold">3. Data Security</h3>
                <p>We implement technical measures to protect your data. Your password is encrypted, and sensitive payment details are never stored on our local server, as they are handled by <strong>Stripe</strong>.</p>
            </section>

            <div class="alert alert-info border-0">
                <strong>Note:</strong> You can update your personal information at any time through your <a href="cliente.php">Client Dashboard</a>.
            </div>
        </div>
    </div>
</main>

<?php require_once 'layouts/footer.php'; ?>