#!/bin/sh

# This file is subject to the terms and conditions defined in file
# 'LICENSE.txt', which is part of this source code package.
# 
# @copyright Prisma Group (C) 2014
# @license Close source - see LICENSE.txt file

print_usage() {
    printf 'Usage: %s [help] [coverage] [<pattern>]\n' "$0"
    printf 'Arguments :\n'
    printf '    help      : Print this help message.\n'
    printf '    coverage  : Check for coding style.\n'
    printf '    force     : Force the launch of tests (ignore the user limitation).\n'
    printf '    <pattern> : used to filter items list.\n'
    printf 'Beware: Launching as super user may results in boggy results'
    printf '\n'
    exit 0
}

VERBOSE=false
DO_COVERAGE=false
TODO_PATTERN='.'
FORCE_LAUNCH=false

# parse arguments
while [ $# -ne 0 ]; do
    case "$1" in
        help)       print_usage       ;;
        coverage)   DO_COVERAGE=true  ;;
        force)      FORCE_LAUNCH=true ;;
        *)          TODO_PATTERN="$1" ;;
    esac
    shift
done

WHO_LAUNCH=$(id -u)

if [ $WHO_LAUNCH = 0 ]; then
    printf '\33[1;37;41mThis script should not be run as root\033[0m' 1>&2
    printf '\n' 1>&2
    if $FORCE_LAUNCH; then
          printf '\33[1;37;38mTesting as super user may result in boggy results\033[0m' 1>&2
    else
          exit 1
    fi
fi

##############################################################################
# Check for build.list file
##############################################################################
TEST_LIST_FILE='./build/config/test.list'
if [ ! -r "${TEST_LIST_FILE}" ]; then
    printf '\33[1;37;41mERROR: Do not know what to test ; cannot read "%s".\033[0m\n\n' "${TEST_LIST_FILE}"
    printf 'Note: each line of this file may contain project tests/ directory\n'
    printf 'Note: this file must end by an empty line.\n'
    exit 1
fi

if [ $( grep -v '^$' "${TEST_LIST_FILE}" | wc -l ) -ne $( cat "${TEST_LIST_FILE}" | wc -l ) ]; then
    printf '\33[1;37;41mERROR: build file "%s" must end with empty line and cannot have empty line.\033[0m\n\n' "${TEST_LIST_FILE}"
    exit 1
fi

##############################################################################
# PHPUnit
##############################################################################
# PHPUnit
# github : https://github.com/sebastianbergmann/phpunit/
# composer : "phpunit/phpunit": "4.1.*",
# phar : 
PHPUNIT_BIN=
[ -r "$(pwd)/phpunit.phar" ] && PHPUNIT_BIN="$(pwd)/phpunit.phar"
[ -r "$(pwd)/vendor/bin/phpunit" ] && PHPUNIT_BIN="$(pwd)/vendor/bin/phpunit"

if [ -z "${PHPUNIT_BIN}" ]; then
    printf '\33[1;37;41mERROR: Cannot find PHPUnit\033[0m\n\n'
    printf 'Installation possibilities:\n'
    printf ' - Github : https://github.com/sebastianbergmann/phpunit/\n'
    printf ' - Composer : "phpunit/phpunit": "4.1.*"\n'
    exit 1
fi

if [ "${DO_COVERAGE}" = 'true' ]; then
    PHPUNIT_COVERAGE_DIR="$(pwd)/build/coverage"
    if [ ! -d "${PHPUNIT_COVERAGE_DIR}" ]; then
        mkdir -p "${PHPUNIT_COVERAGE_DIR}" 2>/dev/null
        if [ $? -ne 0 ]; then
            printf '\33[1;37;41mERROR: Cannot write into "%s"\033[0m\n\n' "${PHPUNIT_COVERAGE_DIR}"
            exit 1
        fi
    fi
fi


##############################################################################
# DO THE STUFF
##############################################################################

TODO_LIST=$( grep "${TODO_PATTERN}" "${TEST_LIST_FILE}")
if [ -z "${TODO_LIST}" ]; then
    printf 'ERROR: %s is not listed in %s file' "${TODO_PATTERN}" "${TEST_LIST_FILE}"
    print_usage;
fi

printf '\n'
printf '\033[1;32mConfiguration file : %s\033[0m\n\n' "${TEST_LIST_FILE}"

RESULT=0
echo "${TODO_LIST}" | while read item; do

    printf 'Process: \033[1;32m%s\033[0m\n' "${item}"
    if [ ! -d "${item}" ]; then
        printf '\33[1;37;41mERROR: Cannot go into directory "%s".\033[0m\n\n' "${item}"
        exit 1
    fi

    # go into module directory
    cd "${item}"

    if [ "${DO_COVERAGE}" = 'true' ]; then
        module=$( basename "`dirname ${item}`" )
        dir="${PHPUNIT_COVERAGE_DIR}/${module}/"
        mkdir -p "${dir}" 2>/dev/null
        if [ $? -ne 0 ]; then
            printf '\33[1;37;41mERROR: Cannot write into "%s"\033[0m\n\n' "${dir}"
            exit 1
        fi
        PHPUNIT_OPT="--coverage-html=${dir}"
    fi
    # correct source code with level = all (psr2, ...)
    php "${PHPUNIT_BIN}" ${PHPUNIT_OPT} || RESULT=1

    # go back to project directory
    cd - >/dev/null
done # < "${TEST_LIST_FILE}"

##############################################################################
# PRINT RESULT
##############################################################################
if [ ${RESULT} -eq 0 ]; then
    printf '\n\033[1;32mResult OK\033[0m\n\n'
else
    printf '\n\33[1;37;41mResult KO\033[0m\n'
    printf '\n'
fi

exit ${RESULT}