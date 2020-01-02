<?php
/**
 * @package     Bettum
 * @copyright   Copyright (C) 2019 Charlie Lodder. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$app  = Factory::getApplication();
$isSidebarNav = $app->getTemplate(true)->params->get('menu', 1) ? true : false;
$logo = $app->getTemplate(true)->params->get('siteLogo')
	? Uri::root() . $app->getTemplate(true)->params->get('siteLogo')
	: Uri::root(true) . '/administrator/templates/' . $app->getTemplate() . '/images/logo-sm.svg';

$hideLinks = $app->input->getBool('hidemainmenu');
$href = $hideLinks ? '#' : Route::_('index.php');

HTMLHelper::_('script', 'mod_menu/admin-menu.min.js', ['version' => 'auto', 'relative' => true]);

// Recurse through children of root node if they exist
?>
<?php if ($isSidebarNav && $root->hasChildren()) : ?>
	<?php HTMLHelper::_('stylesheet', 'administrator/templates/bettum/css/sidebar_nav' . (Factory::getDocument()->direction === 'rtl' ? '-rtl' : '') . '.css', ['version' => 'auto']); ?>
	<nav aria-label="<?php echo Text::_('MOD_MENU_ARIA_MAIN_MENU'); ?>">
		<a class="navbar-brand text-center" href="<?php echo $href; ?>">
			<img src="<?php echo $logo; ?>" alt="<?php echo Text::_('TPL_BETTUM_ALTTEXT_SITE_LOGO_LABEL'); ?>">
		</a>
		<ul id="collapse" class="navbar-nav">
			<?php $menu->renderSubmenu(ModuleHelper::getLayoutPath('mod_menu', 'default_submenu_sidebar'), $root); ?>
		</ul>
	</nav>
<?php elseif (!$isSidebarNav && $root->hasChildren()) : ?>
	<nav class="navbar navbar-expand-lg navbar-dark" aria-label="<?php echo Text::_('MOD_MENU_ARIA_MAIN_MENU'); ?>">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topMenu" aria-controls="topMenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
		<a class="navbar-brand" href="<?php echo $href; ?>">
			<img src="<?php echo $logo; ?>" alt="<?php echo Text::_('TPL_BETTUM_ALTTEXT_SITE_LOGO_LABEL'); ?>">
		</a>
		<div class="collapse navbar-collapse" id="topMenu">
			<ul id="menu" class="navbar-nav mr-auto">
				<?php $menu->renderSubmenu(ModuleHelper::getLayoutPath('mod_menu', 'default_submenu'), $root); ?>
			</ul>
		</div>
	</nav>
<?php endif; ?>
