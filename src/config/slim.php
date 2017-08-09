<?php
/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.6
 */

return [
	'settings' => [
		'displayErrorDetails' => boolval(getenv('SLIM_DISPLAY_ERROR_DETAILS')),
		'renderer' => [
			'template_path' => getenv('BASE_DIR') . getenv('SLIM_TEMPLATE_PATH')
		]
	]
];