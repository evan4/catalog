<h1>Admin Panel</h1>
<h2>Edit your profile</h2>
<form method="POST" action="/update">
<input type="number" class="d-none" name="id" value="<?php echo $currentUser['id']; ?>">
<div class="mb-3">
    <label for="username" class="form-label">User name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="username" id="username" value="<?php echo $currentUser['username']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
    <input type="email" class="form-control" name="email" id="email" value="<?php echo $currentUser['email']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
    <input type="tel" class="form-control" name="phone" id="phone" value="<?php echo $currentUser['phone']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="password">
  </div>

  <button type="submit" class="btn btn-primary">Update</button>
</form>
