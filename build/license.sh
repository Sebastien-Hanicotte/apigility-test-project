#!/bin/bash

# This file is subject to the terms and conditions defined in file
# 'LICENSE.txt', which is part of this source code package.
# 
# @copyright Prisma Group (C) 2014
# @license Close source - see LICENSE.txt file

function usage {
    printf 'Usage: %s <directory>\n' $0
    exit 1
}

DIR="${1:-}"
TMP_DIR="/tmp"
LICENCE_HEADER_FILE="$(dirname "$0")/config/license_header.txt"
NB_LICENCE_LINE=$( wc -l "${LICENCE_HEADER_FILE}" | awk '{print $1}' )

[ $# -ne 1 ] && usage
[ ! -d "${DIR}" ] && usage

if [ ! -r "${LICENCE_HEADER_FILE}" ]; then
    printf 'ERROR: Cannot read %s\n' "${LICENCE_HEADER_FILE}"
    exit 2
fi

##############################################################################
# CHECK INNER LINE OF LICENCE FILE
##############################################################################
TMP_LICENCE_HEADER_FILE="${TMP_DIR}/$( basename "${LICENCE_HEADER_FILE}")"
while read line ; do
    line=$( echo "${line}" | sed -e 's/^[[:space:]]*$//' -e 's/[[:space:]]*$//' )
    if [ -z "${line}" ]; then
        printf ' *\n'
    else
        printf ' * %s\n' "${line}"
    fi
done < "${LICENCE_HEADER_FILE}" > "${TMP_LICENCE_HEADER_FILE}"

##############################################################################
# MAKE THE STUFF
##############################################################################
printf '\n'
printf '\033[1;32mProcess *.php files in : %s\033[0m\n\n' "${DIR}"
printf '\n'
for file in $( find "${DIR}" -name "*.php" | grep -v 'config/' ); do
    tmp_file="${TMP_DIR}/$( basename "${file}" ).tmp"
    # head NL_LICENCE_LINE + '<?php' + '/**' and tail to remove '<?php' + '/**'
    head -n $(( ${NB_LICENCE_LINE} + 2 )) < "$file" | tail -n +3 > "${tmp_file}"
    diff "${tmp_file}" "${TMP_LICENCE_HEADER_FILE}" >/dev/null 2>/dev/null
    if [ $? -ne 0 ]; then
        # on teste la seconde ligne, pour voir s'il y a pas déjà un commentaire de fichier
        second_line=$( head -n 2 "${file}" | tail -n 1 )
        if [ "${second_line}" != '/**' ]; then
            printf '. \033[1;32m  Added \033[0m - %s\n' "${file#${DIR}}"
            # no file comment
            line=$( tail -n +2 "${file}" | head -n 1)
            printf '<?php\n'                            >  "${tmp_file}"
            printf '/**\n'                              >> "${tmp_file}"
            cat "${TMP_LICENCE_HEADER_FILE}"            >> "${tmp_file}"
            printf ' */\n'                              >> "${tmp_file}"
            [ -n "${line}" ] && printf '\n'             >> "${tmp_file}"
            # remove @author tags
            tail -n +2 "${file}" | grep -v '@author'    >> "${tmp_file}"
        else
            printf '. \033[1;32mInserted\033[0m - %s\n' "${file}"
            # we insert licence text at the beginning of the file comment
            printf '<?php\n'                            >  "${tmp_file}"
            printf '/**\n'                              >> "${tmp_file}"
            cat "${TMP_LICENCE_HEADER_FILE}"            >> "${tmp_file}"
            printf ' *\n'                               >> "${tmp_file}"
            # remove @author tags
            tail -n +3 "${file}" | grep -v '@author'    >> "${tmp_file}"
        fi
        # save changes
        mv "${tmp_file}" "${file}"
    fi
done

printf '\n'
printf '\033[1;32mFinish\033[0m\n'
printf '\n'