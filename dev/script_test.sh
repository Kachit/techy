#!/bin/sh

SCRIPT=$1
PLATFORM=test

export SCRIPT
export PLATFORM

env php -f sh_script.php $1 $2 $3 $4 $5