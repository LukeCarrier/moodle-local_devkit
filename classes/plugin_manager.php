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

namespace local_devkit;

use core_plugin_manager;

/**
 * Bastardised plugin manager.
 *
 * Allows us to perform forceful uninstallations of plugins. We're developers,
 * we don't need no stinkin' validation.
 */
class plugin_manager extends core_plugin_manager {
    /**
     * Should DevKit use force?
     *
     * @var boolean
     */
    protected $devkituseforce;

    /**
     * @override \core_plugin_manager
     */
    public function can_uninstall_plugin($component) {
        return $this->devkituseforce
                || parent::can_uninstall_plugin($component);
    }

    /**
     * Tell DevKit whether to use force.
     *
     * @param boolean $force
     */
    public function devkit_use_force($force) {
        $this->devkituseforce = $force;
    }
}
