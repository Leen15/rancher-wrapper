#!/bin/bash

# manually load ENVs
chmod +x $PROJECT_PATH/generate_env.sh
/bin/bash $PROJECT_PATH/generate_env.sh >> $PROJECT_PATH/.env


echo $HTPASSWD > $PROJECT_PATH/.htpasswd

if [ "$BASIC_AUTH" == "1" ]
    then
        sed -i '/Satisfy/s/^/#/g' $PROJECT_PATH/public/.htaccess
    else
        sed -i '/Satisfy/s/#//g' $PROJECT_PATH/public/.htaccess
fi



exec /usr/sbin/apache2ctl -D FOREGROUND