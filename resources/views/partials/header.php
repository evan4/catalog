<header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
            <a href="/">My CMS</a>
        </div>
        <div class="col-4 text-center"></div>
        <div class="col-4 d-flex justify-content-end align-items-center">
            <?php 
            if(getUsername() != ''){ ?>
            <a href="/admin">Hello <?=getUsername()?></a>
            <a class="btn btn-sm btn-outline-secondary" href="/logout">Logout</a>
            <?php }else { ?>
              <a class="btn btn-sm btn-outline-secondary" href="/singup">Singup</a>
            <?php } ?>
        </div>
    </div>
</header>
<?php 
if( isset($_SESSION['error'])
  && !empty($_SESSION['error'])
  ): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <?php echo $_SESSION['error']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php 
unset($_SESSION['error']);
elseif( isset($_SESSION['success'])
  && !empty($_SESSION['success'])
  ): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?php echo $_SESSION['success']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php  unset($_SESSION['success']);
endif;
?>
