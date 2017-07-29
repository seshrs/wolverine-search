invalid_build_command:
	sh build/make_usage_message.sh

init:
	sh build/init.sh

build: FORCE
	mkdir data
	touch data/commandFileMap.json
	php build/buildCommandFileMap.php

FORCE: ;
