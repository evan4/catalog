<h1>Singup</h1>
<form method="POST" action="/register">
<div class="mb-3">
    <label for="username" class="form-label">User name</label>
    <input type="text" class="form-control" name="username" id="username" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" name="email" id="email" required>
  </div>
  <div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="tel" class="form-control" name="phone" id="phone" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="password" required>
  </div>
  <div class="mb-3">
    <label for="repeat-password" class="form-label">Repeat password</label>
    <input type="password" class="form-control" name="repeat-password" id="repeat-password" required>
  </div>

  <button type="submit" class="btn btn-primary">Singup</button>
</form>
