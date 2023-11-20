<?php

require_once 'Autoload.php';
new Autoload();

$user = new Users();
if (isset($_SESSION['user'])) {
    $user->bind($_SESSION['user']);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Средство сокращения URL-адресов</title>
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libs/fontawesome/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css?<?= time() ?>">
</head>
<body>
<nav class="navbar bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand"><strong>MyShortener.link</strong></a>
        <form class="d-flex align-items-center">
            <?php
            if (isset($user->id)) { ?>
                Добро пожаловать&nbsp;<strong><?= $user->name ?></strong>&nbsp;<small>(<?=$user->email?>)</small>&nbsp;
                <a href="logout.php" class="ms-3 text-decoration-none">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;Выход
                </a>
            <?php } else {
                ?>
                <button type="button" class="btn btn-link text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#registration-modal">
                    <i class="fa-solid fa-user-plus"></i>&nbsp;Зарегистрироваться
                </button>
                <button type="button" class="btn btn-link text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#login-modal">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>&nbsp;Войти
                </button>
            <?php } ?>
        </form>
    </div>
</nav>
<section class="shortener-header">
    <div class="container">
        <form id="shortener-form">
            <div class="shortener-input">
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-lg" id="url-text"
                           placeholder="Example:https://long-link.com/shorten-it" aria-describedby="shorten-input">
                    <span class="input-group-text bg-primary p-0" id="shorten-input">
                    <button type="submit" class="btn btn-primary h-100">
                        Сокращать!&nbsp;<i class="fa-solid fa-circle-arrow-right"></i>
                    </button>
                </span>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="container">
    <?php if (isset($user->id)) {
        $links = $user->getLinks();
        $since = new DateTime($user->email_confirmed_at);
        $total = count($links);
        ?>
        <section class="user-links mt-5">
            <h3 class="border-bottom border-black border-3 border-primary-subtle pb-1">Пользователь
                <strong><?= $user->name ?></strong>, список ссылок с
                <strong><small><i><?= $since->format('d.m.Y') ?></i></small></strong></h3>
            <table class="table table-striped">
                <thead>
                <?php if ($total > 0) { ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Итог:</strong> <?= $total ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="text-center" scope="col">№</th>
                    <th scope="col">Полный URL-адрес</th>
                    <th scope="col">Короткий URL-адрес</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($links)) { ?>
                    <tr>
                        <td colspan="3" class="text-center text-danger">Сокращенных ссылок найдено не было.</td>
                    </tr>
                <?php } else {
                    $pos = 1;
                    foreach ($links as $link) { ?>
                        <tr>
                            <th class="text-center"><?= $pos ?></th>
                            <td>
                                <div class="table-col d-flex">
                                    <?php
                                    $len = strlen($link['full_url']);
                                    $text = $link['full_url'];
                                    if ($len > 100) {
                                        $text = substr($link['full_url'], 0, 100)." ...";
                                    } ?>
                                    <a href="<?= $link['full_url'] ?>" target="_blank"><?= $text ?></a>
                                    <span onclick="copy('<?= $link['full_url'] ?>')" class="copy-link ms-5">
                                        <i class="fa-regular fa-clipboard"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="table-col d-flex">
                                    <a target="_blank" href="https://myshortener.link/?l=<?= $link['short_url'] ?>">https://myshortener.link/?l=<?= $link['short_url'] ?></a>
                                    <span onclick="copy('https://myshortener.link/?l=<?= $link['short_url'] ?>')" class="copy-link">
                                        <i class="fa-regular fa-clipboard"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <?php $pos++;
                    }
                }
                ?>
                </tbody>
            </table>
        </section>
    <?php } ?>
</div>
<div class="modal fade" id="registration-modal" tabindex="-1" aria-labelledby="registration-modal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="registration-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5"><i class="fa-solid fa-user-plus"></i>&nbsp;<strong>Регистрация
                            пользователя</strong></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-container">
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Владимир Владимирович">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">Подтвердите пароль</label>
                            <input type="password" class="form-control" name="confirm-password" id="confirm-password"
                                   placeholder="">
                        </div>
                        <div class="text-center">
                            <button type="button" onclick="showConfirmationCode()" id="have-code" class="btn btn-link btn-sm">У меня есть код</button>
                        </div>
                    </div>
                    <div class="registration-success">
                        <div class="alert alert-success">
                            Мы отправили вам код для подтверждения вашей электронной почты, пожалуйста, введите его в
                            поле:
                        </div>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="code" id="confirm-code"
                                   aria-describedby="shorten-input" maxlength="6">
                            <button type="button" id="confirm-btn" class="btn btn-primary">Подтвердите код</button>
                        </div>
                        <p class="text-success code-valid">Код подтвержден</p>
                        <p class="text-danger code-invalid">Неверный код</p>
                        <div class="text-center">
                            <button type="button" onclick="showRegistrationForm()" id="back-to-registration" class="btn btn-link btn-sm">Зарегистрировать
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="submit" class="btn btn-primary register-btn">Зарегистрировать</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="login-modal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="login-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5"><i class="fa-solid fa-arrow-right-to-bracket"></i>&nbsp;<strong>Авторизоваться</strong>
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-container">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="login-email" name="name"
                                   placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Пароль</label>
                            <input type="password" class="form-control" id="login-password" name="password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary login-btn">Войти</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
<script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script>
    const user_id = "<?=$user->id?>";
</script>
<script src="assets/js/scripts.js?<?= time() ?>"></script>
</html>