#!/bin/bash
#For installing the Valligator framework on first use
echo "Installing the Valligator framework, please make sure MySQL is installed and running."
echo "Enter the MySQL root username:"
read un
echo "Enter the MySQL root password:"
read -s pw 
php install.php $un $pw