<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle/Totara LMS DevKit plugin.
 *
 * A suite of developer tools that aim to smooth over some of the cracks in
 * Moodle. This plugin should absolutely not be installed on production sites.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @copyright 2014 Luke Carrier
 * @license GPL v3
 */

namespace local_devkit\helper;

use core_component;
use local_devkit\exception\plugin_does_not_exist;
use local_devkit\exception\plugin_uninstall_not_allowed;
use local_devkit\util\shell_util;

defined('MOODLE_INTERNAL') || die;

/**
 * Plugin command helper.
 *
 * Most of the logic within this class has been based heavily upon that found in
 * /admin/plugins.php.
 *
 * Extensive changes have been made to the plugin installation process:
 *  -> A great deal of sanity checking has been removed. It is assumed that
 *     plugins can already be installed by running the command line or Site
 *     Administration upgrader. This code may return in the near future
 *     following some refactoring.
 *  -> We don't do a complete upgrade of all plugins when performing an
 *     installation of a single plugin unless explicitly asked to peforma a
 *     complete upgrade. This saves on upgrade time and may allow plugins to be
 *     be installed despite others breaking the upgrade process.
 */
class plugin_helper {
    /**
     * Plugin manager instance.
     *
     * @var \core_plugin_manager
     */
    protected $pluginmgr;

    /**
     * Initialiser.
     *
     * @param \core_plugin_manager $pluginmgr The plugin manager instance to use
     *                                        for plugin management operations.
     */
    public function __construct($pluginmgr) {
        $this->pluginmgr = $pluginmgr;
    }

    /**
     * Print help information.
     *
     * @return string
     */
    public function help() {
        return <<<EOF
Plugin manager.

CLI tool for rapidly managing plugins installed within your local Moodle
installation.

Switches:
    --action (-a)       One of the following:
                            install   - install a specific --component
                            uninstall - uninstall a specific --component
                            reinstall - both uninstall and install --component
                            upgrade   - upgrade all components
    --component (-c)    The frankenstyle component name, e.g.:
                            local_devkit
                            mod_mymod

EOF;
    }

    /**
     * Install a plugin of any type.
     *
     * This method merely routes the component's installation to the most
     * appropriate installation method. Blocks and activity modules require
     * specialised installation routines. All other plugin types can be
     * installed by a more generic installation process.
     *
     * @param string          $component The name of the component to install.
     * @param \progress_trace $progress  Progress trace to direct output to.
     *
     * @return void
     */
    public function install($component, $progress) {
        global $CFG, $DB;

        list($type, $name) = core_component::normalize_component($component);

        switch ($type) {
            case 'block': return $this->install_block($name);
            case 'mod':   return $this->install_module($name);
            default:      return $this->install_generic($type, $name);
        }
    }

    /**
     * Install a plugin using the generic routine.
     *
     * @param string $type The type of the component to install.
     * @param string $name The name of the component to install.
     *
     */
    public function install_generic($type, $name) {
        $plugininfo = $this->pluginmgr->get_plugin_info("{$type}_{$name}");
        $version    = $this->get_plugin_version($plugininfo);

    }

    /**
     * Get plugin version information from version.php.
     *
     * It's best to do this sort of thing in a sandbox where we're least likely
     * to lose important state.
     *
     * @param \core\plugininfo\base $plugininfo Plugin information.
     *
     * @return \stdClass An object containing data sourced from the version
     *                   file.
     */
    protected function get_plugin_version($plugininfo) {
        $plugin = (object) array(
            'version' => null,
        );
        require $file;

        $plugin->name     = $plugininfo->name;
        $plugin->fullname = "{$plugininfo->type}_{$plugininfo->name}";

        return $plugin;
    }

    /**
     * Uninstall a plugin.
     *
     * @param string          $component The name of the component to install.
     * @param \progress_trace $progress  Progress trace to direct output to.
     *
     * @return \progress_trace The output from the uninstallation process.
     */
    public function uninstall($component, $progress) {
        $info = $this->pluginmgr->get_plugin_info($component);

        if ($info === null) {
            throw new plugin_does_not_exist($component);
        }

        if (!$this->pluginmgr->can_uninstall_plugin($info->component)) {
            throw new plugin_uninstall_not_allowed($info->component);
        }

        $this->pluginmgr->uninstall_plugin($info->component, $progress);

        return $progress;
    }

    /**
     * Upgrade all plugins.
     *
     * Run the Moodle upgrade process.
     *
     * @param \progress_trace $progress Progress trace to direct output to.
     *
     * @return \progress_trace The output from the installation process.
     */
    public function upgrade($progress) {
        global $CFG;

        $output = shell_util::shell_exec_args(shell_util::get_php_binary(), array(
            "{$CFG->dirroot}/admin/cli/upgrade.php",
            '--allow-unstable',
            '--non-interactive',
        ));

        $progress->output($output);
        return $progress;
    }
}
