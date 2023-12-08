#!/bin/bash

touch .env
ex_env=$(<.env.example)
while IFS= read -r line; do
  line=${line%$'\r'}
  if [[ $line =~ ^([A-Z][A-Z0-9_]{2,})=(.*)$ ]]; then
    VAR=${BASH_REMATCH[1]}
    VAL=${BASH_REMATCH[2]}
    if [[ -v vars[$VAR] ]]; then
      VAL=vars[$VAR]
      echo "$VAR - is set to .env from github vars\n"
    fi
    if [[ -v secrets[$VAR] ]]; then
      VAL=vars[$VAR]
      echo "$VAR - is set to .env from github secrets\n"
    fi
    if [[ VAR=="APP_KEY" ]]; then
      VAL=$(php artisan key:generate --force --show)
      echo "$VAR - is set to .env from github secrets\n"
    fi
    echo "$VAR=$VAL" >> .env
  else
    echo $line >> .env
  fi
done <<<"$ex_env"
cat .env


#          echo APP_NAME="ProgMan.site" >> .env
#          echo APP_KEY=$(php artisan key:generate --force --show) >> .env
#          echo APP_DEBUG=true >> .env
#          echo DB_CONNECTION=mysql >> .env
#          echo DB_HOST=${{ vars.DB_HOST }} >> .env
#          echo DB_DATABASE=${{ vars.DB_DATABASE }} >> .env
#          echo DB_USERNAME=${{ vars.DB_USER }} >> .env
#          echo DB_PASSWORD=${{ secrets.DB_PASSWORD }} >> .env
#          echo TELEGRAM_API_TOKEN=${{ secrets.TELEGRAM_API_TOKEN }} >> .env
#          echo TELEGRAM_ADMIN_CHAT_ID=${{ vars.TELEGRAM_ADMIN_CHAT_ID }} >> .env
#          cat .env


