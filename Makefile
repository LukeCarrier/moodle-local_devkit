# Moodle/Totara LMS DevKit plugin.
#
# A suite of developer tools that aim to smooth over some of the cracks in
# Moodle. This plugin should absolutely not be installed on production sites.
#
# @author Luke Carrier <luke@carrier.im>
# @copyright (c) 2014 Luke Carrier
#

.PHONY: all clean

TOP := $(dir $(CURDIR)/$(word $(words $(MAKEFILE_LIST)), $(MAKEFILE_LIST)))

all: build/local_devkit.zip

clean:
	rm -rf $(TOP)build

build/local_devkit.zip:
	mkdir -p $(TOP)build
	cp -rv $(TOP)src $(TOP)build/devkit
	cp $(TOP)README.md $(TOP)build/devkit/README.txt
	cd $(TOP)build \
		&& zip -r local_devkit.zip devkit
	rm -rfv $(TOP)build/devkit
