#!/bin/bash

# the purpose of this scritp it to create a new page in the laravel project

# 1. SET VARIABLES:
# #########################
# ADD STORE TO SAVE THE FORM DATA:
#clear
echo "This Script will create 3 steps to create a page"
set -e
echo "1. Create a route in web.php"
set -e
echo "2. Create a method in controller file"
set -e
echo "3. Create a blade view file"
set -e
# NEWPAGENAME='store'
NEWPAGENAME_DEFAULT="logout"
read -p "Enter the Page Name: [$NEWPAGENAME_DEFAULT] " NEWPAGENAME
if [[ $NEWPAGENAME == "" ]]
then
    NEWPAGENAME=$NEWPAGENAME_DEFAULT
    set -e
fi


# // CONTROLLERS NAMING CONVENTIONS:
# // 7 Common Resource Routes:
# // index - Show all listings
# // show - SHOW single listing
# // create - Show form to create new listing (POST)
# // edit - show form to edit listing
# // update - update listing (POST)
# // destroy - Delete Listing
CONTROLLER_DEFAULT="edit"
read -p "Enter the Controller Resource Type: [$CONTROLLER_DEFAULT] (index, show, create, store, edit, update, destroy)" CONTROLLER
if [[ $CONTROLLER == "" ]]
then
    CONTROLLER=$CONTROLLER_DEFAULT
    set -e
fi

# NEWPAGENAME='store'
CONTROLLERNAME_DEFAULT="UserController"
read -p "Enter the Controlller Name: [$CONTROLLERNAME_DEFAULT] (CamelizeText: ListingControllers,UserController)" CONTROLLERNAME
if [[ $CONTROLLERNAME == "" ]]
then
    CONTROLLERNAME=$CONTROLLERNAME_DEFAULT
    set -e
fi



# PAGETYPE='post'
PAGETYPE_DEFAULT="get"
read -p "Enter the Page Type: [$PAGETYPE_DEFAULT] (view,get,post,put,delete) " PAGETYPE
if [[ $PAGETYPE == "" ]]
then
    PAGETYPE=$PAGETYPE_DEFAULT
    set -e
fi

# COMPONENTNAME='listings'
COMPONENTNAME_DEFAULT="Users"
read -p "Enter the Page Type: [$COMPONENTNAME_DEFAULT] (capitalized)" COMPONENTNAME
if [[ $COMPONENTNAME == "" ]]
then
    COMPONENTNAME=$COMPONENTNAME_DEFAULT
    set -e
fi

################################# ROUTE
# EXAMPLE: Route::get('/listings/create', [ListingController::class, 'create']);
# DEBUG
#echo "Route::$PAGETYPE('/$COMPONENTNAME/$NEWPAGENAME', [$CONTROLLERNAME::class, '$CONTROLLER']);"
#exit

echo "Route::$PAGETYPE('/$COMPONENTNAME/$NEWPAGENAME', [$CONTROLLERNAME::class, '$CONTROLLER']);" >> routes/web.php
# open routes file
code routes/web.php

################################# CONTROLLER
CONTROLLERFILE="app/Http/Controllers/$CONTROLLERNAME.php"

# check if directory exists
if [ ! -f $CONTROLLERFILE ]
then

    # Contorller file exisits, OK
    # controller file does not exits, so create
    mkdir $CONTROLLERFILE
    set -e
    echo "Controller method Added: $CONTROLLERNAME"
fi
CONTROLERCODE="public function $NEWPAGENAME() {\n\t\t\treturn $COMPONENTNAME('$COMPONENTNAME_DEFAULT.$NEWPAGENAME');\n\t\t}"
sed -i "\/\/\NewMethods/a $CONTROLERCODE" $CONTROLLERFILE
code $CONTROLLERFILE

################################# VIEW
COMPONENT_FOLDER="resources/views/$COMPONENTNAME"
# check if directory exists
if [ ! -d $COMPONENT_FOLDER ]
then
    # controller file does not exits, so create
    mkdir $COMPONENT_FOLDER
    set -e
    echo "Component Folder Created: $COMPONENT_FOLDER"
fi

COMPONENTNAME_FILE="$COMPONENT_FOLDER/$NEWPAGENAME.blade.php"
# check if file exists
if [ ! -f $COMPONENTNAME_FILE ]
then
    # controller file does not exits, so create
    touch $COMPONENTNAME_FILE
    set -e
fi


VIEWCODE=$'<x-layout>\n\t... content\n</x-layout>'

echo "$VIEWCODE" > $COMPONENTNAME_FILE

code $COMPONENTNAME_FILE
xdg-open http://localhost/$COMPONENTNAME/$NEWPAGENAME
echo "done"


