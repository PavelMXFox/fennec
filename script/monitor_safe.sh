#/bin/sh

exec_filename=/var/www/html/script/monitor.php
pid_filename=/tmp/monitor.pid
runas_user=www-data

if (test -f $pid_filename)
then

    pidstamp=`stat -t -c%Y $pid_filename`;
    currstamp=`date +%s`;

    let "delta= $currstamp - $pidstamp";
#    echo $delta;

    if (let "$delta>1000")
    then
        read PID<$pid_filename;
        echo "PID $PID expired. $delta Kill it!";
        kill -9 $PID;
        rm -f $pid_filename
    fi
    exit;
fi

echo "$$" > $pid_filename

sudo -Eu ${runas_user} ${exec_filename} > /tmp/monitor.log 2>/tmp/monitor2.log

rm -f $pid_filename
