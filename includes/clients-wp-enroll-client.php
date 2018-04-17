<h2>Register to Client Group: <b><?= $group->post_title ?></b></h2>
<div class="row front-forms">
	<div class="col-md-12">
		<form action="" method="POST">
			<input type="hidden" name="user_registration" value="true">
			<input type="hidden" name="clients_wp_add_member" value="true">
			<input type="hidden" name="_is_new_member" value="true">
			<input type="hidden" name="client_group" value="<?= $group->ID ?>">

			<?php if(isset($_SESSION['error']) || isset($_SESSION['success'])) { ?>
			<div class="form-grp">
				<?php if(isset($_SESSION['error'])) { ?>
				<div class="alrt-msg err-msg">
					<?= $_SESSION['error'] ?>
				</div>
				<?php } ?>

				<?php if(isset($_SESSION['success'])) { ?>
				<div class="alrt-msg suc-msg">
					<?= $_SESSION['success'] ?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

			<div class="form-grp">
				<input type="text" value="<?= isset($_SESSION['fname']) ? $_SESSION['fname'] : '' ?>" name="user_fname" id="user_fname" class="form-ctrl" autocomplete="off" placeholder="First Name" required>
			</div>

			<div class="form-grp">
				<input type="text" value="<?= isset($_SESSION['lname']) ? $_SESSION['lname'] : '' ?>" name="user_lname" id="user_lname" class="form-ctrl" autocomplete="off" placeholder="Last Name" required>
			</div>

			<div class="form-grp">
				<input type="email" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>" name="user_email" id="user_email" class="form-ctrl" autocomplete="off" placeholder="Email" required>
			</div>

			<div class="form-grp">
				<input type="password" name="user_password" id="user_password" class="form-ctrl" autocomplete="off" placeholder="Password" required>
			</div>

			<div class="form-grp">
				<input type="password" name="user_confirmpassword" id="user_confirmpassword" class="form-ctrl" autocomplete="off" placeholder="Confirm Password" required>
			</div>

			<button type="submit" class="btn btn-primary" style="float: right;">Register</button>
		</form>
	</div>
</div>