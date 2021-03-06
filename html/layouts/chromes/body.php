<?php
/**
 * @package    Bettum
 * @copyright  Copyright (C) 2020 Charlie Lodder. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$module = $displayData['module'];
$params = $displayData['params'];

if ($module->content) :
	$id = $module->id;

	// Permission checks
	$user      = Factory::getUser();
	$canEdit   = $user->authorise('core.edit', 'com_modules.module.' . $id) && $user->authorise('core.manage', 'com_modules');
	$canChange = $user->authorise('core.edit.state', 'com_modules.module.' . $id) && $user->authorise('core.manage', 'com_modules');

	$moduleTag      = $params->get('module_tag', 'div');
	$bootstrapSize  = (int) $params->get('bootstrap_size', 6);
	$moduleClass    = ($bootstrapSize) ? 'col-md-' . $bootstrapSize : 'col-md-12';
	$headerTag      = htmlspecialchars($params->get('header_tag', 'h2'));
	$moduleClassSfx = $params->get('moduleclass_sfx', '');

	// Temporarily store header class in variable
	$headerClass = $params->get('header_class');
	$headerClass = ($headerClass) ? ' ' . htmlspecialchars($headerClass) : '';
	?>
	<div class="<?php echo $moduleClass; ?> module-wrapper">
		<<?php echo $moduleTag; ?> class="card mb-3<?php echo $moduleClassSfx; ?>">
			<?php if ($canEdit || $canChange) : ?>
				<?php $dropdownPosition = Factory::getLanguage()->isRTL() ? 'left' : 'right'; ?>
				<div class="module-actions dropdown">
					<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn" id="dropdownMenuButton-<?php echo $id; ?>">
						<svg aria-hidden="true" width="1em" height="1em"><use href="#bettum-cog"></use></svg>
						<span class="sr-only"><?php echo Text::_('JACTION_EDIT') . ' ' . $module->title; ?></span>
					</button>
					<a class="btn btn-link handle">
						<svg aria-hidden="true" class="handle d-inline-block" width="1em" height="1em"><use href="#bettum-drag"></use></svg>
						<span class="sr-only"><?php echo Text::_('JLIB_HTML_BEHAVIOR_DRAG_TO_MOVE') . ' ' . $module->title; ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-<?php echo $dropdownPosition; ?>" aria-labelledby="dropdownMenuButton-<?php echo $id; ?>">
						<?php if ($canEdit) : ?>
							<?php $uri = Uri::getInstance(); ?>
							<?php $url = Route::_('index.php?option=com_modules&task=module.edit&id=' . $id . '&return=' . base64_encode($uri)); ?>
							<a class="dropdown-item" href="<?php echo $url; ?>"><?php echo Text::_('JACTION_EDIT'); ?></a>
						<?php endif; ?>
						<?php if ($canChange) : ?>
							<button type="button" class="dropdown-item unpublish-module" data-module-id="<?php echo $id; ?>"><?php echo Text::_('JACTION_UNPUBLISH'); ?></button>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($module->showtitle) : ?>
				<<?php echo $headerTag; ?> class="card-header<?php echo $headerClass; ?>"><?php echo $module->title; ?></<?php echo $headerTag; ?>>
			<?php endif; ?>
			<div class="module-body">
				<?php echo $module->content; ?>
			</div>
		</<?php echo $moduleTag; ?>>
	</div>
<?php endif; ?>
