
<?php function draw_register() { ?>
    <form class="register-form" method="POST" action="/../action/actionRegister.php">
      <h2>Register</h2>
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