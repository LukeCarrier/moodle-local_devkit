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

use local_devkit\exception\plugin_does_not_exist;
use local_devkit\exception\plugin_uninstall_not_allowed;
use local_devkit\util\shell_util;
use progress_trace_buffer;
use text_progress_trace;

defined('MOODLE_INTERNAL') || die;

/**
 * Plugin command helper.
 *
 * Most of the logic within this class has been based heavily upon that found in
 * /admin/plugins.php.
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
                            install - install a specific --component
                            upgrade - upgrade all components
    --component (-c)    The frankenstyle component name, e.g.:
                            local_devkit
                            mod_mymod

EOF;
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
     *
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
