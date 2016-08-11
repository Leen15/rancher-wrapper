#!/bin/bash

# manually load ENVs
chmod +x $PROJECT_PATH/generate_env.sh
/bin/bash $PROJECT_PATH/generate_env.sh >> $PROJECT_PATH/.env


envsubst < .htpasswd


exec /usr/sbin/apache2ctl -D FOREGROUND