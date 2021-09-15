#/bin/sh

exec_filename=/var/www/html/script/cron.php
pid_filename=/tmp/cron.pid
runas_user=www-data

if (test -f $pid_filename)
then

    pidstamp=`stat -t -c%Y $pid_filename`;
    currstamp=`date +%s`;

    let "delta= $currstamp - $pidstamp";
#    echo $delta;

    if (let "$delta>100")
    then
        read PID<$pid_filename;
        echo "PID $PID expired. $delta Kill it!";
        kill -9 $PID;
        rm -f $pid_filename
    fi
    exit;
fi

echo "$$" > $pid_filename

sudo -Eu ${runas_user} ${exec_filename}

rm -f $pid_filename
