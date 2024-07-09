<header class="navbar sticky-top bg-primary flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fw-bold text-white" href="./index.php">
        <span class="lang-en">Dashboard - Security Assessment</span>
    </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars text-white border py-1 px-2 rounded-1"></i>
    </button>
    <?php
    if (isLoggedIn()) {
        ?>
        <h5 class="text-white fw-bold mx-auto mb-0 d-md-block d-none">
            <span class="lang-en">Hi, <?php echo $_SESSION['name']; ?></span>
        </h5>
        <?php
    }
    ?>
    <div class="navbar-nav flex-row gap-1 align-items-center d-md-flex d-none">
        <div class="nav-item text-nowrap">
            <?php
            if (isLoggedIn()) {
                ?>
                <a class="nav-link px-3" href="./logout.php">
                    <span class="lang-en">Logout</span>
                    <span class="lang-de">Ausloggen</span>
                </a>
                <?php
            } else {
                ?>
                <a class="nav-link px-3" href="./login.php">
                    <span class="lang-en">Login</span>
                    <span class="lang-de">Anmelden</span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
</header>