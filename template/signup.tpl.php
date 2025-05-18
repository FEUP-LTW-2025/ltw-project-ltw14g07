
<?php function draw_register() { ?>
    <form class="register-form" method="POST" action="/../action/actionRegister.php">
      <h2>Register</h2>
      
      <?php /* WSL-COMPATIBLE ADMIN CREATION */ ?>
      <?php if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1'): ?>
        <div class="admin-notice">
          <p>Creating admin account (visible only in WSL/localhost)</p>
          <input type="hidden" name="admin_secret" value="wsl_admin_<?= bin2hex(random_bytes(4)) ?>">
        </div>
      <?php endif; ?>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required />

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required />

      <label for="confirm">Confirm Password</label>
      <input type="password" id="confirm" name="confirm" required />

      <button type="submit">Create Account</button>
    </form>
<?php } ?>



<?php function draw_login() { ?>
    <section id="login">
        <form action="/../action/actionLogin.php" method="POST" onsubmit="console.log('Form submitting to:', this.action);">
            <h1>Login</h1>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
    </section>
<?php } ?>