<?php
// Mencegah error jika variabel $page belum didefinisikan di file utama
if (!isset($page)) { $page = ''; }
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home_user.php">SiMona USER</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'home') ? 'active fw-bold' : ''; ?>" href="home_user.php">Data Penimbangan</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'jadwal') ? 'active fw-bold' : ''; ?>" href="jadwal.php">Jadwal & Daftar</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'galeri') ? 'active fw-bold' : ''; ?>" href="galeri_user.php">Galeri</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'profil') ? 'active fw-bold' : ''; ?>" href="profil.php">Profil</a>
                </li>
                
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-warning btn-sm fw-bold mt-1" href="logout.php" onclick="return confirm('Yakin ingin keluar?');">Logout</a>
                </li>
                
            </ul>
        </div>
    </div>
</nav>