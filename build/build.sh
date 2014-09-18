#!/bin/sh

# This file is subject to the terms and conditions defined in file
# 'LICENSE.txt', which is part of this source code package.
# 
# @copyright Prisma Group (C) 2014
# @license Close source - see LICENSE.txt file

print_usage() {
    printf 'Usage: %s [help] [verbose] [phpcs|cs] [phpmd|md] [phpmd-all|md-all]\n' "$0"
    printf 'Arguments :\n'
    printf '    help              : Print this help message.\n'
    printf '    verbose           : Verbose mode.\n'
    printf '    phpcs|cs          : Check for coding style.\n'
    printf '    phpmd|md          : Check code with PHP mess detector\n'
    printf '    phpmd-all|md-all  : Check code with PHP mess detector\n'
    printf '    phpmetrics|phpm   : Compute metrics'
    printf '\n'
    exit 0
}

VERBOSE=false
DO_PHPCS=false
DO_PHPMD=false

# parse arguments
while [ $# -ne 0 ]; do
    case "$1" in
        help)             print_usage        ;;
        verbose)          VERBOSE=true       ;;
        phpcs|cs)         DO_PHPCS=true      ;;
        phpmd|md)         DO_PHPMD=true      ;;
        phpmd-all|md-all) DO_PHPMD_ALL=true  ;;
        phpmetrics|phpm)  DO_PHPMETRICS=true ;;
        *)                print_usage        ;;
    esac
    shift
done

##############################################################################
error_and_exit()
{
    printf '\33[1;37;41mERROR: %s\033[0m\n\n' "$*"
    exit 1
}

##############################################################################
# Check for build.list file
##############################################################################
BUILD_LIST_FILE='./build/config/build.list'
if [ ! -r "${BUILD_LIST_FILE}" ]; then
    printf '\33[1;37;41mERROR: Do not know what to build ; cannot read "%s".\033[0m\n\n' "${BUILD_LIST_FILE}"
    printf 'Note: each line of this file may contain project directory or project filename\n'
    printf 'Note: this file must end by an empty line.\n'
    exit 1
fi

if [ $( grep -v '^$' "${BUILD_LIST_FILE}" | wc -l ) -ne $( cat "${BUILD_LIST_FILE}" | wc -l ) ]; then
    error_and_exit $( printf 'build file "%s" must end with empty line and cannot have empty line.\033[0m\n\n' "${BUILD_LIST_FILE}" )
fi

##############################################################################
# PHP Coding Style Fixer
##############################################################################
# github : https://github.com/fabpot/PHP-CS-Fixer
# composer : "fabpot/php-cs-fixer": "0.5.*@dev"
# phar : http://get.sensiolabs.org/php-cs-fixer.phar
PHPCS_FIXER_BIN=
[ -r './php-cs-fixer.phar' ] && PHPCS_FIXER_BIN='./php-cs-fixer.phar'
[ -r './vendor/bin/php-cs-fixer' ] && PHPCS_FIXER_BIN='./vendor/bin/php-cs-fixer'


PHPCS_FIXER_OPT='--quiet'
[ $VERBOSE = true ] && PHPCS_FIXER_OPT="${PHPCS_FIXER_OPT} --verbose"

# CHECK FOR PHPCS-FIXER ------------------------------------------------------
if [ -z "${PHPCS_FIXER_BIN}" ]; then
    printf '\33[1;37;41mERROR: Cannot find php-cs-fixer.\033[0m\n\n'
    printf 'Installation possibilities:\n'
    printf ' - Download phar : http://get.sensiolabs.org/php-cs-fixer.phar\n'
    printf ' - Composer : "fabpot/php-cs-fixer": "0.5.*@dev"\n'
    exit 1
fi

