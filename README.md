Moodle Developer Kit
====================

Moodle provides a pretty great framework that empowers developers with most of
the tools they need to create awesome utilities for educators. This Moodle
plugin helps to smooth over some of the current cracks in these foundations,
simplifying development and helping you get on with your projects.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LukeCarrier/moodle-local_devkit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LukeCarrier/moodle-local_devkit/?branch=master)

License
-------

    Copyright (c) Luke Carrier

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

Features
--------

* A configuration assistance library
    * Per git branch configuration of development sites
    * Automatic configuration of wwwroot based upon ```$_SERVER['SERVER_ADDR']```
* Shortcuts for plugin installation and configuration

Building
--------

1. Clone this repository, and ````cd```` into it
2. Execute ````make```` to generate a zip file containing the plugin
3. Upload to the ````moodle.org```` plugins site
