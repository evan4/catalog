<h2>Please sign in</h2>
<form method="POST" action="/login">
  <div class="mb-3">
    <label for="phone-email" class="form-label">Phone or Email address</label>
    <input type="text" class="form-control" name="phone-email" id="phone-email" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="password" required>
  </div>
  <div
    id="captcha-container"
    class="smart-captcha"
    style="height: 100px"
    data-sitekey="<?php echo $_ENV['SMARTCAPTCHA_CLIENT_KEY']; ?>"
    ></div>
  <button type="submit" class="btn btn-primary mt-1">Sign in</button>
</form>
