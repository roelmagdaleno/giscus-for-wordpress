<div class="wrap">
	<h1>Giscus</h1>

	<div class="giscus-links">
		<a href="https://giscus.app/" target="_blank" style="margin-right: 15px;">Documentation</a>

		<a href="https://github.com/giscus/giscus" target="_blank" style="margin-right: 15px;">GitHub: Giscus</a>

		<a href="https://github.com/roelmagdaleno/giscus-for-wordpress" target="_blank">GitHub: Giscus for WordPress</a>
	</div>

	<form method="POST" action="options.php" novalidate>
		<?php

		settings_fields( 'giscus_group' );
		do_settings_sections( 'giscus' );

		submit_button();

		?>
	</form>
</div>
