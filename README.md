# wellspringTest

## Some notes and admissions
There is pretty much zero consideration of security within this application. It is easily hit with many forms of injection. I meant it to be a very simple proof of concept idea which is what I assumed was required.
There was no deployment config set up as I, rather embarrassingly, have broken my own aws account and am in the middle of trying to recover that. I also was not sure what the options for installation were for anyone looking at this, so I choose the simplest option I could.
I made the assumption that every file being uploaded will have the same format as was provided in the original email.

## Set Up
I didn't set up a deployment for this as its very basic. Follow these instructions, and you should have a working site in no time. Albeit not the most gorgeous.

1. Create a vhost config and host file update for whatever url you choose, this application should be domain agnostic. Mine looked something like:
        <VirtualHost *:80>
            ServerAdmin webmaster@wellspringTest.com
            DocumentRoot "C:/Users/billy/workspace/wellspringTest"
            ServerName wellspringTest.com
            ErrorLog "logs/wellspring-error.log"
            CustomLog "logs/wellspring-access.log" common
            <Directory C:/Users/billy/workspace/wellspringTest>
                Options Indexes FollowSymLinks
                AllowOverride All
                Require all granted
            </Directory>
        </VirtualHost>
2. Run the trains.sql file to build out the db
3. Create and appropriate user or update the config in src/DB.php
4. Install composer and run `composer update` in the root of the project to pull the required packages (I think there's 2?)
5. Once you have all the previous steps completed it should just be a matter of pulling the site up and uploading a file.

## Things I wanted to do but decided not to fall down the proverbial rabbit hole.
1. I wanted to actually poll the db on page load to pull up any stored data
2. Update the uploaded csv with any info that was in the db so someone can have a more complete list (crowdsources tangential)
3. The whole ui is just not pretty, I would have fixed it, but functionally it is there, and I would have been on that for too long
4. Finally, I chose to stop before really building a complete CRUD app as I was closing in on the hour mark and starting on it would have had either a lot of code that never got used or I would have spent more time than would be prudent.

Thank you so much for your time, and I really genuinely hope to hear from you soon.