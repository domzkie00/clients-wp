<h2>Add User to Client Group</h2>
<div class="row front-forms">
	<div class="col-md-12">
		<form action="" method="POST">
			<input type="hidden" name="add_user_to_group" value="true">
			<input type="hidden" name="client_group" value="<?= $client_group_id ?>">
			<input type="hidden" name="clients_wp_add_member" value="true">
			<input type="hidden" name="_is_new_member" value="true">

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

			<h3>Client Group: <b><?= $client_group->posts[0]->post_title ?></b></h3>

			<div class="form-grp">
				<input type="text" value="<?= isset($_SESSION['fname']) ? $_SESSION['fname'] : '' ?>" name="user_fname" id="user_fname" class="form-ctrl" autocomplete="off" placeholder="First Name" required>
			</div>

			<div class="form-grp">
				<input type="text" value="<?= isset($_SESSION['lname']) ? $_SESSION['lname'] : '' ?>" name="user_lname" id="user_lname" class="form-ctrl" autocomplete="off" placeholder="Last Name" required>
			</div>

			<div class="form-grp">
				<input type="email" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>" name="user_email" id="user_email" class="form-ctrl" autocomplete="off" placeholder="Email" required>
			</div>

			<div class="form-grp" style="font-style: italic;">
				<span>Default password will be '{{last_name}}1234'.</span><br/>
				<span>Sample: <b>cruz1234</b></span>
			</div>

			<button type="submit" class="btn btn-primary" style="float: right;">Register</button>
		</form>
	</div>
</div>