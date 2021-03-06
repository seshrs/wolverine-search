bold=$(tput bold)
red=$(tput setaf 1)
green=$(tput setaf 2)
magenta=$(tput setaf 5)
normal=$(tput sgr0)

echo "\nAre you sure you want to uninstall? (All database information will be deleted.) [yes/${bold}${magenta}no${normal}]"
while true; do
  read -p "" yn
  case $yn in
    #[Nn]* ) sh build/installWithPrompts.sh; exit;;
    [Yy]* ) break;;
    * ) exit; #echo "Please answer yes or no.";;
  esac
done

echo "${red}${bold}Uninstalling...${normal}\n"

echo "${bold}[1/5] Removing build artifacts...${normal}"
# rm -r data
rm -r search/__build__
echo "Done!\n"

echo "${bold}[2/5] Removing tables in database...${normal}"
mysql <build/delete_ws_tables.sql
echo "Done!\n"

echo "${bold}[3/5] Deleting dbconfig.php...${normal}"
rm scripts/dbconfig.php
echo "Done!\n"

echo "${bold}[4/5] Deleting Sitevars.php and other utils...${normal}"
rm -r __util__
echo "Done!\n"

echo "${bold}[5/5] Finishing post-flight tasks...${normal}"
rm -r scripts/logs
echo "Done!\n"

echo ""
echo "${magenta}Uninstallation Complete! To reinstall, use the command ${bold}'make init'${normal}${magenta}.\n${normal}"
