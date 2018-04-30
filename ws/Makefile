help:
	@sh build/make_usage_message.sh

init:
	@sh build/init.sh

install: init

build: FORCE
	@touch data/commandFileMap.json
	@php build/buildCommandFileMap.php

build_refactor: FORCE
	@touch search_refactor/__build__/commandMap.json
	@php build/buildCommandMap.php
	@touch search_refactor/__build__/documentation_content.json
	@php build/buildDocumentation.php

uninstall: FORCE
	@sh build/uninstall.sh

clean: uninstall

FORCE: ;
