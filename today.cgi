#!/bin/sh
#
# today.cgi - display the Official UNIX Calendar events for this day
# provided by Kam Kashani (kamk@sgi.com)
#
# Written 17-Jul-99 by Ron Record (rr@sco.com)
#
# Modified to work as a cgi-bin program 21-Jul-99 by Ron Record (rr@sco.com)
#
# Usage: today [-u] [-f calendar] [month [day]]
#

DATA=calendar
DATE=`date +"%b %e"`
FDAT=`date +"%B %d"`

header() {
    echo "Content-type: text/plain"
    echo
}

usage() {
    echo -e "\n\tUseage: today [-u] [-f calendar] [month [day]]\n"
    exit 1
}

case "X$1" in
   "X-u") header
          usage
   ;;
   "X-f") DATA=$2
       shift 2
   ;;
esac

[ -f "$DATA" ] || {
    header
    echo -e "\nThe Official UNIX Calendar database, $DATA"
    echo "does not appear to be installed. Contact your"
    echo "system administrator, or use the -f option to"
    echo "specify another location."
    usage
}

[ "$1" ] && {
    MON=`echo $1 | sed -e "s/,//"`
    case $MON in
       January|JAN) MON="Jan"
       ;;
       February|FEB) MON="Feb"
       ;;
       March|MAR) MON="Mar"
       ;;
       April|APR) MON="Apr"
       ;;
       MAY) MON="May"
       ;;
       June|JUN) MON="Jun"
       ;;
       July|JUL) MON="Jul"
       ;;
       August|AUG) MON="Aug"
       ;;
       September|SEP) MON="Sep"
       ;;
       October|OCT) MON="Oct"
       ;;
       November|NOV) MON="Nov"
       ;;
       December|DEC) MON="Dec"
       ;;
    esac

    [ "$2" ] && {
       DAY=$2
       case $DAY in
          [1-9]) DAY=" $2"
          ;;
          0?)  DAY=`echo $2 | sed -e "s/0/ /"`
          ;;
       esac
    }
    DATE="$MON $DAY"
    FDAT="$MON $DAY"
}

header

echo -e "\nOn this day in history ($FDAT) :\n"

grep "$DATE" $DATA | sed -e "s/$DATE//" -e "s/	/ /" -e "s/  / /"

