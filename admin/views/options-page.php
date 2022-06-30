<div class="wrap">
	<h1>Giscus</h1>

	<form method="POST" action="options.php" novalidate>
		<?php

		settings_fields( 'giscus_group' );
		do_settings_sections( 'giscus' );

		submit_button();

		?>
	</form>
</div>
