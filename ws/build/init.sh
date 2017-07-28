bold=$(tput bold)
red=$(tput setaf 1)
green=$(tput setaf 2)
magenta=$(tput setaf 5)
normal=$(tput sgr0)

domain="http://www.ws.dev"
fallback_command="g"
dbname="ws_db"
dbuser="root"
dbpwd="root"

echo "\nShall we proceed with default initialization? [${bold}${magenta}yes${normal}/no]"
echo "(If you made no changes to any of the configurations, type 'yes'.)"
while true; do
  read -p "" yn
  case $yn in
    [Nn]* ) sh build/installWithPrompts.sh; exit;;
    [Yy]* ) break;;
    * ) echo "Please answer yes or no.";;
  esac
done

#echo "\n${bold}${magenta}Initializing Wolverine Search config files...${normal}\n"

#domain=""
#echo "${bold}Q1. ${normal}What is the domain of the site? Type the name, then press [ENTER]."
#echo "  ${green}(Hint: If you followed the setup instructions exactly, type 'www.ws.dev')${normal}"
#while [ "$domain" == "" ]
#  do
#    read domain
#  done

# Fetch info about MySQL DB
# Ask if default. If not, populate details
# Then create tables
# TODO: Need to test MySQL on Vagrant
#name="root"
#echo "\n${bold}Q2. ${normal}What is the name"

echo "${bold}[1/4] Creating sitevars.php...${normal}"
php build/create_sitevars.php $domain $fallback_command >sitevars.php
echo "Done!\n"

echo "${bold}[2/4] Creating dbconfig.php...${normal}"
php build/create_dbconfig.php $dbname $dbuser $dbpwd >scripts/dbconfig.php
echo "Done!\n"

echo "${bold}[3/4] Creating tables in database...${normal}"
cat <<EOM >~/.my.cnf
[mysql]
user=$dbuser
password=$dbpwd
EOM
mysql <build/create_ws_tables.sql
echo "Done!\n"

echo "${bold}[4/4] Building commands...${normal}"
make build

echo ""
echo "${magenta}Initialization Complete! Visit ${bold}"$domain"${normal}${magenta} on your browser now :)\n${normal}"
