help:
	@sh build/make_usage_message.sh

init:
	@sh build/init.sh

install: init

build: FORCE
	@touch search/__build__/commandMap.json
	@php build/buildCommandMap.php
	@touch search/__build__/documentation_content.json
	@php build/buildDocumentation.php

uninstall: FORCE
	@sh build/uninstall.sh

clean: uninstall

FORCE: ;
