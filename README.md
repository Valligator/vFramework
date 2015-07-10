### Valligator vFramework 0.1 ###

What is the Valligator Framework?

The Valligator Framework provides a standard way for creating validations and tests for any HTTP enabled project.  This framework is required for setting up a Valligator front-end.

The code is PHP based and allows users to share validation or test modules with other users.

Requirements:
	Apache
	MySQL
	PHP

Installation:
	1. Clone the git repository and place the code in a web accessible folder.
		git clone https://github.com/Valligator/vFramework.git
	2. Run the installer that sets up the database connections and initializes the database
		cd vFramework/install
		./install.sh (Linux/MAC OS)

Term Definitions
	
	Modules - are objects that contain the actual validation or testing code.
	Module Config - objects outline what data is needed to successfully run a Module
	Tasks - are a specific instance of the validation code.  They are scheduled to run at a certain time, have a status and return the results of the validation.
	Task Parameters - contain all the information needed to be loaded into the Modules at runtime.
	Executor - this object handles the actual loading and running of the validation modules.

How Does it Work?

	Once Modules and Tasks are configured, they are given a scheduled runtime that is stored in the MySQL database.  A cron task runs every minute and checks if any tasks need to be executed.  If a task needs to be executed, it is passed to the Executor and run.  All the results are stored in the database for retrieval by the Valligator front-end.