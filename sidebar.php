<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <?php
        if (isLoggedIn()) {
            ?>
            <h5 class="text-white fw-bold mx-auto w-100 text-center my-3 d-md-none d-block">
                <span class="lang-en">Hi, <?php echo $_SESSION['name']; ?></span>
            </h5>
            <?php
        }
        ?>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $active = ($page == 'questions') ? 'active' : '' ?>" href="./index.php">
                    <i class="fa fa-question"></i>
                    <span>
                        <span class="lang-en">Questions</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active = ($page == 'change_password') ? 'active' : '' ?>"
                    href="./change_password.php">
                    <i class="fa fa-lock"></i>
                    <span>
                        <span class="lang-en">Change Password</span>
                    </span>
                </a>
            </li>
        </ul>
        <div class="border-bottom border-secondary mx-3 my-3"></div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="./questionnaire.php">
                    <span>
                        <span class="lang-en">Questionnaire Page</span>
                    </span>
                </a>
            </li>
        </ul>
        <div class="navbar-nav flex-column gap-1 align-items-center d-md-none d-flex">
            <div class="nav-item text-nowrap">
                <?php
                if (isLoggedIn()) {
                    ?>
                    <a class="nav-link px-3" href="./logout.php">
                        <span class="lang-en">Logout</span>
                    </a>
                    <?php
                } else {
                    ?>
                    <a class="nav-link px-3" href="./login.php">
                        <span class="lang-en">Login</span>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</nav>