##############################################################################
# Code_Sniffer
##############################################################################
if [ "${DO_PHPCS}" = 'true' ]; then
    # github : https://github.com/squizlabs/PHP_CodeSniffer
    # composer : "squizlabs/php_codesniffer": "1.5.2"
    # phar : https://github.com/squizlabs/PHP_CodeSniffer/archive/1.5.2.zip
    PHPCS_BIN=
    [ -r './vendor/bin/phpcs' ] && PHPCS_BIN='./vendor/bin/phpcs'

    PHPCS_REPORT_DIR='./build/reports/phpcs'
    PHPCS_OPT='--report-width=140 --report=full'
    PHPCS_CONFIG_FILE='./build/config/phpcs_ruleset.xml'

    # CHECK FOR PHPCS ------------------------------------------------------------

    if [ -z "${PHPCS_BIN}" ]; then
        printf '\33[1;37;41mERROR: Cannot find phpcs\033[0m\n\n'
        printf 'Installation possibilities:\n'
        printf ' - Download phar : https://github.com/squizlabs/PHP_CodeSniffer/archive/1.5.2.zip\n'
        printf ' - Composer : "squizlabs/php_codesniffer": "1.5.2"\n'
        exit 1
    fi

    if [ ! -r "${PHPCS_CONFIG_FILE}" ]; then
        printf '\33[1;37;41mERROR: Cannot read phpcs configuration file %s.\033[0m\n\n' "${PHPCS_CONFIG_FILE}"
        exit 1
    fi

    touch "${PHPCS_REPORT_FILE}" 2>/dev/null
    if [ $? -ne 0 ]; then
        printf '\33[1;37;41mERROR: Cannot write report file "%s".\033[0m\n\n' "${PHPCS_REPORT_FILE}"
        exit 1  
    fi
    # empty report file
    printf '' > "${PHPCS_REPORT_FILE}"
fi

##############################################################################
# PHP mess detector
##############################################################################
if [ "${DO_PHPMD}" = 'true' ]; then
    # github : 
    # composer : "phpmd/phpmd" : "2.0.*"
    # phar : 
    PHPMD_BIN=
    [ -r './phpmd.phar' ] && PHPMD_BIN='./phpmd.phar'
    [ -r './vendor/bin/phpmd' ] && PHPMD_BIN='./vendor/bin/phpmd'

    PHPMD_REPORT_DIR='./build/reports/phpmd'
    PHPMD_OPT='text'
    PHPMD_CONFIG_FILE='./build/config/phpmd_ruleset.xml'
    if [ "${DO_PHPMD_ALL}" = 'true' ]; then
        PHPMD_CONFIG_FILE='./build/config/phpmd-all_ruleset.xml'
        DO_PHPMD=true
    fi


    # CHECK FOR PHPMD ------------------------------------------------------------

    if [ -z "${PHPMD_BIN}" ]; then
        printf '\33[1;37;41mERROR: Cannot find phpcs\033[0m\n\n'
        printf 'Installation possibilities:\n'
        printf ' - Download phar : https://github.com/squizlabs/PHP_CodeSniffer/archive/1.5.2.zip\n'
        printf ' - Composer : "phpmd/phpmd" : "2.0.*"\n'
        exit 1
    fi

    [ -r "${PHPMD_CONFIG_FILE}" ]                   || error_and_exit $( printf 'Cannot read configuration file %s.' "${PHPMD_CONFIG_FILE}" )
    mkdir -p "${PHPMD_REPORT_DIR}"   2>/dev/null    || error_and_exit $( printf 'Cannot create directory "%s".' "${PHPMD_REPORT_DIR}" )
    touch "${PHPMD_REPORT_DIR}/test" 2>/dev/null    || error_and_exit $( printf 'Cannot write report file into "%s"' "${PHPMD_REPORT_DIR}" )
    rm -f "${PHPMD_REPORT_DIR}/test"
fi

##############################################################################
# PHP mess detector
##############################################################################
# github : 
# composer : 
# phar : 

PHPMETRICS_BIN=
[ -r './build/phpmetrics.phar' ] && PHPMETRICS_BIN='./build/phpmetrics.phar'

PHPMETRICS_REPORT_DIR='./build/reports/phpmetrics'
PHPMETRICS_REPORT_CSV='/tmp/report.csv'
PHPMETRICS_OPT='--extensions=php --excluded-dirs="tests|Exception|config"'

# CHECK FOR PHPMD ------------------------------------------------------------
if [ -z "${PHPMETRICS_BIN}" ]; then
    printf '\33[1;37;41mERROR: Cannot find phpmetrics\033[0m\n\n'
    printf 'Installation possibilities:\n'
    printf ' - Download phar : https://github.com/Halleck45/PhpMetrics/raw/master/build/phpmetrics.phar\n'
    printf ' - Composer : "halleck45/phpmetrics": "@dev"\n'
    printf ' - Github : https://github.com/Halleck45/PhpMetrics'
    exit 1
fi

