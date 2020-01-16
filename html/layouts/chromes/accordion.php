<?php
/**
 * @package     Bettum
 * @copyright   Copyright (C) 2019 Charlie Lodder. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$module  = $displayData['module'];
$params  = $displayData['params'];
$attribs = $displayData['attribs'];

if ($module->content) :
	$id = $module->id;

	// Permission checks
	$user    = Factory::getUser();
	$canEdit = $user->authorise('core.edit', 'com_modules.module.' . $id) && $user->authorise('core.manage', 'com_modules');
	$canChange  = $user->authorise('core.edit.state', 'com_modules.module.' . $id) && $user->authorise('core.manage', 'com_modules');

	$moduleTag      = $params->get('module_tag', 'div');
	$bootstrapSize  = (int) $params->get('bootstrap_size', 6);
	$moduleClass    = ($bootstrapSize) ? 'col-md-' . $bootstrapSize : 'col-md-12';
	$headerTag      = htmlspecialchars($params->get('header_tag', 'h2'));
	$moduleClassSfx = $params->get('moduleclass_sfx', '');

	// Temporarily store header class in variable
	$headerClass = !empty($params->get('header_class')) ? 'class="' . htmlspecialchars($params->get('header_class')) . '"' : '';

	// Get the module icon
	$headerIcon = '';

	$margin = Factory::getLanguage()->isRtl() ? ' ml-2' : ' mr-2';

	if (!empty($params->get('header_icon')))
	{
		$headerIcon = '<span class="' . htmlspecialchars($params->get('header_icon')) .  $margin . '" aria-hidden="true"></span>';
	}
	?>
	<div class="card">
		<<?php echo $moduleTag; ?> class="<?php echo $moduleClassSfx; ?>">
			<?php if ($canEdit || $canChange || $headerIcon || $module->showtitle) : ?>
				<div class="card-header" id="<?php echo htmlspecialchars($module->title); ?>">
					<?php if ($canEdit || $canChange) : ?>
						<?php $dropdownPosition = Factory::getLanguage()->isRTL() ? 'left' : 'right'; ?>
						<div class="module-actions dropdown">
							<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn" id="dropdownMenuButton-<?php echo $id; ?>">
								<span class="fas fa-cog" aria-hidden="true"></span>
								<span class="sr-only"><?php echo Text::_('JACTION_EDIT') . ' ' . $module->title; ?></span>
							</button>
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
						<h2<?php echo $headerClass; ?>>
							<span class="fas fa-chevron-right fa-xs" aria-hidden="true"></span>
							<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseAccordion<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseAccordion<?php echo $id; ?>"><?php echo $headerIcon . htmlspecialchars($module->title); ?></button>
						</h2>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div id="collapseAccordion<?php echo $id; ?>" class="collapse" aria-labelledby="<?php echo htmlspecialchars($module->title); ?>" data-parent="#cpanelAccordion">
				<div class="card-body"><?php echo $module->content; ?></div>
			</div>
		</<?php echo $moduleTag; ?>>
	</div>
<?php endif; ?>
