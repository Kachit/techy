#!/bin/sh

SCRIPT=$1
PLATFORM=dev

export SCRIPT
export PLATFORM

env php -f sh_script.php $1 $2 $3 $4 $5