if [ "${DO_PHPMETRICS}" = 'true' ]; then
    mkdir -p "${PHPMETRICS_REPORT_DIR}"   2>/dev/null    || error_and_exit $( printf 'Cannot create directory "%s".' "${PHPMETRICS_REPORT_DIR}" )
    touch "${PHPMETRICS_REPORT_DIR}/test" 2>/dev/null    || error_and_exit $( printf 'Cannot write report file into "%s"' "${PHPMETRICS_REPORT_DIR}" )
    rm -f "${PHPMETRICS_REPORT_DIR}/test"
fi

##############################################################################
# DO THE STUFF
##############################################################################
printf '\n'
printf '\033[1;32mConfiguration file : %s\033[0m\n\n' "${BUILD_LIST_FILE}"

RESULT=0
RESULT_PHPMETRICS=0
RESULT_PHPCS=0
RESULT_PHPMD=0

while read item; do
    item=${item%/}
    printf 'Process: \033[1;32m%s\033[0m\n' "${item}"
    itemName=$(basename "${item}")

    # correct source code with level = all (psr2, ...)
    php "${PHPCS_FIXER_BIN}" fix ${PHPCS_FIXER_OPT} --level=all "$item"

    # generate autload_classmap.php
    php ./vendor/bin/classmap_generator.php --overwrite --library "${item}/src" --output "${item}/autoload_classmap.php" >/dev/null

    if [ "${DO_PHPMETRICS}" = 'true' ]; then
        # generate report
        PHPMETRICS_REPORT_OPT="--report-html=${PHPMETRICS_REPORT_DIR}/${itemName}.html"
    fi

    # check for maintability
    rm -f "${PHPMETRICS_REPORT_CSV}" 2>/dev/null
    php "${PHPMETRICS_BIN}" --report-csv="${PHPMETRICS_REPORT_CSV}" ${PHPMETRICS_OPT} ${PHPMETRICS_REPORT_OPT} "${item}/src" >/dev/null
    badFiles=$( < "${PHPMETRICS_REPORT_CSV}" grep -v '^filename' | tr ',' ' ' \
            | cut -d' ' -f1,13-14 | sed -e 's/[.][0-9]*//g;'                  \
            | awk '{ if( $3 < 65 ) print $3" (< 65) - "$1 }' | sort -nr )
    if [ -n "${badFiles}" ]; then
        printf '  \33[1;37;41m= Those files are not maintainabled ! =\033[0m\n'
        echo "${badFiles}" | while read score file; do
            printf '  .  %.0f\t%s\n' "${score}" "${file}"
        done
        RESULT_PHPMETRICS=1
        RESULT=1
    fi

    if [ "${DO_PHPCS}" = 'true' ]; then
        # check coding style
        php "${PHPCS_BIN}" ${PHPCS_OPT} --standard="${PHPCS_CONFIG_FILE}" "$item" > "${PHPCS_REPORT_DIR}/${itemName}.txt"
        if [ $? -ne 0 ]; then
            RESULT_PHPCS=1
            RESULT=1
        fi
    fi

    if [ "${DO_PHPMD}" = 'true' ]; then
        # check code
        php "${PHPMD_BIN}" "$item" ${PHPMD_OPT} "${PHPMD_CONFIG_FILE}" > "${PHPMD_REPORT_DIR}/${itemName}.txt"
        if [ $? -ne 0 ]; then
            RESULT_PHPMD=1
            RESULT=1
        fi
    fi

done < "${BUILD_LIST_FILE}"

# clean up
rm -f "${PHPMETRICS_REPORT_CSV}" 2>/dev/null

##############################################################################
# PRINT RESULT
##############################################################################
if [ "${RESULT}" = "0" ]; then
    printf '\n\033[1;32mResult OK\033[0m\n\n'
else
    printf '\n\33[1;37;41mResult KO\033[0m\n'
    if [ "${RESULT_PHPMETRICS}" = "1" ]; then
        if [ "${DO_PHPMETRICS}" = 'true' ]; then
            printf '.  phpmetrics report files in "%s"\n' "${PHPMETRICS_REPORT_DIR}"
        else
            printf '.  run build with "phpm" argument to build metrics report\n'
        fi
    fi
    if [ "${DO_PHPCS}" = 'true' -a "${RESULT_PHPCS}" = "1" ]; then
        printf '.  phpcs report files in "%s"\n' "${PHPCS_REPORT_DIR}"
    fi
    if [ "${DO_PHPMD}" = 'true' -a "${RESULT_PHPMD}" = "1" ]; then
        printf '.  phpmd report files in "%s"\n' "${PHPMD_REPORT_DIR}"
    fi
    printf '\n'
fi

exit ${RESULT}