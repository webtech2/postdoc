# Evolution Tool

Evolution Tool is a software prototype for change discovery and treatment implemented within the framework of the post-doctoral project No. 1.1.1.2./VIAA/1/16/057.

## Installation

Initial installation requires creation of metadata tables. The scripts for DDL operations are available in the file create_tables.sql and folder db structure. The packages available in the folder sql must be compiled. 

Before using the application, it is necessary to fill the metadata with the supported change adaptation operations, conditions and change adaptation scenarios out of them. There is no special interface available for this functionality since this activity must be performed only once. The initial metadata can be created by running SQL insert statements. The operations, conditions and scenarios supported in the prototype were created according to the research project definitions and can be inserted into the corresponding metadata tables by executing the scripts available in the code repository in the folder db dml. 

The installation of the web interface is possible using the Composer. 
Execute a git command

git clone https://github.com/webtech2/evolution_final.git evolution

Go to the new project folder rental: cd evolution

Run composer to install the dependencies: composer install

After installation, the file .env must be created and filled with database connection information and other optional settings according to Laravel requirements. The application includes a functionality of a file upload (examples of data sets). By default, the files will be stored at the web server. It is possible to store data set example files in the cloud by configuring FILESYSTEM_CLOUD parameter in the .env file. This is especially topical for large volume data sources.

New user registration is not available via the application interface, so new users must be registered in the database directly before using the application.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
