help:
	@sh build/make_usage_message.sh

init:
	@sh build/init.sh

install: init

build: FORCE
	@touch data/commandFileMap.json
	@php build/buildCommandFileMap.php

uninstall: FORCE
	@sh build/uninstall.sh

clean: uninstall

FORCE: ;
