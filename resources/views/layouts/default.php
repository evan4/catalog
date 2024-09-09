<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/libs/bootstrap.min.css">
    <title>My CMS - <?= $meta['title'] ?? ''; ?> </title>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>

<div class="container">

      <?php require_once VIEWS.'/partials/header.php'; ?>
      
    </div>

    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 blog-main">
        <?php
        echo $content;
        ?>
        </div><!-- /.blog-main -->

      </div><!-- /.row -->

    </main><!-- /.container -->

    <footer class="blog-footer">
     
    </footer>
<script src="/js/libs/jquery-3.7.1.min.js"></script>
<script src="/js/libs/bootstrap.bundle.min.js"></script>
<script src="/js/app.js"></script>
</body>
</html>