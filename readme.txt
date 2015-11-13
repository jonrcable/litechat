LITE CHAT MODULE v0.1 (beta) - Sample Code Base
(c) All rights reserved 05/30/13
Jon Cable - me@joncable.com

Hosted Demo: http://www.joncable.com/demos/litechat/
Archive: http://www.joncable.com/demos/litechat/litechat.zip

Technical Specifications 
-------------------------------
Core: * SQLite3 / PHP 5.4 / jQuery 2.1
Bootstrap: * Twitter 2.3.3 - http://twitter.github.io/bootstrap/
Libraries: * FlowPlayer 3.2.10 – http://www.flowplayer.org/
(notes)
- To create a portable DB we utilize SQLite3 to store rows in a flat file.
- To boost rapid development we utilize the Twitter Bootstrap.
- To allow live Flash (flv/h264) streaming we embed the flowplayer libs

Installation
---------------
1) Unzip the archive into your root Apache directory
2) Make sure apache has read/write permission to the databse file /db/litechat
3) Check the info.php file in root and ensure you are using PHP5.4+ (necessary for SQLite3)

Introduction
----------------
Statement of Purpose:
The purpose of this project is to create a self-contained chat module that is lite weight and allows listeners of radio programs to chat during live broadcasts. This code should demonstrate my ability to create a stand-alone application using open source technologies. This application will have a real world case when integrated into our existing popup player on everydayjunglist.com (EDJ). You can find more information about the EDJ project at the following links.
Online: http://www.everydayjunglist.com/
History: http://www.joncable.com/music/

Goal of Design:
The goal of this application is to utilize the entire jQuery/PHP/SQL stack to build a fluid application that can be used for real world implementation. Users may choose their own username as long as another user has not already claimed it and it is min of 5chars long, no password is required to join chat. The chat console will refresh automagically at a defined interval in order to track other remote users who are interacting simultaneously. In order to keep a lean DB history all records for a user are purged upon logout as well anything other than the latest 100 rows. This encompasses a lean, clean and portable chat application that can simply be unzipped and ready to run in any PHP5.4+ environment.

Layout and Structure:
The structure of this application has a clear separation of each of the technical layers in effort to easily debug and document the layout. The directory locations can easily defined the intended purpose of each file for better clarity in the overall layout. The current folder layout is defined as:
/css – Bootstrap style(s)
/db - SQLite3 database(s)
/functions – Class definitions
/html – jQuery callback events
/js – Bootstrap and declared custom JS functions
/libs – Embedded libraries

Programming Style:
This application reflects my programming style by utilizing separation of each layer into defined container(s). This makes extending, debugging and reading the code easier for new developers and myself. The main template and definitions are composed in the main index.php file while all common classes are contained in the /functions directory with callback events returned via jQuery from the /html directory. All jQuery functions are included via the /js/chatlite.js file and called in the footer of the main template to ensure all HTML as been rendered first. Utilizing jQuery event handlers we are able to init the header on page load and incrementally loading data as needed making for a quick and fluid user experience.

Considered Changes:
This project was designed as a quick self-contained application for easy installation. If the complexity of this design were to grow beyond the current scope I would continue to separate the jQuery callback events in /html/chat.php and the defined functions in /functions/chat.php with a more segmented layout of ‘event handlers’ and ‘initialization actions’. Additionally the template layout, contained in the main index.php file, could be further segmented to a header/body/footer layout allowing the common includes to be utilized in additional layout types. Lastly, the responsive bootstrapped layout should be reworked to better accommodate lower resolutions including mobile devices and tablets which have not currently been tested. 

Planned Updates:
- Create an API Bridge to grab an existing username from an existing session if available. EDJ utilizes http://www.jamroom.net
- Add smiles and fun icons replacing common string occurrences … eg. :(  
- Allow users to edit/remove existing rows that they have created
- Test responsive layout against mobile devices
- Add jPlayer lib to allow live audio steaming without a Flash based device